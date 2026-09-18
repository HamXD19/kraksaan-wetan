<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AgendaKegiatan;
use App\Models\Berita;
use App\Models\Dokumen;
use App\Models\Galeri;
use App\Models\Layanan;
use App\Models\Lembaga;
use App\Models\Lingkungan;
use App\Models\MasterKategori;
use App\Models\Pengumuman;
use App\Models\PerangkatKelurahan;
use App\Models\ProfilKelurahan;
use App\Models\Statistik;
use App\Models\TransparansiAnggaran;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class KelurahanController extends Controller
{
    public function getProfil(): JsonResponse
    {
        $profil = ProfilKelurahan::first();
        $perangkat = PerangkatKelurahan::orderBy('urutan')->get();

        if (! $profil) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data profil belum tersedia',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'nama' => $profil->nama,
                'kecamatan' => $profil->kecamatan,
                'kabupaten' => $profil->kabupaten,
                'provinsi' => $profil->provinsi,
                'kode_pos' => $profil->kode_pos,
                'alamat' => $profil->alamat,
                'telepon' => $profil->telepon,
                'email' => $profil->email,
                'jam_kerja' => $profil->jam_kerja,
                'deskripsi' => $profil->deskripsi,
                'sejarah' => $profil->sejarah,
                'visi' => $profil->visi,
                'misi' => $profil->misi ?? [],
                'logo' => $profil->logo,
                'hero_mode' => $profil->hero_mode ?? 'slider',
                'hero_image' => $profil->hero_image,
                'link_span_lapor' => $profil->link_span_lapor,
                'halo_sae_wa' => $profil->halo_sae_wa,
                'halo_sae_link' => $profil->halo_sae_link,
                'sambutan_lurah' => $profil->lurah_sambutan,
                'lurah' => [
                    'nama' => $profil->lurah_nama,
                    'nip' => $profil->lurah_nip,
                    'jabatan' => $profil->lurah_jabatan,
                    'sambutan' => $profil->lurah_sambutan,
                    'foto' => $profil->lurah_foto,
                ],
                'perangkat' => $perangkat->map(fn ($p) => [
                    'id' => $p->id,
                    'nama' => $p->nama,
                    'jabatan' => $p->jabatan,
                    'bidang' => $p->bidang,
                    'foto' => $p->foto,
                    'urutan' => $p->urutan,
                ]),
            ],
        ]);
    }

    public function getStatistik(): JsonResponse
    {
        $stat = Statistik::first();
        $lingkungan = Lingkungan::orderBy('urutan')->get();

        if (! $stat) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data statistik belum tersedia',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'penduduk' => $stat->penduduk,
                'kk' => $stat->kk,
                'laki_laki' => $stat->laki_laki,
                'perempuan' => $stat->perempuan,
                'rt' => $stat->rt,
                'rw' => $stat->rw,
                'luas_wilayah' => $stat->luas_wilayah,
                'kepadatan' => $stat->kepadatan,
                'lingkungan' => $lingkungan->map(fn ($l) => [
                    'id' => $l->id,
                    'nama' => $l->nama,
                    'rt' => $l->rt,
                    'penduduk' => $l->penduduk,
                ]),
            ],
        ]);
    }

    public function getLayanan(Request $request): JsonResponse
    {
        $query = Layanan::orderBy('urutan');

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

    public function getBerita(Request $request): JsonResponse
    {
        $query = Berita::query()->latest();

        if ($request->filled('kategori') && $request->kategori !== 'Semua') {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('judul', 'like', "%{$s}%")
                    ->orWhere('ringkasan', 'like', "%{$s}%")
                    ->orWhere('konten', 'like', "%{$s}%");
            });
        }

        $berita = $query->get();

        return response()->json([
            'status' => 'success',
            'data' => $berita,
        ]);
    }

    public function getBeritaBySlug(string $slug): JsonResponse
    {
        $item = Berita::where('slug', $slug)->first();

        if (! $item) {
            return response()->json([
                'status' => 'error',
                'message' => 'Berita tidak ditemukan',
            ], 404);
        }

        // Increment dynamic view count in database
        $item->increment('dilihat');

        return response()->json([
            'status' => 'success',
            'data' => $item,
        ]);
    }

    public function getPengumuman(Request $request): JsonResponse
    {
        $query = Pengumuman::latest();

        if ($request->filled('prioritas') && $request->prioritas !== 'Semua') {
            $query->where('prioritas', $request->prioritas);
        }

        if ($request->filled('kategori') && $request->kategori !== 'Semua') {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('judul', 'like', "%{$s}%")
                    ->orWhere('isi', 'like', "%{$s}%");
            });
        }

        $items = $query->get()->map(function ($item) {
            $fileUrl = null;
            $fileNama = null;

            if (! empty($item->file)) {
                $fileUrl = url("/api/pengumuman/{$item->id}/unduh");
                $rawName = basename(parse_url($item->file, PHP_URL_PATH) ?? $item->file);

                if (preg_match('/^[A-Za-z0-9_-]{16,}\.pdf$/i', $rawName)) {
                    $fileNama = Str::limit($item->judul, 45, '').'.pdf';
                } else {
                    $fileNama = $rawName;
                }
            }

            return array_merge($item->toArray(), [
                'file_url' => $fileUrl,
                'file_nama' => $fileNama,
            ]);
        });

        return response()->json([
            'status' => 'success',
            'data' => $items,
        ]);
    }

    /**
     * Unduh lampiran dokumen PDF pengumuman secara riil
     */
    public function unduhPengumuman(int $id)
    {
        $pengumuman = Pengumuman::findOrFail($id);

        if (empty($pengumuman->file)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pengumuman ini tidak memiliki lampiran dokumen PDF.',
            ], 404);
        }

        $fileVal = $pengumuman->file;

        // Nama file saat diunduh pengguna
        $cleanJudul = Str::slug($pengumuman->judul);
        $downloadName = ($cleanJudul ?: 'lampiran-pengumuman').'.pdf';

        // 1. Ekstraksi path lokal di public disk
        $parsedPath = parse_url($fileVal, PHP_URL_PATH) ?? $fileVal;
        $relativePath = ltrim($parsedPath, '/');
        if (str_starts_with($relativePath, 'storage/')) {
            $relativePath = substr($relativePath, 8);
        }

        $candidates = [
            $relativePath,
            'uploads/'.ltrim($relativePath, '/'),
            'uploads/'.basename($fileVal),
            basename($fileVal),
        ];

        foreach ($candidates as $cand) {
            if (Storage::disk('public')->exists($cand)) {
                $fullPath = Storage::disk('public')->path($cand);

                return response()->download($fullPath, $downloadName, [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'attachment; filename="'.$downloadName.'"',
                ]);
            }
        }

        // 2. Jika file berupa URL eksternal (misal CDN)
        if (filter_var($fileVal, FILTER_VALIDATE_URL) && ! str_contains($fileVal, request()->getHost())) {
            return redirect()->away($fileVal);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Berkas lampiran dokumen tidak ditemukan di penyimpanan server.',
        ], 404);
    }

    /**
     * Mengambil daftar dokumen publik kelurahan (PDF) yang aktif
     */
    public function getDokumen(Request $request): JsonResponse
    {
        $query = Dokumen::where('aktif', true);

        if ($request->filled('kategori') && $request->kategori !== 'Semua') {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('subkategori') && $request->subkategori !== 'Semua') {
            $query->where('subkategori', $request->subkategori);
        }

        if ($request->filled('periode') && $request->periode !== 'Semua') {
            $query->where('periode', $request->periode);
        }

        if ($request->filled('tahun') && $request->tahun !== 'Semua') {
            $query->where(function ($q) use ($request) {
                $q->where('tahun', $request->tahun)
                    ->orWhere('tahun_selesai', $request->tahun);
            });
        }

        if ($request->filled('q')) {
            $search = trim($request->q);
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('nomor_dokumen', 'like', "%{$search}%")
                    ->orWhere('subkategori', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%")
                    ->orWhere('kategori', 'like', "%{$search}%");
            });
        }

        $dokumen = $query->orderByDesc('id')->get()->map(function ($doc) {
            $fileUrl = asset('storage/'.ltrim($doc->file, '/'));

            return [
                'id' => $doc->id,
                'judul' => $doc->judul,
                'nomor_dokumen' => $doc->nomor_dokumen,
                'kategori' => $doc->kategori ?: 'Umum',
                'subkategori' => $doc->subkategori,
                'periode' => $doc->periode ?: 'Tahunan',
                'periode_ke' => $doc->periode_ke,
                'tahun' => $doc->tahun,
                'tahun_selesai' => $doc->tahun_selesai,
                'label_periode_lengkap' => $doc->label_periode_lengkap,
                'deskripsi' => $doc->deskripsi,
                'file' => $doc->file,
                'file_url' => $fileUrl,
                'preview_url' => $fileUrl,
                'nama_file_asli' => $doc->nama_file_asli ?? basename($doc->file),
                'ukuran_file' => $doc->ukuran_file,
                'diunduh' => (int) $doc->diunduh,
                'tanggal_publikasi' => $doc->tanggal_publikasi?->format('Y-m-d'),
                'tanggal' => $doc->tanggal_format,
            ];
        });

        // Kategori taxonomy dari MasterKategori
        $masterTree = MasterKategori::where('modul', 'dokumen')
            ->whereNull('parent_id')
            ->where('is_aktif', true)
            ->with(['subkategoris' => function ($q) {
                $q->where('is_aktif', true);
            }])
            ->orderBy('urutan')
            ->orderBy('nama')
            ->get();

        // Kategori list
        $kategoriList = Dokumen::where('aktif', true)
            ->whereNotNull('kategori')
            ->distinct()
            ->pluck('kategori')
            ->filter()
            ->values();

        $subkategoriList = Dokumen::where('aktif', true)
            ->whereNotNull('subkategori')
            ->distinct()
            ->pluck('subkategori')
            ->filter()
            ->values();

        $tahunList = Dokumen::where('aktif', true)
            ->whereNotNull('tahun')
            ->distinct()
            ->orderByDesc('tahun')
            ->pluck('tahun')
            ->filter()
            ->values();

        $periodeList = ['5 Tahunan', 'Tahunan', 'Semesteran', 'Triwulanan', 'Bulanan', 'Sewaktu-waktu'];

        return response()->json([
            'status' => 'success',
            'data' => $dokumen,
            'meta' => [
                'total' => $dokumen->count(),
                'kategori_list' => $kategoriList,
                'subkategori_list' => $subkategoriList,
                'periode_list' => $periodeList,
                'tahun_list' => $tahunList,
                'master_kategori_tree' => $masterTree,
            ],
        ]);
    }

    /**
     * Unduh berkas dokumen PDF secara riil
     */
    public function unduhDokumen(int $id)
    {
        $dokumen = Dokumen::findOrFail($id);

        if (empty($dokumen->file)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Dokumen ini tidak memiliki berkas lampiran PDF.',
            ], 404);
        }

        $fileVal = $dokumen->file;

        // Nama file saat diunduh pengguna
        $cleanJudul = Str::slug($dokumen->judul);
        $downloadName = ($cleanJudul ?: 'dokumen-kelurahan').'.pdf';

        // 1. Ekstraksi path lokal di public disk
        $parsedPath = parse_url($fileVal, PHP_URL_PATH) ?? $fileVal;
        $relativePath = ltrim($parsedPath, '/');
        if (str_starts_with($relativePath, 'storage/')) {
            $relativePath = substr($relativePath, 8);
        }

        $candidates = [
            $relativePath,
            'uploads/'.ltrim($relativePath, '/'),
            'uploads/'.basename($fileVal),
            basename($fileVal),
        ];

        foreach ($candidates as $cand) {
            if (Storage::disk('public')->exists($cand)) {
                $dokumen->increment('diunduh');
                $fullPath = Storage::disk('public')->path($cand);

                return response()->download($fullPath, $downloadName, [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'attachment; filename="'.$downloadName.'"',
                ]);
            }
        }

        // 2. Jika file berupa URL eksternal
        if (filter_var($fileVal, FILTER_VALIDATE_URL) && ! str_contains($fileVal, request()->getHost())) {
            $dokumen->increment('diunduh');

            return redirect()->away($fileVal);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Berkas dokumen PDF tidak ditemukan di penyimpanan server.',
        ], 404);
    }

    public function getKategori(Request $request): JsonResponse
    {
        $query = MasterKategori::where('is_aktif', true)->orderBy('urutan')->orderBy('nama');

        if ($request->filled('modul') && $request->modul !== 'semua') {
            $query->where('modul', $request->modul);
        }

        return response()->json([
            'status' => 'success',
            'data' => $query->get(),
        ]);
    }

    public function getGaleri(Request $request): JsonResponse
    {
        $query = Galeri::latest();

        if ($request->filled('tipe') && in_array($request->tipe, ['foto', 'video'])) {
            $query->where('tipe', $request->tipe);
        }

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

    public function getGaleriById(int $id): JsonResponse
    {
        $item = Galeri::find($id);

        if (! $item) {
            return response()->json([
                'status' => 'error',
                'message' => 'Dokumentasi galeri tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $item,
        ]);
    }

    public function getLembaga(): JsonResponse
    {
        $lembaga = Lembaga::where('aktif', true)
            ->orderBy('urutan')
            ->orderBy('id')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $lembaga,
        ]);
    }

    public function getTransparansi(Request $request): JsonResponse
    {
        // Distinct years available
        $daftarTahun = TransparansiAnggaran::where('aktif', true)
            ->distinct()
            ->orderByDesc('tahun')
            ->pluck('tahun')
            ->values();

        $selectedYear = $request->filled('tahun') && $request->tahun !== 'Semua'
            ? (int) $request->tahun
            : ($daftarTahun->first() ?? (int) date('Y'));

        // Query for summary stats
        $yearQuery = TransparansiAnggaran::where('aktif', true);
        if ($request->input('tahun') !== 'Semua') {
            $yearQuery->where('tahun', $selectedYear);
        }

        // Summary calculations
        $totalRencana = (float) (clone $yearQuery)->sum('anggaran_rencana');
        $totalRealisasi = (float) (clone $yearQuery)->sum('anggaran_realisasi');
        $totalSisa = max(0, $totalRencana - $totalRealisasi);
        $persentaseTotal = $totalRencana > 0 ? round(($totalRealisasi / $totalRencana) * 100, 1) : 0.0;
        $totalKegiatan = (clone $yearQuery)->count();
        $kegiatanSelesai = (clone $yearQuery)->where('status', 'Selesai')->count();
        $kegiatanBerjalan = (clone $yearQuery)->where('status', 'Sedang Berjalan')->count();

        // Breakdown per kategori for the selected year
        $breakdownKategori = (clone $yearQuery)
            ->selectRaw('kategori, sum(anggaran_rencana) as rencana, sum(anggaran_realisasi) as realisasi, count(*) as jumlah_kegiatan')
            ->groupBy('kategori')
            ->get()
            ->map(function ($row) {
                $rencana = (float) $row->rencana;
                $realisasi = (float) $row->realisasi;
                $persen = $rencana > 0 ? round(($realisasi / $rencana) * 100, 1) : 0.0;

                return [
                    'kategori' => $row->kategori,
                    'rencana' => $rencana,
                    'realisasi' => $realisasi,
                    'sisa' => max(0, $rencana - $realisasi),
                    'persentase' => $persen,
                    'jumlah_kegiatan' => (int) $row->jumlah_kegiatan,
                ];
            });

        // Filter for returned list
        $listQuery = clone $yearQuery;

        if ($request->filled('kategori') && $request->kategori !== 'Semua') {
            $listQuery->where('kategori', $request->kategori);
        }

        if ($request->filled('status') && $request->status !== 'Semua') {
            $listQuery->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $listQuery->where(function ($q) use ($s) {
                $q->where('program', 'like', "%{$s}%")
                    ->orWhere('kegiatan', 'like', "%{$s}%")
                    ->orWhere('deskripsi', 'like', "%{$s}%")
                    ->orWhere('penerima_manfaat_target', 'like', "%{$s}%")
                    ->orWhere('lokasi', 'like', "%{$s}%");
            });
        }

        $items = $listQuery->orderBy('urutan')->orderBy('id')->get();

        // Distinct categories for filter
        $daftarKategori = TransparansiAnggaran::where('aktif', true)
            ->distinct()
            ->pluck('kategori')
            ->values();

        return response()->json([
            'status' => 'success',
            'data' => [
                'summary' => [
                    'tahun_terpilih' => $request->input('tahun') === 'Semua' ? 'Semua' : $selectedYear,
                    'total_rencana' => $totalRencana,
                    'total_realisasi' => $totalRealisasi,
                    'total_sisa' => $totalSisa,
                    'persentase_total' => $persentaseTotal,
                    'total_kegiatan' => $totalKegiatan,
                    'kegiatan_selesai' => $kegiatanSelesai,
                    'kegiatan_berjalan' => $kegiatanBerjalan,
                    'daftar_tahun' => $daftarTahun,
                    'daftar_kategori' => $daftarKategori,
                    'breakdown_kategori' => $breakdownKategori,
                ],
                'kegiatan' => $items,
            ],
        ]);
    }

    /**
     * Endpoint Audio Text-to-Speech (TTS) Bahasa Indonesia
     * Menghasilkan audio ucapan alami dengan disk caching server-side
     */
    public function getTtsAudio(Request $request)
    {
        $text = trim((string) $request->query('text', ''));
        if (empty($text) || mb_strlen($text) > 120) {
            return response()->noContent();
        }

        $hash = md5(mb_strtolower($text));
        $cacheDir = storage_path('app/tts');
        if (! is_dir($cacheDir)) {
            @mkdir($cacheDir, 0755, true);
        }
        $cacheFile = $cacheDir.'/'.$hash.'.mp3';

        if (file_exists($cacheFile) && filesize($cacheFile) > 300) {
            return response()->file($cacheFile, [
                'Content-Type' => 'audio/mpeg',
                'Cache-Control' => 'public, max-age=31536000',
            ]);
        }

        try {
            $url = 'https://translate.google.com/translate_tts?ie=UTF-8&client=tw-ob&tl=id&q='.urlencode($text);
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
            ])->timeout(5)->get($url);

            if ($response->successful() && strlen($response->body()) > 300) {
                file_put_contents($cacheFile, $response->body());

                return response()->file($cacheFile, [
                    'Content-Type' => 'audio/mpeg',
                    'Cache-Control' => 'public, max-age=31536000',
                ]);
            }
        } catch (\Throwable $e) {
            // fallback gracefully
        }

        return response()->noContent();
    }

    /**
     * Kirim Pesan / Pengaduan Warga (Kontak)
     */
    public function kirimKontak(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'telepon' => 'nullable|string|max:25',
            'email' => 'nullable|email|max:100',
            'kategori' => 'nullable|string|max:50',
            'pesan' => 'required|string',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Pesan atau aspirasi Anda telah berhasil dikirimkan ke pihak Kelurahan Kraksaan Wetan.',
            'data' => $validated,
        ]);
    }

    /**
     * Daftar Agenda Kegiatan Publik
     * Otomatis menyaring hanya kegiatan aktif & belum melewati tanggal selesai, kecuali jika tampilkan_riwayat diset.
     */
    public function getAgenda(Request $request): JsonResponse
    {
        $query = AgendaKegiatan::query();

        if ($request->boolean('tampilkan_riwayat')) {
            $query->where('is_aktif', true)
                ->where('tanggal_selesai', '<', now())
                ->orderBy('tanggal_mulai', 'desc');
        } else {
            $query->where('is_aktif', true)
                ->where('tanggal_selesai', '>=', now())
                ->orderBy('tanggal_mulai', 'asc');
        }

        if ($request->filled('kategori') && $request->kategori !== 'Semua') {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('judul', 'like', "%{$s}%")
                    ->orWhere('deskripsi', 'like', "%{$s}%")
                    ->orWhere('lokasi', 'like', "%{$s}%");
            });
        }

        $agendas = $query->get();

        $totalAktif = AgendaKegiatan::where('is_aktif', true)->where('tanggal_selesai', '>=', now())->count();
        $totalRiwayat = AgendaKegiatan::where('is_aktif', true)->where('tanggal_selesai', '<', now())->count();

        return response()->json([
            'status' => 'success',
            'data' => $agendas,
            'counts' => [
                'aktif' => $totalAktif,
                'riwayat' => $totalRiwayat,
            ],
        ]);
    }

    /**
     * Detail Agenda Kegiatan Berdasarkan Slug
     */
    public function getAgendaBySlug(string $slug): JsonResponse
    {
        $agenda = AgendaKegiatan::where('slug', $slug)
            ->where('is_aktif', true)
            ->first();

        if (! $agenda) {
            return response()->json([
                'status' => 'error',
                'message' => 'Agenda kegiatan tidak ditemukan atau sudah tidak aktif.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $agenda,
        ]);
    }
}
