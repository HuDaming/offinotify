<?php

namespace Tantupix\Offinotify;

use Illuminate\Support\ServiceProvider;

class OffinotifyServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/migrations');
    }

    public function register()
    {
        $this->app->singleton('offinotify', function ($app) {
            return new Offinotify();
        });
    }
}