<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class AdminAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (! $token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Sesi tidak valid atau belum login. Silakan masuk terlebih dahulu.',
            ], 401);
        }

        $user = null;

        // 1. Cek token dari Cache
        if (Cache::has('admin_auth_token_'.$token)) {
            $userId = Cache::get('admin_auth_token_'.$token);
            $user = User::find($userId);
        } else {
            // 2. Fallback verifikasi base64 token format: userId|random|timestamp
            $decoded = base64_decode($token, true);
            if ($decoded && str_contains($decoded, '|')) {
                $parts = explode('|', $decoded);
                if (count($parts) === 3) {
                    $userId = (int) $parts[0];
                    $timestamp = (int) $parts[2];

                    // Token berlaku selama 7 hari
                    if ($timestamp > 0 && (time() - $timestamp) < (7 * 86400)) {
                        $user = User::find($userId);
                        if ($user) {
                            Cache::put('admin_auth_token_'.$token, $user->id, now()->addDays(7));
                        }
                    }
                }
            }
        }

        if (! $user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Sesi Anda telah kedaluwarsa atau tidak sah. Silakan login kembali.',
            ], 401);
        }

        // Set user terautentikasi ke dalam Request
        $request->setUserResolver(fn () => $user);

        return $next($request);
    }
}
