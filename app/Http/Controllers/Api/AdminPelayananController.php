<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Layanan;
use App\Models\MaklumatPelayanan;
use App\Models\SurveiSkm;
use App\Services\FileStorageHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminPelayananController extends Controller
{
    /**
     * Mengubah status aktif / nonaktif jenis layanan.
     */
    public function toggleAktifLayanan(Request $request, int $id): JsonResponse
    {
        $layanan = Layanan::findOrFail($id);

        $validated = $request->validate([
            'aktif' => 'required|boolean',
        ]);

        $layanan->update(['aktif' => $validated['aktif']]);

        $statusText = $validated['aktif'] ? 'mengaktifkan' : 'menonaktifkan';
        ActivityLog::record(
            action: 'update',
            module: 'layanan',
            description: "Telah {$statusText} status aktif layanan: \"{$layanan->judul}\"",
            properties: ['layanan_id' => $layanan->id, 'aktif' => $validated['aktif']],
            user: $request->user()
        );

        return response()->json([
            'status' => 'success',
            'message' => "Status aktif layanan \"{$layanan->judul}\" berhasil diperbarui.",
            'data' => $layanan,
        ]);
    }

    /* ----------------------------------------------------
     * MAKLUMAT PELAYANAN CMS
     * ---------------------------------------------------- */

    public function getMaklumat(Request $request): JsonResponse
    {
        $maklumat = MaklumatPelayanan::where('aktif', true)->latest('id')->first()
            ?: MaklumatPelayanan::latest('id')->first();

        if (! $maklumat) {
            $maklumat = MaklumatPelayanan::create([
                'judul' => 'Maklumat Pelayanan Kelurahan Kraksaan Wetan',
                'nomor_sk' => 'SK Lurah Kraksaan Wetan No. 188.45/04/426.411.01/2026',
                'motto' => 'Melayani dengan Ikhlas, Ramah, Cepat, Akuntabel, dan 100% Bebas Biaya (Gratis)',
                'konten' => 'Dengan ini, kami seluruh jajaran pimpinan dan aparatur Pemerintah Kelurahan Kraksaan Wetan menyatakan sanggup menyelenggarakan pelayanan publik sesuai Standar Pelayanan yang telah ditetapkan serta memberikan pelayanan yang transparan dan tanpa biaya.',
                'gambar' => null,
                'aktif' => true,
            ]);
        }

        $riwayat = MaklumatPelayanan::latest('id')->get();

        return response()->json([
            'status' => 'success',
            'data' => $maklumat,
            'riwayat' => $riwayat,
        ]);
    }

    public function saveMaklumat(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'id' => 'nullable|integer',
            'judul' => 'required|string|max:255',
            'nomor_sk' => 'nullable|string|max:255',
            'motto' => 'nullable|string|max:255',
            'konten' => 'required|string',
            'gambar' => 'nullable|string',
            'aktif' => 'nullable|boolean',
            'is_new_version' => 'nullable|boolean',
        ]);

        $isNewVersion = $request->boolean('is_new_version');
        $id = $request->input('id');

        // Jika dibuat sebagai versi baru atau belum ada id
        if ($isNewVersion || ! $id) {
            $validated['aktif'] = $request->has('aktif') ? $request->boolean('aktif') : true;
            unset($validated['id'], $validated['is_new_version']);

            $maklumat = MaklumatPelayanan::create($validated);
            $action = 'create';
            $msg = 'Maklumat Pelayanan terbaru berhasil diterbitkan. Data maklumat lama telah otomatis dinonaktifkan.';
        } else {
            $maklumat = MaklumatPelayanan::findOrFail($id);
            if ($maklumat->gambar && isset($validated['gambar']) && $maklumat->gambar !== $validated['gambar']) {
                FileStorageHelper::deleteFileIfLocal($maklumat->gambar);
            }
            unset($validated['is_new_version']);
            $maklumat->update($validated);
            $action = 'update';
            $msg = 'Maklumat Pelayanan berhasil diperbarui.';
        }

        // Jika data ini berstatus aktif, pastikan semua data lainnya otomatis nonaktif
        if ($maklumat->aktif) {
            MaklumatPelayanan::where('id', '!=', $maklumat->id)->update(['aktif' => false]);
        }

        ActivityLog::record(
            action: $action,
            module: 'layanan',
            description: ($action === 'create' ? 'Menerbitkan Maklumat Pelayanan versi baru' : 'Memperbarui Maklumat Pelayanan').": \"{$maklumat->judul}\"",
            properties: ['maklumat_id' => $maklumat->id, 'judul' => $maklumat->judul],
            user: $request->user()
        );

        return response()->json([
            'status' => 'success',
            'message' => $msg,
            'data' => $maklumat,
            'riwayat' => MaklumatPelayanan::latest('id')->get(),
        ]);
    }

    public function toggleAktifMaklumat(Request $request, int $id): JsonResponse
    {
        $maklumat = MaklumatPelayanan::findOrFail($id);
        $newAktif = ! $maklumat->aktif;

        if ($newAktif) {
            MaklumatPelayanan::where('id', '!=', $maklumat->id)->update(['aktif' => false]);
            $maklumat->update(['aktif' => true]);
            $msg = "Maklumat \"{$maklumat->judul}\" berhasil diaktifkan. Data lama otomatis dinonaktifkan.";
        } else {
            $maklumat->update(['aktif' => false]);
            $msg = "Maklumat \"{$maklumat->judul}\" telah dinonaktifkan.";
        }

        ActivityLog::record(
            action: 'update',
            module: 'layanan',
            description: "Mengubah status tayang Maklumat Pelayanan ID {$maklumat->id} menjadi ".($newAktif ? 'Aktif' : 'Nonaktif'),
            properties: ['maklumat_id' => $maklumat->id, 'aktif' => $newAktif],
            user: $request->user()
        );

        return response()->json([
            'status' => 'success',
            'message' => $msg,
            'data' => $maklumat,
            'riwayat' => MaklumatPelayanan::latest('id')->get(),
        ]);
    }

    public function deleteMaklumat(Request $request, int $id): JsonResponse
    {
        $maklumat = MaklumatPelayanan::findOrFail($id);
        $judul = $maklumat->judul;
        $gambar = $maklumat->gambar;
        $maklumat->delete();

        if ($gambar) {
            FileStorageHelper::deleteFileIfLocal($gambar);
        }

        if (! MaklumatPelayanan::where('aktif', true)->exists()) {
            $latest = MaklumatPelayanan::latest('id')->first();
            if ($latest) {
                $latest->update(['aktif' => true]);
            }
        }

        ActivityLog::record(
            action: 'delete',
            module: 'layanan',
            description: "Menghapus riwayat Maklumat Pelayanan: \"{$judul}\"",
            properties: ['judul' => $judul],
            user: $request->user()
        );

        return response()->json([
            'status' => 'success',
            'message' => "Maklumat \"{$judul}\" berhasil dihapus.",
            'riwayat' => MaklumatPelayanan::latest('id')->get(),
        ]);
    }

    /* ----------------------------------------------------
     * SURVEI KEPUASAN MASYARAKAT (SKM) CMS
     * ---------------------------------------------------- */

    public function getSurveiSkm(Request $request): JsonResponse
    {
        $list = SurveiSkm::orderBy('urutan')->orderByDesc('tahun')->get();

        return response()->json([
            'status' => 'success',
            'data' => $list,
        ]);
    }

    public function storeSurveiSkm(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'tahun' => 'required|string|max:50',
            'periode' => 'required|string|max:100',
            'skor_ikm' => 'required|numeric|min:0',
            'skala_maksimal' => 'required|numeric|min:1',
            'mutu_pelayanan' => 'required|string|max:100',
            'predikat' => 'required|string|max:100',
            'jumlah_responden' => 'required|integer|min:0',
            'metodologi' => 'nullable|string',
            'unsur_penilaian' => 'nullable|array',
            'link_survei' => 'nullable|string|max:500',
            'file_laporan' => 'nullable|string',
            'aktif' => 'boolean',
            'urutan' => 'integer',
        ]);

        $skm = SurveiSkm::create($validated);

        ActivityLog::record(
            action: 'create',
            module: 'layanan',
            description: "Menambahkan data Survei SKM Tahun {$skm->tahun} ({$skm->periode}) - Skor: {$skm->skor_ikm}",
            properties: ['skm_id' => $skm->id, 'tahun' => $skm->tahun, 'skor' => $skm->skor_ikm],
            user: $request->user()
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Data Survei Kepuasan Masyarakat (SKM) berhasil ditambahkan.',
            'data' => $skm,
        ]);
    }

    public function updateSurveiSkm(Request $request, int $id): JsonResponse
    {
        $skm = SurveiSkm::findOrFail($id);

        $validated = $request->validate([
            'tahun' => 'required|string|max:50',
            'periode' => 'required|string|max:100',
            'skor_ikm' => 'required|numeric|min:0',
            'skala_maksimal' => 'required|numeric|min:1',
            'mutu_pelayanan' => 'required|string|max:100',
            'predikat' => 'required|string|max:100',
            'jumlah_responden' => 'required|integer|min:0',
            'metodologi' => 'nullable|string',
            'unsur_penilaian' => 'nullable|array',
            'link_survei' => 'nullable|string|max:500',
            'file_laporan' => 'nullable|string',
            'aktif' => 'boolean',
            'urutan' => 'integer',
        ]);

        if ($skm->file_laporan && isset($validated['file_laporan']) && $skm->file_laporan !== $validated['file_laporan']) {
            FileStorageHelper::deleteFileIfLocal($skm->file_laporan);
        }

        $skm->update($validated);

        ActivityLog::record(
            action: 'update',
            module: 'layanan',
            description: "Memperbarui data Survei SKM Tahun {$skm->tahun} ({$skm->periode})",
            properties: ['skm_id' => $skm->id, 'tahun' => $skm->tahun, 'skor' => $skm->skor_ikm],
            user: $request->user()
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Data Survei SKM berhasil diperbarui.',
            'data' => $skm,
        ]);
    }

    public function toggleAktifSurveiSkm(Request $request, int $id): JsonResponse
    {
        $skm = SurveiSkm::findOrFail($id);

        $validated = $request->validate([
            'aktif' => 'required|boolean',
        ]);

        $skm->update(['aktif' => $validated['aktif']]);

        $statusText = $validated['aktif'] ? 'mengaktifkan' : 'menonaktifkan';
        ActivityLog::record(
            action: 'update',
            module: 'layanan',
            description: "Telah {$statusText} status tayang SKM Tahun {$skm->tahun} ({$skm->periode})",
            properties: ['skm_id' => $skm->id, 'aktif' => $validated['aktif']],
            user: $request->user()
        );

        return response()->json([
            'status' => 'success',
            'message' => "Status tayang SKM Tahun {$skm->tahun} berhasil diperbarui.",
            'data' => $skm,
        ]);
    }

    public function deleteSurveiSkm(Request $request, int $id): JsonResponse
    {
        $skm = SurveiSkm::findOrFail($id);
        $tahun = $skm->tahun;
        $periode = $skm->periode;
        $fileLaporan = $skm->file_laporan;

        $skm->delete();
        FileStorageHelper::deleteFileIfLocal($fileLaporan);

        ActivityLog::record(
            action: 'delete',
            module: 'layanan',
            description: "Menghapus data Survei SKM Tahun {$tahun} ({$periode})",
            properties: ['skm_id' => $id, 'tahun' => $tahun],
            user: $request->user()
        );

        return response()->json([
            'status' => 'success',
            'message' => "Data Survei SKM Tahun {$tahun} ({$periode}) berhasil dihapus.",
        ]);
    }
}
