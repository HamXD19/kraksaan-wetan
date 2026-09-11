<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Layanan;
use App\Models\ServiceDocument;
use App\Models\ServiceRequest;
use App\Models\ServiceRequestHistory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

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

    /**
     * Submit formulir permohonan layanan online oleh warga.
     */
    public function submitPengajuan(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'layanan_id' => 'required|exists:layanans,id',
            'nama_pemohon' => 'required|string|max:100',
            'nik' => ['required', 'string', 'size:16', 'regex:/^[0-9]+$/'],
            'no_kk' => ['nullable', 'string', 'size:16', 'regex:/^[0-9]+$/'],
            'no_hp' => ['required', 'string', 'min:8', 'max:20', 'regex:/^[0-9+\-\s]+$/'],
            'alamat' => 'required|string|max:500',
            'keperluan' => 'required|string|max:500',
            'keterangan' => 'nullable|string|max:1000',
            'dokumen' => 'nullable|array',
            'dokumen.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120', // Maks 5MB per file
            'nama_dokumen' => 'nullable|array',
            'nama_dokumen.*' => 'nullable|string|max:100',
        ], [
            'nik.size' => 'Nomor Induk Kependudukan (NIK) harus berjumlah 16 digit angka.',
            'nik.regex' => 'NIK hanya boleh memuat digit angka.',
            'no_kk.size' => 'Nomor Kartu Keluarga (KK) harus berjumlah 16 digit angka.',
            'no_kk.regex' => 'Nomor KK hanya boleh memuat digit angka.',
            'no_hp.regex' => 'Format nomor handphone/WhatsApp tidak valid.',
            'dokumen.*.mimes' => 'Format berkas harus berupa PDF, JPG, JPEG, atau PNG.',
            'dokumen.*.max' => 'Ukuran setiap berkas persyaratan maksimal 5 Megabytes (5MB).',
        ]);

        $layanan = Layanan::findOrFail($validated['layanan_id']);

        if (! $layanan->aktif) {
            throw ValidationException::withMessages([
                'layanan_id' => 'Layanan ini sedang dinonaktifkan sementara oleh pihak kelurahan.',
            ]);
        }

        $serviceRequest = DB::transaction(function () use ($validated, $request, $layanan) {
            $nomorPengajuan = ServiceRequest::generateNomorPengajuan();

            $sr = ServiceRequest::create([
                'nomor_pengajuan' => $nomorPengajuan,
                'layanan_id' => $layanan->id,
                'nama_pemohon' => $validated['nama_pemohon'],
                'nik' => $validated['nik'],
                'no_kk' => $validated['no_kk'] ?? null,
                'no_hp' => $validated['no_hp'],
                'alamat' => $validated['alamat'],
                'keperluan' => $validated['keperluan'],
                'keterangan' => $validated['keterangan'] ?? null,
                'status' => 'Menunggu Verifikasi',
                'submitted_at' => now(),
            ]);

            // Simpan dokumen persyaratan ke private storage
            if ($request->hasFile('dokumen')) {
                $files = $request->file('dokumen');
                $names = $request->input('nama_dokumen', []);

                foreach ($files as $index => $file) {
                    if ($file && $file->isValid()) {
                        $docName = $names[$index] ?? ('Dokumen Persyaratan #'.($index + 1));
                        $originalName = $file->getClientOriginalName();
                        $mimeType = $file->getClientMimeType();
                        $fileSize = $file->getSize();

                        // Disimpan di private disk (bukan public)
                        $path = $file->store('service_documents/'.date('Y/m'), 'local');

                        ServiceDocument::create([
                            'service_request_id' => $sr->id,
                            'nama_dokumen' => $docName,
                            'file_path' => $path,
                            'file_name' => $originalName,
                            'file_type' => $mimeType,
                            'file_size' => $fileSize,
                        ]);
                    }
                }
            }

            // Catat riwayat status awal
            ServiceRequestHistory::create([
                'service_request_id' => $sr->id,
                'status' => 'Menunggu Verifikasi',
                'catatan' => 'Permohonan online berhasil didaftarkan oleh warga dan masuk antrean verifikasi petugas kelurahan.',
                'admin_id' => null,
            ]);

            return $sr;
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Pengajuan pelayanan berhasil dikirim.',
            'data' => [
                'nomor_pengajuan' => $serviceRequest->nomor_pengajuan,
                'nama_pemohon' => $serviceRequest->nama_pemohon,
                'layanan' => $layanan->judul,
                'status' => $serviceRequest->status,
                'tanggal_pengajuan' => $serviceRequest->submitted_at->translatedFormat('d F Y, H:i').' WIB',
            ],
        ], 201);
    }

    /**
     * Cek status pengajuan oleh warga (Tracking).
     */
    public function trackPengajuan(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nomor_pengajuan' => 'required|string|max:50',
            'nik' => ['required', 'string', 'size:16', 'regex:/^[0-9]+$/'],
        ], [
            'nomor_pengajuan.required' => 'Nomor Pengajuan wajib diisi.',
            'nik.required' => 'NIK pemohon wajib diisi.',
            'nik.size' => 'NIK harus berjumlah 16 digit.',
        ]);

        $item = ServiceRequest::where('nomor_pengajuan', trim($validated['nomor_pengajuan']))
            ->where('nik', trim($validated['nik']))
            ->with(['layanan', 'histories'])
            ->first();

        if (! $item) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pengajuan tidak ditemukan. Pastikan Nomor Pengajuan dan NIK yang Anda masukkan sudah sesuai dengan data saat pendaftaran.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'nomor_pengajuan' => $item->nomor_pengajuan,
                'layanan' => [
                    'id' => $item->layanan->id,
                    'judul' => $item->layanan->judul,
                    'kategori' => $item->layanan->kategori,
                    'waktu' => $item->layanan->waktu,
                    'biaya' => $item->layanan->biaya,
                ],
                'nama_pemohon' => $item->nama_pemohon,
                'masked_nik' => $item->masked_nik,
                'masked_no_kk' => $item->masked_no_kk,
                'alamat' => $item->alamat,
                'keperluan' => $item->keperluan,
                'keterangan' => $item->keterangan,
                'status' => $item->status,
                'catatan_admin' => $item->catatan_admin,
                'submitted_at' => $item->submitted_at ? $item->submitted_at->translatedFormat('d F Y, H:i').' WIB' : '-',
                'processed_at' => $item->processed_at ? $item->processed_at->translatedFormat('d F Y, H:i').' WIB' : null,
                'completed_at' => $item->completed_at ? $item->completed_at->translatedFormat('d F Y, H:i').' WIB' : null,
                'jumlah_dokumen' => $item->documents()->count(),
                'histories' => $item->histories->map(fn ($h) => [
                    'status' => $h->status,
                    'catatan' => $h->catatan,
                    'tanggal' => $h->created_at->translatedFormat('d F Y, H:i').' WIB',
                ]),
            ],
        ]);
    }
}
