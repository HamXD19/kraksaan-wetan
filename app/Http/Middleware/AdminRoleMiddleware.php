<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminRoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Sesi tidak valid atau belum terautentikasi.',
            ], 401);
        }

        // Super Admin memegang kendali penuh atas semua modul
        if ($user->role === User::ROLE_SUPER_ADMIN || $user->isSuperAdmin()) {
            return $next($request);
        }

        // Jika user memiliki salah satu peran yang diizinkan langsung
        if (in_array($user->role, $roles, true)) {
            return $next($request);
        }

        // Periksa izin menu granular berdasarkan path request
        $matchedMenu = $this->getMenuFromPath($request->path());
        if ($matchedMenu && $user->canAccessMenu($matchedMenu)) {
            return $next($request);
        }

        $allowedLabels = array_map(function ($r) {
            return User::ROLES[$r] ?? $r;
        }, $roles);

        return response()->json([
            'status' => 'error',
            'message' => 'Akses ditolak. Peran Anda ('.($user->role_label ?? $user->role).') tidak memiliki izin untuk mengakses modul ini. Fitur ini hanya dapat diakses oleh: '.implode(', ', $allowedLabels).'.',
        ], 403);
    }

    /**
     * Map request path segment to menu permission key
     */
    protected function getMenuFromPath(string $path): ?string
    {
        $segments = explode('/', trim($path, '/'));
        $adminIdx = array_search('admin', $segments, true);
        if ($adminIdx === false || ! isset($segments[$adminIdx + 1])) {
            return null;
        }

        $module = $segments[$adminIdx + 1];

        return match ($module) {
            'berita' => 'berita',
            'pengumuman' => 'pengumuman',
            'agenda' => 'agenda',
            'galeri' => 'galeri',
            'dokumen' => 'dokumen',
            'layanan', 'maklumat-pelayanan', 'survei-skm' => 'layanan',
            'lembaga' => 'lembaga',
            'statistik', 'lingkungan' => 'statistik',
            'transparansi' => 'transparansi',
            'kategori' => 'kategori',
            'profil', 'perangkat' => 'profil',
            default => null,
        };
    }
}
