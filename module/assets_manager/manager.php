<?php include("../../core/page/header01.php"); ?>
<style type="text/css">
    .asset-item {
        padding: 15px;
        margin: 8px 0;
        border: 1px solid #ddd;
        border-radius: 4px;
        background: #fff;
        transition: all 0.3s ease;
    }
    
    .asset-item:hover {
        border-color: #3498db;
        box-shadow: 0 2px 8px rgba(52, 152, 219, 0.2);
    }
    
    .asset-item.disabled {
        opacity: 0.6;
        background-color: #f9f9f9;
    }
    
    .asset-category {
        margin: 30px 0;
        padding: 20px;
        border-left: 4px solid #3498db;
        background: #f8f9fa;
    }
    
    .asset-category h4 {
        margin-top: 0;
        margin-bottom: 15px;
        color: #2c3e50;
    }
    
    .asset-badge {
        display: inline-block;
        padding: 3px 8px;
        margin-left: 5px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: bold;
    }
    
    .asset-badge.js {
        background-color: #f1c40f;
        color: #fff;
    }
    
    .asset-badge.css {
        background-color: #3498db;
        color: #fff;
    }
    
    .asset-description {
        color: #7f8c8d;
        font-size: 12px;
        margin: 5px 0;
    }
    
    .asset-path {
        color: #27ae60;
        font-size: 11px;
        word-break: break-all;
        font-family: monospace;
    }
    
    .custom-asset-item {
        padding: 15px;
        margin: 8px 0;
        border: 1px solid #e0e0e0;
        border-radius: 4px;
        background: #fff;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .custom-asset-info h5 {
        margin: 0 0 5px 0;
    }
    
    .custom-asset-info p {
        margin: 0;
        font-size: 12px;
        color: #7f8c8d;
    }
    
    .asset-buttons {
        margin-top: 10px;
    }
    
    .asset-buttons .btn {
        margin-right: 5px;
    }
    
    .tabs-container {
        margin-top: 20px;
    }
    
    .tab-content {
        padding: 20px;
        background: #fff;
        border: 1px solid #ddd;
        border-top: none;
    }
    
    .no-assets {
        text-align: center;
        padding: 40px;
        color: #95a5a6;
    }
    
    .asset-checkbox {
        margin-right: 8px;
    }
    
    .filter-section {
        margin-bottom: 20px;
        padding: 15px;
        background: #ecf0f1;
        border-radius: 4px;
    }
</style>
<?php include("../../core/page/header02.php"); ?>

<?php
require_once '../../core/inc/pdo_fun_calss.php';

$pdo_fun = new PDO_fun('website');
$case_id = $_GET['case_id'] ?? '';

if (empty($case_id)) {
    echo '<div class="alert alert-danger">案件ID不能為空</div>';
    include("../../core/page/footer01.php");
    exit;
}

// 取得案件名稱
$sql = "SELECT aTitle FROM build_case WHERE Tb_index=:Tb_index";
$row_case = $pdo_fun->select($sql, ['Tb_index'=>$case_id], 'one');
if (!$row_case) {
    echo '<div class="alert alert-danger">案件不存在</div>';
    include("../../core/page/footer01.php");
    exit;
}
?>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-title">
                    <h3>
                        <i class="fa fa-cubes"></i> 資源管理
                        <small><?php echo $row_case['aTitle']; ?></small>
                    </h3>
                    <div class="ibox-tools">
                        <button class="btn btn-primary" id="btn-save-assets">
                            <i class="fa fa-save"></i> 儲存設定
                        </button>
                        <button class="btn btn-success" id="btn-add-custom">
                            <i class="fa fa-plus"></i> 新增自訂資源
                        </button>
                        <a href="admin.php" class="btn btn-info">
                            <i class="fa fa-cog"></i> 套件管理
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="tabs-container">
                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#tab-library">
                                <i class="fa fa-cube"></i> 套件庫
                                <span class="badge" id="library-count">0</span>
                            </a></li>
                            <li><a data-toggle="tab" href="#tab-custom">
                                <i class="fa fa-code"></i> 自訂資源
                                <span class="badge" id="custom-count">0</span>
                            </a></li>
                        </ul>
                        <div class="tab-content">
                            <!-- 套件庫 -->
                            <div id="tab-library" class="tab-pane active">
                                <div class="filter-section">
                                    <label>篩選:</label>
                                    <select id="filter-category" class="form-control" style="width: auto; display: inline-block;">
                                        <option value="">-- 所有分類 --</option>
                                    </select>
                                </div>
                                <div id="assets-library"></div>
                            </div>
                            
                            <!-- 自訂資源 -->
                            <div id="tab-custom" class="tab-pane">
                                <div id="custom-assets"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 自訂資源編輯 Modal -->
