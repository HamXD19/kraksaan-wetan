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
     * @param  string  ...$roles
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

        if (! in_array($user->role, $roles, true)) {
            $allowedLabels = array_map(function ($r) {
                return User::ROLES[$r] ?? $r;
            }, $roles);

            return response()->json([
                'status' => 'error',
                'message' => 'Akses ditolak. Peran Anda (' . ($user->role_label ?? $user->role) . ') tidak memiliki izin untuk mengakses modul ini. Fitur ini hanya dapat diakses oleh: ' . implode(', ', $allowedLabels) . '.',
            ], 403);
        }

        return $next($request);
    }
}
