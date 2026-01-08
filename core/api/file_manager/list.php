<?php
// List files under product_html/<case_id>/img (and optional subdir)

declare(strict_types=1);
require __DIR__ . '/config.php';
require __DIR__ . '/util.php';

$caseId = fm_require_case_id();
$baseDir = fm_base_dir($caseId);
$relDir = fm_normalize_rel($_GET['dir'] ?? '');
$targetDir = fm_join_path($baseDir, $relDir);
if (!is_dir($targetDir)) {
    fm_ensure_dir($targetDir);
}

$items = [];
$entries = scandir($targetDir);
if ($entries === false) {
    fm_fail('cannot read directory');
}

foreach ($entries as $name) {
    if ($name === '.' || $name === '..' || $name === '.thumbs') {
        continue;
    }
    $fullPath = $targetDir . DIRECTORY_SEPARATOR . $name;
    if (!is_file($fullPath)) {
        continue;
    }
    $stat = stat($fullPath);
    $relPath = ($relDir === '' ? '' : $relDir . '/') . $name;
    $items[] = [
        'name' => $name,
        'path' => $relPath,
        'size' => $stat['size'] ?? 0,
        'mtime' => $stat['mtime'] ?? time(),
        'url' => fm_file_url($caseId, $relPath),
        'thumb' => '/core/api/file_manager/thumb.php?case_id=' . rawurlencode($caseId) . '&path=' . rawurlencode($relPath) . '&w=200&h=200',
    ];
}

fm_send_json(['success' => true, 'data' => $items]);
