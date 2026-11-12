<?php

namespace App\Http\Middleware;

use App\Models\BlockedIp;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckBlockedIp
{
    /**
     * Tolak akses dari alamat IP yang telah diblokir melalui AI Security Log Panel.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Izinkan admin yang sedang login mengakses panel admin untuk membuka blokir
        if ($request->is('admin/*') && auth()->check() && auth()->user()->isAdmin()) {
            return $next($request);
        }

        $ip = $request->ip();
        if (BlockedIp::isBlocked($ip)) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Akses ditolak: Alamat IP Anda diblokir oleh sistem keamanan.',
                ], 403);
            }

            abort(403, 'Akses ditolak: Alamat IP Anda telah diblokir oleh sistem keamanan XCODE-Friends.');
        }

        return $next($request);
    }
}
