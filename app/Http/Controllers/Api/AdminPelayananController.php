<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Layanan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminPelayananController extends Controller
{    /**
     * Mengubah status aktif / nonaktif jenis layanan.
     */
    public function toggleAktifLayanan(Request $request, int $id): JsonResponse
    {
        $layanan = Layanan::findOrFail($id);

        $validated = $request->validate([
            'aktif' => 'required|boolean',
        ]);

        $layanan->update(['aktif' => $validated['aktif']]);

        return response()->json([
            'status' => 'success',
            'message' => "Status aktif layanan \"{$layanan->judul}\" berhasil diperbarui.",
            'data' => $layanan,
        ]);
    }
}
