<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckOfflineMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Pengecualian route untuk admin dan authentication (login/logout)
        if ($request->is('admin*') || $request->is('login*') || $request->is('logout*') || $request->is('offline*')) {
            return $next($request);
        }

        // Cek status offline_mode dari database
        try {
            $offlineMode = DB::table('jcow_gvars')->where('gkey', 'offline_mode')->value('gvalue');
            
            if ($offlineMode == '1') {
                // Biarkan administrator tetap bisa akses frontend walau offline mode
                if (Auth::check()) {
                    $user = Auth::user();
                    if ($user->level == 1 || in_array(strtolower($user->roles ?? ''), ['admin', 'administrator'])) {
                        return $next($request);
                    }
                }
                
                return redirect()->route('offline');
            }
        } catch (\Exception $e) {
            // Abaikan jika tabel tidak ditemukan (instalasi awal)
        }

        return $next($request);
    }
}
