<?php
/**
 * 案件資源管理 - AJAX 處理
 */

require_once '../../core/inc/config.php';
require_once '../../core/inc/pdo_fun_calss.php';
require_once './render.php';

header('Content-Type: application/json; charset=utf-8');

$pdo_fun = new PDO_fun('website');
$action = $_POST['action'] ?? $_GET['action'] ?? '';

try {
    switch ($action) {
        // 取得案件的套件庫資源列表
        case 'get_library_assets':
            $case_id = $_POST['case_id'] ?? '';
            
            if (empty($case_id)) {
                throw new Exception('案件ID不能為空');
            }
            
            // 取得所有套件
            $sql_all = "SELECT * FROM assets_library WHERE OnLineOrNot=1 ORDER BY asset_category, load_order";
            $all_assets = $pdo_fun->select($sql_all, 'no', 'all');
            
            // 取得案件已選資源
            $sql_selected = "SELECT asset_id FROM case_assets WHERE case_id=:case_id";
            $selected = $pdo_fun->select($sql_selected, ['case_id' => $case_id], 'all');
            $selected_ids = array_column($selected ?? [], 'asset_id');
            
            echo json_encode([
                'status' => 'success',
                'data' => $all_assets ?? [],
                'selected' => $selected_ids
            ]);
            break;
            
        // 儲存案件資源設定
        case 'save_case_assets':
            $case_id = $_POST['case_id'] ?? '';
            $assets = $_POST['assets'] ?? [];
            
            if (empty($case_id)) {
                throw new Exception('案件ID不能為空');
            }
            
            try {
                // 先刪除舊設定
                $pdo_fun->delete_more('case_assets', ['case_id' => $case_id]);
                
                // 新增選中的資源
                foreach ($assets as $index => $asset_id) {
                    // 驗證資源是否存在
                    $check_sql = "SELECT Tb_index FROM assets_library WHERE Tb_index=:Tb_index AND OnLineOrNot=1";
                    $check = $pdo_fun->select($check_sql, ['Tb_index' => $asset_id], 'one');
                    
                    if ($check) {
                        $data = [
                            'case_id' => $case_id,
                            'asset_id' => $asset_id,
                            'is_enabled' => 1,
                            'load_order' => ($index + 1) * 10
                        ];
                        $pdo_fun->insert('case_assets', $data);
                    }
                }
                
                echo json_encode([
                    'status' => 'success',
                    'message' => '設定已更新'
                ]);
            } catch (Exception $e) {
                throw $e;
            }
            break;
            
        // 取得案件的自訂資源列表
        case 'get_custom_assets':
            $case_id = $_POST['case_id'] ?? '';
            
            if (empty($case_id)) {
                throw new Exception('案件ID不能為空');
            }
            
            $sql = "SELECT * FROM custom_assets WHERE case_id=:case_id ORDER BY asset_type, load_order";
            $data = $pdo_fun->select($sql, ['case_id' => $case_id], 'all');
            
            echo json_encode([
                'status' => 'success',
                'data' => $data ?? []
            ]);
            break;
            
        // 新增或編輯自訂資源
        case 'save_custom_asset':
            $case_id = $_POST['case_id'] ?? '';
            $asset_name = $_POST['asset_name'] ?? '';
            $asset_type = $_POST['asset_type'] ?? 'js';
            $content = $_POST['content'] ?? '';
            $load_position = $_POST['load_position'] ?? 'body_bottom';
            $load_order = $_POST['load_order'] ?? 200;
            $Tb_index = $_POST['Tb_index'] ?? '';
            
            if (empty($case_id) || empty($asset_name) || empty($content)) {
                throw new Exception('必填欄位不能為空');
            }
            
            if (!empty($Tb_index)) {
                // 編輯
                $data = [
                    'asset_name' => $asset_name,
                    'asset_type' => $asset_type,
                    'content' => $content,
                    'load_position' => $load_position,
                    'load_order' => $load_order
                ];
                $where = ['Tb_index' => $Tb_index, 'case_id' => $case_id];
                $pdo_fun->update('custom_assets', $data, $where);
                
                echo json_encode([
                    'status' => 'success',
                    'message' => '資源已更新',
                    'Tb_index' => $Tb_index
                ]);
            } else {
                // 新增
                $data = [
                    'case_id' => $case_id,
                    'asset_name' => $asset_name,
                    'asset_type' => $asset_type,
                    'content' => $content,
                    'load_position' => $load_position,
                    'load_order' => $load_order,
                    'is_enabled' => 1
                ];
                $last_id = $pdo_fun->insertid('custom_assets', $data);
                
                echo json_encode([
                    'status' => 'success',
                    'message' => '資源已新增',
                    'Tb_index' => $last_id
                ]);
            }
            break;
            
        // 刪除自訂資源
        case 'delete_custom_asset':
            $Tb_index = $_POST['Tb_index'] ?? '';
            $case_id = $_POST['case_id'] ?? '';
            
            if (empty($Tb_index) || empty($case_id)) {
                throw new Exception('ID不能為空');
            }
            
            $pdo_fun->delete_more('custom_assets', ['Tb_index' => $Tb_index, 'case_id' => $case_id]);
            
            echo json_encode([
                'status' => 'success',
                'message' => '資源已刪除'
            ]);
            break;
            
        // 切換資源啟用狀態
        case 'toggle_custom_asset':
            $Tb_index = $_POST['Tb_index'] ?? '';
            $case_id = $_POST['case_id'] ?? '';
            
            if (empty($Tb_index) || empty($case_id)) {
                throw new Exception('ID不能為空');
            }
            
            // 取得現在狀態
            $sql_check = "SELECT is_enabled FROM custom_assets WHERE Tb_index=:Tb_index AND case_id=:case_id";
            $result = $pdo_fun->select($sql_check, ['Tb_index' => $Tb_index, 'case_id' => $case_id], 'one');
            
            if (!$result) {
                throw new Exception('資源不存在');
            }
            
            $new_status = $result['is_enabled'] ? 0 : 1;
            
            $data = ['is_enabled' => $new_status];
            $where = ['Tb_index' => $Tb_index, 'case_id' => $case_id];
            $pdo_fun->update('custom_assets', $data, $where);
            
            echo json_encode([
                'status' => 'success',
                'message' => '狀態已更新',
                'is_enabled' => $new_status
            ]);
            break;
            
        // 取得資源計數
        case 'get_asset_count':
            $case_id = $_POST['case_id'] ?? '';
            
            if (empty($case_id)) {
                throw new Exception('案件ID不能為空');
            }
            
            $renderer = new AssetsRender($case_id);
            $count = $renderer->getAssetCount();
            
            echo json_encode([
                'status' => 'success',
                'count' => $count
            ]);
            break;
            
        // 取得資源預覽
        case 'preview_custom_asset':
            $Tb_index = $_POST['Tb_index'] ?? '';
            $case_id = $_POST['case_id'] ?? '';
            
            if (empty($Tb_index) || empty($case_id)) {
                throw new Exception('ID不能為空');
            }
            
            $sql = "SELECT * FROM custom_assets WHERE Tb_index=:Tb_index AND case_id=:case_id";
            $data = $pdo_fun->select($sql, ['Tb_index' => $Tb_index, 'case_id' => $case_id], 'one');
            
            if (!$data) {
                throw new Exception('資源不存在');
            }
            
            echo json_encode([
                'status' => 'success',
                'data' => $data
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
