<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Shared-hosting subdirectory fix for LiteSpeed/Apache
// Strip /sundal or /sundal/public prefix from REQUEST_URI so Laravel routes correctly
if (isset($_SERVER['REQUEST_URI'])) {
    $uri = $_SERVER['REQUEST_URI'];
    // Remove query string for comparison
    $path = strstr($uri, '?', true) ?: $uri;
    $query = strstr($uri, '?') ?: '';

    // Strip /sundal/public first (more specific), then /sundal
    foreach (['/sundal/public', '/sundal'] as $prefix) {
        if ($path === $prefix || str_starts_with($path, $prefix . '/')) {
            $stripped = substr($path, strlen($prefix)) ?: '/';
            $_SERVER['REQUEST_URI'] = $stripped . $query;
            break;
        }
    }
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
