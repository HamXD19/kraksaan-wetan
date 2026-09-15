<?php

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\AdminPelayananController;
use App\Http\Controllers\Api\KelurahanController;
use App\Http\Controllers\Api\PelayananController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - Kelurahan Kraksaan Wetan
|--------------------------------------------------------------------------
*/

// Public Endpoints
Route::prefix('v1')->group(function () {
    Route::get('/profil', [KelurahanController::class, 'getProfil']);
    Route::get('/statistik', [KelurahanController::class, 'getStatistik']);
    Route::get('/layanan', [PelayananController::class, 'getLayanan']);
    Route::get('/layanan/{slug}', [PelayananController::class, 'getLayananBySlug']);
    Route::get('/pelayanan', [PelayananController::class, 'getLayanan']);
    Route::get('/pelayanan/{slug}', [PelayananController::class, 'getLayananBySlug']);
    Route::post('/pelayanan/pengajuan', [PelayananController::class, 'submitPengajuan']);
    Route::post('/pelayanan/tracking', [PelayananController::class, 'trackPengajuan']);
    Route::get('/berita', [KelurahanController::class, 'getBerita']);
    Route::get('/berita/{slug}', [KelurahanController::class, 'getBeritaBySlug']);
    Route::get('/pengumuman', [KelurahanController::class, 'getPengumuman']);
    Route::get('/galeri', [KelurahanController::class, 'getGaleri']);
    Route::get('/galeri/{id}', [KelurahanController::class, 'getGaleriById']);
    Route::get('/lembaga', [KelurahanController::class, 'getLembaga']);
    Route::get('/transparansi', [KelurahanController::class, 'getTransparansi']);
    Route::post('/kontak', [KelurahanController::class, 'kirimKontak']);
    Route::get('/tts', [KelurahanController::class, 'getTtsAudio']);
});

Route::get('/profil', [KelurahanController::class, 'getProfil']);
Route::get('/statistik', [KelurahanController::class, 'getStatistik']);
Route::get('/layanan', [PelayananController::class, 'getLayanan']);
Route::get('/layanan/{slug}', [PelayananController::class, 'getLayananBySlug']);
Route::get('/pelayanan', [PelayananController::class, 'getLayanan']);
Route::get('/pelayanan/{slug}', [PelayananController::class, 'getLayananBySlug']);
Route::post('/pelayanan/pengajuan', [PelayananController::class, 'submitPengajuan']);
Route::post('/pelayanan/tracking', [PelayananController::class, 'trackPengajuan']);
Route::get('/berita', [KelurahanController::class, 'getBerita']);
Route::get('/berita/{slug}', [KelurahanController::class, 'getBeritaBySlug']);
Route::get('/kategori', [KelurahanController::class, 'getKategori']);
Route::get('/pengumuman', [KelurahanController::class, 'getPengumuman']);
Route::get('/galeri', [KelurahanController::class, 'getGaleri']);
Route::get('/galeri/{id}', [KelurahanController::class, 'getGaleriById']);
Route::get('/lembaga', [KelurahanController::class, 'getLembaga']);
Route::get('/transparansi', [KelurahanController::class, 'getTransparansi']);
Route::post('/kontak', [KelurahanController::class, 'kirimKontak']);
Route::get('/tts', [KelurahanController::class, 'getTtsAudio']);

