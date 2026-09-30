<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AgendaKegiatan;
use App\Models\AnggaranRealisasi;
use App\Models\Berita;
use App\Models\Dokumen;
use App\Models\Galeri;
use App\Models\HalamanKustom;
use App\Models\Layanan;
use App\Models\Lembaga;
use App\Models\Lingkungan;
use App\Models\MaklumatPelayanan;
use App\Models\MasterKategori;
use App\Models\Pengumuman;
use App\Models\PerangkatKelurahan;
use App\Models\ProfilKelurahan;
use App\Models\Statistik;
use App\Models\SurveiSkm;
use App\Models\TransparansiAnggaran;
use App\Services\FileStorageHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
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

        $customNavMenus = $profil->custom_nav_menus ?? [
            'profil' => [],
            'pemerintahan' => [],
            'informasi' => [],
        ];

        // Otomatis sertakan HalamanKustom aktif ke navbar jika belum terdaftar
        $halamanAktif = HalamanKustom::where('aktif', true)->orderBy('urutan')->get();
        foreach ($halamanAktif as $h) {
            $cat = in_array($h->kategori, ['profil', 'pemerintahan', 'informasi']) ? $h->kategori : 'profil';
            if (! isset($customNavMenus[$cat])) {
                $customNavMenus[$cat] = [];
            }
            $targetUrl = "/halaman/{$h->slug}";
            $exists = false;
            foreach ($customNavMenus[$cat] as $menuItem) {
                if (($menuItem['url'] ?? '') === $targetUrl || ($menuItem['label'] ?? '') === $h->judul) {
                    $exists = true;
                    break;
                }
            }
            if (! $exists) {
                $customNavMenus[$cat][] = [
                    'label' => $h->judul,
                    'url' => $targetUrl,
                    'target' => '_self',
                ];
            }
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
                'sejarah_timeline' => $profil->sejarah_timeline ?? [
                    [
                        'tahun' => 'Era Hindia Belanda & Pra-Kemerdekaan',
                        'judul' => 'Sentra Niaga Pesisir',
                        'deskripsi' => 'Berkembang sebagai sentra niaga masyarakat agraris dan pesisir di sekitar stasiun dan jalur pos Daendels.',
                    ],
                    [
                        'tahun' => 'Peralihan Menjadi Kelurahan Definitif',
                        'judul' => 'Penataan Administrasi',
                        'deskripsi' => 'Status tata kelola pemerintahan bertransformasi menjadi kelurahan dengan penataan administrasi RT/RW modern.',
                    ],
                    [
                        'tahun' => 'Tahun 2010 - Sekarang: Ibu Kota Kabupaten',
                        'judul' => 'Pusat Ibu Kota Baru',
                        'deskripsi' => 'Pusat pemekaran infrastruktur perkotaan, digitalisasi pelayanan, dan penguatan UMKM warga.',
                    ],
                ],
                'batas_wilayah' => $profil->batas_wilayah ?? [
                    'utara' => 'Desa Kalibuntu & Selat Madura',
                    'selatan' => 'Desa Sumberlele & Kecamatan Besuk',
                    'timur' => 'Desa Bulu & Desa Rondokuning',
                    'barat' => 'Sungai Kraksaan & Kelurahan Patokan',
                ],
                'potensi_unggulan' => $profil->potensi_unggulan ?? [
                    [
                        'judul' => 'UMKM Kuliner & Niaga',
                        'deskripsi' => 'Pusat jajanan tradisional, olahan hasil laut Kraksaan, dan sentra pedagang pasar lokal.',
                    ],
                    [
                        'judul' => 'Kawasan Pemukiman',
                        'deskripsi' => 'Lingkungan RT/RW tertib dengan semangat gotong royong dan posyandu integrasi aktif.',
                    ],
                    [
                        'judul' => 'Pelayanan Digital',
                        'deskripsi' => 'Pemanfaatan sistem digital kependudukan dan transparansi informasi warga berbasis website.',
                    ],
                ],
                'visi' => $profil->visi,
                'misi' => $profil->misi ?? [],
                'tata_nilai' => $profil->tata_nilai ?? [
                    [
                        'judul' => 'Berorientasi Pelayanan',
                        'deskripsi' => 'Memahami dan memenuhi kebutuhan masyarakat secara ramah, cekatan, dan solutif.',
                    ],
                    [
                        'judul' => 'Akuntabel & Transparan',
                        'deskripsi' => 'Melaksanakan tugas dengan jujur, bertanggung jawab, cermat, disiplin, dan bebas pungli.',
                    ],
                    [
                        'judul' => 'Harmonis & Gotong Royong',
                        'deskripsi' => 'Saling peduli, menghargai keberagaman warga, dan menjaga kerukunan antarkomunitas.',
                    ],
                    [
                        'judul' => 'Adaptif & Kolaboratif',
                        'deskripsi' => 'Terus berinovasi dan memanfaatkan teknologi digital untuk percepatan layanan publik.',
                    ],
                ],
                'logo' => $profil->logo,
                'hero_mode' => $profil->hero_mode ?? 'slider',
                'hero_image' => $profil->hero_image,
                'link_span_lapor' => $profil->link_span_lapor,
                'halo_sae_wa' => $profil->halo_sae_wa,
                'halo_sae_link' => $profil->halo_sae_link,
                'custom_nav_menus' => $customNavMenus,
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
        $query = Berita::published()->latest();

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

    public function getRunningText(): JsonResponse
    {
        $items = Berita::published()
            ->runningText()
            ->latest()
            ->take(10)
            ->get(['id', 'slug', 'judul', 'kategori', 'tanggal']);

        return response()->json([
            'status' => 'success',
            'data' => $items,
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

        $fileVal = $pengumuman->file;

        // 1. Jika file berupa URL eksternal (misal CDN)
        if (! empty($fileVal) && filter_var($fileVal, FILTER_VALIDATE_URL) && ! str_contains($fileVal, request()->getHost())) {
            return redirect()->away($fileVal);
        }

        // 2. Pastikan berkas fisik tersedia di storage
        $relPath = FileStorageHelper::ensureValidPdfFile(
            $fileVal,
            $pengumuman->judul,
            'Pengumuman Resmi',
            '',
            $pengumuman->isi
        );

        if (empty($pengumuman->file) || $pengumuman->file !== $relPath) {
            $pengumuman->update(['file' => $relPath]);
        }

        $cleanJudul = Str::slug($pengumuman->judul);
        $downloadName = ($cleanJudul ?: 'lampiran-pengumuman').'.pdf';
        $fullPath = Storage::disk('public')->path($relPath);

        return response()->download($fullPath, $downloadName, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="'.$downloadName.'"',
        ]);
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
            $storageUrl = asset('storage/'.ltrim($doc->file, '/'));
            $previewUrl = url("/api/dokumen/{$doc->id}/pratinjau");
            $unduhUrl = url("/api/dokumen/{$doc->id}/unduh");

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
                'file_url' => $unduhUrl,
                'preview_url' => $previewUrl,
                'storage_url' => $storageUrl,
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

        $fileVal = $dokumen->file;

        // 1. Jika URL eksternal, redirect langsung
        if (! empty($fileVal) && filter_var($fileVal, FILTER_VALIDATE_URL) && ! str_contains($fileVal, request()->getHost())) {
            $dokumen->increment('diunduh');

            return redirect()->away($fileVal);
        }

        // 2. Pastikan berkas fisik tersedia di storage (atau digenerate jika belum ada)
        $relPath = FileStorageHelper::ensureValidPdfFile(
            $fileVal,
            $dokumen->judul,
            $dokumen->kategori ?: 'Umum',
            $dokumen->nomor_dokumen,
            $dokumen->deskripsi
        );

        if (empty($dokumen->file) || $dokumen->file !== $relPath) {
            $dokumen->update(['file' => $relPath]);
        }

        $cleanJudul = Str::slug($dokumen->judul);
        $downloadName = ($cleanJudul ?: 'dokumen-kelurahan').'.pdf';
        $fullPath = Storage::disk('public')->path($relPath);

        $dokumen->increment('diunduh');

        return response()->download($fullPath, $downloadName, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="'.$downloadName.'"',
        ]);
    }

    /**
     * Pratinjau inline berkas dokumen PDF (untuk iframe preview)
     */
    public function pratinjauDokumen(int $id)
    {
        $dokumen = Dokumen::findOrFail($id);
        $fileVal = $dokumen->file;

        if (! empty($fileVal) && filter_var($fileVal, FILTER_VALIDATE_URL) && ! str_contains($fileVal, request()->getHost())) {
            return redirect()->away($fileVal);
        }

        $relPath = FileStorageHelper::ensureValidPdfFile(
            $fileVal,
            $dokumen->judul,
            $dokumen->kategori ?: 'Umum',
            $dokumen->nomor_dokumen,
            $dokumen->deskripsi
        );

        if (empty($dokumen->file) || $dokumen->file !== $relPath) {
            $dokumen->update(['file' => $relPath]);
        }

        $fullPath = Storage::disk('public')->path($relPath);

        return response()->file($fullPath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.basename($fullPath).'"',
        ]);
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
        // 1. Ambil daftar tahun dari AnggaranRealisasi (atau fallback TransparansiAnggaran)
        $daftarTahunRealisasi = AnggaranRealisasi::published()
            ->distinct()
            ->orderByDesc('tahun')
            ->pluck('tahun');

        $daftarTahunLegacy = TransparansiAnggaran::where('aktif', true)
            ->distinct()
            ->orderByDesc('tahun')
            ->pluck('tahun');

        $daftarTahun = $daftarTahunRealisasi->concat($daftarTahunLegacy)->unique()->values();
        if ($daftarTahun->isEmpty()) {
            $daftarTahun = collect([(int) date('Y')]);
        }

        $selectedYear = $request->filled('tahun') && $request->tahun !== 'Semua'
            ? (int) $request->tahun
            : ($daftarTahun->first() ?? (int) date('Y'));

        // 2. Query Card List APBD / Anggaran Publik
        $budgetsQuery = AnggaranRealisasi::published()->withCount('items');
        if ($request->filled('tahun') && $request->tahun !== 'Semua') {
            $budgetsQuery->where('tahun', $selectedYear);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $budgetsQuery->where(function ($q) use ($s) {
                $q->where('judul', 'like', "%{$s}%")
                    ->orWhere('deskripsi', 'like', "%{$s}%");
            });
        }
        $budgets = $budgetsQuery->orderByDesc('tahun')->orderByDesc('tanggal_publikasi')->get();

        // 3. Ringkasan & data legacy (jika ada pos kegiatan individual)
        $yearQuery = TransparansiAnggaran::where('aktif', true);
        if ($request->input('tahun') !== 'Semua') {
            $yearQuery->where('tahun', $selectedYear);
        }

        $totalRencana = (float) (clone $yearQuery)->sum('anggaran_rencana');
        $totalRealisasi = (float) (clone $yearQuery)->sum('anggaran_realisasi');

        // Jika ada data AnggaranRealisasi untuk tahun terpilih, prioritaskan nilainya
        $selectedBudget = $budgets->firstWhere('tahun', $selectedYear) ?? $budgets->first();
        if ($selectedBudget) {
            $totalRencana = (float) $selectedBudget->total_belanja_rencana ?: (float) $selectedBudget->total_pendapatan_rencana;
            $totalRealisasi = (float) $selectedBudget->total_belanja_realisasi ?: (float) $selectedBudget->total_pendapatan_realisasi;
        }

        $totalSisa = max(0, $totalRencana - $totalRealisasi);
        $persentaseTotal = $totalRencana > 0 ? round(($totalRealisasi / $totalRencana) * 100, 1) : 0.0;
        $totalKegiatan = (clone $yearQuery)->count() + ($selectedBudget ? $selectedBudget->items_count : 0);
        $kegiatanSelesai = (clone $yearQuery)->where('status', 'Selesai')->count();
        $kegiatanBerjalan = (clone $yearQuery)->where('status', 'Sedang Berjalan')->count();

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
                'budgets' => $budgets,
                'kegiatan' => $items,
            ],
        ]);
    }

    /**
     * Endpoint detail publik APBD dengan 3 tabel terstruktur: Pendapatan, Belanja, Pembiayaan
     */
    public function getTransparansiDetail(string $slugOrId): JsonResponse
    {
        $budget = AnggaranRealisasi::where('slug', $slugOrId)
            ->orWhere('id', is_numeric($slugOrId) ? (int) $slugOrId : 0)
            ->with(['items' => function ($q) {
                $q->orderBy('urutan')->orderBy('id');
            }])
            ->first();

        if (! $budget) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data anggaran tidak ditemukan.',
            ], 404);
        }

        // Cek visibilitas publik: jika draft dan user bukan admin auth, tolak
        if ($budget->status !== 'published') {
            $token = request()->bearerToken();
            $isAuthedAdmin = false;
            if ($token) {
                if (Cache::has('admin_auth_token_'.$token)) {
                    $isAuthedAdmin = true;
                } else {
                    $decoded = base64_decode($token, true);
                    if ($decoded && str_contains($decoded, '|')) {
                        $parts = explode('|', $decoded);
                        if (count($parts) === 3 && (int) $parts[0] > 0) {
                            $isAuthedAdmin = true;
                        }
                    }
                }
            }
            if (! $isAuthedAdmin) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Dokumen anggaran ini masih berstatus draft.',
                ], 404);
            }
        }

        $allItems = $budget->items;

        // --- 1. SEKSI PENDAPATAN ---
        $pendapatanRows = $allItems->where('tipe', 'pendapatan');
        $pendapatanGrouped = $pendapatanRows->groupBy('kategori')->map(function ($rows, $catName) {
            $subtotalAnggaran = (float) $rows->sum('anggaran');
            $subtotalRealisasi = (float) $rows->sum('realisasi');

            return [
                'kategori' => $catName,
                'items' => $rows->values(),
                'subtotal_anggaran' => $subtotalAnggaran,
                'subtotal_realisasi' => $subtotalRealisasi,
                'subtotal_selisih' => $subtotalRealisasi - $subtotalAnggaran,
            ];
        })->values();

        $totalPendapatanAnggaran = (float) $pendapatanRows->sum('anggaran');
        $totalPendapatanRealisasi = (float) $pendapatanRows->sum('realisasi');
        $totalPendapatanSelisih = $totalPendapatanRealisasi - $totalPendapatanAnggaran;

        // --- 2. SEKSI BELANJA ---
        $belanjaRows = $allItems->where('tipe', 'belanja');
        $belanjaGrouped = $belanjaRows->groupBy('kategori')->map(function ($rows, $catName) {
            $subtotalAnggaran = (float) $rows->sum('anggaran');
            $subtotalRealisasi = (float) $rows->sum('realisasi');

            return [
                'kategori' => $catName,
                'items' => $rows->values(),
                'subtotal_anggaran' => $subtotalAnggaran,
                'subtotal_realisasi' => $subtotalRealisasi,
                'subtotal_selisih' => $subtotalRealisasi - $subtotalAnggaran,
            ];
        })->values();

        $totalBelanjaAnggaran = (float) $belanjaRows->sum('anggaran');
        $totalBelanjaRealisasi = (float) $belanjaRows->sum('realisasi');
        $totalBelanjaSelisih = $totalBelanjaRealisasi - $totalBelanjaAnggaran;

        // Surplus / (Defisit) = Pendapatan - Belanja
        $surplusDefisitAnggaran = $totalPendapatanAnggaran - $totalBelanjaAnggaran;
        $surplusDefisitRealisasi = $totalPendapatanRealisasi - $totalBelanjaRealisasi;
        $surplusDefisitSelisih = $surplusDefisitRealisasi - $surplusDefisitAnggaran;

        // --- 3. SEKSI PEMBIAYAAN ---
        $pembiayaanRows = $allItems->where('tipe', 'pembiayaan');
        $penerimaanRows = $pembiayaanRows->filter(function ($i) {
            return str_contains(strtolower($i->kategori), 'penerimaan');
        })->values();
        $pengeluaranRows = $pembiayaanRows->filter(function ($i) {
            return str_contains(strtolower($i->kategori), 'pengeluaran');
        })->values();

        $penerimaanAnggaran = (float) $penerimaanRows->sum('anggaran');
        $penerimaanRealisasi = (float) $penerimaanRows->sum('realisasi');
        $penerimaanSelisih = $penerimaanRealisasi - $penerimaanAnggaran;

        $pengeluaranAnggaran = (float) $pengeluaranRows->sum('anggaran');
        $pengeluaranRealisasi = (float) $pengeluaranRows->sum('realisasi');
        $pengeluaranSelisih = $pengeluaranRealisasi - $pengeluaranAnggaran;

        $pembiayaanNettoAnggaran = $penerimaanAnggaran - $pengeluaranAnggaran;
        $pembiayaanNettoRealisasi = $penerimaanRealisasi - $pengeluaranRealisasi;
        $pembiayaanNettoSelisih = $pembiayaanNettoRealisasi - $pembiayaanNettoAnggaran;

        // Sisa Lebih Pembiayaan Anggaran (SILPA) = Surplus/Defisit + Pembiayaan Netto
        $silpaAnggaran = $surplusDefisitAnggaran + $pembiayaanNettoAnggaran;
        $silpaRealisasi = $surplusDefisitRealisasi + $pembiayaanNettoRealisasi;
        $silpaSelisih = $silpaRealisasi - $silpaAnggaran;

        return response()->json([
            'status' => 'success',
            'data' => [
                'header' => $budget,
                'sections' => [
                    'pendapatan' => [
                        'kelompok' => $pendapatanGrouped,
                        'total_anggaran' => $totalPendapatanAnggaran,
                        'total_realisasi' => $totalPendapatanRealisasi,
                        'total_selisih' => $totalPendapatanSelisih,
                    ],
                    'belanja' => [
                        'kelompok' => $belanjaGrouped,
                        'total_anggaran' => $totalBelanjaAnggaran,
                        'total_realisasi' => $totalBelanjaRealisasi,
                        'total_selisih' => $totalBelanjaSelisih,
                        'surplus_defisit_anggaran' => $surplusDefisitAnggaran,
                        'surplus_defisit_realisasi' => $surplusDefisitRealisasi,
                        'surplus_defisit_selisih' => $surplusDefisitSelisih,
                    ],
                    'pembiayaan' => [
                        'penerimaan' => [
                            'items' => $penerimaanRows,
                            'total_anggaran' => $penerimaanAnggaran,
                            'total_realisasi' => $penerimaanRealisasi,
                            'total_selisih' => $penerimaanSelisih,
                        ],
                        'pengeluaran' => [
                            'items' => $pengeluaranRows,
                            'total_anggaran' => $pengeluaranAnggaran,
                            'total_realisasi' => $pengeluaranRealisasi,
                            'total_selisih' => $pengeluaranSelisih,
                        ],
                        'netto' => [
                            'anggaran' => $pembiayaanNettoAnggaran,
                            'realisasi' => $pembiayaanNettoRealisasi,
                            'selisih' => $pembiayaanNettoSelisih,
                        ],
                        'silpa' => [
                            'anggaran' => $silpaAnggaran,
                            'realisasi' => $silpaRealisasi,
                            'selisih' => $silpaSelisih,
                        ],
                    ],
                ],
                'unduh_url' => url("/api/transparansi/{$budget->slug}/unduh"),
            ],
        ]);
    }

    /**
     * Endpoint unduh dokumen PDF resmi lampiran APBD
     */
    public function unduhDokumenTransparansi(string $slugOrId)
    {
        $budget = AnggaranRealisasi::where('slug', $slugOrId)
            ->orWhere('id', is_numeric($slugOrId) ? (int) $slugOrId : 0)
            ->first();

        if (! $budget) {
            return response()->json([
                'status' => 'error',
                'message' => 'Dokumen anggaran tidak ditemukan.',
            ], 404);
        }

        if ($budget->status !== 'published') {
            $token = request()->bearerToken();
            $isAuthedAdmin = false;
            if ($token) {
                if (Cache::has('admin_auth_token_'.$token)) {
                    $isAuthedAdmin = true;
                } else {
                    $decoded = base64_decode($token, true);
                    if ($decoded && str_contains($decoded, '|')) {
                        $parts = explode('|', $decoded);
                        if (count($parts) === 3 && (int) $parts[0] > 0) {
                            $isAuthedAdmin = true;
                        }
                    }
                }
            }
            if (! $isAuthedAdmin) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Dokumen anggaran ini masih berstatus draft.',
                ], 404);
            }
        }

        $pdfPath = FileStorageHelper::ensureValidPdfFile(
            $budget->file_lampiran,
            $budget->judul,
            'Transparansi APBD',
            'APBD-'.$budget->tahun,
            $budget->deskripsi
        );

        $fullPath = Storage::disk('public')->path($pdfPath);
        if (! file_exists($fullPath)) {
            return response()->json(['status' => 'error', 'message' => 'Berkas PDF fisik tidak ditemukan.'], 404);
        }

        $downloadFilename = Str::slug($budget->judul).'.pdf';

        return response()->download($fullPath, $downloadFilename, [
            'Content-Type' => 'application/pdf',
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

    /**
     * Detail Halaman Kustom Publik Berdasarkan Slug
     */
    public function getHalamanBySlug(string $slug): JsonResponse
    {
        $halaman = HalamanKustom::where('slug', $slug)
            ->where('aktif', true)
            ->first();

        if (! $halaman) {
            return response()->json([
                'status' => 'error',
                'message' => 'Halaman tidak ditemukan atau sedang tidak aktif.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $halaman,
        ]);
    }

    /**
     * Data Maklumat Pelayanan Publik
     */
    public function getMaklumatPelayanan(): JsonResponse
    {
        $maklumat = MaklumatPelayanan::where('aktif', true)->latest('id')->first();

        if (! $maklumat) {
            $maklumat = MaklumatPelayanan::latest('id')->first();
        }

        return response()->json([
            'status' => 'success',
            'data' => $maklumat,
        ]);
    }

    /**
     * Data Publik Survei Kepuasan Masyarakat (SKM)
     */
    public function getSurveiSkm(Request $request): JsonResponse
    {
        $query = SurveiSkm::where('aktif', true)->orderBy('urutan')->orderByDesc('tahun');

        if ($request->filled('tahun') && $request->tahun !== 'Semua') {
            $query->where('tahun', $request->tahun);
        }

        $list = $query->get();
        $latest = $list->first();
        $availableYears = SurveiSkm::where('aktif', true)->distinct()->pluck('tahun')->sortDesc()->values();

        return response()->json([
            'status' => 'success',
            'data' => [
                'latest' => $latest,
                'skm_terbaru' => $latest,
                'list' => $list,
                'arsip' => $list,
                'available_years' => $availableYears,
                'total_responden' => (int) $list->sum('jumlah_responden'),
            ],
        ]);
    }

    /**
     * Detail SKM per ID
     */
    public function getSurveiSkmDetail(int $id): JsonResponse
    {
        $skm = SurveiSkm::findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $skm,
        ]);
    }

    /**
     * Unduh Laporan SKM Resmi
     */
    public function unduhLaporanSkm(int $id)
    {
        $skm = SurveiSkm::findOrFail($id);

        if (! empty($skm->file_laporan)) {
            $relPath = FileStorageHelper::getRelativePublicPath($skm->file_laporan);
            if ($relPath && Storage::disk('public')->exists($relPath)) {
                $filename = 'Laporan_SKM_'.$skm->tahun.'_'.$skm->periode.'.pdf';

                return Storage::disk('public')->download($relPath, $filename);
            }
        }

        // Generate laporan PDF dinamis jika belum ada berkas upload fisik
        $title = "LAPORAN HASIL SURVEI KEPUASAN MASYARAKAT (SKM) TAHUN {$skm->tahun}";
        $docNum = "SKM/{$skm->tahun}/{$skm->periode}";
        $desc = "Indeks Kepuasan Masyarakat (IKM): {$skm->skor_ikm} / {$skm->skala_maksimal}. Mutu Pelayanan: {$skm->mutu_pelayanan} ({$skm->predikat}). Jumlah responden: {$skm->jumlah_responden}. Metodologi: {$skm->metodologi}";

        $pdfBinary = FileStorageHelper::generatePdfContent($title, 'Survei SKM', $docNum, $desc);
        $downloadName = "Laporan_SKM_{$skm->tahun}_{$skm->periode}.pdf";

        return response($pdfBinary, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="'.$downloadName.'"',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
        ]);
    }
}
