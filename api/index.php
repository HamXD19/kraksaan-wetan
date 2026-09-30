<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

if (! defined('LARAVEL_START')) {
    define('LARAVEL_START', microtime(true));
}

// Konfigurasi lingkungan serverless Vercel
putenv('APP_STORAGE=/tmp/storage');
putenv('VERCEL=1');
$_ENV['APP_STORAGE'] = '/tmp/storage';
$_ENV['VERCEL'] = '1';
$_SERVER['APP_STORAGE'] = '/tmp/storage';
$_SERVER['VERCEL'] = '1';

// Pastikan direktori storage di /tmp dibuat untuk lingkungan serverless Vercel
$storagePath = '/tmp/storage';
$subDirs = [
    $storagePath,
    $storagePath.'/app',
    $storagePath.'/app/public',
    $storagePath.'/framework',
    $storagePath.'/framework/views',
    $storagePath.'/framework/cache',
    $storagePath.'/framework/cache/data',
    $storagePath.'/framework/sessions',
    $storagePath.'/logs',
];

foreach ($subDirs as $dir) {
    if (! is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
}

// Salin database bawaan ke /tmp jika menggunakan SQLite di lingkungan serverless
$tmpDb = '/tmp/database.sqlite';
$bundledDb = __DIR__.'/../database/database.sqlite';
if (! file_exists($tmpDb) && file_exists($bundledDb)) {
    @copy($bundledDb, $tmpDb);
}

// Register autoloader & bootstrap Laravel
require __DIR__.'/../vendor/autoload.php';

/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
