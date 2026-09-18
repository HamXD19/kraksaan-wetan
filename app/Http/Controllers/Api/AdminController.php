<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
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
use App\Models\User;
use App\Services\FileStorageHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    /**
     * Generate Random Security Captcha Code with SVG Visual
     */
    public function getCaptcha(): JsonResponse
    {
        $chars = '23456789ABCDEFGHJKLMNPQRSTUVWXYZ';
        $code = '';
        $length = 5;
        for ($i = 0; $i < $length; $i++) {
            $code .= $chars[random_int(0, strlen($chars) - 1)];
        }

        $key = Str::uuid()->toString();
        Cache::put('admin_captcha_'.$key, $code, now()->addMinutes(10));

        $width = 160;
        $height = 46;
        $characters = str_split($code);

        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="'.$width.'" height="'.$height.'" viewBox="0 0 '.$width.' '.$height.'" class="rounded-xl select-none" style="pointer-events: none;">';
        $svg .= '<defs><linearGradient id="cbg" x1="0" y1="0" x2="1" y2="1"><stop offset="0%" stop-color="#0c201a"/><stop offset="100%" stop-color="#14362b"/></linearGradient></defs>';
        $svg .= '<rect width="100%" height="100%" fill="url(#cbg)" rx="10"/>';

        // Noise curves
        $noiseColors = ['#34d399', '#f59e0b', '#38bdf8', '#a7f3d0'];
        for ($i = 0; $i < 4; $i++) {
            $x1 = random_int(0, 30);
            $y1 = random_int(6, $height - 6);
            $cx = random_int(50, 110);
            $cy = random_int(6, $height - 6);
            $x2 = random_int(130, 160);
            $y2 = random_int(6, $height - 6);
            $color = $noiseColors[random_int(0, count($noiseColors) - 1)];
            $svg .= '<path d="M'.$x1.' '.$y1.' Q'.$cx.' '.$cy.' '.$x2.' '.$y2.'" stroke="'.$color.'" stroke-width="1.5" fill="none" opacity="0.45"/>';
        }

        // Noise dots
        for ($i = 0; $i < 24; $i++) {
            $x = random_int(5, $width - 5);
            $y = random_int(5, $height - 5);
            $r = random_int(1, 2);
            $svg .= '<circle cx="'.$x.'" cy="'.$y.'" r="'.$r.'" fill="#a7f3d0" opacity="0.3"/>';
        }

        // Characters
        $textColors = ['#6ee7b7', '#fcd34d', '#5eead4', '#f8fafc', '#7dd3fc'];
        foreach ($characters as $idx => $char) {
            $x = 18 + ($idx * 27);
            $y = random_int(30, 34);
            $rot = random_int(-15, 15);
            $color = $textColors[$idx % count($textColors)];
            $svg .= '<text x="'.$x.'" y="'.$y.'" fill="'.$color.'" font-family="monospace, Courier, sans-serif" font-weight="900" font-size="25" transform="rotate('.$rot.', '.$x.', '.$y.')">'.$char.'</text>';
        }

        $svg .= '</svg>';

        return response()->json([
            'status' => 'success',
            'data' => [
                'key' => $key,
                'svg' => $svg,
            ],
        ]);
    }

    /**
     * Admin Authentication Login
     */
    public function login(Request $request): JsonResponse
    {
        $rules = [
            'email' => 'required|email',
            'password' => 'required|string',
        ];

        // Validasi captcha jika captcha_key dikirimkan atau bukan di environment testing
        if ($request->has('captcha_key') || ! app()->environment('testing')) {
            $rules['captcha'] = 'required|string';
            $rules['captcha_key'] = 'required|string';
        }

        $validated = $request->validate($rules);

        if (! empty($validated['captcha_key'])) {
            $expectedCaptcha = Cache::get('admin_captcha_'.$validated['captcha_key']);
            if (! $expectedCaptcha || strtolower(trim($validated['captcha'])) !== strtolower(trim($expectedCaptcha))) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Kode acak keamanan (Captcha) salah atau telah kedaluwarsa. Silakan coba lagi.',
                ], 422);
            }
            Cache::forget('admin_captcha_'.$validated['captcha_key']);
        }

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

        ActivityLog::record(
            action: 'login',
            module: 'auth',
            description: "Login berhasil ke panel admin sebagai {$user->role_label}",
            properties: ['role' => $user->role],
            user: $user
        );

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

        if ($user = $request->user()) {
            ActivityLog::record(
                action: 'logout',
                module: 'auth',
                description: 'Keluar (logout) dari sesi panel admin',
                user: $user
            );
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

        ActivityLog::record(
            action: 'update',
            module: 'auth',
            description: 'Memperbarui kata sandi akun sendiri',
            user: $user
        );

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
        } elseif ($type === 'document' || $type === 'pdf') {
            $request->validate([
                'file' => [
                    'required',
                    'file',
                    'max:10240',
                    function ($attribute, $value, $fail) {
                        $ext = strtolower($value->getClientOriginalExtension());
                        $mime = $value->getMimeType();
                        $allowedMimes = [
                            'application/pdf',
                            'application/x-pdf',
                            'application/acrobat',
                            'applications/vnd.pdf',
                            'text/pdf',
                            'text/x-pdf',
                        ];
                        if ($ext !== 'pdf' && ! in_array($mime, $allowedMimes, true)) {
                            $fail('Format dokumen tidak didukung. Hanya file PDF yang diperbolehkan.');
                        }
                    },
                ],
            ], [
                'file.required' => 'File dokumen wajib dipilih.',
                'file.file' => 'Input harus berupa file yang valid.',
                'file.max' => 'Ukuran dokumen tidak boleh melebihi 10MB.',
            ]);
        } elseif ($type === 'video') {
            $request->validate([
                'file' => 'required|file|mimes:mp4,mov,ogg,qt,webm,mkv|max:51200',
            ], [
                'file.required' => 'File video wajib dipilih.',
                'file.file' => 'Input harus berupa file yang valid.',
                'file.mimes' => 'Format file video tidak didukung. Hanya format (MP4, WEBM, MOV, MKV, OGG) yang diperbolehkan.',
                'file.max' => 'Ukuran file video tidak boleh melebihi 50MB.',
            ]);
        } else {
            $request->validate([
                'file' => 'required|file|mimes:jpeg,png,jpg,webp,svg,pdf,mp4,webm,mov,mkv|max:51200',
            ], [
                'file.required' => 'File wajib dipilih.',
                'file.file' => 'Input harus berupa file yang valid.',
                'file.mimes' => 'Format file tidak didukung. Hanya file gambar, PDF, atau video yang diperbolehkan.',
                'file.max' => 'Ukuran file tidak boleh melebihi 50MB.',
            ]);
        }

        $file = $request->file('file');
        $rawExt = strtolower($file->getClientOriginalExtension());
        $ext = $rawExt ?: (($type === 'document' || $type === 'pdf') ? 'pdf' : 'jpg');
        $filename = Str::random(24).'.'.$ext;
        $path = $file->storeAs('uploads', $filename, 'public');

        ActivityLog::record(
            action: 'upload',
            module: 'media',
            description: "Mengunggah file {$file->getClientOriginalName()}",
            properties: ['filename' => $file->getClientOriginalName(), 'path' => $path, 'type' => $type],
            user: $request->user()
        );

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
                    'dokumen' => Dokumen::count(),
                    'layanan' => Layanan::count(),
                    'galeri' => Galeri::count(),
                    'aparatur' => PerangkatKelurahan::count(),
                    'lembaga' => Lembaga::count(),
                    'transparansi' => TransparansiAnggaran::count(),
                    'staff' => User::count(),
                ],
                'recent_berita' => Berita::latest()->take(5)->get(),
                'recent_pesan' => [],
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

        $this->ensureMasterKategori('berita', $validated['kategori'] ?? null);

        ActivityLog::record(
            action: 'create',
            module: 'berita',
            description: "Menerbitkan berita baru: \"{$berita->judul}\"",
            properties: ['berita_id' => $berita->id, 'judul' => $berita->judul, 'kategori' => $berita->kategori],
            user: $request->user()
        );

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

        if (array_key_exists('gambar', $validated) && $validated['gambar'] !== $berita->gambar) {
            FileStorageHelper::deleteFileIfLocal($berita->gambar);
        }

        $berita->update($validated);

        $this->ensureMasterKategori('berita', $validated['kategori'] ?? null);

        ActivityLog::record(
            action: 'update',
            module: 'berita',
            description: "Memperbarui berita: \"{$berita->judul}\"",
            properties: ['berita_id' => $berita->id, 'judul' => $berita->judul],
            user: $request->user()
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Berita berhasil diperbarui.',
            'data' => $berita,
        ]);
    }

    public function deleteBerita(Request $request, int $id): JsonResponse
    {
        $berita = Berita::findOrFail($id);
        $judul = $berita->judul;
        $beritaId = $berita->id;
        $berita->delete();

        ActivityLog::record(
            action: 'delete',
            module: 'berita',
            description: "Menghapus berita: \"{$judul}\"",
            properties: ['berita_id' => $beritaId, 'judul' => $judul],
            user: $request->user()
        );

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
        $items = Pengumuman::latest()->get()->map(function ($item) {
            $fileUrl = null;
            if (! empty($item->file)) {
                $fileUrl = url("/api/pengumuman/{$item->id}/unduh");
            }

            return array_merge($item->toArray(), [
                'file_url' => $fileUrl,
            ]);
        });

        return response()->json([
            'status' => 'success',
            'data' => $items,
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
        $this->ensureMasterKategori('pengumuman', $validated['kategori'] ?? null);

        ActivityLog::record(
            action: 'create',
            module: 'pengumuman',
            description: "Menerbitkan pengumuman baru: \"{$pengumuman->judul}\"",
            properties: ['pengumuman_id' => $pengumuman->id, 'judul' => $pengumuman->judul],
            user: $request->user()
        );

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

        if (array_key_exists('file', $validated) && $validated['file'] !== $pengumuman->file) {
            FileStorageHelper::deleteFileIfLocal($pengumuman->file);
        }
        if (array_key_exists('banner', $validated) && $validated['banner'] !== $pengumuman->banner) {
            FileStorageHelper::deleteFileIfLocal($pengumuman->banner);
        }
        if (array_key_exists('thumbnail', $validated) && $validated['thumbnail'] !== $pengumuman->thumbnail) {
            FileStorageHelper::deleteFileIfLocal($pengumuman->thumbnail);
        }

        $pengumuman->update($validated);

        $this->ensureMasterKategori('pengumuman', $validated['kategori'] ?? null);

        ActivityLog::record(
            action: 'update',
            module: 'pengumuman',
            description: "Memperbarui pengumuman: \"{$pengumuman->judul}\"",
            properties: ['pengumuman_id' => $pengumuman->id, 'judul' => $pengumuman->judul],
            user: $request->user()
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Pengumuman berhasil diperbarui.',
            'data' => $pengumuman,
        ]);
    }

    public function deletePengumuman(Request $request, int $id): JsonResponse
    {
        $pengumuman = Pengumuman::findOrFail($id);
        $judul = $pengumuman->judul;
        $pengumumanId = $pengumuman->id;
        $pengumuman->delete();

        ActivityLog::record(
            action: 'delete',
            module: 'pengumuman',
            description: "Menghapus pengumuman: \"{$judul}\"",
            properties: ['pengumuman_id' => $pengumumanId, 'judul' => $judul],
            user: $request->user()
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Pengumuman berhasil dihapus.',
        ]);
    }

    /* ----------------------------------------------------
     * DOKUMEN PUBLIK CRUD
     * ---------------------------------------------------- */
    public function getDokumen(Request $request): JsonResponse
    {
        $query = Dokumen::latest('id');

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

        if ($request->filled('status')) {
            if ($request->status === 'aktif') {
                $query->where('aktif', true);
            } elseif ($request->status === 'nonaktif') {
                $query->where('aktif', false);
            }
        }

        if ($request->filled('q')) {
            $search = trim($request->q);
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('nomor_dokumen', 'like', "%{$search}%")
                    ->orWhere('subkategori', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        $items = $query->get()->map(function ($item) {
            return array_merge($item->toArray(), [
                'file_url' => url("/api/dokumen/{$item->id}/unduh"),
                'preview_url' => asset('storage/'.ltrim($item->file, '/')),
            ]);
        });

        // Kategori tree for Dokumen from MasterKategori
        $masterKategoriTree = MasterKategori::where('modul', 'dokumen')
            ->whereNull('parent_id')
            ->where('is_aktif', true)
            ->with(['subkategoris' => function ($q) {
                $q->where('is_aktif', true);
            }])
            ->orderBy('urutan')
            ->orderBy('nama')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $items,
            'summary' => [
                'total' => Dokumen::count(),
                'aktif' => Dokumen::where('aktif', true)->count(),
                'total_unduhan' => (int) Dokumen::sum('diunduh'),
            ],
            'meta' => [
                'kategori_list' => Dokumen::distinct()->whereNotNull('kategori')->pluck('kategori')->filter()->values(),
                'subkategori_list' => Dokumen::distinct()->whereNotNull('subkategori')->pluck('subkategori')->filter()->values(),
                'tahun_list' => Dokumen::distinct()->whereNotNull('tahun')->orderByDesc('tahun')->pluck('tahun')->filter()->values(),
                'master_kategori_tree' => $masterKategoriTree,
            ],
        ]);
    }

    public function storeDokumen(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'nomor_dokumen' => 'nullable|string|max:100',
            'kategori' => 'required|string|max:100',
            'subkategori' => 'nullable|string|max:100',
            'periode' => 'required|string|max:50',
            'tahun' => 'nullable|string|max:10',
            'tahun_selesai' => 'nullable|string|max:10',
            'periode_ke' => 'nullable|string|max:50',
            'tanggal_publikasi' => 'nullable|date',
            'deskripsi' => 'nullable|string',
            'file' => 'required|string',
            'nama_file_asli' => 'nullable|string|max:255',
            'ukuran_file' => 'nullable|string|max:50',
            'aktif' => 'nullable|boolean',
        ]);

        $validated['aktif'] = $request->boolean('aktif', true);
        $validated['tahun'] = $validated['tahun'] ?? date('Y');
        $validated['tanggal_publikasi'] = $validated['tanggal_publikasi'] ?? date('Y-m-d');

        $dokumen = Dokumen::create($validated);
        $this->ensureMasterKategori('dokumen', $validated['kategori'] ?? null, $validated['subkategori'] ?? null);

        ActivityLog::record(
            action: 'create',
            module: 'dokumen',
            description: "Menambahkan dokumen publik: \"{$dokumen->judul}\"",
            properties: ['dokumen_id' => $dokumen->id, 'judul' => $dokumen->judul],
            user: $request->user()
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Dokumen berhasil ditambahkan.',
            'data' => $dokumen,
        ]);
    }

    public function updateDokumen(Request $request, int $id): JsonResponse
    {
        $dokumen = Dokumen::findOrFail($id);

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'nomor_dokumen' => 'nullable|string|max:100',
            'kategori' => 'required|string|max:100',
            'subkategori' => 'nullable|string|max:100',
            'periode' => 'required|string|max:50',
            'tahun' => 'nullable|string|max:10',
            'tahun_selesai' => 'nullable|string|max:10',
            'periode_ke' => 'nullable|string|max:50',
            'tanggal_publikasi' => 'nullable|date',
            'deskripsi' => 'nullable|string',
            'file' => 'required|string',
            'nama_file_asli' => 'nullable|string|max:255',
            'ukuran_file' => 'nullable|string|max:50',
            'aktif' => 'nullable|boolean',
        ]);

        $validated['aktif'] = $request->boolean('aktif', true);
        if (empty($validated['tanggal_publikasi'])) {
            $validated['tanggal_publikasi'] = $dokumen->tanggal_publikasi ?: date('Y-m-d');
        }

        if (array_key_exists('file', $validated) && $validated['file'] !== $dokumen->file) {
            FileStorageHelper::deleteFileIfLocal($dokumen->file);
        }

        $dokumen->update($validated);

        $this->ensureMasterKategori('dokumen', $validated['kategori'] ?? null, $validated['subkategori'] ?? null);

        ActivityLog::record(
            action: 'update',
            module: 'dokumen',
            description: "Memperbarui dokumen publik: \"{$dokumen->judul}\"",
            properties: ['dokumen_id' => $dokumen->id, 'judul' => $dokumen->judul],
            user: $request->user()
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Dokumen berhasil diperbarui.',
            'data' => $dokumen,
        ]);
    }

    public function deleteDokumen(Request $request, int $id): JsonResponse
    {
        $dokumen = Dokumen::findOrFail($id);
        $judul = $dokumen->judul;
        $dokumenId = $dokumen->id;
        $filePath = $dokumen->file;

        $dokumen->delete();

        FileStorageHelper::deleteFileIfLocal($filePath);

        ActivityLog::record(
            action: 'delete',
            module: 'dokumen',
            description: "Menghapus dokumen publik: \"{$judul}\"",
            properties: ['dokumen_id' => $dokumenId, 'judul' => $judul],
            user: $request->user()
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Dokumen berhasil dihapus.',
        ]);
    }

    public function toggleDokumenStatus(Request $request, int $id): JsonResponse
    {
        $dokumen = Dokumen::findOrFail($id);
        $dokumen->aktif = ! $dokumen->aktif;
        $dokumen->save();

        ActivityLog::record(
            action: 'update',
            module: 'dokumen',
            description: ($dokumen->aktif ? 'Mengaktifkan' : 'Menonaktifkan')." dokumen: \"{$dokumen->judul}\"",
            properties: ['dokumen_id' => $dokumen->id, 'aktif' => $dokumen->aktif],
            user: $request->user()
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Status dokumen berhasil diubah.',
            'data' => $dokumen,
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
        $this->ensureMasterKategori('layanan', $validated['kategori'] ?? null);

        ActivityLog::record(
            action: 'create',
            module: 'layanan',
            description: "Menambahkan layanan baru: \"{$layanan->judul}\"",
            properties: ['layanan_id' => $layanan->id, 'judul' => $layanan->judul, 'kategori' => $layanan->kategori],
            user: $request->user()
        );

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
        $this->ensureMasterKategori('layanan', $validated['kategori'] ?? null);

        ActivityLog::record(
            action: 'update',
            module: 'layanan',
            description: "Memperbarui layanan: \"{$layanan->judul}\"",
            properties: ['layanan_id' => $layanan->id, 'judul' => $layanan->judul],
            user: $request->user()
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Layanan berhasil diperbarui.',
            'data' => $layanan,
        ]);
    }

    public function deleteLayanan(Request $request, int $id): JsonResponse
    {
        $layanan = Layanan::findOrFail($id);
        $judul = $layanan->judul;
        $layananId = $layanan->id;
        $layanan->delete();

        ActivityLog::record(
            action: 'delete',
            module: 'layanan',
            description: "Menghapus layanan: \"{$judul}\"",
            properties: ['layanan_id' => $layananId, 'judul' => $judul],
            user: $request->user()
        );

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
        $this->ensureMasterKategori('galeri', $validated['kategori'] ?? null);

        ActivityLog::record(
            action: 'create',
            module: 'galeri',
            description: "Menambahkan galeri baru: \"{$galeri->judul}\"",
            properties: ['galeri_id' => $galeri->id, 'judul' => $galeri->judul, 'tipe' => $galeri->tipe],
            user: $request->user()
        );

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

        if (array_key_exists('gambar', $validated) && $validated['gambar'] !== $galeri->gambar) {
            FileStorageHelper::deleteFileIfLocal($galeri->gambar);
        }

        $galeri->update($validated);

        $this->ensureMasterKategori('galeri', $validated['kategori'] ?? null);

        ActivityLog::record(
            action: 'update',
            module: 'galeri',
            description: "Memperbarui galeri: \"{$galeri->judul}\"",
            properties: ['galeri_id' => $galeri->id, 'judul' => $galeri->judul],
            user: $request->user()
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Data galeri berhasil diperbarui.',
            'data' => $galeri,
        ]);
    }

    public function deleteGaleri(Request $request, int $id): JsonResponse
    {
        $galeri = Galeri::findOrFail($id);
        $judul = $galeri->judul;
        $galeriId = $galeri->id;
        $galeri->delete();

        ActivityLog::record(
            action: 'delete',
            module: 'galeri',
            description: "Menghapus galeri: \"{$judul}\"",
            properties: ['galeri_id' => $galeriId, 'judul' => $judul],
            user: $request->user()
        );

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
            'kecamatan' => 'nullable|string|max:100',
            'kabupaten' => 'nullable|string|max:100',
            'provinsi' => 'nullable|string|max:100',
            'kode_pos' => 'nullable|string|max:20',
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

        // Bersihkan file lama jika berkas diubah atau direset
        if (array_key_exists('logo', $validated) && $validated['logo'] !== $profil->logo) {
            FileStorageHelper::deleteFileIfLocal($profil->logo);
        }
        if (array_key_exists('hero_image', $validated) && $validated['hero_image'] !== $profil->hero_image) {
            FileStorageHelper::deleteFileIfLocal($profil->hero_image);
        }
        if (array_key_exists('lurah_foto', $validated) && $validated['lurah_foto'] !== $profil->lurah_foto) {
            FileStorageHelper::deleteFileIfLocal($profil->lurah_foto);
        }

        $profil->update($validated);

        ActivityLog::record(
            action: 'update',
            module: 'profil',
            description: 'Memperbarui profil kelurahan dan informasi aparatur pimpinan',
            user: $request->user()
        );

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

        ActivityLog::record(
            action: 'create',
            module: 'profil',
            description: "Menambahkan data aparatur: \"{$p->nama}\" ({$p->jabatan})",
            properties: ['perangkat_id' => $p->id, 'nama' => $p->nama, 'jabatan' => $p->jabatan],
            user: $request->user()
        );

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

        if (array_key_exists('foto', $validated) && $validated['foto'] !== $p->foto) {
            FileStorageHelper::deleteFileIfLocal($p->foto);
        }

        $p->update($validated);

        ActivityLog::record(
            action: 'update',
            module: 'profil',
            description: "Memperbarui data aparatur: \"{$p->nama}\" ({$p->jabatan})",
            properties: ['perangkat_id' => $p->id, 'nama' => $p->nama, 'jabatan' => $p->jabatan],
            user: $request->user()
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Data aparatur berhasil diperbarui.',
            'data' => $p,
        ]);
    }

    public function deletePerangkat(Request $request, int $id): JsonResponse
    {
        $p = PerangkatKelurahan::findOrFail($id);
        $nama = $p->nama;
        $jabatan = $p->jabatan;
        $perangkatId = $p->id;
        $p->delete();

        ActivityLog::record(
            action: 'delete',
            module: 'profil',
            description: "Menghapus data aparatur: \"{$nama}\" ({$jabatan})",
            properties: ['perangkat_id' => $perangkatId, 'nama' => $nama, 'jabatan' => $jabatan],
            user: $request->user()
        );

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

        ActivityLog::record(
            action: 'update',
            module: 'statistik',
            description: 'Memperbarui data statistik kependudukan wilayah',
            properties: ['penduduk' => $validated['penduduk'], 'kk' => $validated['kk']],
            user: $request->user()
        );

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

        ActivityLog::record(
            action: 'create',
            module: 'statistik',
            description: "Menambahkan data lingkungan RW: \"{$l->nama}\"",
            properties: ['lingkungan_id' => $l->id, 'nama' => $l->nama, 'rt' => $l->rt, 'penduduk' => $l->penduduk],
            user: $request->user()
        );

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

        ActivityLog::record(
            action: 'update',
            module: 'statistik',
            description: "Memperbarui data lingkungan RW: \"{$l->nama}\"",
            properties: ['lingkungan_id' => $l->id, 'nama' => $l->nama],
            user: $request->user()
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Data lingkungan RW berhasil diperbarui.',
            'data' => $l,
        ]);
    }

    public function deleteLingkungan(Request $request, int $id): JsonResponse
    {
        $l = Lingkungan::findOrFail($id);
        $nama = $l->nama;
        $lingkunganId = $l->id;
        $l->delete();

        ActivityLog::record(
            action: 'delete',
            module: 'statistik',
            description: "Menghapus data lingkungan RW: \"{$nama}\"",
            properties: ['lingkungan_id' => $lingkunganId, 'nama' => $nama],
            user: $request->user()
        );

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
        $this->ensureMasterKategori('lembaga', $validated['kategori'] ?? null);

        ActivityLog::record(
            action: 'create',
            module: 'lembaga',
            description: "Menambahkan lembaga kemasyarakatan: \"{$lembaga->nama}\"",
            properties: ['lembaga_id' => $lembaga->id, 'nama' => $lembaga->nama],
            user: $request->user()
        );

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

        if (array_key_exists('logo', $validated) && $validated['logo'] !== $lembaga->logo) {
            FileStorageHelper::deleteFileIfLocal($lembaga->logo);
        }

        $lembaga->update($validated);

        $this->ensureMasterKategori('lembaga', $validated['kategori'] ?? null);

        ActivityLog::record(
            action: 'update',
            module: 'lembaga',
            description: "Memperbarui lembaga kemasyarakatan: \"{$lembaga->nama}\"",
            properties: ['lembaga_id' => $lembaga->id, 'nama' => $lembaga->nama],
            user: $request->user()
        );

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

        $statusText = $validated['aktif'] ? 'mengaktifkan' : 'menonaktifkan';
        ActivityLog::record(
            action: 'update',
            module: 'lembaga',
            description: "Telah {$statusText} status lembaga: \"{$lembaga->nama}\"",
            properties: ['lembaga_id' => $lembaga->id, 'aktif' => $validated['aktif']],
            user: $request->user()
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Status aktif lembaga berhasil diubah.',
            'data' => $lembaga,
        ]);
    }

    public function deleteLembaga(Request $request, int $id): JsonResponse
    {
        $lembaga = Lembaga::findOrFail($id);
        $nama = $lembaga->nama;
        $lembagaId = $lembaga->id;
        $lembaga->delete();

        ActivityLog::record(
            action: 'delete',
            module: 'lembaga',
            description: "Menghapus lembaga kemasyarakatan: \"{$nama}\"",
            properties: ['lembaga_id' => $lembagaId, 'nama' => $nama],
            user: $request->user()
        );

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
        $this->ensureMasterKategori('transparansi', $validated['kategori'] ?? null);

        ActivityLog::record(
            action: 'create',
            module: 'transparansi',
            description: "Menambahkan program transparansi anggaran: \"{$item->kegiatan}\" (Tahun {$item->tahun})",
            properties: ['transparansi_id' => $item->id, 'tahun' => $item->tahun, 'kegiatan' => $item->kegiatan, 'anggaran' => $item->anggaran_rencana],
            user: $request->user()
        );

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
        $this->ensureMasterKategori('transparansi', $validated['kategori'] ?? null);

        ActivityLog::record(
            action: 'update',
            module: 'transparansi',
            description: "Memperbarui program transparansi anggaran: \"{$item->kegiatan}\" (Tahun {$item->tahun})",
            properties: ['transparansi_id' => $item->id, 'tahun' => $item->tahun, 'kegiatan' => $item->kegiatan],
            user: $request->user()
        );

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

        $statusText = $validated['aktif'] ? 'mengaktifkan' : 'menonaktifkan';
        ActivityLog::record(
            action: 'update',
            module: 'transparansi',
            description: "Telah {$statusText} program transparansi anggaran: \"{$item->kegiatan}\"",
            properties: ['transparansi_id' => $item->id, 'aktif' => $validated['aktif']],
            user: $request->user()
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Status aktif kegiatan berhasil diubah.',
            'data' => $item,
        ]);
    }

    public function deleteTransparansi(Request $request, int $id): JsonResponse
    {
        $item = TransparansiAnggaran::findOrFail($id);
        $kegiatan = $item->kegiatan;
        $tahun = $item->tahun;
        $transparansiId = $item->id;
        $item->delete();

        ActivityLog::record(
            action: 'delete',
            module: 'transparansi',
            description: "Menghapus program transparansi anggaran: \"{$kegiatan}\" (Tahun {$tahun})",
            properties: ['transparansi_id' => $transparansiId, 'kegiatan' => $kegiatan, 'tahun' => $tahun],
            user: $request->user()
        );

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

        ActivityLog::record(
            action: 'create',
            module: 'staff',
            description: "Menambahkan akun staf baru: {$user->name} ({$user->role_label})",
            properties: ['staff_id' => $user->id, 'name' => $user->name, 'email' => $user->email, 'role' => $user->role],
            user: $request->user()
        );

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

        ActivityLog::record(
            action: 'update',
            module: 'staff',
            description: "Memperbarui data akun staf: {$user->name} ({$user->role_label})",
            properties: ['staff_id' => $user->id, 'name' => $user->name, 'email' => $user->email, 'role' => $user->role],
            user: $request->user()
        );

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

        ActivityLog::record(
            action: 'reset_password',
            module: 'staff',
            description: "Mereset kata sandi akun staf: {$user->name} ({$user->role_label})",
            properties: ['staff_id' => $user->id, 'name' => $user->name, 'email' => $user->email],
            user: $request->user()
        );

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

        $userData = [
            'staff_id' => $targetUser->id,
            'name' => $targetUser->name,
            'email' => $targetUser->email,
            'role' => $targetUser->role,
        ];

        $targetUser->delete();

        ActivityLog::record(
            action: 'delete',
            module: 'staff',
            description: "Menghapus akun staf: {$userData['name']} ({$userData['email']})",
            properties: $userData,
            user: $currentUser
        );

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
        $query = MasterKategori::with(['parent:id,nama,slug', 'subkategoris'])
            ->orderBy('modul')
            ->orderBy('urutan')
            ->orderBy('nama');

        if ($request->filled('modul') && $request->modul !== 'semua') {
            $query->where('modul', $request->modul);
        }

        if ($request->has('parent_id')) {
            if ($request->parent_id === 'null' || $request->parent_id === '') {
                $query->whereNull('parent_id');
            } else {
                $query->where('parent_id', $request->parent_id);
            }
        }

        return response()->json([
            'status' => 'success',
            'data' => $query->get(),
        ]);
    }

    public function storeMasterKategori(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'modul' => 'required|string|max:50',
            'parent_id' => 'nullable|integer|exists:master_kategoris,id',
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

        ActivityLog::record(
            action: 'create',
            module: 'kategori',
            description: "Menambahkan master kategori: \"{$kategori->nama}\" (Modul {$kategori->modul})",
            properties: ['kategori_id' => $kategori->id, 'modul' => $kategori->modul, 'nama' => $kategori->nama],
            user: $request->user()
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Master kategori berhasil ditambahkan.',
            'data' => $kategori->load(['parent', 'subkategoris']),
        ]);
    }

    public function updateMasterKategori(Request $request, int $id): JsonResponse
    {
        $kategori = MasterKategori::findOrFail($id);

        $validated = $request->validate([
            'modul' => 'required|string|max:50',
            'parent_id' => 'nullable|integer|exists:master_kategoris,id',
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

        ActivityLog::record(
            action: 'update',
            module: 'kategori',
            description: "Memperbarui master kategori: \"{$kategori->nama}\" (Modul {$kategori->modul})",
            properties: ['kategori_id' => $kategori->id, 'modul' => $kategori->modul, 'nama' => $kategori->nama],
            user: $request->user()
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Master kategori berhasil diperbarui.',
            'data' => $kategori->load(['parent', 'subkategoris']),
        ]);
    }

    public function deleteMasterKategori(Request $request, int $id): JsonResponse
    {
        $kategori = MasterKategori::findOrFail($id);
        $nama = $kategori->nama;
        $modul = $kategori->modul;
        $kategoriId = $kategori->id;
        $kategori->delete();

        ActivityLog::record(
            action: 'delete',
            module: 'kategori',
            description: "Menghapus master kategori: \"{$nama}\" (Modul {$modul})",
            properties: ['kategori_id' => $kategoriId, 'nama' => $nama, 'modul' => $modul],
            user: $request->user()
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Master kategori berhasil dihapus.',
        ]);
    }

    /* ----------------------------------------------------
     * ACTIVITY LOGS (SUPER ADMIN ONLY)
     * ---------------------------------------------------- */
    public function getActivityLogs(Request $request): JsonResponse
    {
        $query = ActivityLog::with('user:id,name,email,role')->latest();

        if ($request->filled('user_id') && $request->user_id !== 'semua' && $request->user_id !== '') {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('module') && $request->module !== 'semua' && $request->module !== '') {
            $query->where('module', $request->module);
        }

        if ($request->filled('action') && $request->action !== 'semua' && $request->action !== '') {
            $query->where('action', $request->action);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('description', 'like', "%{$s}%")
                    ->orWhere('user_name', 'like', "%{$s}%")
                    ->orWhere('user_email', 'like', "%{$s}%")
                    ->orWhere('ip_address', 'like', "%{$s}%");
            });
        }

        $perPage = min(max((int) $request->input('per_page', 20), 5), 100);
        $logs = $query->paginate($perPage);

        // Summary metrics for Super Admin
        $today = now()->toDateString();
        $totalLogs = ActivityLog::count();
        $todayLogs = ActivityLog::whereDate('created_at', $today)->count();
        $activeUsersToday = ActivityLog::whereDate('created_at', $today)
            ->whereNotNull('user_id')
            ->distinct('user_id')
            ->count('user_id');

        $mostActiveModule = ActivityLog::select('module', DB::raw('count(*) as total'))
            ->groupBy('module')
            ->orderByDesc('total')
            ->first();

        return response()->json([
            'status' => 'success',
            'data' => $logs,
            'summary' => [
                'total_logs' => $totalLogs,
                'today_logs' => $todayLogs,
                'active_users_today' => $activeUsersToday,
                'most_active_module' => $mostActiveModule ? $mostActiveModule->module : '-',
            ],
        ]);
    }

    public function getActivityLogUsers(): JsonResponse
    {
        $users = User::select('id', 'name', 'email', 'role')
            ->orderBy('name')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $users,
        ]);
    }

    // =========================================================================
    // MODUL AGENDA KEGIATAN KELURAHAN (Staff Konten & Super Admin)
    // =========================================================================

    public function getAgenda(Request $request): JsonResponse
    {
        $query = AgendaKegiatan::query();

        if ($request->filled('status') && $request->status !== 'semua') {
            $now = now();
            switch ($request->status) {
                case 'akan_datang':
                    $query->where('is_aktif', true)->where('tanggal_mulai', '>', $now);
                    break;
                case 'berlangsung':
                    $query->where('is_aktif', true)->where('tanggal_mulai', '<=', $now)->where('tanggal_selesai', '>=', $now);
                    break;
                case 'selesai':
                    $query->where('tanggal_selesai', '<', $now);
                    break;
                case 'nonaktif':
                    $query->where('is_aktif', false);
                    break;
            }
        }

        if ($request->filled('kategori') && $request->kategori !== 'Semua') {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('judul', 'like', "%{$s}%")
                    ->orWhere('deskripsi', 'like', "%{$s}%")
                    ->orWhere('lokasi', 'like', "%{$s}%")
                    ->orWhere('penyelenggara', 'like', "%{$s}%");
            });
        }

        $items = $query->orderBy('tanggal_mulai', 'desc')->get();

        $now = now();
        $totalAll = AgendaKegiatan::count();
        $totalBerlangsung = AgendaKegiatan::where('is_aktif', true)->where('tanggal_mulai', '<=', $now)->where('tanggal_selesai', '>=', $now)->count();
        $totalAkanDatang = AgendaKegiatan::where('is_aktif', true)->where('tanggal_mulai', '>', $now)->count();
        $totalSelesai = AgendaKegiatan::where('tanggal_selesai', '<', $now)->count();
        $totalNonaktif = AgendaKegiatan::where('is_aktif', false)->count();

        return response()->json([
            'status' => 'success',
            'data' => $items,
            'counts' => [
                'total' => $totalAll,
                'berlangsung' => $totalBerlangsung,
                'akan_datang' => $totalAkanDatang,
                'selesai' => $totalSelesai,
                'nonaktif' => $totalNonaktif,
            ],
        ]);
    }

    public function storeAgenda(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'foto' => 'nullable|string',
            'lokasi' => 'nullable|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'penyelenggara' => 'nullable|string|max:150',
            'kategori' => 'nullable|string|max:100',
            'is_aktif' => 'nullable|boolean',
        ]);

        $slugBase = Str::slug($validated['judul']);
        $slug = $slugBase;
        $counter = 1;
        while (AgendaKegiatan::where('slug', $slug)->exists()) {
            $slug = $slugBase.'-'.$counter;
            $counter++;
        }
        $validated['slug'] = $slug;
        $validated['is_aktif'] = $request->has('is_aktif') ? (bool) $request->is_aktif : true;
        $validated['kategori'] = $validated['kategori'] ?: 'Umum';

        $agenda = AgendaKegiatan::create($validated);
        $this->ensureMasterKategori('agenda', $agenda->kategori);

        ActivityLog::record(
            action: 'create',
            module: 'agenda',
            description: "Menambahkan agenda kegiatan baru: \"{$agenda->judul}\"",
            properties: ['agenda_id' => $agenda->id, 'judul' => $agenda->judul],
            user: $request->user()
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Agenda kegiatan berhasil ditambahkan.',
            'data' => $agenda,
        ]);
    }

    public function updateAgenda(Request $request, int $id): JsonResponse
    {
        $agenda = AgendaKegiatan::findOrFail($id);

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'foto' => 'nullable|string',
            'lokasi' => 'nullable|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'penyelenggara' => 'nullable|string|max:150',
            'kategori' => 'nullable|string|max:100',
            'is_aktif' => 'nullable|boolean',
        ]);

        if ($agenda->judul !== $validated['judul']) {
            $slugBase = Str::slug($validated['judul']);
            $slug = $slugBase;
            $counter = 1;
            while (AgendaKegiatan::where('slug', $slug)->where('id', '!=', $id)->exists()) {
                $slug = $slugBase.'-'.$counter;
                $counter++;
            }
            $validated['slug'] = $slug;
        }

        $validated['is_aktif'] = $request->has('is_aktif') ? (bool) $request->is_aktif : $agenda->is_aktif;
        $validated['kategori'] = $validated['kategori'] ?: 'Umum';

        if (array_key_exists('foto', $validated) && $validated['foto'] !== $agenda->foto) {
            FileStorageHelper::deleteFileIfLocal($agenda->foto);
        }

        $agenda->update($validated);

        $this->ensureMasterKategori('agenda', $agenda->kategori);

        ActivityLog::record(
            action: 'update',
            module: 'agenda',
            description: "Memperbarui agenda kegiatan: \"{$agenda->judul}\"",
            properties: ['agenda_id' => $agenda->id, 'judul' => $agenda->judul],
            user: $request->user()
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Agenda kegiatan berhasil diperbarui.',
            'data' => $agenda,
        ]);
    }

    public function toggleAktifAgenda(Request $request, int $id): JsonResponse
    {
        $agenda = AgendaKegiatan::findOrFail($id);
        $agenda->is_aktif = ! $agenda->is_aktif;
        $agenda->save();

        ActivityLog::record(
            action: 'update',
            module: 'agenda',
            description: "Mengubah status publikasi agenda \"{$agenda->judul}\" menjadi ".($agenda->is_aktif ? 'Aktif' : 'Nonaktif'),
            properties: ['agenda_id' => $agenda->id, 'is_aktif' => $agenda->is_aktif],
            user: $request->user()
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Status agenda berhasil diperbarui menjadi '.($agenda->is_aktif ? 'Aktif' : 'Nonaktif').'.',
            'data' => $agenda,
        ]);
    }

    public function deleteAgenda(Request $request, int $id): JsonResponse
    {
        $agenda = AgendaKegiatan::findOrFail($id);
        $title = $agenda->judul;
        $agenda->delete();

        ActivityLog::record(
            action: 'delete',
            module: 'agenda',
            description: "Menghapus agenda kegiatan: \"{$title}\"",
            properties: ['agenda_id' => $id, 'judul' => $title],
            user: $request->user()
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Agenda kegiatan berhasil dihapus.',
        ]);
    }

    /**
     * Pastikan kategori (dan subkategori opsional) terdaftar pada Master Kategori untuk modul terkait.
     */
    private function ensureMasterKategori(string $modul, ?string $kategori, ?string $subkategori = null): void
    {
        if (empty($kategori)) {
            return;
        }

        $clean = trim($kategori);
        if ($clean === '' || $clean === 'Umum') {
            return;
        }

        $parent = MasterKategori::firstOrCreate(
            ['modul' => $modul, 'nama' => $clean],
            ['slug' => Str::slug($clean), 'is_aktif' => true, 'warna' => 'emerald']
        );

        if (! empty($subkategori)) {
            $cleanSub = trim($subkategori);
            if ($cleanSub !== '' && $cleanSub !== 'Umum') {
                $subSlug = Str::slug($clean.'-'.$cleanSub);
                MasterKategori::firstOrCreate(
                    ['modul' => $modul, 'nama' => $cleanSub, 'parent_id' => $parent->id],
                    ['slug' => $subSlug, 'is_aktif' => true, 'warna' => $parent->warna ?: 'emerald']
                );
            }
        }
    }
}
