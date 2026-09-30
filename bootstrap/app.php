<?php

use App\Http\Middleware\AdminAuthMiddleware;
use App\Http\Middleware\AdminRoleMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\SetCacheHeaders;
use Illuminate\Http\Request;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');
        $middleware->alias([
            'admin.auth' => AdminAuthMiddleware::class,
            'admin.role' => AdminRoleMiddleware::class,
        ]);
        $middleware->api(append: [
            SetCacheHeaders::class.':no_cache;no_store;must_revalidate',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();

if (! empty(env('VERCEL')) || ! empty(env('APP_STORAGE')) || isset($_ENV['VERCEL']) || isset($_SERVER['VERCEL'])) {
    $storage = env('APP_STORAGE', '/tmp/storage');
    $subDirs = [
        $storage,
        $storage.'/app',
        $storage.'/app/public',
        $storage.'/framework',
        $storage.'/framework/views',
        $storage.'/framework/cache',
        $storage.'/framework/cache/data',
        $storage.'/framework/sessions',
        $storage.'/logs',
    ];
    foreach ($subDirs as $dir) {
        if (! is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
    }
    $app->useStoragePath($storage);

    // Cek apakah ada konfigurasi remote database (misal MySQL / PostgreSQL yang persisten)
    $dbConn = env('DB_CONNECTION');
    $dbHost = env('DB_HOST');
    $isRemoteDb = (! empty($dbConn) && $dbConn !== 'sqlite') || ! empty($dbHost);

    if (! $isRemoteDb) {
        // Fallback SQLite di /tmp jika tidak ada database remote yang dikonfigurasi
        $tmpDb = '/tmp/database.sqlite';
        $versionFile = '/tmp/database.version';
        $bundledDb = dirname(__DIR__).'/api/database.sqlite';
        if (! file_exists($bundledDb)) {
            $bundledDb = dirname(__DIR__).'/database/database.sqlite';
        }
        if (file_exists($bundledDb)) {
            $bundledHash = md5_file($bundledDb);
            $needsCopy = ! file_exists($tmpDb)
                || ! file_exists($versionFile)
                || @file_get_contents($versionFile) !== $bundledHash
                || filesize($tmpDb) !== filesize($bundledDb);

            if ($needsCopy) {
                @copy($bundledDb, $tmpDb);
                @chmod($tmpDb, 0666);
                @file_put_contents($versionFile, $bundledHash);
            }
        }
        $app->booting(function () use ($tmpDb, $storage): void {
            config([
                'database.default' => 'sqlite',
                'database.connections.sqlite.database' => $tmpDb,
                'cache.default' => 'file',
                'cache.stores.file.path' => $storage.'/framework/cache/data',
            ]);
        });
    } else {
        $app->booting(function () use ($storage): void {
            config([
                'cache.default' => 'file',
                'cache.stores.file.path' => $storage.'/framework/cache/data',
            ]);
        });
    }
}

return $app;
