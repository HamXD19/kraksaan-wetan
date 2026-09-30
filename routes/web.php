<?php

use Illuminate\Support\Facades\Route;

Route::get('/storage/{path}', function (string $path) {
    $tmpPath = '/tmp/storage/app/public/'.$path;
    if (file_exists($tmpPath) && is_file($tmpPath)) {
        return response()->file($tmpPath);
    }

    $storagePath = storage_path('app/public/'.$path);
    if (file_exists($storagePath) && is_file($storagePath)) {
        return response()->file($storagePath);
    }

    $publicPath = public_path('storage/'.$path);
    if (file_exists($publicPath) && is_file($publicPath)) {
        return response()->file($publicPath);
    }

    abort(404);
})->where('path', '.*');

Route::get('/{any?}', function () {
    return view('app');
})->where('any', '^(?!api|storage).*$');
