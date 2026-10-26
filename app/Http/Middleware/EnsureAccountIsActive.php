<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAccountIsActive
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Akun berstatus Suspend (disabled = 2) diblokir total dari sesi
        if ($user && (int) $user->disabled === 2) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Akun Anda sedang disuspend.',
                ], 403);
            }

            return redirect()->route('login')->withErrors([
                'login' => 'Akun Anda sedang disuspend.',
            ]);
        }

        // Catatan: Akun berstatus Pending (disabled = 1) tetap diizinkan login
        // dengan banner peringatan di layout dan pembatasan fitur (posting limit, pembuatan grup, dll.)

        return $next($request);
    }
}