<?php
/**
 * 資源管理系統 - 資料庫初始化
 * 在 SQL 中執行這個腳本來建立相關表格
 */

// 如果在 PHP 中執行，可以用這個函數
function initAssetsDatabase($pdo) {
    try {
        // 1. 建立套件庫表
        $sql_assets_library = "
        CREATE TABLE IF NOT EXISTS `assets_library` (
          `Tb_index` VARCHAR(50) PRIMARY KEY COMMENT '主鍵',
          `asset_name` VARCHAR(100) NOT NULL COMMENT '套件名稱',
          `asset_type` ENUM('js', 'css') NOT NULL COMMENT '資源類型',
          `asset_category` VARCHAR(50) DEFAULT NULL COMMENT '套件分類',
          `file_path` VARCHAR(500) DEFAULT NULL COMMENT 'CDN或本地路徑',
          `version` VARCHAR(20) DEFAULT NULL COMMENT '版本號',
          `load_position` ENUM('head', 'body_top', 'body_bottom') DEFAULT 'body_bottom' COMMENT '載入位置',
          `load_order` INT DEFAULT 100 COMMENT '載入順序',
          `description` TEXT COMMENT '套件說明',
          `dependencies` TEXT COMMENT '依賴關係(JSON格式)',
          `is_default` TINYINT(1) DEFAULT 0 COMMENT '是否為預設套件',
          `OnLineOrNot` TINYINT(1) DEFAULT 1 COMMENT '啟用狀態',
          `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
          `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='JS/CSS資源套件庫'
        ";
        
        $pdo->exec($sql_assets_library);
        echo "✓ 套件庫表建立成功\n";
        
        // 2. 建立案件資源關聯表
        $sql_case_assets = "
        CREATE TABLE IF NOT EXISTS `case_assets` (
          `Tb_index` INT AUTO_INCREMENT PRIMARY KEY COMMENT '主鍵',
          `case_id` VARCHAR(50) NOT NULL COMMENT '案件ID',
          `asset_id` VARCHAR(50) NOT NULL COMMENT '資源ID',
          `is_enabled` TINYINT(1) DEFAULT 1 COMMENT '是否啟用',
          `load_order` INT DEFAULT 100 COMMENT '此案件中的載入順序',
          `custom_params` TEXT COMMENT '自訂參數(JSON格式)',
          `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
          KEY `idx_case_id` (`case_id`),
          KEY `idx_asset_id` (`asset_id`),
          UNIQUE KEY `unique_case_asset` (`case_id`, `asset_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='案件與資源關聯表'
        ";
        
        $pdo->exec($sql_case_assets);
        echo "✓ 案件資源關聯表建立成功\n";
        
        // 3. 建立自訂資源表
        $sql_custom_assets = "
        CREATE TABLE IF NOT EXISTS `custom_assets` (
          `Tb_index` INT AUTO_INCREMENT PRIMARY KEY COMMENT '主鍵',
          `case_id` VARCHAR(50) NOT NULL COMMENT '案件ID',
          `asset_name` VARCHAR(100) NOT NULL COMMENT '自訂資源名稱',
          `asset_type` ENUM('js', 'css') NOT NULL COMMENT '資源類型',
          `content` LONGTEXT NOT NULL COMMENT '程式碼內容',
          `load_position` ENUM('head', 'body_top', 'body_bottom') DEFAULT 'body_bottom',
          `load_order` INT DEFAULT 200 COMMENT '載入順序',
          `is_enabled` TINYINT(1) DEFAULT 1 COMMENT '是否啟用',
          `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
          `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
          KEY `idx_case_id` (`case_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='自訂資源表'
        ";
        
        $pdo->exec($sql_custom_assets);
        echo "✓ 自訂資源表建立成功\n";
        
        // 4. 插入預設套件
        $default_libraries = [
            [
                'Tb_index' => 'jL0001',
                'asset_name' => 'jQuery 3.6',
                'asset_type' => 'js',
                'asset_category' => 'JavaScript框架',
                'file_path' => 'https://code.jquery.com/jquery-3.6.0.min.js',
                'version' => '3.6.0',
                'load_position' => 'head',
                'load_order' => 10,
                'description' => 'jQuery核心函式庫',
                'is_default' => 1
            ],
            [
                'Tb_index' => 'cL0001',
                'asset_name' => 'Bootstrap 5 CSS',
                'asset_type' => 'css',
                'asset_category' => 'UI框架',
                'file_path' => 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css',
                'version' => '5.1.3',
                'load_position' => 'head',
                'load_order' => 20,
                'description' => 'Bootstrap CSS框架',
                'is_default' => 1
            ],
            [
                'Tb_index' => 'jL0002',
                'asset_name' => 'Bootstrap 5 JS',
                'asset_type' => 'js',
                'asset_category' => 'UI框架',
                'file_path' => 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js',
                'version' => '5.1.3',
                'load_position' => 'body_bottom',
                'load_order' => 30,
                'description' => 'Bootstrap JavaScript',
                'dependencies' => '{"requires":["jL0001"]}',
                'is_default' => 1
            ],
            [
                'Tb_index' => 'cL0002',
                'asset_name' => 'Font Awesome',
                'asset_type' => 'css',
                'asset_category' => '圖標字型',
                'file_path' => 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css',
                'version' => '6.0.0',
                'load_position' => 'head',
                'load_order' => 25,
                'description' => '圖標字型庫',
                'is_default' => 1
            ],
            [
                'Tb_index' => 'cL0003',
                'asset_name' => 'Animate.css',
                'asset_type' => 'css',
                'asset_category' => '動畫效果',
                'file_path' => 'https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css',
                'version' => '4.1.1',
                'load_position' => 'head',
                'load_order' => 40,
                'description' => 'CSS動畫庫',
                'is_default' => 0
            ],
            [
                'Tb_index' => 'jL0003',
                'asset_name' => 'Swiper輪播 CSS',
                'asset_type' => 'css',
                'asset_category' => 'UI組件',
                'file_path' => 'https://unpkg.com/swiper/swiper-bundle.min.css',
                'version' => 'latest',
                'load_position' => 'head',
                'load_order' => 50,
                'description' => 'Swiper輪播CSS',
                'is_default' => 0
            ],
            [
                'Tb_index' => 'jL0004',
                'asset_name' => 'Swiper輪播 JS',
                'asset_type' => 'js',
                'asset_category' => 'UI組件',
                'file_path' => 'https://unpkg.com/swiper/swiper-bundle.min.js',
                'version' => 'latest',
                'load_position' => 'body_bottom',
                'load_order' => 51,
                'description' => 'Swiper輪播JS',
                'is_default' => 0
            ]
        ];
        
        $sql_insert = "INSERT IGNORE INTO `assets_library` 
                      (Tb_index, asset_name, asset_type, asset_category, file_path, version, 
                       load_position, load_order, description, dependencies, is_default)
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $pdo->prepare($sql_insert);
        
        foreach ($default_libraries as $lib) {
            $stmt->execute([
                $lib['Tb_index'],
                $lib['asset_name'],
                $lib['asset_type'],
                $lib['asset_category'],
                $lib['file_path'],
                $lib['version'],
                $lib['load_position'],
                $lib['load_order'],
                $lib['description'],
                $lib['dependencies'] ?? null,
                $lib['is_default']
            ]);
        }
        
        echo "✓ 預設套件插入成功\n";
        echo "\n✓ 資源管理系統初始化完成！\n";
        
        return true;
    } catch (Exception $e) {
        echo "✗ 初始化失敗: " . $e->getMessage() . "\n";
        return false;
    }
}

// 如果直接執行此文件
if (php_sapi_name() === 'cli') {
    require_once '../../core/inc/config.php';
    $pdo = pdo_conn();
    initAssetsDatabase($pdo);
}
?>
