<?php include("../../core/page/header01.php"); ?>
<style type="text/css">
    .library-item {
        padding: 15px;
        margin: 8px 0;
        border: 1px solid #ddd;
        border-radius: 4px;
        background: #fff;
        transition: all 0.3s ease;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .library-item:hover {
        border-color: #3498db;
        box-shadow: 0 2px 8px rgba(52, 152, 219, 0.2);
    }
    
    .library-item.default {
        border-left: 4px solid #f39c12;
        background: #fffaf0;
    }
    
    .library-info h5 {
        margin: 0 0 5px 0;
        word-break: break-word;
    }
    
    .library-info p {
        margin: 0;
        font-size: 12px;
        color: #7f8c8d;
    }
    
    .library-badges {
        margin: 5px 0;
    }
    
    .badge-asset-type {
        display: inline-block;
        padding: 3px 8px;
        margin-right: 5px;
        border-radius: 3px;
        font-size: 11px;
        font-weight: bold;
    }
    
    .badge-asset-type.js {
        background-color: #f1c40f;
        color: #fff;
    }
    
    .badge-asset-type.css {
        background-color: #3498db;
        color: #fff;
    }
    
    .badge-default {
        background-color: #f39c12;
        color: #fff;
        font-size: 11px;
        padding: 3px 8px;
    }
    
    .library-path {
        color: #27ae60;
        font-size: 11px;
        word-break: break-all;
        font-family: monospace;
        margin-top: 5px;
    }
    
    .library-buttons {
        margin-top: 10px;
    }
    
    .library-buttons .btn {
        margin-right: 5px;
    }
    
    .stats-card {
        padding: 20px;
        margin: 15px 0;
        border-radius: 4px;
        text-align: center;
        color: #fff;
    }
    
    .stats-card.total {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .stats-card.js {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }
    
    .stats-card.css {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }
    
    .stats-card.default {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    }
    
    .stats-card h3 {
        margin: 0;
        font-size: 32px;
    }
    
    .stats-card p {
        margin: 0;
        font-size: 14px;
        opacity: 0.9;
    }
    
    .filter-section {
        margin-bottom: 20px;
        padding: 15px;
        background: #ecf0f1;
        border-radius: 4px;
    }
    
    .filter-section label {
        margin-right: 10px;
        font-weight: bold;
    }
    
    .filter-section select {
        width: auto;
        display: inline-block;
        margin-right: 10px;
    }
    
    .no-libraries {
        text-align: center;
        padding: 40px;
        color: #95a5a6;
    }
    
    .search-section {
        margin-bottom: 20px;
    }
    
    .search-section input {
        width: 100%;
    }

</style>
<?php include("../../core/page/header02.php"); ?>

<?php
require_once '../../core/inc/pdo_fun_calss.php';

$pdo_fun = new PDO_fun('website');

// 統計資訊將由 AJAX 載入
$stats = [
    'total' => 0,
    'js' => 0,
    'css' => 0,
    'default' => 0
];

// 取得所有套件（用於分類篩選）
$sql = "SELECT * FROM assets_library WHERE OnLineOrNot=1 ORDER BY asset_category, load_order";
$libraries = $pdo_fun->select($sql, 'no', 'all');
?>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-title">
                    <h3>
                        <i class="fa fa-cubes"></i> 套件庫管理
                    </h3>
                    <div class="ibox-tools">
                        <button class="btn btn-success" id="btn-add-library">
                            <i class="fa fa-plus"></i> 新增套件
                        </button>
                        <button class="btn btn-info" id="btn-refresh-library" onclick="location.reload();">
                            <i class="fa fa-refresh"></i> 重新整理
                        </button>
                        
                    </div>
                </div>
                <div class="ibox-content">
                    <!-- 統計卡片 -->
                    <div class="row">
                        <div class="col-md-3">
                            <div class="stats-card total">
                                <h3 id="stat-total">0</h3>
                                <p>總套件數</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stats-card js">
                                <h3 id="stat-js">0</h3>
                                <p>JavaScript</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stats-card css">
                                <h3 id="stat-css">0</h3>
                                <p>CSS</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stats-card default">
                                <h3 id="stat-default">0</h3>
                                <p>預設套件</p>
                            </div>
                        </div>
                    </div>

                    <!-- 搜尋和篩選 -->
                    <div class="search-section">
                        <input type="text" id="search-library" class="form-control" placeholder="搜尋套件名稱或路徑...">
                    </div>

                    <div class="filter-section">
                        <label>篩選:</label>
                        <select id="filter-type" class="form-control">
                            <option value="">-- 所有類型 --</option>
                            <option value="js">JavaScript</option>
                            <option value="css">CSS</option>
                        </select>
                        <select id="filter-category" class="form-control">
                            <option value="">-- 所有分類 --</option>
                            <?php
                            $categories = array_unique(array_column($libraries, 'asset_category'));
                            foreach ($categories as $cat) {
                                echo '<option value="' . $cat . '">' . $cat . '</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <!-- 套件列表 -->
                    <div id="libraries-list"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 新增/編輯套件 Modal -->
<div class="modal fade" id="modal-edit-library" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-title">新增套件</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form-library">
                <div class="modal-body">
                    <input type="hidden" id="lib-Tb_index">

                    <div class="form-group">
                        <label>套件名稱 *</label>
                        <input type="text" class="form-control" id="lib-asset_name" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>資源類型 *</label>
                                <select class="form-control" id="lib-asset_type" required>
                                    <option value="js">JavaScript</option>
                                    <option value="css">CSS</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>分類</label>
                                <input type="text" class="form-control" id="lib-asset_category" placeholder="例: UI框架">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>檔案路徑 (CDN或本地) *</label>
                        <input type="text" class="form-control" id="lib-file_path" required
                               placeholder="https://cdn.example.com/lib.min.js">
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>版本</label>
                                <input type="text" class="form-control" id="lib-version" placeholder="1.0.0">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>載入位置 *</label>
                                <select class="form-control" id="lib-load_position" required>
                                    <option value="head">Head (頁面頭部)</option>
                                    <option value="body_top">Body Top (頁面開始)</option>
                                    <option value="body_bottom" selected>Body Bottom (頁面結尾)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>載入順序</label>
                                <input type="number" class="form-control" id="lib-load_order" value="100" min="1">
                                <small class="form-text text-muted">數字越小越優先載入</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="checkbox_div">
                                    <label>
                                        <input type="checkbox" id="lib-is_default">設為預設套件
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>描述</label>
                        <textarea class="form-control" id="lib-description" rows="3"></textarea>
                    </div>

                    <div class="form-group">
                        <label>依賴套件 (JSON格式，選填)</label>
                        <textarea class="form-control" id="lib-dependencies" rows="2"
                                  placeholder='{"requires":["lib_id1","lib_id2"]}'></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">關閉</button>
                    <button type="button" class="btn btn-primary" id="btn-save-library">儲存</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include("../../core/page/footer01.php"); ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
let allLibraries = [];

// 初始化
$(function() {
    loadStats();
    loadLibraries();
});

// 載入統計資訊
function loadStats() {
    $.ajax({
        url: 'library_ajax.php',
        type: 'POST',
        data: { action: 'get_library_stats' },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                const stats = response.stats;
                $('#stat-total').text(stats.total);
                $('#stat-js').text(stats.js);
                $('#stat-css').text(stats.css);
                $('#stat-default').text(stats.default);
            }
        },
        error: function() {
            console.error('載入統計資訊失敗');
        }
    });
}

// 載入套件列表
function loadLibraries() {
    $.ajax({
        url: 'library_ajax.php',
        type: 'POST',
        data: { action: 'get_all_library' },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                allLibraries = response.data;
                renderLibraries(response.data);
            }
        }
    });
}

