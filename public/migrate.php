<?php
// Debug: check what Laravel's URL detection returns
$base = dirname(__DIR__);
require $base . '/vendor/autoload.php';
$app = require_once $base . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "<pre style='font-family:monospace;font-size:13px;padding:20px;background:#1a1a2e;color:#eee;'>";
echo "SCRIPT_NAME: " . ($_SERVER['SCRIPT_NAME'] ?? 'not set') . "\n";
echo "REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'not set') . "\n";
echo "request->root(): " . request()->root() . "\n";
echo "url('/'): " . url('/') . "\n";
echo "route('home'): " . route('home') . "\n";
echo "config('app.url'): " . config('app.url') . "\n";
echo "\nIf root() shows https://codecartz.com/sundal -> URL detection is working\n";
echo "If root() shows https://codecartz.com -> URL detection needs manual fix\n";
echo "</pre>";









