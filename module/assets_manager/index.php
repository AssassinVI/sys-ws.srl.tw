<?php
$caseId = isset($_GET['case_id']) ? preg_replace('/[^A-Za-z0-9_-]/', '', $_GET['case_id']) : 'demo';
?>
<!doctype html>
<html lang="zh-TW">
<head>
  <meta charset="UTF-8">
  <title>簡易檔案管理器</title>
  <link rel="stylesheet" href="../../css/file-manager.css">
  <!-- FancyBox CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.css">
  <style>
    body { margin: 16px; background: #eef2f7; }
  </style>
</head>
<body>
  <div class="fm-app" id="fm-app">
    <div class="fm-toolbar">
      <label class="fm-btn" for="fm-upload">上傳圖片/影片</label>
      <input id="fm-upload" type="file" accept="image/*,video/*" multiple style="display:none" data-fm-upload>
      <span class="fm-path" data-fm-path></span>
      <span class="fm-error" data-fm-error style="display:none"></span>
    </div>
    <div class="fm-upload-area" data-fm-upload-list>
      <p style="margin:0; color: #6b7280;">拖曳圖片/影片至此或按上傳按鈕</p>
    </div>
    <div class="fm-grid" data-fm-list></div>
  </div>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- FancyBox JS -->
  <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.umd.js"></script>
  <script src="../../js/file-manager.js"></script>
  <script>
    FileManager.init({
      container: '#fm-app',
      apiBase: '/core/api/file_manager',
      caseId: '<?php echo $caseId; ?>'
    });
  </script>
</body>
</html>
