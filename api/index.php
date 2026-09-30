<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

if (! defined('LARAVEL_START')) {
    define('LARAVEL_START', microtime(true));
}

// Tangani routing Vercel: pastikan REQUEST_URI dan PATH_INFO mencerminkan URL asli
if (isset($_GET['__route__'])) {
    $originalPath = '/'.ltrim($_GET['__route__'], '/');
    unset($_GET['__route__']);
    $queryString = http_build_query($_GET);
    $_SERVER['REQUEST_URI'] = $originalPath.($queryString !== '' ? '?'.$queryString : '');
    $_SERVER['PATH_INFO'] = $originalPath;
} elseif (! empty($_SERVER['HTTP_X_MATCHED_PATH']) && $_SERVER['HTTP_X_MATCHED_PATH'] !== '/api/index.php') {
    $queryString = ! empty($_SERVER['QUERY_STRING']) ? '?'.$_SERVER['QUERY_STRING'] : '';
    $_SERVER['REQUEST_URI'] = $_SERVER['HTTP_X_MATCHED_PATH'].($queryString !== '' ? '?'.$queryString : '');
    $_SERVER['PATH_INFO'] = $_SERVER['HTTP_X_MATCHED_PATH'];
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
if ((! file_exists($tmpDb) || filesize($tmpDb) < 1000) && file_exists($bundledDb)) {
    @copy($bundledDb, $tmpDb);
    @chmod($tmpDb, 0666);
}

putenv('DB_CONNECTION=sqlite');
putenv('DB_DATABASE='.$tmpDb);
$_ENV['DB_CONNECTION'] = 'sqlite';
$_ENV['DB_DATABASE'] = $tmpDb;
$_SERVER['DB_CONNECTION'] = 'sqlite';
$_SERVER['DB_DATABASE'] = $tmpDb;

// Register autoloader & bootstrap Laravel
require __DIR__.'/../vendor/autoload.php';

/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
