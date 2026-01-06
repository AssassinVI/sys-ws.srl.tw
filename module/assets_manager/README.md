# 資源管理系統 (Assets Manager)

## 📋 模組結構

```
module/assets_manager/
├── admin.php              # 套件庫管理介面
├── manager.php            # 案件資源管理介面
├── ajax_handler.php       # 案件資源 AJAX 處理
├── library_ajax.php       # 套件庫 AJAX 處理
├── render.php             # 資源渲染類別
├── init_database.php      # 資料庫初始化
└── README.md              # 本文件
```

## 🗄️ 資料庫表格

### 1. assets_library (套件庫表)
存放所有可用的 JS/CSS 套件資訊

| 欄位名 | 類型 | 說明 |
|------|------|------|
| Tb_index | VARCHAR(50) | 主鍵 (jL0001, cL0001 格式) |
| asset_name | VARCHAR(100) | 套件名稱 |
| asset_type | ENUM('js','css') | 資源類型 |
| asset_category | VARCHAR(50) | 分類 (UI框架, 動畫效果等) |
| file_path | VARCHAR(500) | CDN或本地路徑 |
| version | VARCHAR(20) | 版本號 |
| load_position | ENUM(...) | 載入位置 (head, body_top, body_bottom) |
| load_order | INT | 載入順序 (越小越優先) |
| description | TEXT | 套件說明 |
| dependencies | TEXT | 依賴關係 (JSON格式) |
| is_default | TINYINT(1) | 是否為預設套件 |
| OnLineOrNot | TINYINT(1) | 啟用狀態 |
| created_at | DATETIME | 建立時間 |
| updated_at | DATETIME | 更新時間 |

### 2. case_assets (案件資源關聯表)
記錄每個案件使用了哪些資源

| 欄位名 | 類型 | 說明 |
|------|------|------|
| Tb_index | INT | 主鍵 (自動編號) |
| case_id | VARCHAR(50) | 案件ID (FK: build_case.Tb_index) |
| asset_id | VARCHAR(50) | 資源ID (FK: assets_library.Tb_index) |
| is_enabled | TINYINT(1) | 是否啟用 |
| load_order | INT | 此案件中的載入順序 |
| custom_params | TEXT | 自訂參數 (JSON格式) |
| created_at | DATETIME | 建立時間 |

### 3. custom_assets (自訂資源表)
儲存使用者自訂的 JS/CSS 程式碼

| 欄位名 | 類型 | 說明 |
|------|------|------|
| Tb_index | INT | 主鍵 (自動編號) |
| case_id | VARCHAR(50) | 案件ID |
| asset_name | VARCHAR(100) | 資源名稱 |
| asset_type | ENUM('js','css') | 資源類型 |
| content | LONGTEXT | 程式碼內容 |
| load_position | ENUM(...) | 載入位置 |
| load_order | INT | 載入順序 |
| is_enabled | TINYINT(1) | 是否啟用 |
| created_at | DATETIME | 建立時間 |
| updated_at | DATETIME | 更新時間 |

## 🚀 使用方式

### 1. 初始化資料庫

#### 方法A: 執行 SQL 腳本
在 phpMyAdmin 或 MySQL 客戶端中執行 `init_database.php` 中的 SQL 語句

#### 方法B: PHP CLI
```bash
php init_database.php
```

#### 方法C: Web 瀏覽器
訪問 `module/assets_manager/init_database.php`

### 2. 套件庫管理 (admin.php)

**URL:** `/module/assets_manager/admin.php`

功能：
- ✅ 新增套件
- ✅ 編輯套件
- ✅ 刪除套件
- ✅ 搜尋和篩選
- ✅ 統計資訊
- ✅ 設定預設套件

### 3. 案件資源管理 (manager.php)

**URL:** `/module/assets_manager/manager.php?case_id=CASE001`

功能：
- ✅ 選擇套件庫資源
- ✅ 管理自訂資源
- ✅ 啟用/停用資源
- ✅ 調整載入順序
- ✅ 預覽程式碼

## 💻 API 文件

### AJAX Handler (ajax_handler.php)

#### 取得案件的套件庫資源
```
POST: ajax_handler.php
{
  "action": "get_library_assets",
  "case_id": "CASE001"
}

返回:
{
  "status": "success",
  "data": [...],        // 所有套件
  "selected": [...]     // 案件已選的套件 ID
}
```

#### 儲存案件資源設定
```
POST: ajax_handler.php
{
  "action": "save_case_assets",
  "case_id": "CASE001",
  "assets": ["jL0001", "cL0001", ...]
}
```

#### 新增/編輯自訂資源
```
POST: ajax_handler.php
{
  "action": "save_custom_asset",
  "case_id": "CASE001",
  "asset_name": "自訂JS",
  "asset_type": "js",
  "content": "console.log('test');",
  "load_position": "body_bottom",
  "load_order": 200,
  "Tb_index": ""  // 不填時為新增
}
```

