<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\SettingHelper;
use Symfony\Component\HttpFoundation\Response;

class CheckPrivateNetwork
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Pengecualian route untuk autentikasi, admin, asset, dan recovery
        if (
            $request->is('admin*') ||
            $request->is('login*') ||
            $request->is('register*') ||
            $request->is('logout*') ||
            $request->is('forgot-password*') ||
            $request->is('reset-password*') ||
            $request->is('captcha*') ||
            $request->is('offline*') ||
            $request->is('dev-login*') ||
            $request->is('up')
        ) {
            return $next($request);
        }

        try {
            $privateNetwork = SettingHelper::get('private_network', '0');
            if ($privateNetwork == '1' && !Auth::check()) {
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json(['message' => 'Unauthenticated.'], 401);
                }
                return redirect()->route('login')->with('error', 'Jejaring ini bersifat privat. Silakan login atau daftar terlebih dahulu.');
            }
        } catch (\Exception $e) {
            // Abaikan jika database belum siap
        }

        return $next($request);
    }
}
