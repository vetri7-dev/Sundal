<?php

/**
 * Subdirectory entry point for shared hosting deployment at /sundal/
 * Strips the /sundal prefix from REQUEST_URI before delegating to public/index.php
 */

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/');
$query = $_SERVER['QUERY_STRING'] ?? '';

// Strip /sundal prefix
$prefix = '/sundal';
if ($uri === $prefix || str_starts_with($uri, $prefix . '/')) {
    $uri = substr($uri, strlen($prefix)) ?: '/';
}

// Serve real files from public/ directly (assets, fonts, images)
if ($uri !== '/' && file_exists(__DIR__ . '/public' . $uri)) {
    return false; // Let LiteSpeed/Apache serve the file
}

$_SERVER['REQUEST_URI'] = $uri . ($query ? '?' . $query : '');

require_once __DIR__ . '/public/index.php';

