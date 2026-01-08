<?php
// Rename a file within product_html/<case_id>/img (overwrite allowed)

declare(strict_types=1);
require __DIR__ . '/config.php';
require __DIR__ . '/util.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    fm_fail('method not allowed', 405);
}

$caseId = fm_require_case_id();
$baseDir = fm_base_dir($caseId);
$path = fm_normalize_rel($_POST['path'] ?? '');
$newNameRaw = $_POST['newName'] ?? '';

if ($path === '') {
    fm_fail('path is required');
}

$oldFull = fm_join_path($baseDir, $path);
if (!is_file($oldFull)) {
    fm_fail('file not found', 404);
}

// Extract filename without extension and preserve extension
$oldName = basename($path);
$extPos = strrpos($oldName, '.');
$ext = ($extPos !== false) ? substr($oldName, $extPos) : '';

// Sanitize new name (filename only, no extension)
$newNameOnly = fm_sanitize_filename($newNameRaw);
if ($newNameOnly === '') {
    fm_fail('invalid new name');
}

// Combine new name with original extension
$newName = $newNameOnly . $ext;

$dirPart = dirname($path);
$dirPart = $dirPart === '.' ? '' : $dirPart;
$newRel = ($dirPart === '' ? '' : $dirPart . '/') . $newName;
$newFull = fm_join_path($baseDir, $newRel);

if (file_exists($newFull) && !unlink($newFull)) {
    fm_fail('cannot overwrite target');
}

if (!rename($oldFull, $newFull)) {
    fm_fail('rename failed');
}

fm_send_json([
    'success' => true,
    'data' => [
        'name' => $newName,
        'path' => $newRel,
        'url' => fm_file_url($caseId, $newRel),
        'thumb' => '/core/api/file_manager/thumb.php?case_id=' . rawurlencode($caseId) . '&path=' . rawurlencode($newRel) . '&w=200&h=200',
    ],
]);
