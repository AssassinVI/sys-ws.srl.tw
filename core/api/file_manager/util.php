<?php
// Shared helpers for file manager API

declare(strict_types=1);

// Configure actual root directory
// Default: relative path from this file to project root
// Override via environment variable or define constant before require
if (!defined('FM_ROOT_DIR')) {
    $default_root = realpath(__DIR__ . '/../../../') ?: __DIR__;
    define('FM_ROOT_DIR', getenv('FM_ROOT_DIR') ?: $default_root);
}

function fm_send_json(array $payload, int $status = 200): void
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    exit;
}

function fm_fail(string $message, int $status = 400): void
{
    fm_send_json(['success' => false, 'message' => $message], $status);
}

function fm_require_case_id(): string
{
    $caseId = $_REQUEST['case_id'] ?? '';
    if ($caseId === '' || !preg_match('/^[A-Za-z0-9_-]+$/', $caseId)) {
        fm_fail('invalid case_id');
    }
    return $caseId;
}

function fm_base_dir(string $caseId): string
{
    $root = rtrim(FM_ROOT_DIR, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'product_html';
    $dir = $root . DIRECTORY_SEPARATOR . $caseId . DIRECTORY_SEPARATOR . 'img';
    if (!is_dir($dir) && !mkdir($dir, 0775, true)) {
        fm_fail('cannot create base dir');
    }
    $real = realpath($dir);
    if ($real === false) {
        fm_fail('base dir not accessible');
    }
    return $real;
}

function fm_normalize_rel(?string $rel): string
{
    $rel = $rel ?? '';
    $rel = str_replace(['\\', '\r', '\n'], '/', $rel);
    $rel = trim($rel, "/\t\n\r\0\x0B");
    if ($rel === '') {
        return '';
    }
    $parts = [];
    foreach (explode('/', $rel) as $seg) {
        if ($seg === '' || $seg === '.') {
            continue;
        }
        if ($seg === '..') {
            fm_fail('path traversal blocked');
        }
        $parts[] = $seg;
    }
    return implode('/', $parts);
}

function fm_join_path(string $baseDir, string $rel): string
{
    $path = $rel === '' ? $baseDir : $baseDir . DIRECTORY_SEPARATOR . $rel;
    $normalized = realpath(is_dir($path) ? $path : dirname($path));
    if ($normalized === false || strpos($normalized, $baseDir) !== 0) {
        fm_fail('path outside base');
    }
    return $path;
}

function fm_ensure_dir(string $dir): void
{
    if (!is_dir($dir) && !mkdir($dir, 0775, true)) {
        fm_fail('cannot create directory');
    }
}

function fm_sanitize_filename(string $name): string
{
    $name = preg_replace('/[^A-Za-z0-9._-]+/', '_', $name);
    return ltrim($name, '._');
}

function fm_allowed_mimes(): array
{
    return [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/gif' => 'gif',
        'image/webp' => 'webp',
        'image/svg+xml' => 'svg',
        'video/mp4' => 'mp4',
        'video/mpeg' => 'mpeg',
        'video/quicktime' => 'mov',
        'video/x-msvideo' => 'avi',
        'video/webm' => 'webm',
        'video/ogg' => 'ogv',
    ];
}

function fm_detect_mime(string $tmp): string
{
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = $finfo ? finfo_file($finfo, $tmp) : null;
    if ($finfo) {
        finfo_close($finfo);
    }
    return $mime ?: '';
}

function fm_file_url(string $caseId, string $rel): string
{
    $rel = ltrim($rel, '/');
    return '/product_html/' . rawurlencode($caseId) . '/img/' . str_replace('%2F', '/', rawurlencode($rel));
}
