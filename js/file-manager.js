/* Minimal, dependency-free file manager UI */
(function () {
  const qs = (sel, ctx = document) => ctx.querySelector(sel);
  const qsa = (sel, ctx = document) => Array.from(ctx.querySelectorAll(sel));

  const formatSize = (bytes) => {
    if (bytes > 1024 * 1024) return (bytes / 1024 / 1024).toFixed(1) + ' MB';
    if (bytes > 1024) return (bytes / 1024).toFixed(1) + ' KB';
    return bytes + ' B';
  };

  const formatDate = (ts) => {
    const d = new Date(ts * 1000);
    return d.toLocaleString();
  };

  function init(opts) {
    const root = typeof opts.container === 'string' ? qs(opts.container) : opts.container;
    if (!root) return;

    const state = {
      dir: '',
      items: [],
      loading: false,
      error: '',
    };

    const apiBase = opts.apiBase || '/core/api/file_manager';
    const caseId = opts.caseId;
    const grid = qs('[data-fm-list]', root);
    const pathEl = qs('[data-fm-path]', root);
    const errorEl = qs('[data-fm-error]', root);
    const uploadInput = qs('[data-fm-upload]', root);
    const uploadList = qs('[data-fm-upload-list]', root);
    const toastHost = (() => {
      let node = qs('.fm-toast-container');
      if (!node) {
        node = document.createElement('div');
        node.className = 'fm-toast-container';
        document.body.appendChild(node);
      }
      return node;
    })();

    function setError(msg) {
      errorEl.textContent = msg || '';
      errorEl.style.display = msg ? 'block' : 'none';
    }

    async function load() {
      state.loading = true;
      setError('');
      const url = `${apiBase}/list.php?case_id=${encodeURIComponent(caseId)}&dir=${encodeURIComponent(state.dir)}`;
      try {
        const res = await fetch(url);
        const data = await res.json();
        if (!data.success) throw new Error(data.message || 'Load failed');
        state.items = data.data || [];
        render();
      } catch (err) {
        setError(err.message || 'Load failed');
      } finally {
        state.loading = false;
      }
    }

    function render() {
      pathEl.textContent = state.dir ? `/${state.dir}` : '/';
      grid.innerHTML = '';
      if (!state.items.length) {
        grid.innerHTML = `<div class="fm-empty">尚無檔案</div>`;
        return;
      }
      state.items.forEach((item) => {
        const card = document.createElement('div');
        card.className = 'fm-card';
        card.innerHTML = `
          <a href="https://ws.srl.tw/${item.url}" class="fm-thumb-link" data-fancybox="gallery" data-caption="${item.name}">
            <img class="fm-thumb" loading="lazy" src="${item.thumb}" alt="${item.name}">
          </a>
          <div class="fm-meta">
            <strong>${item.name}</strong>
            <span>${formatSize(item.size)}</span>
            <span>${formatDate(item.mtime)}</span>
          </div>
          <div class="fm-actions">
            <button class="fm-btn secondary" data-action="rename">重新命名</button>
            <button class="fm-btn danger" data-action="delete">刪除</button>
          </div>
        `;
        card.querySelector('[data-action="rename"]').addEventListener('click', () => onRename(item));
        card.querySelector('[data-action="delete"]').addEventListener('click', () => onDelete(item));
        grid.appendChild(card);
      });
      
      // Initialize fancybox if available
      if (typeof $.fancybox === 'function') {
        $('[data-fancybox="gallery"]').fancybox({
          buttons: ['zoom', 'share', 'slideShow', 'fullScreen', 'download', 'thumbs', 'close'],
          protect: true
        });
      }
    }

    function createPreview(file) {
      return new Promise((resolve) => {
        const reader = new FileReader();
        reader.onload = (e) => resolve(e.target.result);
        reader.onerror = () => resolve(null);
        reader.readAsDataURL(file);
      });
    }

    async function onUpload(file) {
      setError('');
      const preview = await createPreview(file);
      const toast = document.createElement('div');
      toast.className = 'fm-toast';
      toast.innerHTML = `
        <img class="fm-toast-thumb" src="${preview || ''}" alt="">
        <div class="fm-toast-info">
          <div class="fm-toast-name">${file.name}</div>
          <div class="fm-progress"><div class="fm-progress-bar"></div></div>
          <div class="fm-toast-status">準備上傳...</div>
        </div>
      `;
      toastHost.appendChild(toast);

      const fd = new FormData();
      fd.append('file', file);
      fd.append('case_id', caseId);
      fd.append('dir', state.dir);

      const progressBar = toast.querySelector('.fm-progress-bar');
      const statusEl = toast.querySelector('.fm-toast-status');

      const finalize = (ok = true) => {
        if (ok) {
          progressBar.style.width = '100%';
          statusEl.textContent = '完成';
        }
        setTimeout(() => {
          toast.style.opacity = '0';
          toast.style.transform = 'translateY(-6px)';
          setTimeout(() => toast.remove(), 200);
        }, ok ? 800 : 1800);
      };

      try {
        const xhr = new XMLHttpRequest();
        xhr.upload.addEventListener('progress', (e) => {
          if (e.lengthComputable) {
            const percent = Math.round((e.loaded / e.total) * 100);
            progressBar.style.width = percent + '%';
            statusEl.textContent = percent + '%';
          }
        });

        const res = await new Promise((resolve, reject) => {
          xhr.onload = () => resolve(xhr.response);
          xhr.onerror = () => reject(new Error('Network error'));
          xhr.open('POST', `${apiBase}/upload.php`);
          xhr.send(fd);
        });

        const data = JSON.parse(res);
        if (!data.success) throw new Error(data.message || 'Upload failed');

        finalize(true);
        await load();
      } catch (err) {
        statusEl.textContent = '失敗: ' + (err.message || 'Unknown error');
        statusEl.style.color = 'var(--fm-danger)';
        finalize(false);
      }
    }

    async function onRename(item) {
      const nameOnly = item.name.lastIndexOf('.') > 0 
        ? item.name.substring(0, item.name.lastIndexOf('.'))
        : item.name;
      const newName = window.prompt('輸入新檔名（不需副檔名）', nameOnly);
      if (!newName || newName === nameOnly) return;
      
      const fd = new FormData();
      fd.append('case_id', caseId);
      fd.append('path', item.path);
      fd.append('newName', newName);
      try {
        const res = await fetch(`${apiBase}/rename.php`, { method: 'POST', body: fd });
        const data = await res.json();
        if (!data.success) throw new Error(data.message || 'Rename failed');
        await load();
      } catch (err) {
        setError(err.message || 'Rename failed');
      }
    }

    async function onDelete(item) {
      if (!window.confirm(`確定刪除 ${item.name} ?`)) return;
      const fd = new FormData();
      fd.append('case_id', caseId);
      fd.append('path', item.path);
      try {
        const res = await fetch(`${apiBase}/delete.php`, { method: 'POST', body: fd });
        const data = await res.json();
        if (!data.success) throw new Error(data.message || 'Delete failed');
        await load();
      } catch (err) {
        setError(err.message || 'Delete failed');
      }
    }

    uploadInput.addEventListener('change', (e) => {
      const files = Array.from(e.target.files || []);
      files.forEach(onUpload);
      uploadInput.value = '';
    });

    // Drag and drop
    if (uploadList) {
      uploadList.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadList.classList.add('drag');
      });
      uploadList.addEventListener('dragleave', () => {
        uploadList.classList.remove('drag');
      });
      uploadList.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadList.classList.remove('drag');
        const files = Array.from(e.dataTransfer.files || []);
        files.forEach(onUpload);
      });
    }

    load();
  }

  window.FileManager = { init };
})();
