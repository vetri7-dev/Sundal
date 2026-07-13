<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Strip subdirectory prefix so routes match correctly on shared hosting
// When deployed at /sundal/, REQUEST_URI contains /sundal/foo but routes expect /foo
$subdir = '/sundal';
if (isset($_SERVER['REQUEST_URI']) && str_starts_with($_SERVER['REQUEST_URI'], $subdir)) {
    $_SERVER['REQUEST_URI'] = substr($_SERVER['REQUEST_URI'], strlen($subdir)) ?: '/';
}
if (isset($_SERVER['PATH_INFO']) && str_starts_with($_SERVER['PATH_INFO'], $subdir)) {
    $_SERVER['PATH_INFO'] = substr($_SERVER['PATH_INFO'], strlen($subdir)) ?: '/';
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
