<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Layanan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PelayananController extends Controller
{
    /**
     * Mengambil daftar layanan aktif untuk portal publik.
     */
    public function getLayanan(Request $request): JsonResponse
    {
        $query = Layanan::aktif()->orderBy('urutan');

        if ($request->filled('kategori') && $request->kategori !== 'Semua') {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('judul', 'like', "%{$s}%")
                    ->orWhere('deskripsi', 'like', "%{$s}%");
            });
        }

        return response()->json([
            'status' => 'success',
            'data' => $query->get(),
        ]);
    }

    /**
     * Mengambil detail satu jenis layanan berdasarkan slug.
     */
    public function getLayananBySlug(string $slug): JsonResponse
    {
        $layanan = Layanan::where('slug', $slug)->first();

        if (! $layanan) {
            return response()->json([
                'status' => 'error',
                'message' => 'Jenis pelayanan tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $layanan,
        ]);
    }
}
