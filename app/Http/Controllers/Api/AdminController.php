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
use App\Models\ServiceRequest;
use App\Models\Statistik;
use App\Models\TransparansiAnggaran;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    /**
     * Admin Authentication Login
     */
    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (! $user || ! Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Email atau kata sandi tidak sesuai.',
            ], 401);
        }

        // Generate token session string (berlaku 7 hari)
        $token = base64_encode($user->id.'|'.Str::random(40).'|'.time());
        Cache::put('admin_auth_token_'.$token, $user->id, now()->addDays(7));

        return response()->json([
            'status' => 'success',
            'message' => 'Login berhasil. Selamat datang di Panel Admin Kelurahan Kraksaan Wetan.',
            'data' => [
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'role_label' => $user->role_label,
                ],
            ],
        ]);
    }

    /**
     * Admin Logout
     */
    public function logout(Request $request): JsonResponse
    {
        $token = $request->bearerToken();
        if ($token) {
            Cache::forget('admin_auth_token_'.$token);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Sesi berhasil diakhiri (logout).',
        ]);
    }

    /**
     * Get Current Authenticated Admin Profile
     */
    public function me(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'role_label' => $user->role_label,
            ],
        ]);
    }

    /**
     * Update Admin Password
     */
    public function updatePassword(Request $request): JsonResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if (! Hash::check($validated['current_password'], $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kata sandi saat ini tidak sesuai.',
            ], 422);
        }

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Kata sandi administrator berhasil diperbarui.',
        ]);
    }

    /**
     * Upload Image or Document File with strict MIME filtering
     */
    public function upload(Request $request): JsonResponse
    {
        $type = $request->input('type', 'image');

        if ($type === 'image') {
            $request->validate([
                'file' => 'required|file|image|mimes:jpeg,png,jpg,webp,svg|max:10240',
            ], [
                'file.required' => 'File gambar wajib dipilih.',
                'file.file' => 'Input harus berupa file yang valid.',
                'file.image' => 'File harus berupa gambar (JPG, JPEG, PNG, WEBP, SVG).',
                'file.mimes' => 'Format file tidak didukung. Hanya file gambar (JPG, JPEG, PNG, WEBP, SVG) yang diperbolehkan.',
                'file.max' => 'Ukuran file gambar tidak boleh melebihi 10MB.',
            ]);
        } elseif ($type === 'document') {
            $request->validate([
                'file' => 'required|file|mimes:pdf|max:10240',
            ], [
                'file.required' => 'File dokumen wajib dipilih.',
                'file.file' => 'Input harus berupa file yang valid.',
                'file.mimes' => 'Format dokumen tidak didukung. Hanya file PDF yang diperbolehkan.',
                'file.max' => 'Ukuran dokumen tidak boleh melebihi 10MB.',
            ]);
        } else {
            $request->validate([
                'file' => 'required|file|mimes:jpeg,png,jpg,webp,svg,pdf|max:10240',
            ], [
                'file.required' => 'File wajib dipilih.',
                'file.file' => 'Input harus berupa file yang valid.',
                'file.mimes' => 'Format file tidak didukung. Hanya file gambar atau PDF yang diperbolehkan.',
                'file.max' => 'Ukuran file tidak boleh melebihi 10MB.',
            ]);
        }

        $file = $request->file('file');
        $filename = Str::random(20).'.'.$file->getClientOriginalExtension();
        $path = $file->storeAs('uploads', $filename, 'public');

        return response()->json([
            'status' => 'success',
            'message' => 'File berhasil diunggah.',
            'data' => [
                'url' => asset('storage/'.$path),
                'path' => $path,
                'filename' => $file->getClientOriginalName(),
            ],
        ]);
    }

    /**
     * Admin Dashboard Summary
     */
    public function dashboard(): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => [
                'counts' => [
                    'berita' => Berita::count(),
                    'pengumuman' => Pengumuman::count(),
                    'layanan' => Layanan::count(),
                    'galeri' => Galeri::count(),
                    'pesan_baru' => Pesan::where('status', 'baru')->count(),
                    'total_pesan' => Pesan::count(),
                    'aparatur' => PerangkatKelurahan::count(),
                    'lembaga' => Lembaga::count(),
                    'transparansi' => TransparansiAnggaran::count(),
                    'staff' => User::count(),
                    'pengajuan_menunggu' => ServiceRequest::where('status', 'Menunggu Verifikasi')->count(),
                    'pengajuan_aktif' => ServiceRequest::where('status', '!=', 'Selesai')->count(),
                    'pengajuan_arsip' => ServiceRequest::where('status', 'Selesai')->count(),
                    'total_pengajuan' => ServiceRequest::count(),
                ],
                'recent_pengajuan' => ServiceRequest::with('layanan')->latest('submitted_at')->take(5)->get(),
                'recent_pesan' => Pesan::latest()->take(5)->get(),
                'recent_berita' => Berita::latest()->take(5)->get(),
            ],
        ]);
    }

    /* ----------------------------------------------------
     * BERITA CRUD
     * ---------------------------------------------------- */
    public function getBerita(): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => Berita::latest()->get(),
        ]);
    }

    public function storeBerita(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'required|string|max:50',
            'tanggal' => 'nullable|string|max:50',
            'penulis' => 'nullable|string|max:100',
            'ringkasan' => 'required|string',
            'konten' => 'required|string',
            'gambar' => 'nullable|string',
        ]);

        $slug = Str::slug($validated['judul']);
        // Check uniqueness
        $count = Berita::where('slug', 'like', "{$slug}%")->count();
        if ($count > 0) {
            $slug = "{$slug}-".($count + 1);
        }

        $berita = Berita::create([
            'slug' => $slug,
            'judul' => $validated['judul'],
            'kategori' => $validated['kategori'],
            'tanggal' => $validated['tanggal'] ?? now()->translatedFormat('d F Y'),
            'penulis' => $validated['penulis'] ?? 'Tim Humas Kelurahan',
            'ringkasan' => $validated['ringkasan'],
            'konten' => $validated['konten'],
            'gambar' => $validated['gambar'] ?? 'https://images.unsplash.com/photo-1577495508048-b635879837f1?auto=format&fit=crop&w=800&q=80',
            'dilihat' => 0,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Berita berhasil diterbitkan.',
            'data' => $berita,
        ]);
    }

    public function updateBerita(Request $request, int $id): JsonResponse
    {
        $berita = Berita::findOrFail($id);

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'required|string|max:50',
            'tanggal' => 'nullable|string|max:50',
            'penulis' => 'nullable|string|max:100',
            'ringkasan' => 'required|string',
            'konten' => 'required|string',
            'gambar' => 'nullable|string',
        ]);

        $berita->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Berita berhasil diperbarui.',
            'data' => $berita,
        ]);
    }

    public function deleteBerita(int $id): JsonResponse
    {
        $berita = Berita::findOrFail($id);
        $berita->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Berita berhasil dihapus.',
        ]);
    }

    /* ----------------------------------------------------
     * PENGUMUMAN CRUD
     * ---------------------------------------------------- */
    public function getPengumuman(): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => Pengumuman::latest()->get(),
        ]);
    }

    public function storePengumuman(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'tanggal' => 'nullable|string|max:50',
            'penyelenggara' => 'nullable|string|max:100',
            'prioritas' => 'required|string|max:50',
            'isi' => 'required|string',
            'file' => 'nullable|string',
            'banner' => 'nullable|string',
            'thumbnail' => 'nullable|string',
            'kategori' => 'nullable|string|max:100',
        ]);

        $validated['tanggal'] = $validated['tanggal'] ?? now()->translatedFormat('d F Y');

        $pengumuman = Pengumuman::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Pengumuman berhasil dibuat.',
            'data' => $pengumuman,
        ]);
    }

    public function updatePengumuman(Request $request, int $id): JsonResponse
    {
        $pengumuman = Pengumuman::findOrFail($id);

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'tanggal' => 'nullable|string|max:50',
            'penyelenggara' => 'nullable|string|max:100',
            'prioritas' => 'required|string|max:50',
            'isi' => 'required|string',
            'file' => 'nullable|string',
            'banner' => 'nullable|string',
            'thumbnail' => 'nullable|string',
            'kategori' => 'nullable|string|max:100',
        ]);

        $pengumuman->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Pengumuman berhasil diperbarui.',
            'data' => $pengumuman,
        ]);
    }

    public function deletePengumuman(int $id): JsonResponse
    {
        $pengumuman = Pengumuman::findOrFail($id);
        $pengumuman->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Pengumuman berhasil dihapus.',
        ]);
    }

    /* ----------------------------------------------------
     * LAYANAN CRUD
     * ---------------------------------------------------- */
    public function getLayanan(): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => Layanan::orderBy('urutan')->get(),
        ]);
    }

    public function storeLayanan(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'required|string|max:50',
            'icon' => 'nullable|string|max:50',
            'deskripsi' => 'required|string',
            'persyaratan' => 'nullable|array',
            'alur' => 'nullable|string',
            'waktu' => 'nullable|string|max:50',
            'biaya' => 'nullable|string|max:50',
            'urutan' => 'nullable|integer',
        ]);

        $validated['slug'] = Str::slug($validated['judul']);
        $validated['icon'] = $validated['icon'] ?? 'FileText';
        $validated['waktu'] = $validated['waktu'] ?? '10 - 15 Menit';
        $validated['biaya'] = $validated['biaya'] ?? 'Gratis (Rp 0)';

        $layanan = Layanan::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Layanan baru berhasil ditambahkan.',
            'data' => $layanan,
        ]);
    }

    public function updateLayanan(Request $request, int $id): JsonResponse
    {
        $layanan = Layanan::findOrFail($id);

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'required|string|max:50',
            'icon' => 'nullable|string|max:50',
            'deskripsi' => 'required|string',
            'persyaratan' => 'nullable|array',
            'alur' => 'nullable|string',
            'waktu' => 'nullable|string|max:50',
            'biaya' => 'nullable|string|max:50',
            'urutan' => 'nullable|integer',
        ]);

        $layanan->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Layanan berhasil diperbarui.',
            'data' => $layanan,
        ]);
    }

    public function deleteLayanan(int $id): JsonResponse
    {
        $layanan = Layanan::findOrFail($id);
        $layanan->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Layanan berhasil dihapus.',
        ]);
    }

    /* ----------------------------------------------------
     * GALERI CRUD
     * ---------------------------------------------------- */
    public function getGaleri(): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => Galeri::latest()->get(),
        ]);
    }

    public function storeGaleri(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'required|string|max:50',
            'tipe' => 'nullable|string|in:foto,video',
            'tanggal' => 'nullable|string|max:50',
            'gambar' => 'nullable|string',
            'video_url' => 'nullable|string',
            'deskripsi' => 'nullable|string',
        ]);

        $validated['tipe'] = $validated['tipe'] ?? 'foto';
        $validated['tanggal'] = $validated['tanggal'] ?? now()->translatedFormat('d F Y');

        if ($validated['tipe'] === 'video') {
            if (empty($validated['video_url'])) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tautan video dokumentasi wajib diisi untuk galeri bertipe video.',
                ], 422);
            }

            // Jika gambar thumbnail belum diisi, otomatis ekstrak thumbnail YouTube HQ
            if (empty($validated['gambar'])) {
                if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=|shorts\/)|youtu\.be\/)([^"&?\/\s]{11})/i', $validated['video_url'], $matches)) {
                    $validated['gambar'] = "https://img.youtube.com/vi/{$matches[1]}/hqdefault.jpg";
                } else {
                    $validated['gambar'] = 'https://images.unsplash.com/photo-1518173946687-a4c8a383392e?auto=format&fit=crop&w=800&q=80';
                }
            }
        } else {
            if (empty($validated['gambar'])) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Foto kegiatan wajib diisi untuk galeri bertipe foto.',
                ], 422);
            }
        }

        $galeri = Galeri::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => $galeri->tipe === 'video' ? 'Video dokumentasi berhasil ditambahkan ke galeri.' : 'Foto kegiatan berhasil ditambahkan ke galeri.',
            'data' => $galeri,
        ]);
    }

    public function updateGaleri(Request $request, int $id): JsonResponse
    {
        $galeri = Galeri::findOrFail($id);

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'required|string|max:50',
            'tipe' => 'nullable|string|in:foto,video',
            'tanggal' => 'nullable|string|max:50',
            'gambar' => 'nullable|string',
            'video_url' => 'nullable|string',
            'deskripsi' => 'nullable|string',
        ]);

        $validated['tipe'] = $validated['tipe'] ?? ($galeri->tipe ?? 'foto');

        if ($validated['tipe'] === 'video') {
            if (empty($validated['video_url']) && empty($galeri->video_url)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tautan video dokumentasi wajib diisi untuk galeri bertipe video.',
                ], 422);
            }

            if (empty($validated['gambar'])) {
                $targetUrl = $validated['video_url'] ?? $galeri->video_url;
                if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=|shorts\/)|youtu\.be\/)([^"&?\/\s]{11})/i', $targetUrl, $matches)) {
                    $validated['gambar'] = "https://img.youtube.com/vi/{$matches[1]}/hqdefault.jpg";
                }
            }
        } else {
            if (empty($validated['gambar']) && empty($galeri->gambar)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Foto kegiatan wajib diisi untuk galeri bertipe foto.',
                ], 422);
            }
        }

        $galeri->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Data galeri berhasil diperbarui.',
            'data' => $galeri,
        ]);
    }

    public function deleteGaleri(int $id): JsonResponse
    {
        $galeri = Galeri::findOrFail($id);
        $galeri->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Item galeri berhasil dihapus.',
        ]);
    }

    /* ----------------------------------------------------
     * PROFIL & APARATUR MANAGEMENT
     * ---------------------------------------------------- */
    public function updateProfil(Request $request): JsonResponse
    {
        $profil = ProfilKelurahan::firstOrFail();

        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'alamat' => 'required|string',
            'telepon' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:100',
            'jam_kerja' => 'nullable|string|max:100',
            'deskripsi' => 'required|string',
            'sejarah' => 'nullable|string',
            'visi' => 'required|string',
            'misi' => 'nullable|array',
            'logo' => 'nullable|string',
            'hero_mode' => 'nullable|string|max:50',
            'hero_image' => 'nullable|string',
            'lurah_nama' => 'nullable|string|max:100',
            'lurah_nip' => 'nullable|string|max:50',
            'lurah_jabatan' => 'nullable|string|max:100',
            'lurah_sambutan' => 'nullable|string',
            'lurah_foto' => 'nullable|string',
            'link_span_lapor' => 'nullable|string|max:255',
            'halo_sae_wa' => 'nullable|string|max:50',
            'halo_sae_link' => 'nullable|string|max:255',
        ]);

        $profil->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Profil kelurahan berhasil diperbarui.',
            'data' => $profil,
        ]);
    }

    public function storePerangkat(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'jabatan' => 'required|string|max:100',
            'bidang' => 'nullable|string|max:100',
            'foto' => 'nullable|string',
            'urutan' => 'nullable|integer',
        ]);

        $p = PerangkatKelurahan::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Aparatur berhasil ditambahkan.',
            'data' => $p,
        ]);
    }

    public function updatePerangkat(Request $request, int $id): JsonResponse
    {
        $p = PerangkatKelurahan::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'jabatan' => 'required|string|max:100',
            'bidang' => 'nullable|string|max:100',
            'foto' => 'nullable|string',
            'urutan' => 'nullable|integer',
        ]);

        $p->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Data aparatur berhasil diperbarui.',
            'data' => $p,
        ]);
    }

    public function deletePerangkat(int $id): JsonResponse
    {
        $p = PerangkatKelurahan::findOrFail($id);
        $p->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Aparatur berhasil dihapus.',
        ]);
    }

    /* ----------------------------------------------------
     * STATISTIK MANAGEMENT
     * ---------------------------------------------------- */
    public function updateStatistik(Request $request): JsonResponse
    {
        $stat = Statistik::firstOrFail();

        $validated = $request->validate([
            'penduduk' => 'required|integer',
            'kk' => 'required|integer',
            'laki_laki' => 'required|integer',
            'perempuan' => 'required|integer',
            'rt' => 'required|integer',
            'rw' => 'required|integer',
            'luas_wilayah' => 'required|string',
            'kepadatan' => 'nullable|string',
        ]);

        $stat->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Data statistik berhasil diperbarui.',
            'data' => $stat,
        ]);
    }

    public function storeLingkungan(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'rt' => 'required|integer',
            'penduduk' => 'required|integer',
            'urutan' => 'nullable|integer',
        ]);

        $l = Lingkungan::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Lingkungan RW berhasil ditambahkan.',
            'data' => $l,
        ]);
    }

    public function updateLingkungan(Request $request, int $id): JsonResponse
    {
        $l = Lingkungan::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'rt' => 'required|integer',
            'penduduk' => 'required|integer',
            'urutan' => 'nullable|integer',
        ]);

        $l->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Data lingkungan RW berhasil diperbarui.',
            'data' => $l,
        ]);
    }

    public function deleteLingkungan(int $id): JsonResponse
    {
        $l = Lingkungan::findOrFail($id);
        $l->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Lingkungan RW berhasil dihapus.',
        ]);
    }

    /* ----------------------------------------------------
     * LEMBAGA KEMASYARAKATAN (LKK) CRUD
     * ---------------------------------------------------- */
    public function getLembaga(): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => Lembaga::orderBy('urutan')->orderBy('id')->get(),
        ]);
    }

    public function storeLembaga(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'singkatan' => 'nullable|string|max:50',
            'kategori' => 'nullable|string|max:100',
            'ketua' => 'nullable|string|max:150',
            'kontak' => 'nullable|string|max:100',
            'alamat' => 'nullable|string|max:255',
            'deskripsi' => 'required|string',
            'program_kerja' => 'nullable|array',
            'program_kerja.*' => 'string|max:255',
            'jumlah_anggota' => 'nullable|string|max:100',
            'logo' => 'nullable|string|max:500',
            'warna_tema' => 'nullable|string|in:emerald,amber,rose,blue,indigo,purple',
            'urutan' => 'nullable|integer|min:0',
            'aktif' => 'nullable|boolean',
        ]);

        $validated['warna_tema'] = $validated['warna_tema'] ?? 'emerald';
        $validated['urutan'] = $validated['urutan'] ?? 0;
        $validated['aktif'] = $validated['aktif'] ?? true;

        $lembaga = Lembaga::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Lembaga Kemasyarakatan berhasil ditambahkan.',
            'data' => $lembaga,
        ], 201);
    }

    public function updateLembaga(Request $request, int $id): JsonResponse
    {
        $lembaga = Lembaga::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'singkatan' => 'nullable|string|max:50',
            'kategori' => 'nullable|string|max:100',
            'ketua' => 'nullable|string|max:150',
            'kontak' => 'nullable|string|max:100',
            'alamat' => 'nullable|string|max:255',
            'deskripsi' => 'required|string',
            'program_kerja' => 'nullable|array',
            'program_kerja.*' => 'string|max:255',
            'jumlah_anggota' => 'nullable|string|max:100',
            'logo' => 'nullable|string|max:500',
            'warna_tema' => 'nullable|string|in:emerald,amber,rose,blue,indigo,purple',
            'urutan' => 'nullable|integer|min:0',
            'aktif' => 'nullable|boolean',
        ]);

        $lembaga->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Lembaga Kemasyarakatan berhasil diperbarui.',
            'data' => $lembaga,
        ]);
    }

    public function toggleAktifLembaga(Request $request, int $id): JsonResponse
    {
        $lembaga = Lembaga::findOrFail($id);

        $validated = $request->validate([
            'aktif' => 'required|boolean',
        ]);

        $lembaga->update(['aktif' => $validated['aktif']]);

        return response()->json([
            'status' => 'success',
            'message' => 'Status aktif lembaga berhasil diubah.',
            'data' => $lembaga,
        ]);
    }

    public function deleteLembaga(int $id): JsonResponse
    {
        $lembaga = Lembaga::findOrFail($id);
        $lembaga->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Lembaga Kemasyarakatan berhasil dihapus.',
        ]);
    }

    /* ----------------------------------------------------
     * TRANSPARANSI & AKUNTABILITAS ANGGARAN CRUD
     * ---------------------------------------------------- */
    public function getTransparansi(Request $request): JsonResponse
    {
        $query = TransparansiAnggaran::query();

        if ($request->filled('tahun') && $request->tahun !== 'Semua') {
            $query->where('tahun', (int) $request->tahun);
        }

        if ($request->filled('kategori') && $request->kategori !== 'Semua') {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('program', 'like', "%{$s}%")
                    ->orWhere('kegiatan', 'like', "%{$s}%")
                    ->orWhere('deskripsi', 'like', "%{$s}%")
                    ->orWhere('penerima_manfaat_target', 'like', "%{$s}%")
                    ->orWhere('lokasi', 'like', "%{$s}%");
            });
        }

        $items = $query->orderByDesc('tahun')->orderBy('urutan')->orderBy('id')->get();

        // Calculations for admin summary
        $totalRencana = (float) (clone $query)->sum('anggaran_rencana');
        $totalRealisasi = (float) (clone $query)->sum('anggaran_realisasi');
        $totalSisa = max(0, $totalRencana - $totalRealisasi);
        $persentaseTotal = $totalRencana > 0 ? round(($totalRealisasi / $totalRencana) * 100, 1) : 0.0;

        return response()->json([
            'status' => 'success',
            'data' => [
                'summary' => [
                    'total_rencana' => $totalRencana,
                    'total_realisasi' => $totalRealisasi,
                    'total_sisa' => $totalSisa,
                    'persentase_total' => $persentaseTotal,
                    'total_kegiatan' => $items->count(),
                ],
                'items' => $items,
            ],
        ]);
    }

    public function storeTransparansi(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'tahun' => 'required|integer|min:2000|max:2100',
            'program' => 'required|string|max:255',
            'kegiatan' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'sumber_dana' => 'required|string|max:100',
            'anggaran_rencana' => 'required|numeric|min:0',
            'anggaran_realisasi' => 'nullable|numeric|min:0',
            'penerima_manfaat_target' => 'nullable|string|max:255',
            'penerima_manfaat_realisasi' => 'nullable|string|max:255',
            'progres_fisik' => 'nullable|integer|min:0|max:100',
            'status' => 'nullable|string|in:Rencana,Sedang Berjalan,Selesai,Evaluasi',
            'lokasi' => 'nullable|string|max:255',
            'penanggung_jawab' => 'nullable|string|max:150',
            'deskripsi' => 'nullable|string',
            'urutan' => 'nullable|integer|min:0',
            'aktif' => 'nullable|boolean',
        ]);

        $validated['anggaran_realisasi'] = $validated['anggaran_realisasi'] ?? 0;
        $validated['progres_fisik'] = $validated['progres_fisik'] ?? 0;
        $validated['status'] = $validated['status'] ?? 'Sedang Berjalan';
        $validated['urutan'] = $validated['urutan'] ?? 0;
        $validated['aktif'] = $validated['aktif'] ?? true;

        $item = TransparansiAnggaran::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Program kegiatan anggaran berhasil ditambahkan.',
            'data' => $item,
        ], 201);
    }

    public function updateTransparansi(Request $request, int $id): JsonResponse
    {
        $item = TransparansiAnggaran::findOrFail($id);

        $validated = $request->validate([
            'tahun' => 'required|integer|min:2000|max:2100',
            'program' => 'required|string|max:255',
            'kegiatan' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'sumber_dana' => 'required|string|max:100',
            'anggaran_rencana' => 'required|numeric|min:0',
            'anggaran_realisasi' => 'nullable|numeric|min:0',
            'penerima_manfaat_target' => 'nullable|string|max:255',
            'penerima_manfaat_realisasi' => 'nullable|string|max:255',
            'progres_fisik' => 'nullable|integer|min:0|max:100',
            'status' => 'nullable|string|in:Rencana,Sedang Berjalan,Selesai,Evaluasi',
            'lokasi' => 'nullable|string|max:255',
            'penanggung_jawab' => 'nullable|string|max:150',
            'deskripsi' => 'nullable|string',
            'urutan' => 'nullable|integer|min:0',
            'aktif' => 'nullable|boolean',
        ]);

        $item->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Program kegiatan anggaran berhasil diperbarui.',
            'data' => $item,
        ]);
    }

    public function toggleAktifTransparansi(Request $request, int $id): JsonResponse
    {
        $item = TransparansiAnggaran::findOrFail($id);

        $validated = $request->validate([
            'aktif' => 'required|boolean',
        ]);

        $item->update(['aktif' => $validated['aktif']]);

        return response()->json([
            'status' => 'success',
            'message' => 'Status aktif kegiatan berhasil diubah.',
            'data' => $item,
        ]);
    }

    public function deleteTransparansi(int $id): JsonResponse
    {
        $item = TransparansiAnggaran::findOrFail($id);
        $item->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Program kegiatan anggaran berhasil dihapus.',
        ]);
    }

    /* ----------------------------------------------------
     * STAFF MANAGEMENT (SUPER ADMIN ONLY)
     * ---------------------------------------------------- */
    public function getStaff(Request $request): JsonResponse
    {
        $query = User::query();

        if ($request->filled('role') && $request->role !== 'Semua') {
            $query->where('role', $request->role);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                    ->orWhere('email', 'like', "%{$s}%");
            });
        }

        $users = $query->orderBy('id', 'asc')->get();

        // Summary counts per role
        $counts = [
            'total' => User::count(),
            'super_admin' => User::where('role', User::ROLE_SUPER_ADMIN)->count(),
            'staff_konten' => User::where('role', User::ROLE_STAFF_KONTEN)->count(),
            'staff_pelayanan' => User::where('role', User::ROLE_STAFF_PELAYANAN)->count(),
            'staff_administrasi' => User::where('role', User::ROLE_STAFF_ADMINISTRASI)->count(),
        ];

        return response()->json([
            'status' => 'success',
            'data' => [
                'counts' => $counts,
                'staff' => $users,
                'roles' => User::ROLES,
            ],
        ]);
    }

    public function storeStaff(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'email' => 'required|email|max:150|unique:users,email',
            'role' => 'required|string|in:super_admin,staff_konten,staff_pelayanan,staff_administrasi',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => "Akun staf untuk {$user->name} berhasil dibuat.",
            'data' => $user,
        ], 201);
    }

    public function updateStaff(Request $request, int $id): JsonResponse
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'email' => 'required|email|max:150|unique:users,email,'.$id,
            'role' => 'required|string|in:super_admin,staff_konten,staff_pelayanan,staff_administrasi',
            'password' => 'nullable|string|min:6',
        ]);

        // Cek keamanan: jika satu-satunya super_admin ingin mengubah perannya menjadi staf biasa
        if ($user->role === User::ROLE_SUPER_ADMIN && $validated['role'] !== User::ROLE_SUPER_ADMIN) {
            $superAdminCount = User::where('role', User::ROLE_SUPER_ADMIN)->count();
            if ($superAdminCount <= 1) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tidak dapat mengubah peran. Sistem harus memiliki minimal 1 akun Super Admin.',
                ], 422);
            }
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];

        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Data akun staf berhasil diperbarui.',
            'data' => $user,
        ]);
    }

    public function resetPasswordStaff(Request $request, int $id): JsonResponse
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'password' => 'required|string|min:6',
        ]);

        $user->password = Hash::make($validated['password']);
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => "Kata sandi untuk {$user->name} berhasil direset.",
        ]);
    }

    public function deleteStaff(Request $request, int $id): JsonResponse
    {
        $targetUser = User::findOrFail($id);
        $currentUser = $request->user();

        // 1. Cegah menghapus akun sendiri
        if ($currentUser && $currentUser->id === $targetUser->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda tidak dapat menghapus akun Anda sendiri.',
            ], 422);
        }

        // 2. Cegah menghapus Super Admin terakhir
        if ($targetUser->role === User::ROLE_SUPER_ADMIN) {
            $superAdminCount = User::where('role', User::ROLE_SUPER_ADMIN)->count();
            if ($superAdminCount <= 1) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tidak dapat menghapus Super Admin terakhir pada sistem.',
                ], 422);
            }
        }

        $targetUser->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Akun staf berhasil dihapus.',
        ]);
    }

    /* ----------------------------------------------------
     * MASTER KATEGORI CRUD
     * ---------------------------------------------------- */
    public function getMasterKategori(Request $request): JsonResponse
    {
        $query = MasterKategori::orderBy('modul')->orderBy('urutan')->orderBy('nama');

        if ($request->filled('modul') && $request->modul !== 'semua') {
            $query->where('modul', $request->modul);
        }

        return response()->json([
            'status' => 'success',
            'data' => $query->get(),
        ]);
    }

    public function storeMasterKategori(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'modul' => 'required|string|in:berita,pengumuman,layanan,galeri,lembaga,transparansi',
            'nama' => 'required|string|max:100',
            'slug' => 'nullable|string|max:100',
            'keterangan' => 'nullable|string',
            'warna' => 'nullable|string|max:50',
            'urutan' => 'nullable|integer',
            'is_aktif' => 'nullable|boolean',
            'aktif' => 'nullable|boolean',
        ]);

        if ($request->has('aktif') && ! $request->has('is_aktif')) {
            $validated['is_aktif'] = $request->boolean('aktif');
        }
        unset($validated['aktif']);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['nama']);
        }

        // Check uniqueness per modul
        $exists = MasterKategori::where('modul', $validated['modul'])->where('slug', $validated['slug'])->exists();
        if ($exists) {
            $validated['slug'] = $validated['slug'].'-'.(MasterKategori::where('modul', $validated['modul'])->count() + 1);
        }

        $kategori = MasterKategori::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Master kategori berhasil ditambahkan.',
            'data' => $kategori,
        ]);
    }

    public function updateMasterKategori(Request $request, int $id): JsonResponse
    {
        $kategori = MasterKategori::findOrFail($id);

        $validated = $request->validate([
            'modul' => 'required|string|in:berita,pengumuman,layanan,galeri,lembaga,transparansi',
            'nama' => 'required|string|max:100',
            'slug' => 'nullable|string|max:100',
            'keterangan' => 'nullable|string',
            'warna' => 'nullable|string|max:50',
            'urutan' => 'nullable|integer',
            'is_aktif' => 'nullable|boolean',
            'aktif' => 'nullable|boolean',
        ]);

        if ($request->has('aktif') && ! $request->has('is_aktif')) {
            $validated['is_aktif'] = $request->boolean('aktif');
        }
        unset($validated['aktif']);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['nama']);
        }

        $exists = MasterKategori::where('modul', $validated['modul'])
            ->where('slug', $validated['slug'])
            ->where('id', '!=', $id)
            ->exists();
        if ($exists) {
            $validated['slug'] = $validated['slug'].'-'.time();
        }

        $kategori->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Master kategori berhasil diperbarui.',
            'data' => $kategori,
        ]);
    }

    public function deleteMasterKategori(int $id): JsonResponse
    {
        $kategori = MasterKategori::findOrFail($id);
        $kategori->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Master kategori berhasil dihapus.',
        ]);
    }
}
