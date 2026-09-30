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
    Route::get('/berita', [KelurahanController::class, 'getBerita']);
    Route::get('/berita/{slug}', [KelurahanController::class, 'getBeritaBySlug']);
    Route::get('/pengumuman', [KelurahanController::class, 'getPengumuman']);
    Route::get('/pengumuman/{id}/unduh', [KelurahanController::class, 'unduhPengumuman']);
    Route::get('/dokumen', [KelurahanController::class, 'getDokumen']);
    Route::get('/dokumen/{id}/unduh', [KelurahanController::class, 'unduhDokumen']);
    Route::get('/dokumen/{id}/pratinjau', [KelurahanController::class, 'pratinjauDokumen']);
    Route::get('/galeri', [KelurahanController::class, 'getGaleri']);
    Route::get('/galeri/{id}', [KelurahanController::class, 'getGaleriById']);
    Route::get('/lembaga', [KelurahanController::class, 'getLembaga']);
    Route::get('/transparansi', [KelurahanController::class, 'getTransparansi']);
    Route::get('/transparansi/{slug}', [KelurahanController::class, 'getTransparansiDetail']);
    Route::get('/transparansi/{slug}/unduh', [KelurahanController::class, 'unduhDokumenTransparansi']);
    Route::get('/apbd', [KelurahanController::class, 'getTransparansi']);
    Route::get('/apbd/{slug}', [KelurahanController::class, 'getTransparansiDetail']);
    Route::get('/apbd/{slug}/unduh', [KelurahanController::class, 'unduhDokumenTransparansi']);
    Route::get('/agenda', [KelurahanController::class, 'getAgenda']);
    Route::get('/agenda/{slug}', [KelurahanController::class, 'getAgendaBySlug']);
    Route::get('/halaman/{slug}', [KelurahanController::class, 'getHalamanBySlug']);
    Route::get('/maklumat-pelayanan', [KelurahanController::class, 'getMaklumatPelayanan']);
    Route::get('/survei-skm', [KelurahanController::class, 'getSurveiSkm']);
    Route::get('/survei-skm/{id}', [KelurahanController::class, 'getSurveiSkmDetail']);
    Route::get('/survei-skm/{id}/unduh', [KelurahanController::class, 'unduhLaporanSkm']);
    Route::get('/running-text', [KelurahanController::class, 'getRunningText']);
    Route::get('/tts', [KelurahanController::class, 'getTtsAudio']);
});

