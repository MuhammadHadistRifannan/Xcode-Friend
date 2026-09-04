<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UpdateLastSeen
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $userId = Auth::id();
            $lastSeen = DB::table('jcow_accounts')
                ->where('id', $userId)
                ->value('last_seen');

            if (!$lastSeen || (time() - $lastSeen) > 60) {
                DB::table('jcow_accounts')
                    ->where('id', $userId)
                    ->update(['last_seen' => time()]);
            }
        }

        return $next($request);
    }
}
