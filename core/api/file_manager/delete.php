<?php
// Delete a file within product_html/<case_id>/img

declare(strict_types=1);
require __DIR__ . '/config.php';
require __DIR__ . '/util.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    fm_fail('method not allowed', 405);
}

$caseId = fm_require_case_id();
$baseDir = fm_base_dir($caseId);
$path = fm_normalize_rel($_POST['path'] ?? '');
if ($path === '') {
    fm_fail('path is required');
}

$full = fm_join_path($baseDir, $path);
if (!is_file($full)) {
    fm_fail('file not found', 404);
}

if (!unlink($full)) {
    fm_fail('delete failed');
}

fm_send_json(['success' => true]);
