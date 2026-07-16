<?php

namespace App\Providers;

use App\Auth\LegacyPasswordEloquentUserProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class LegacyAuthServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Auth::provider('eloquent-legacy', function ($app, array $config) {
            return new LegacyPasswordEloquentUserProvider($app['hash'], $config['model']);
        });
    }
}
