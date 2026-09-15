<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Layanan;
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
}