// 渲染套件列表
function renderLibraries(data) {
    let html = '';

    if (data.length === 0) {
        html = '<div class="no-libraries"><p>沒有套件</p></div>';
    } else {
        data.forEach(lib => {
            // console.log(lib);
            const icon = lib.asset_type === 'js' ? 'fa-file-code-o' : 'fa-css3';
            const typeClass = lib.asset_type === 'js' ? 'js' : 'css';
            const defaultClass = lib.is_default== 1 ? 'default' : '';
            const defaultBadge = lib.is_default== 1 ? '<span class="badge badge-default">預設</span>' : '';

            html += `
                <div class="library-item ${defaultClass}" data-lib-id="${lib.Tb_index}">
                    <div class="library-info">
                        <h5>
                            <i class="fa ${icon}"></i>
                            ${lib.asset_name}
                            ${defaultBadge}
                        </h5>
                        <p>
                            <strong>ID:</strong> ${lib.Tb_index} | 
                            <strong>分類:</strong> ${lib.asset_category || '未分類'} | 
                            <strong>順序:</strong> ${lib.load_order}
                        </p>
                        <div class="library-badges">
                            <span class="badge-asset-type ${typeClass}">${lib.asset_type.toUpperCase()}</span>
                            <span class="label label-info">${lib.load_position}</span>
                        </div>
                        ${lib.description ? '<p style="margin-top: 5px;">' + lib.description + '</p>' : ''}
                        <div class="library-path">
                            <i class="fa fa-link"></i> ${lib.file_path}
                        </div>
                        ${lib.version ? '<small class="text-muted">版本: ' + lib.version + '</small>' : ''}
                    </div>
                    <div class="library-buttons">
                        <button class="btn btn-xs btn-primary btn-edit-lib" data-id="${lib.Tb_index}">
                            <i class="fa fa-edit"></i> 編輯
                        </button>
                        <button class="btn btn-xs btn-danger btn-delete-lib" data-id="${lib.Tb_index}">
                            <i class="fa fa-trash"></i> 刪除
                        </button>
                    </div>
                </div>
            `;
        });
    }

    $('#libraries-list').html(html);

    // 綁定事件
    $('.btn-edit-lib').click(editLibrary);
    $('.btn-delete-lib').click(deleteLibrary);
}

