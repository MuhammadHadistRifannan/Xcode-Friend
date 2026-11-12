<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withProviders([
        \App\Providers\BroadcastServiceProvider::class,
    ])
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'is_admin' => \App\Http\Middleware\IsAdmin::class,
            'module.active' => \App\Http\Middleware\CheckModuleActive::class,
            'blocked.ip' => \App\Http\Middleware\CheckBlockedIp::class,
        ]);
        $middleware->redirectUsersTo('/beranda');
        $middleware->web(append: [
            \App\Http\Middleware\CheckBlockedIp::class,
            \App\Http\Middleware\CheckOfflineMode::class,
            \App\Http\Middleware\CheckPrivateNetwork::class,
            \App\Http\Middleware\EnsureAccountIsActive::class,
            \App\Http\Middleware\UpdateLastSeen::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();