<div class="modal fade" id="modal-edit-custom" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-title">新增自訂資源</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form-custom-asset">
                <div class="modal-body">
                    <input type="hidden" id="custom-Tb_index">
                    
                    <div class="form-group">
                        <label>資源名稱 *</label>
                        <input type="text" class="form-control" id="custom-asset_name" required>
                    </div>
                    
                    <div class="form-group">
                        <label>資源類型 *</label>
                        <select class="form-control" id="custom-asset_type" required>
                            <option value="js">JavaScript</option>
                            <option value="css">CSS</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>載入位置 *</label>
                        <select class="form-control" id="custom-load_position" required>
                            <option value="head">Head (頁面頭部)</option>
                            <option value="body_top">Body Top (頁面開始)</option>
                            <option value="body_bottom" selected>Body Bottom (頁面結尾)</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>載入順序</label>
                        <input type="number" class="form-control" id="custom-load_order" value="200" min="1">
                        <small class="form-text text-muted">數字越小越優先載入</small>
                    </div>
                    
                    <div class="form-group">
                        <label>程式碼內容 *</label>
                        <textarea class="form-control" id="custom-content" rows="10" required style="font-family: monospace;"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">關閉</button>
                    <button type="button" class="btn btn-primary" id="btn-save-custom">儲存</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- 預覽 Modal -->
<div class="modal fade" id="modal-preview" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">預覽</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <pre id="preview-content" style="background: #f4f4f4; padding: 15px; border-radius: 4px; max-height: 400px; overflow: auto;"></pre>
            </div>
        </div>
    </div>
</div>

<?php include("../../core/page/footer01.php"); ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
const caseId = '<?php echo $case_id; ?>';
let allLibraryAssets = [];
let customAssets = [];

// 初始化
$(function() {
    loadLibrary();
    loadCustomAssets();
    loadCategories();
    updateAssetCount();
});

// 載入套件庫
function loadLibrary() {
    $.ajax({
        url: 'ajax_handler.php',
        type: 'POST',
        data: { action: 'get_library_assets', case_id: caseId },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                allLibraryAssets = response.data;
                renderLibrary(response.data, response.selected);
            }
        }
    });
}

// 載入分類
function loadCategories() {
    $.ajax({
        url: 'library_ajax.php',
        type: 'POST',
        data: { action: 'get_categories' },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                let html = '<option value="">-- 所有分類 --</option>';
                response.data.forEach(cat => {
                    html += '<option value="' + cat + '">' + cat + '</option>';
                });
                $('#filter-category').html(html);
            }
        }
    });
}

// 渲染套件庫
function renderLibrary(data, selected = []) {
    let html = '';
    const categories = {};
    
    // 依分類分組
    data.forEach(item => {
        const cat = item.asset_category || '其他';
        if (!categories[cat]) categories[cat] = [];
        categories[cat].push(item);
    });
    
    // 如果沒有資料
    if (Object.keys(categories).length === 0) {
        html = '<div class="no-assets"><p>沒有可用的套件</p></div>';
        $('#assets-library').html(html);
        return;
    }
    
    // 渲染各分類
    for (let cat in categories) {
        html += '<div class="asset-category"><h4>' + cat + '</h4>';
        
        categories[cat].forEach(asset => {
            const checked = selected.includes(asset.Tb_index) ? 'checked' : '';
            const icon = asset.asset_type === 'js' ? 'fa-file-code-o' : 'fa-css3';
            const badgeClass = asset.asset_type === 'js' ? 'js' : 'css';
            const badgeText = asset.asset_type.toUpperCase();
            
            html += `
                <div class="asset-item" data-asset-id="${asset.Tb_index}">
                    <label style="margin: 0; cursor: pointer;">
                        <input type="checkbox" class="asset-checkbox" 
                               value="${asset.Tb_index}" ${checked}>
                        <i class="fa ${icon}"></i>
                        <strong>${asset.asset_name}</strong>
                        <span class="asset-badge ${badgeClass}">${badgeText}</span>
                    </label>
                    <div class="asset-description">${asset.description || '(無描述)'}</div>
                    <div class="asset-path">
                        <i class="fa fa-link"></i> ${asset.file_path}
                    </div>
                    ${asset.version ? '<small class="text-muted">版本: ' + asset.version + '</small>' : ''}
                </div>
            `;
        });
        
        html += '</div>';
    }
    
    $('#assets-library').html(html);
    
    // 更新計數
    const checkedCount = $('.asset-checkbox:checked').length;
    $('#library-count').text(checkedCount);
}