// 新增套件
$('#btn-add-library').click(function() {
    $('#lib-Tb_index').val('');
    $('#form-library')[0].reset();
    $('#lib-load_order').val(100);
    $('#lib-load_position').val('body_bottom');
    $('#modal-title').text('新增套件');
    $('#modal-edit-library').modal('show');
    $('#lib-asset_type').val('').prop('disabled', false);
});

// 編輯套件
function editLibrary() {
    const id = $(this).data('id');
    const lib = allLibraries.find(l => l.Tb_index === id);

    if (lib) {
        $.ajax({
            url: 'library_ajax.php',
            type: 'POST',
            data: { action: 'get_library_detail', Tb_index: id },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    const data = response.data;
                    $('#lib-Tb_index').val(data.Tb_index);
                    $('#lib-asset_name').val(data.asset_name);
                    $('#lib-asset_type').val(data.asset_type).prop('disabled', true);
                    $('#lib-asset_category').val(data.asset_category);
                    $('#lib-file_path').val(data.file_path);
                    $('#lib-version').val(data.version);
                    $('#lib-load_position').val(data.load_position);
                    $('#lib-load_order').val(data.load_order);
                    $('#lib-description').val(data.description);
                    $('#lib-dependencies').val(data.dependencies);
                    $('#lib-is_default').prop('checked', data.is_default == 1);
                    $('#modal-title').text('編輯套件');
                    $('#modal-edit-library').modal('show');
                }
            }
        });
    }
}

// 保存套件
$('#btn-save-library').click(function() {
    const Tb_index = $('#lib-Tb_index').val();
    const data = {
        asset_name: $('#lib-asset_name').val(),
        asset_type: $('#lib-asset_type').val(),
        asset_category: $('#lib-asset_category').val(),
        file_path: $('#lib-file_path').val(),
        version: $('#lib-version').val(),
        load_position: $('#lib-load_position').val(),
        load_order: $('#lib-load_order').val(),
        description: $('#lib-description').val(),
        dependencies: $('#lib-dependencies').val() || null,
        is_default: $('#lib-is_default').is(':checked') ? 1 : 0
    };

    const action = Tb_index ? 'edit_library' : 'add_library';
    if (Tb_index) {
        data.Tb_index = Tb_index;
    }
    data.action = action;

    $.ajax({
        url: 'library_ajax.php',
        type: 'POST',
        data: data,
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                Swal.fire('成功', response.message, 'success');
                $('#modal-edit-library').modal('hide');
                loadStats();
                loadLibraries();
            }
        },
        error: function(xhr) {
            const response = JSON.parse(xhr.responseText);
            Swal.fire('錯誤', response.message, 'error');
        }
    });
});

// 刪除套件
function deleteLibrary() {
    const id = $(this).data('id');

    Swal.fire({
        title: '確認刪除',
        text: '確定要刪除此套件嗎？',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: '刪除',
        cancelButtonText: '取消',
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'library_ajax.php',
                type: 'POST',
                data: {
                    action: 'delete_library',
                    Tb_index: id
                },
                dataType: 'json',
                success: function(response) {
                    Swal.fire('成功', response.message, 'success');
                    loadStats();
                    loadLibraries();
                }
            });
        }
    });
}

// 搜尋功能
$('#search-library').keyup(function() {
    const searchTerm = $(this).val().toLowerCase();
    const filtered = allLibraries.filter(lib =>
        lib.asset_name.toLowerCase().includes(searchTerm) ||
        lib.file_path.toLowerCase().includes(searchTerm) ||
        (lib.description && lib.description.toLowerCase().includes(searchTerm))
    );
    renderLibraries(filtered);
});

// 篩選功能
$('#filter-type').change(applyFilters);
$('#filter-category').change(applyFilters);

function applyFilters() {
    const type = $('#filter-type').val();
    const category = $('#filter-category').val();

    let filtered = allLibraries;

    if (type) {
        filtered = filtered.filter(lib => lib.asset_type === type);
    }

    if (category) {
        filtered = filtered.filter(lib => lib.asset_category === category);
    }

    renderLibraries(filtered);
}
</script>


<?php  include("../../core/page/footer02.php");//載入頁面footer02.php?>
