<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Helpers\ModuleHelper;
use Symfony\Component\HttpFoundation\Response;

class CheckModuleActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$modules
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, string ...$modules): Response
    {
        foreach ($modules as $module) {
            if (!ModuleHelper::isActive($module)) {
                if ($request->expectsJson() || $request->is('api/*')) {
                    return response()->json([
                        'message' => 'Modul ' . strtolower($module) . ' sedang dinonaktifkan.',
                    ], 404);
                }

                abort(404, 'Modul ' . strtolower($module) . ' sedang dinonaktifkan.');
            }
        }

        return $next($request);
    }
}
