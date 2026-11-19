<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Tulis access log format "combined" (nginx/apache) ke storage/logs/access.log
 * agar AI Security Agent (Tim B) bisa mendeteksi serangan nyata saat app jalan
 * via `php artisan serve` (yang tidak punya access log). Aktif jika ACCESS_LOG=true;
 * di produksi pakai log nginx saja.
 */
class WriteAccessLog
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (env('ACCESS_LOG', false)) {
            $line = sprintf(
                "%s - - [%s] \"%s %s %s\" %d %d \"%s\" \"%s\"\n",
                $request->ip(),
                now()->format('d/M/Y:H:i:s O'),
                $request->getMethod(),
                $request->getRequestUri(),
                $request->server('SERVER_PROTOCOL', 'HTTP/1.1'),
                $response->getStatusCode(),
                strlen((string) $response->getContent()),
                str_replace('"', '\"', (string) $request->headers->get('referer', '-')),
                str_replace('"', '\"', (string) $request->userAgent()),
            );
            @file_put_contents(storage_path('logs/access.log'), $line, FILE_APPEND | LOCK_EX);
        }

        return $response;
    }
}
