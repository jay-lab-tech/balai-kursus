<?php

namespace App\Providers;

use App\Services\MidtransService;
use Illuminate\Support\ServiceProvider;

class MidtransServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(MidtransService::class, function ($app) {
            return new MidtransService();
        });

        // Alias for easier access
        $this->app->alias(MidtransService::class, 'midtrans');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
