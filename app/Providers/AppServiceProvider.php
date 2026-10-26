<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::defaultView('vendor.pagination.tailwind');
        Paginator::defaultSimpleView('vendor.pagination.simple-tailwind');

        \Illuminate\Support\Facades\Hash::extend('bcrypt', function ($app) {
            return new \App\Hashing\Md5BcryptHasher($app['config']->get('hashing.bcrypt') ?? []);
        });
        \Illuminate\Support\Facades\Hash::forgetDrivers();
    }
}
