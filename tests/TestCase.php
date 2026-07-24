<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        // Load custom vendor package autoloader (DomPDF, Excel, Google Client, etc.)
        $autoload = dirname(__DIR__) . '/bootstrap/autoload-packages.php';
        if (file_exists($autoload)) {
            require_once $autoload;
        }

        parent::setUp();
    }
}