#### 刪除自訂資源
```
POST: ajax_handler.php
{
  "action": "delete_custom_asset",
  "case_id": "CASE001",
  "Tb_index": 1
}
```

#### 取得資源計數
```
POST: ajax_handler.php
{
  "action": "get_asset_count",
  "case_id": "CASE001"
}

返回:
{
  "status": "success",
  "count": {
    "library": 5,
    "custom": 2,
    "total": 7
  }
}
```

### Library AJAX (library_ajax.php)

#### 取得所有套件
```
POST: library_ajax.php
{
  "action": "get_all_library",
  "category": "",  // 可選
  "type": "js"     // 可選
}
```

#### 新增套件
```
POST: library_ajax.php
{
  "action": "add_library",
  "asset_name": "jQuery",
  "asset_type": "js",
  "asset_category": "框架",
  "file_path": "https://...",
  "version": "3.6.0",
  "load_position": "head",
  "load_order": 10,
  "description": "描述",
  "dependencies": null,
  "is_default": 1
}
```

#### 編輯套件
```
POST: library_ajax.php
{
  "action": "edit_library",
  "Tb_index": "jL0001",
  // ... 其他欄位
}
```

#### 刪除套件 (軟刪除)
```
POST: library_ajax.php
{
  "action": "delete_library",
  "Tb_index": "jL0001"
}
```

## 🔧 在頁面中使用資源

### 方法 1: 在頁面中使用渲染類別

```php
<?php
require_once 'module/assets_manager/render.php';

$renderer = new AssetsRender('CASE001');

// 在 <head> 中
echo $renderer->render('head');

// 在 </body> 前
echo $renderer->render('body_bottom');
```

### 方法 2: 一次性渲染所有資源

```php
<?php
$renderer = new AssetsRender('CASE001');
echo $renderer->renderAll();
```

### 方法 3: 取得特定類型資源

```php
<?php
// 取得所有 CSS 資源
$assets = $renderer->getAssets('head', 'css');

// 結構:
// $assets['library']  - 套件庫資源陣列
// $assets['custom']   - 自訂資源陣列
```

## 📝 預設套件清單

系統內建以下預設套件：

| ID | 名稱 | 類型 | 分類 |
|----|------|------|------|
| jL0001 | jQuery 3.6 | JS | JavaScript框架 |
| cL0001 | Bootstrap 5 CSS | CSS | UI框架 |
| jL0002 | Bootstrap 5 JS | JS | UI框架 |
| cL0002 | Font Awesome | CSS | 圖標字型 |
| cL0003 | Animate.css | CSS | 動畫效果 |
| jL0003 | Swiper輪播 CSS | CSS | UI組件 |
| jL0004 | Swiper輪播 JS | JS | UI組件 |

## 🎯 使用範例

### 案例 1: 在案件中使用預設套件

1. 訪問: `/module/assets_manager/manager.php?case_id=CASE001`
2. 勾選需要的套件 (jQuery, Bootstrap 等)
3. 點擊「儲存設定」
4. 在案件頁面使用:
```php
$renderer = new AssetsRender('CASE001');
echo $renderer->renderAll();
```

### 案例 2: 新增自訂 JavaScript

1. 在資源管理頁面點擊「新增自訂資源」
2. 填入資源名稱和程式碼
3. 選擇載入位置和順序
4. 保存後自動應用

### 案例 3: 管理套件庫

1. 訪問: `/module/assets_manager/admin.php`
2. 新增公司常用的 CDN 或本地套件
3. 設定為預設套件
4. 其他案件可直接使用

## ⚙️ 配置建議

### 載入順序最佳實踐

```
Head (10-40):
├─ jQuery (10)
├─ Bootstrap CSS (20)
└─ Font Awesome (25)

Body Bottom (30-200):
├─ jQuery plugins (30-50)
├─ Bootstrap JS (51)
└─ Custom scripts (200+)
```

### 依賴關係設定

```json
{
  "requires": ["jL0001", "cL0001"]
}
```

## 🐛 常見問題

### Q: 如何新增自訂路徑的 CDN?
A: 在 admin.php 中新增套件，填入完整 CDN URL 即可

### Q: 自訂資源有大小限制嗎?
A: 使用 LONGTEXT，限制約 4GB (實務上無限制)

### Q: 可以禁用某個資源嗎?
A: 可以，在資源管理頁面點擊「停用」

### Q: 如何快速切換預設套件組?
A: 在 admin.php 中批量設定預設套件

## 📞 技術支持

- 系統架構: PHP 類別式設計
- 前端框架: jQuery + Bootstrap UI
- 資料庫: MySQL/MariaDB
- 相容性: PHP 7.2+

---

**建立日期:** 2026-01-05
**版本:** 1.0.0
