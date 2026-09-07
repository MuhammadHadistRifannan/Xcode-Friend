<?php

namespace App\Http\Middleware;

use App\Repositories\Contracts\AccountRepositoryInterface;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UpdateLastSeen
{
    public function __construct(
        private AccountRepositoryInterface $accountRepo
    ) {}

    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $userId = Auth::id();
            $lastSeen = $this->accountRepo->findLastSeen($userId);

            if (!$lastSeen || (time() - $lastSeen) > 60) {
                $this->accountRepo->updateLastSeen($userId, time());
            }
        }

        return $next($request);
    }
}
