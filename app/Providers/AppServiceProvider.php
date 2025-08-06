<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
        //
        if ($this->app->environment('local')) {
        $host = request()->getHost();

        // Jika domain mengandung ngrok
        if (str_contains($host, 'ngrok-free.app')) {
            URL::forceScheme('https');
        }
    }
    }
}