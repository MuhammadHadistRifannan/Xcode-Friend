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

        return $next($request);
    }
}