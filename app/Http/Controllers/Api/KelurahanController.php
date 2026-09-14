<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use App\Models\Galeri;
use App\Models\Layanan;
use App\Models\Lembaga;
use App\Models\Lingkungan;
use App\Models\MasterKategori;
use App\Models\Pengumuman;
use App\Models\PerangkatKelurahan;
use App\Models\Pesan;
use App\Models\ProfilKelurahan;
use App\Models\Statistik;
use App\Models\TransparansiAnggaran;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
                'link_span_lapor' => $profil->link_span_lapor ?? 'https://www.lapor.go.id/',
                'halo_sae_wa' => $profil->halo_sae_wa ?? '082131001001',
                'halo_sae_link' => $profil->halo_sae_link ?? 'https://halosae.probolinggokab.go.id',
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

        return response()->json([
            'status' => 'success',
            'data' => $query->get(),
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
                'message' => 'Foto galeri tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $item,
        ]);
    }

    public function kirimKontak(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'telepon' => 'required|string|max:20',
            'email' => 'nullable|email|max:100',
            'kategori' => 'required|string|max:50',
            'pesan' => 'required|string|max:2000',
        ]);

        $pesan = Pesan::create([
            'nama' => $validated['nama'],
            'telepon' => $validated['telepon'],
            'email' => $validated['email'] ?? null,
            'kategori' => $validated['kategori'],
            'pesan' => $validated['pesan'],
            'status' => 'baru',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Terima kasih, pesan dan aspirasi Anda telah tersimpan di sistem Kelurahan Kraksaan Wetan. Petugas kami akan segera menindaklanjuti.',
            'data' => [
                'id' => $pesan->id,
            ],
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
}
