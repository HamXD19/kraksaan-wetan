<?php

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

// Teruskan request ke public/index.php Laravel
require __DIR__.'/../public/index.php';