// 套件篩選
$('#filter-category').change(function() {
    const category = $(this).val();
    
    if (category === '') {
        renderLibrary(allLibraryAssets);
    } else {
        const filtered = allLibraryAssets.filter(item => 
            item.asset_category === category
        );
        renderLibrary(filtered);
    }
});

// 載入自訂資源
function loadCustomAssets() {
    $.ajax({
        url: 'ajax_handler.php',
        type: 'POST',
        data: { action: 'get_custom_assets', case_id: caseId },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                customAssets = response.data;
                renderCustomAssets(response.data);
            }
        }
    });
}

// 渲染自訂資源
function renderCustomAssets(data) {
    let html = '';
    
    if (data.length === 0) {
        html = '<div class="no-assets"><p>還沒有自訂資源</p></div>';
    } else {
        data.forEach(asset => {
            const statusClass = asset.is_enabled ? 'success' : 'warning';
            const statusText = asset.is_enabled ? '已啟用' : '已停用';
            const icon = asset.asset_type === 'js' ? 'fa-file-code-o' : 'fa-css3';
            
            html += `
                <div class="custom-asset-item">
                    <div class="custom-asset-info">
                        <h5>
                            <i class="fa ${icon}"></i>
                            ${asset.asset_name}
                            <span class="label label-${statusClass}">${statusText}</span>
                        </h5>
                        <p>
                            <strong>類型:</strong> ${asset.asset_type.toUpperCase()} | 
                            <strong>位置:</strong> ${asset.load_position} |
                            <strong>順序:</strong> ${asset.load_order}
                        </p>
                    </div>
                    <div class="asset-buttons">
                        <button class="btn btn-xs btn-info btn-preview" data-id="${asset.Tb_index}">
                            <i class="fa fa-eye"></i> 預覽
                        </button>
                        <button class="btn btn-xs btn-primary btn-edit" data-id="${asset.Tb_index}">
                            <i class="fa fa-edit"></i> 編輯
                        </button>
                        <button class="btn btn-xs btn-warning btn-toggle" data-id="${asset.Tb_index}">
                            <i class="fa fa-toggle-on"></i> ${asset.is_enabled ? '停用' : '啟用'}
                        </button>
                        <button class="btn btn-xs btn-danger btn-delete" data-id="${asset.Tb_index}">
                            <i class="fa fa-trash"></i> 刪除
                        </button>
                    </div>
                </div>
            `;
        });
    }
    
    $('#custom-assets').html(html);
    
    // 綁定事件
    $('.btn-edit').click(editCustomAsset);
    $('.btn-delete').click(deleteCustomAsset);
    $('.btn-toggle').click(toggleCustomAsset);
    $('.btn-preview').click(previewCustomAsset);
    
    // 更新計數
    $('#custom-count').text(data.length);
}

// 新增自訂資源
$('#btn-add-custom').click(function() {
    $('#custom-Tb_index').val('');
    $('#form-custom-asset')[0].reset();
    $('#custom-load_order').val(200);
    $('#modal-title').text('新增自訂資源');
    $('#modal-edit-custom').modal('show');
});