// Admin CMS Endpoints
Route::prefix('admin')->group(function () {
    Route::get('/captcha', [AdminController::class, 'getCaptcha']);
    Route::post('/login', [AdminController::class, 'login']);

    Route::middleware('admin.auth')->group(function () {
        // Shared Endpoints for all authenticated staff
        Route::post('/logout', [AdminController::class, 'logout']);
        Route::get('/me', [AdminController::class, 'me']);
        Route::put('/password', [AdminController::class, 'updatePassword']);
        Route::post('/upload', [AdminController::class, 'upload']);
        Route::get('/dashboard', [AdminController::class, 'dashboard']);

        // 1. SUPER ADMIN ONLY (Kendali Penuh, Kelola Akun Staf, Profil & Transparansi)
        Route::middleware('admin.role:super_admin')->group(function () {
            // Kelola Akun Staf
            Route::get('/staff', [AdminController::class, 'getStaff']);
            Route::post('/staff', [AdminController::class, 'storeStaff']);
            Route::put('/staff/{id}', [AdminController::class, 'updateStaff']);
            Route::delete('/staff/{id}', [AdminController::class, 'deleteStaff']);
            Route::put('/staff/{id}/reset-password', [AdminController::class, 'resetPasswordStaff']);

            // Profil & Aparatur Kelurahan
            Route::put('/profil', [AdminController::class, 'updateProfil']);
            Route::post('/perangkat', [AdminController::class, 'storePerangkat']);
            Route::put('/perangkat/{id}', [AdminController::class, 'updatePerangkat']);
            Route::delete('/perangkat/{id}', [AdminController::class, 'deletePerangkat']);

            // Pemantauan Log Aktifitas Setiap Akun (Super Admin Only)
            Route::get('/activity-logs', [AdminController::class, 'getActivityLogs']);
            Route::get('/activity-logs/users', [AdminController::class, 'getActivityLogUsers']);
        });

        // Master Kategori CRUD (Semua Staf Pengelola Konten & Administrasi)
        Route::get('/kategori', [AdminController::class, 'getMasterKategori']);
        Route::post('/kategori', [AdminController::class, 'storeMasterKategori']);
        Route::put('/kategori/{id}', [AdminController::class, 'updateMasterKategori']);
        Route::delete('/kategori/{id}', [AdminController::class, 'deleteMasterKategori']);

        // 2. STAFF KONTEN & HUMAS (Berita, Pengumuman, Galeri Foto)
        Route::middleware('admin.role:super_admin,staff_konten')->group(function () {
            // Berita CRUD
            Route::get('/berita', [AdminController::class, 'getBerita']);
            Route::post('/berita', [AdminController::class, 'storeBerita']);
            Route::put('/berita/{id}', [AdminController::class, 'updateBerita']);
            Route::delete('/berita/{id}', [AdminController::class, 'deleteBerita']);

            // Pengumuman CRUD
            Route::get('/pengumuman', [AdminController::class, 'getPengumuman']);
            Route::post('/pengumuman', [AdminController::class, 'storePengumuman']);
            Route::put('/pengumuman/{id}', [AdminController::class, 'updatePengumuman']);
            Route::delete('/pengumuman/{id}', [AdminController::class, 'deletePengumuman']);

            // Galeri CRUD
            Route::get('/galeri', [AdminController::class, 'getGaleri']);
            Route::post('/galeri', [AdminController::class, 'storeGaleri']);
            Route::put('/galeri/{id}', [AdminController::class, 'updateGaleri']);
            Route::delete('/galeri/{id}', [AdminController::class, 'deleteGaleri']);
        });

        // 3. STAFF PELAYANAN (Kelola Layanan SOP & Informasi)
        Route::middleware('admin.role:super_admin,staff_pelayanan')->group(function () {
            // Layanan CRUD
            Route::get('/layanan', [AdminController::class, 'getLayanan']);
            Route::post('/layanan', [AdminController::class, 'storeLayanan']);
            Route::put('/layanan/{id}', [AdminController::class, 'updateLayanan']);
            Route::put('/layanan/{id}/toggle-aktif', [AdminPelayananController::class, 'toggleAktifLayanan']);
            Route::delete('/layanan/{id}', [AdminController::class, 'deleteLayanan']);
        });

        // 4. STAFF ADMINISTRASI (Lembaga Kemasyarakatan & Statistik Kependudukan)
        Route::middleware('admin.role:super_admin,staff_administrasi')->group(function () {
            // Lembaga Kemasyarakatan (LKK)
            Route::get('/lembaga', [AdminController::class, 'getLembaga']);
            Route::post('/lembaga', [AdminController::class, 'storeLembaga']);
            Route::put('/lembaga/{id}', [AdminController::class, 'updateLembaga']);
            Route::put('/lembaga/{id}/toggle-aktif', [AdminController::class, 'toggleAktifLembaga']);
            Route::delete('/lembaga/{id}', [AdminController::class, 'deleteLembaga']);

            // Statistik & Lingkungan RW/RT
            Route::put('/statistik', [AdminController::class, 'updateStatistik']);
            Route::post('/lingkungan', [AdminController::class, 'storeLingkungan']);
            Route::put('/lingkungan/{id}', [AdminController::class, 'updateLingkungan']);
            Route::delete('/lingkungan/{id}', [AdminController::class, 'deleteLingkungan']);

            // Transparansi Anggaran & Akuntabilitas (Staff Administrasi & Super Admin)
            Route::get('/transparansi', [AdminController::class, 'getTransparansi']);
            Route::post('/transparansi', [AdminController::class, 'storeTransparansi']);
            Route::put('/transparansi/{id}', [AdminController::class, 'updateTransparansi']);
            Route::put('/transparansi/{id}/toggle-aktif', [AdminController::class, 'toggleAktifTransparansi']);
            Route::delete('/transparansi/{id}', [AdminController::class, 'deleteTransparansi']);
        });
    });
});
