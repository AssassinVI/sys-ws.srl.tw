<?php
/**
 * 套件庫管理 - AJAX 處理
 */

require_once '../../core/inc/config.php';
require_once '../../core/inc/pdo_fun_calss.php';

header('Content-Type: application/json; charset=utf-8');

$pdo_fun = new PDO_fun('website');
$action = $_POST['action'] ?? $_GET['action'] ?? '';

try {
    switch ($action) {
        // 取得所有套件
        case 'get_all_library':
            $category = $_POST['category'] ?? '';
            $type = $_POST['type'] ?? '';
            
            $sql = "SELECT * FROM assets_library WHERE OnLineOrNot=1";
            $where = [];
            
            if (!empty($category)) {
                $sql .= " AND asset_category=:asset_category";
                $where['asset_category'] = $category;
            }
            
            if (!empty($type)) {
                $sql .= " AND asset_type=:asset_type";
                $where['asset_type'] = $type;
            }
            
            $sql .= " ORDER BY asset_category, load_order";
            
            $data = $pdo_fun->select($sql, empty($where) ? 'no' : $where, 'all');
            
            echo json_encode([
                'status' => 'success',
                'data' => $data ?? []
            ]);
            break;
            
        // 取得套件分類
        case 'get_categories':
            $sql = "SELECT DISTINCT asset_category FROM assets_library 
                   WHERE OnLineOrNot=1 AND asset_category IS NOT NULL 
                   ORDER BY asset_category";
            $results = $pdo_fun->select($sql, 'no', 'all');
            $categories = array_column($results ?? [], 'asset_category');
            
            echo json_encode([
                'status' => 'success',
                'data' => $categories
            ]);
            break;
            
        // 新增套件
        case 'add_library':
            $asset_name = $_POST['asset_name'] ?? '';
            $asset_type = $_POST['asset_type'] ?? 'js';
            $asset_category = $_POST['asset_category'] ?? '';
            $file_path = $_POST['file_path'] ?? '';
            $version = $_POST['version'] ?? '';
            $load_position = $_POST['load_position'] ?? 'body_bottom';
            $load_order = $_POST['load_order'] ?? 100;
            $description = $_POST['description'] ?? '';
            $dependencies = $_POST['dependencies'] ?? NULL;
            $is_default = $_POST['is_default'] ?? 0;
            
            if (empty($asset_name) || empty($file_path)) {
                throw new Exception('必填欄位不能為空');
            }
            
            // 產生 Tb_index
            $prefix = substr($asset_type, 0, 1) . 'L';
            $Tb_index = $prefix . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT);
            
            // 檢查 Tb_index 是否已存在
            while (true) {
                $check_sql = "SELECT COUNT(*) as cnt FROM assets_library WHERE Tb_index=:Tb_index";
                $check_result = $pdo_fun->select($check_sql, ['Tb_index' => $Tb_index], 'one');
                
                if (($check_result['cnt'] ?? 0) == 0) break;
                
                $Tb_index = $prefix . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT);
            }
            
            $data = [
                'Tb_index' => $Tb_index,
                'asset_name' => $asset_name,
                'asset_type' => $asset_type,
                'asset_category' => $asset_category,
                'file_path' => $file_path,
                'version' => $version,
                'load_position' => $load_position,
                'load_order' => $load_order,
                'description' => $description,
                'dependencies' => $dependencies,
                'is_default' => $is_default,
                'OnLineOrNot' => 1
            ];
            
            $pdo_fun->insert('assets_library', $data);
            
            echo json_encode([
                'status' => 'success',
                'message' => '套件已新增',
                'Tb_index' => $Tb_index
            ]);
            break;
            
        // 編輯套件
        case 'edit_library':
            $Tb_index = $_POST['Tb_index'] ?? '';
            $asset_name = $_POST['asset_name'] ?? '';
            $asset_category = $_POST['asset_category'] ?? '';
            $file_path = $_POST['file_path'] ?? '';
            $version = $_POST['version'] ?? '';
            $load_position = $_POST['load_position'] ?? 'body_bottom';
            $load_order = $_POST['load_order'] ?? 100;
            $description = $_POST['description'] ?? '';
            $dependencies = $_POST['dependencies'] ?? NULL;
            $is_default = $_POST['is_default'] ?? 0;
            
            if (empty($Tb_index) || empty($asset_name) || empty($file_path)) {
                throw new Exception('必填欄位不能為空');
            }
            
            $data = [
                'asset_name' => $asset_name,
                'asset_category' => $asset_category,
                'file_path' => $file_path,
                'version' => $version,
                'load_position' => $load_position,
                'load_order' => $load_order,
                'description' => $description,
                'dependencies' => $dependencies,
                'is_default' => $is_default
            ];
            
            $where = ['Tb_index' => $Tb_index];
            $pdo_fun->update('assets_library', $data, $where);
            
            echo json_encode([
                'status' => 'success',
                'message' => '套件已更新'
            ]);
            break;
            
        // 刪除套件（軟刪除）
        case 'delete_library':
            $Tb_index = $_POST['Tb_index'] ?? '';
            
            if (empty($Tb_index)) {
                throw new Exception('套件ID不能為空');
            }
            
            $data = ['OnLineOrNot' => 0];
            $where = ['Tb_index' => $Tb_index];
            $pdo_fun->update('assets_library', $data, $where);
            
            echo json_encode([
                'status' => 'success',
                'message' => '套件已刪除'
            ]);
            break;
            
        // 取得套件詳情
        case 'get_library_detail':
            $Tb_index = $_POST['Tb_index'] ?? '';
            
            if (empty($Tb_index)) {
                throw new Exception('套件ID不能為空');
            }
            
            $sql = "SELECT * FROM assets_library WHERE Tb_index=:Tb_index";
            $data = $pdo_fun->select($sql, ['Tb_index' => $Tb_index], 'one');
            
            if (!$data) {
                throw new Exception('套件不存在');
            }
            
            echo json_encode([
                'status' => 'success',
                'data' => $data
            ]);
            break;
            
        // 取得預設套件清單
        case 'get_default_library':
            $sql = "SELECT Tb_index, asset_name, asset_type, asset_category, file_path, version
                   FROM assets_library 
                   WHERE OnLineOrNot=1 AND is_default=1
                   ORDER BY asset_category, load_order";
            
            $data = $pdo_fun->select($sql, 'no', 'all');
            
            echo json_encode([
                'status' => 'success',
                'data' => $data ?? []
            ]);
            break;
            
        // 統計套件
        case 'get_library_stats':
            $sql_total = "SELECT COUNT(*) as cnt FROM assets_library WHERE OnLineOrNot=1";
            $total = $pdo_fun->select($sql_total, 'no', 'one');
            
            $sql_js = "SELECT COUNT(*) as cnt FROM assets_library WHERE OnLineOrNot=1 AND asset_type='js'";
            $js_count = $pdo_fun->select($sql_js, 'no', 'one');
            
            $sql_css = "SELECT COUNT(*) as cnt FROM assets_library WHERE OnLineOrNot=1 AND asset_type='css'";
            $css_count = $pdo_fun->select($sql_css, 'no', 'one');
            
            $sql_default = "SELECT COUNT(*) as cnt FROM assets_library WHERE OnLineOrNot=1 AND is_default=1";
            $default_count = $pdo_fun->select($sql_default, 'no', 'one');
            
            echo json_encode([
                'status' => 'success',
                'stats' => [
                    'total' => $total['cnt'] ?? 0,
                    'js' => $js_count['cnt'] ?? 0,
                    'css' => $css_count['cnt'] ?? 0,
                    'default' => $default_count['cnt'] ?? 0
                ]
            ]);
            break;
            
        // 批量設定為預設
        case 'set_default_batch':
            $asset_ids = $_POST['asset_ids'] ?? [];
            
            if (empty($asset_ids)) {
                throw new Exception('請選擇至少一個套件');
            }
            
            // 先清除所有預設
            $data_clear = ['is_default' => 0];
            $pdo_fun->update('assets_library', $data_clear, ['Tb_index' => 'any']);
            
            // 設定新的預設
            foreach ($asset_ids as $asset_id) {
                $data_set = ['is_default' => 1];
                $where_set = ['Tb_index' => $asset_id];
                $pdo_fun->update('assets_library', $data_set, $where_set);
            }
            
            echo json_encode([
                'status' => 'success',
                'message' => '預設套件已更新'
            ]);
            break;
            
        default:
            throw new Exception('未知的操作: ' . $action);
    }
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
?>
