<?php
// Upload an image to product_html/<case_id>/img (overwrite allowed)

declare(strict_types=1);
require __DIR__ . '/config.php';
require __DIR__ . '/util.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    fm_fail('method not allowed', 405);
}

$caseId = fm_require_case_id();
$baseDir = fm_base_dir($caseId);
$relDir = fm_normalize_rel($_POST['dir'] ?? '');
$targetDir = fm_join_path($baseDir, $relDir);
fm_ensure_dir($targetDir);

if (!isset($_FILES['file'])) {
    fm_fail('file field is required');
}
$file = $_FILES['file'];
if (!is_uploaded_file($file['tmp_name'])) {
    fm_fail('upload failed');
}

$mime = fm_detect_mime($file['tmp_name']);
$allowed = fm_allowed_mimes();
if (!isset($allowed[$mime])) {
    fm_fail('unsupported mime');
}

$original = $file['name'] ?? ('upload.' . $allowed[$mime]);
$name = fm_sanitize_filename($original);
if ($name === '') {
    $name = 'upload.' . $allowed[$mime];
}
$target = $targetDir . DIRECTORY_SEPARATOR . $name;

// Overwrite strategy: remove if exists
if (file_exists($target) && !unlink($target)) {
    fm_fail('cannot overwrite existing file');
}

if (!move_uploaded_file($file['tmp_name'], $target)) {
    fm_fail('cannot save file');
}

$relPath = ($relDir === '' ? '' : $relDir . '/') . $name;
$stat = stat($target);

fm_send_json([
    'success' => true,
    'data' => [
        'name' => $name,
        'path' => $relPath,
        'size' => $stat['size'] ?? 0,
        'mtime' => $stat['mtime'] ?? time(),
        'url' => fm_file_url($caseId, $relPath),
        'thumb' => '/core/api/file_manager/thumb.php?case_id=' . rawurlencode($caseId) . '&path=' . rawurlencode($relPath) . '&w=200&h=200',
    ],
]);
