<?php
// Subdirectory entry point with debug output to catch redirect source
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/');
$query = $_SERVER['QUERY_STRING'] ?? '';

$prefix = '/sundal';
if ($uri === $prefix || str_starts_with($uri, $prefix . '/')) {
    $uri = substr($uri, strlen($prefix)) ?: '/';
}

if ($uri !== '/' && file_exists(__DIR__ . '/public' . $uri)) {
    return false;
}

$_SERVER['REQUEST_URI'] = $uri . ($query ? '?' . $query : '');
$_SERVER['SCRIPT_NAME'] = '/index.php';  // Fix SCRIPT_NAME so Symfony detects base correctly

require_once __DIR__ . '/public/index.php';