Route::get('/profil', [KelurahanController::class, 'getProfil']);
Route::get('/statistik', [KelurahanController::class, 'getStatistik']);
Route::get('/running-text', [KelurahanController::class, 'getRunningText']);
Route::get('/layanan', [PelayananController::class, 'getLayanan']);
Route::get('/layanan/{slug}', [PelayananController::class, 'getLayananBySlug']);
Route::get('/pelayanan', [PelayananController::class, 'getLayanan']);
Route::get('/pelayanan/{slug}', [PelayananController::class, 'getLayananBySlug']);
Route::get('/berita', [KelurahanController::class, 'getBerita']);
Route::get('/berita/{slug}', [KelurahanController::class, 'getBeritaBySlug']);
Route::get('/kategori', [KelurahanController::class, 'getKategori']);
Route::get('/pengumuman', [KelurahanController::class, 'getPengumuman']);
Route::get('/pengumuman/{id}/unduh', [KelurahanController::class, 'unduhPengumuman']);
Route::get('/dokumen', [KelurahanController::class, 'getDokumen']);
Route::get('/dokumen/{id}/unduh', [KelurahanController::class, 'unduhDokumen']);
Route::get('/dokumen/{id}/pratinjau', [KelurahanController::class, 'pratinjauDokumen']);
Route::get('/galeri', [KelurahanController::class, 'getGaleri']);
Route::get('/galeri/{id}', [KelurahanController::class, 'getGaleriById']);
Route::get('/lembaga', [KelurahanController::class, 'getLembaga']);
Route::get('/transparansi', [KelurahanController::class, 'getTransparansi']);
Route::get('/transparansi/{slug}', [KelurahanController::class, 'getTransparansiDetail']);
Route::get('/transparansi/{slug}/unduh', [KelurahanController::class, 'unduhDokumenTransparansi']);
Route::get('/apbd', [KelurahanController::class, 'getTransparansi']);
Route::get('/apbd/{slug}', [KelurahanController::class, 'getTransparansiDetail']);
Route::get('/apbd/{slug}/unduh', [KelurahanController::class, 'unduhDokumenTransparansi']);
Route::get('/agenda', [KelurahanController::class, 'getAgenda']);
Route::get('/agenda/{slug}', [KelurahanController::class, 'getAgendaBySlug']);
Route::get('/halaman/{slug}', [KelurahanController::class, 'getHalamanBySlug']);
Route::get('/maklumat-pelayanan', [KelurahanController::class, 'getMaklumatPelayanan']);
Route::get('/survei-skm', [KelurahanController::class, 'getSurveiSkm']);
Route::get('/survei-skm/{id}', [KelurahanController::class, 'getSurveiSkmDetail']);
Route::get('/survei-skm/{id}/unduh', [KelurahanController::class, 'unduhLaporanSkm']);
Route::get('/tts', [KelurahanController::class, 'getTtsAudio']);
Route::post('/kontak', [KelurahanController::class, 'kirimKontak']);

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

            // Setting System & Halaman Kustom (Super Admin Only)
            Route::get('/halaman-kustom', [AdminController::class, 'getHalamanKustom']);
            Route::post('/halaman-kustom', [AdminController::class, 'storeHalamanKustom']);
            Route::put('/halaman-kustom/{id}', [AdminController::class, 'updateHalamanKustom']);
            Route::delete('/halaman-kustom/{id}', [AdminController::class, 'deleteHalamanKustom']);

            // Manajemen Penyimpanan & Berkas Orphan (Super Admin Only)
            Route::get('/storage/stats', [AdminController::class, 'getStorageStats']);
            Route::post('/storage/clean-orphans', [AdminController::class, 'cleanOrphanedStorage']);

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
            Route::put('/berita/{id}/toggle-status', [AdminController::class, 'toggleStatusBerita']);
            Route::put('/berita/{id}/toggle-running-text', [AdminController::class, 'toggleRunningTextBerita']);
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

            // Agenda Kegiatan CRUD
            Route::get('/agenda', [AdminController::class, 'getAgenda']);
            Route::post('/agenda', [AdminController::class, 'storeAgenda']);
            Route::put('/agenda/{id}', [AdminController::class, 'updateAgenda']);
            Route::put('/agenda/{id}/toggle-aktif', [AdminController::class, 'toggleAktifAgenda']);
            Route::delete('/agenda/{id}', [AdminController::class, 'deleteAgenda']);
        });

        // Dokumen Publik (PDF) CRUD (Super Admin, Staff Konten, Staff Administrasi)
        Route::middleware('admin.role:super_admin,staff_konten,staff_administrasi')->group(function () {
            Route::get('/dokumen', [AdminController::class, 'getDokumen']);
            Route::post('/dokumen', [AdminController::class, 'storeDokumen']);
            Route::put('/dokumen/{id}', [AdminController::class, 'updateDokumen']);
            Route::put('/dokumen/{id}/toggle-status', [AdminController::class, 'toggleDokumenStatus']);
            Route::delete('/dokumen/{id}', [AdminController::class, 'deleteDokumen']);
        });

        // 3. STAFF PELAYANAN (Kelola Layanan SOP & Informasi)
        Route::middleware('admin.role:super_admin,staff_pelayanan')->group(function () {
            // Layanan CRUD
            Route::get('/layanan', [AdminController::class, 'getLayanan']);
            Route::post('/layanan', [AdminController::class, 'storeLayanan']);
            Route::put('/layanan/{id}', [AdminController::class, 'updateLayanan']);
            Route::put('/layanan/{id}/toggle-aktif', [AdminPelayananController::class, 'toggleAktifLayanan']);
            Route::delete('/layanan/{id}', [AdminController::class, 'deleteLayanan']);

            // Maklumat Pelayanan
            Route::get('/maklumat-pelayanan', [AdminPelayananController::class, 'getMaklumat']);
            Route::post('/maklumat-pelayanan', [AdminPelayananController::class, 'saveMaklumat']);
            Route::put('/maklumat-pelayanan/{id}/toggle-aktif', [AdminPelayananController::class, 'toggleAktifMaklumat']);
            Route::delete('/maklumat-pelayanan/{id}', [AdminPelayananController::class, 'deleteMaklumat']);

            // Survei Kepuasan Masyarakat (SKM) CRUD
            Route::get('/survei-skm', [AdminPelayananController::class, 'getSurveiSkm']);
            Route::post('/survei-skm', [AdminPelayananController::class, 'storeSurveiSkm']);
            Route::put('/survei-skm/{id}', [AdminPelayananController::class, 'updateSurveiSkm']);
            Route::put('/survei-skm/{id}/toggle-aktif', [AdminPelayananController::class, 'toggleAktifSurveiSkm']);
            Route::delete('/survei-skm/{id}', [AdminPelayananController::class, 'deleteSurveiSkm']);
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
            Route::get('/transparansi/{id}', [AdminController::class, 'showTransparansi']);
            Route::post('/transparansi', [AdminController::class, 'storeTransparansi']);
            Route::put('/transparansi/{id}', [AdminController::class, 'updateTransparansi']);
            Route::put('/transparansi/{id}/toggle-status', [AdminController::class, 'toggleStatusTransparansi']);
            Route::put('/transparansi/{id}/toggle-aktif', [AdminController::class, 'toggleAktifTransparansi']);
            Route::delete('/transparansi/{id}', [AdminController::class, 'deleteTransparansi']);
        });
    });
});
