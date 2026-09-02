<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // 1. Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu untuk mengakses Admin Panel.');
        }

        // 2. Cek apakah user adalah Admin
        // Di database Jcow legacy (jcow_accounts), role disimpan di kolom 'roles'
        // Secara umum, admin diset dengan value '1' atau 'admin' atau 'Administrator'
        $role = Auth::user()->roles;
        $roleLower = strtolower($role);
        
        if ($role != 1 && !in_array($roleLower, ['admin', 'administrator'])) {
            // Tolak akses jika bukan admin
            abort(403, 'Akses Ditolak. Halaman ini khusus untuk Administrator.');
        }

        return $next($request);
    }
}
