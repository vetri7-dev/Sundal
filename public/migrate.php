<?php
$base = dirname(__DIR__);
require $base . '/vendor/autoload.php';
$app = require_once $base . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "<pre style='font-family:monospace;font-size:13px;padding:20px;background:#1a1a2e;color:#eee;'>";

// 1. Check landing page setting
$landingEnabled = getSetting('landingEnabled', true);
echo "Landing page enabled: " . ($landingEnabled ? 'YES' : 'NO - REDIRECTS TO LOGIN') . "\n";

// 2. Check if / route exists
$routes = Route::getRoutes();
$homeRoute = $routes->getByName('home');
echo "Home route registered: " . ($homeRoute ? 'YES' : 'NO') . "\n\n";

// 3. Check what REQUEST_URI and SCRIPT_NAME are in this request
echo "Current SCRIPT_NAME: " . ($_SERVER['SCRIPT_NAME'] ?? 'not set') . "\n";
echo "Current REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'not set') . "\n";
echo "request()->root(): " . request()->root() . "\n";
echo "request()->path(): " . request()->path() . "\n";
echo "request()->getPathInfo(): " . request()->getPathInfo() . "\n\n";

// 4. Check vite manifest for app entry
$manifest = $base . '/public/build/manifest.json';
if (file_exists($manifest)) {
    $m = json_decode(file_get_contents($manifest), true);
    $app_entry = $m['resources/js/app.tsx'] ?? null;
    if ($app_entry) {
        echo "App JS file: " . ($app_entry['file'] ?? 'unknown') . "\n";
        $js_path = $base . '/public/build/' . ($app_entry['file'] ?? '');
        echo "JS file exists on disk: " . (file_exists($js_path) ? 'YES' : 'NO') . "\n";
    }
}

echo "\n5. Checking landing-page/index chunk:\n";
foreach ($m ?? [] as $key => $val) {
    if (str_contains($key, 'landing-page')) {
        echo "  Found: $key -> " . ($val['file'] ?? '') . "\n";
        $chunk_path = $base . '/public/build/' . ($val['file'] ?? '');
        echo "  Exists: " . (file_exists($chunk_path) ? 'YES' : 'NO') . "\n";
    }
}

echo "</pre>";


// Fake a request to the home page
$request = \Illuminate\Http\Request::create('/sundal/', 'GET', [], [], [], [
    'SCRIPT_NAME'     => '/sundal/index.php',
    'HTTP_HOST'       => 'codecartz.com',
    'HTTPS'           => 'on',
    'SERVER_NAME'     => 'codecartz.com',
]);

try {
    $response = $kernel->handle($request);
    $status   = $response->getStatusCode();
    $body     = $response->getContent();
    $isInertia = str_contains($body, 'data-page');

    echo "<pre style='font-family:monospace;font-size:12px;padding:20px;background:#1a1a2e;color:#eee;word-wrap:break-word;'>";
    echo "HTTP Status: $status\n";
    echo "Has data-page div: " . ($isInertia ? '✅ YES' : '❌ NO') . "\n\n";

    if ($isInertia) {
        preg_match('/data-page=\'([^\']+)\'/', $body, $m);
        if ($m) {
            $page = json_decode(html_entity_decode($m[1]), true);
            echo "Component: " . ($page['component'] ?? 'unknown') . "\n";
            echo "URL: " . ($page['url'] ?? 'unknown') . "\n";
            echo "Ziggy url: " . ($page['props']['ziggy']['url'] ?? 'unknown') . "\n";
            echo "Ziggy location: " . ($page['props']['ziggy']['location'] ?? 'unknown') . "\n";
        }
    } else {
        echo "Body (first 500 chars):\n" . htmlspecialchars(substr($body, 0, 500));
    }
    echo "</pre>";
} catch (\Throwable $e) {
    echo "<pre style='color:red;padding:20px;'>";
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine();
    echo "</pre>";
}











