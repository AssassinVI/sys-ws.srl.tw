<?php
/**
 * Build Context
 * 線上 / 靜態匯出 共用切換
 */

define('IS_BUILD', isset($_SERVER['BUILD_STATIC']) && $_SERVER['BUILD_STATIC'] === true);

// 靜態匯出時，所有資源都走相對路徑
define('ASSET_BASE', IS_BUILD ? './' : '/');

// 有些東西在 build 時不該執行
function runtime_only(callable $fn) {
  if (!IS_BUILD) {
    $fn();
  }
}
