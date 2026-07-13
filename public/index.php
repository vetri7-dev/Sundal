<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Shared-hosting subdirectory fix:
// Strip /sundal prefix from REQUEST_URI so Laravel's router matches routes
// without the subdir prefix. Ziggy location is fixed in HandleInertiaRequests
// to reconstruct the full URL using APP_URL + stripped request URI.
$subdir = '/sundal';
if (isset($_SERVER['REQUEST_URI'])) {
    $uri = $_SERVER['REQUEST_URI'];
    if (str_starts_with($uri, $subdir . '/') || $uri === $subdir) {
        $_SERVER['REQUEST_URI'] = substr($uri, strlen($subdir)) ?: '/';
    }
}
if (isset($_SERVER['PHP_SELF']) && str_starts_with($_SERVER['PHP_SELF'], $subdir)) {
    $_SERVER['PHP_SELF'] = substr($_SERVER['PHP_SELF'], strlen($subdir)) ?: '/';
}

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