// 編輯自訂資源
function editCustomAsset() {
    const id = $(this).data('id');
    const asset = customAssets.find(a => a.Tb_index == id);
    
    if (asset) {
        $('#custom-Tb_index').val(asset.Tb_index);
        $('#custom-asset_name').val(asset.asset_name);
        $('#custom-asset_type').val(asset.asset_type);
        $('#custom-load_position').val(asset.load_position);
        $('#custom-load_order').val(asset.load_order);
        $('#custom-content').val(asset.content);
        $('#modal-title').text('編輯自訂資源');
        $('#modal-edit-custom').modal('show');
    }
}

// 預覽自訂資源
function previewCustomAsset() {
    const id = $(this).data('id');
    const asset = customAssets.find(a => a.Tb_index == id);
    
    if (asset) {
        $('#preview-content').text(asset.content);
        $('#modal-preview').modal('show');
    }
}

// 保存自訂資源
$('#btn-save-custom').click(function() {
    const Tb_index = $('#custom-Tb_index').val();
    const data = {
        action: 'save_custom_asset',
        case_id: caseId,
        asset_name: $('#custom-asset_name').val(),
        asset_type: $('#custom-asset_type').val(),
        content: $('#custom-content').val(),
        load_position: $('#custom-load_position').val(),
        load_order: $('#custom-load_order').val()
    };
    
    if (Tb_index) {
        data.Tb_index = Tb_index;
    }
    
    $.ajax({
        url: 'ajax_handler.php',
        type: 'POST',
        data: data,
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                Swal.fire('成功', response.message, 'success');
                $('#modal-edit-custom').modal('hide');
                loadCustomAssets();
                updateAssetCount();
            }
        },
        error: function(xhr) {
            const response = JSON.parse(xhr.responseText);
            Swal.fire('錯誤', response.message, 'error');
        }
    });
});

// 刪除自訂資源
function deleteCustomAsset() {
    const id = $(this).data('id');
    
    Swal.fire({
        title: '確認刪除',
        text: '確定要刪除此資源嗎？',
        type: 'warning',
        showCancelButton: true,
        confirmButtonText: '刪除',
        cancelButtonText: '取消'
    }, function(isConfirm) {
        if (isConfirm) {
            $.ajax({
                url: 'ajax_handler.php',
                type: 'POST',
                data: {
                    action: 'delete_custom_asset',
                    case_id: caseId,
                    Tb_index: id
                },
                dataType: 'json',
                success: function(response) {
                    Swal.fire('成功', response.message, 'success');
                    loadCustomAssets();
                    updateAssetCount();
                }
            });
        }
    });
}

// 切換自訂資源狀態
function toggleCustomAsset() {
    const id = $(this).data('id');
    
    $.ajax({
        url: 'ajax_handler.php',
        type: 'POST',
        data: {
            action: 'toggle_custom_asset',
            case_id: caseId,
            Tb_index: id
        },
        dataType: 'json',
        success: function(response) {
            loadCustomAssets();
            updateAssetCount();
        }
    });
}

// 儲存套件設定
$('#btn-save-assets').click(function() {
    const selected = [];
    $('.asset-checkbox:checked').each(function() {
        selected.push($(this).val());
    });
    
    $.ajax({
        url: 'ajax_handler.php',
        type: 'POST',
        data: { 
            action: 'save_case_assets',
            case_id: caseId,
            assets: selected
        },
        dataType: 'json',
        success: function(response) {
            Swal.fire('成功', response.message, 'success');
            updateAssetCount();
        },
        error: function(xhr) {
            const response = JSON.parse(xhr.responseText);
            Swal.fire('錯誤', response.message, 'error');
        }
    });
});

// 更新資源計數
function updateAssetCount() {
    $.ajax({
        url: 'ajax_handler.php',
        type: 'POST',
        data: {
            action: 'get_asset_count',
            case_id: caseId
        },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                const count = response.count;
                $('#library-count').text(count.library);
                $('#custom-count').text(count.custom);
            }
        }
    });
}

// 監聽複選框變化
$(document).on('change', '.asset-checkbox', function() {
    $('#library-count').text($('.asset-checkbox:checked').length);
});
</script>

<?php include("../../core/page/footer02.php"); ?>
