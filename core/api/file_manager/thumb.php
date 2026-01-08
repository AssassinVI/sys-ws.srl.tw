<?php
// Generate thumbnail (cached) for images under product_html/<case_id>/img

declare(strict_types=1);
require __DIR__ . '/config.php';
require __DIR__ . '/util.php';

$caseId = fm_require_case_id();
$baseDir = fm_base_dir($caseId);
$relPath = fm_normalize_rel($_GET['path'] ?? '');
if ($relPath === '') {
    fm_fail('path is required');
}

$w = max(1, (int)($_GET['w'] ?? 200));
$h = max(1, (int)($_GET['h'] ?? 200));

$source = fm_join_path($baseDir, $relPath);
if (!is_file($source)) {
    fm_fail('file not found', 404);
}

$thumbDir = dirname($source) . DIRECTORY_SEPARATOR . '.thumbs' . DIRECTORY_SEPARATOR . $w . 'x' . $h;
fm_ensure_dir($thumbDir);
$thumbPath = $thumbDir . DIRECTORY_SEPARATOR . basename($source);

if (is_file($thumbPath) && filemtime($thumbPath) >= filemtime($source)) {
    fm_output_image($thumbPath);
}

$mime = fm_detect_mime($source);

// SVG is vector format, no need to generate thumbnail, return original
if ($mime === 'image/svg+xml') {
    fm_output_image($source, $mime);
}

// Video files: return default video icon
if (strpos($mime, 'video/') === 0) {
    fm_output_video_placeholder();
}

$img = fm_image_create($source, $mime);
if ($img === null) {
    fm_fail('unsupported mime');
}

$srcW = imagesx($img);
$srcH = imagesy($img);
$ratio = min($w / $srcW, $h / $srcH, 1);
$dstW = (int)floor($srcW * $ratio);
$dstH = (int)floor($srcH * $ratio);
$dst = imagecreatetruecolor($dstW, $dstH);
if (in_array($mime, ['image/png', 'image/webp', 'image/gif'], true)) {
    imagealphablending($dst, false);
    imagesavealpha($dst, true);
}
imagecopyresampled($dst, $img, 0, 0, 0, 0, $dstW, $dstH, $srcW, $srcH);
fm_image_save($dst, $thumbPath, $mime);
imagedestroy($dst);
imagedestroy($img);

fm_output_image($thumbPath, $mime);

function fm_image_create(string $path, string $mime)
{
    return match ($mime) {
        'image/jpeg' => imagecreatefromjpeg($path),
        'image/png' => imagecreatefrompng($path),
        'image/gif' => imagecreatefromgif($path),
        'image/webp' => function_exists('imagecreatefromwebp') ? imagecreatefromwebp($path) : null,
        default => null,
    };
}

function fm_image_save($img, string $path, string $mime): void
{
    switch ($mime) {
        case 'image/png':
            imagepng($img, $path, 6);
            break;
        case 'image/gif':
            imagegif($img, $path);
            break;
        case 'image/webp':
            if (function_exists('imagewebp')) {
                imagewebp($img, $path, 80);
                break;
            }
            // fallback to jpeg
        default:
            imagejpeg($img, $path, 80);
    }
}

function fm_output_image(string $path, ?string $mime = null): void
{
    $mime = $mime ?: fm_detect_mime($path);
    header('Content-Type: ' . ($mime ?: 'image/jpeg'));
    header('Content-Length: ' . filesize($path));
    readfile($path);
    exit;
}

function fm_output_video_placeholder(): void
{
    header('Content-Type: image/svg+xml');
    $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="200" height="200" viewBox="0 0 200 200">' .
           '<rect width="200" height="200" fill="#f1f5f9"/>' .
           '<path d="M70 60 L140 100 L70 140 Z" fill="#3b82f6"/>' .
           '<text x="100" y="170" text-anchor="middle" font-family="Arial" font-size="14" fill="#64748b">VIDEO</text>' .
           '</svg>';
    echo $svg;
    exit;
}
