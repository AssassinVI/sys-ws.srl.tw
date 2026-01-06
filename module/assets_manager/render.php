<?php
/**
 * 資源管理 - 渲染類別
 * 用於生成 JS/CSS 標籤
 */

require_once dirname(__FILE__) . '/../../core/inc/pdo_fun_calss.php';

class AssetsRender {
    private $pdo_fun;
    private $case_id;
    
    public function __construct($case_id) {
        $this->case_id = $case_id;
        $this->pdo_fun = new PDO_fun('website');
    }
    
    /**
     * 取得案件所有資源(套件+自訂)
     * @param string|null $position 載入位置 (head, body_top, body_bottom)
     * @param string|null $type 資源類型 (js, css)
     * @return array
     */
    public function getAssets($position = null, $type = null) {
        // 查詢套件庫資源
        $sql = "SELECT al.*, ca.load_order as case_order, ca.is_enabled, ca.custom_params
                FROM case_assets ca
                INNER JOIN assets_library al ON ca.asset_id = al.Tb_index
                WHERE ca.case_id = :case_id AND ca.is_enabled = 1 AND al.OnLineOrNot = 1";
        
        $where = ['case_id' => $this->case_id];
        
        if ($type) {
            $sql .= " AND al.asset_type = :asset_type";
            $where['asset_type'] = $type;
        }
        if ($position) {
            $sql .= " AND al.load_position = :load_position";
            $where['load_position'] = $position;
        }
        
        $sql .= " ORDER BY COALESCE(ca.load_order, al.load_order) ASC";
        
        $library_assets = $this->pdo_fun->select($sql, $where, 'all');
        
        // 查詢自訂資源
        $sql_custom = "SELECT * FROM custom_assets 
                       WHERE case_id = :case_id AND is_enabled = 1";
        
        $where_custom = ['case_id' => $this->case_id];
        
        if ($type) {
            $sql_custom .= " AND asset_type = :asset_type";
            $where_custom['asset_type'] = $type;
        }
        if ($position) {
            $sql_custom .= " AND load_position = :load_position";
            $where_custom['load_position'] = $position;
        }
        
        $sql_custom .= " ORDER BY load_order ASC";
        
        $custom_assets = $this->pdo_fun->select($sql_custom, $where_custom, 'all');
        
        // 確保返回陣列格式
        if (!is_array($library_assets)) $library_assets = [];
        if (!is_array($custom_assets)) $custom_assets = [];
        
        return [
            'library' => $library_assets,
            'custom' => $custom_assets
        ];
    }
    
    /**
     * 渲染 HTML 標籤
     * @param string $position 載入位置
     * @return string HTML 標籤
     */
    public function render($position = 'body_bottom') {
        $assets = $this->getAssets($position);
        $output = "\n<!-- ===== Assets Manager [{$position}] ===== -->\n";
        
        // 輸出套件庫資源
        foreach ($assets['library'] as $asset) {
            if ($asset['asset_type'] == 'css') {
                $output .= '<link rel="stylesheet" href="'.$asset['file_path'].'" data-asset="'.$asset['asset_name'].'" data-asset-id="'.$asset['Tb_index'].'">'."\n";
            } else {
                $output .= '<script src="'.$asset['file_path'].'" data-asset="'.$asset['asset_name'].'" data-asset-id="'.$asset['Tb_index'].'"></script>'."\n";
            }
        }
        
        // 輸出自訂資源
        foreach ($assets['custom'] as $asset) {
            if ($asset['asset_type'] == 'css') {
                $output .= '<style data-custom="'.$asset['asset_name'].'" data-custom-id="'.$asset['Tb_index'].'">'.$asset['content'].'</style>'."\n";
            } else {
                $output .= '<script data-custom="'.$asset['asset_name'].'" data-custom-id="'.$asset['Tb_index'].'">'.$asset['content'].'</script>'."\n";
            }
        }
        
        $output .= "<!-- ===== End Assets Manager ===== -->\n";
        return $output;
    }
    
    /**
     * 一次性渲染所有位置的資源
     * @return string HTML 標籤
     */
    public function renderAll() {
        $output = '';
        
        // 依照載入順序渲染
        $positions = ['head', 'body_top', 'body_bottom'];
        
        foreach ($positions as $position) {
            $output .= $this->render($position);
        }
        
        return $output;
    }
    
    /**
     * 取得資源計數
     * @return array
     */
    public function getAssetCount() {
        $sql = "SELECT COUNT(*) as total FROM case_assets 
                WHERE case_id = :case_id AND is_enabled = 1";
        $result = $this->pdo_fun->select($sql, ['case_id' => $this->case_id], 'one');
        
        $sql_custom = "SELECT COUNT(*) as total FROM custom_assets 
                       WHERE case_id = :case_id AND is_enabled = 1";
        $custom_result = $this->pdo_fun->select($sql_custom, ['case_id' => $this->case_id], 'one');
        
        $library_count = isset($result['total']) ? $result['total'] : 0;
        $custom_count = isset($custom_result['total']) ? $custom_result['total'] : 0;
        
        return [
            'library' => $library_count,
            'custom' => $custom_count,
            'total' => $library_count + $custom_count
        ];
    }
}
?>
