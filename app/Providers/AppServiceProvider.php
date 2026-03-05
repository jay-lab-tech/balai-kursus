<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Kursus;
use App\Observers\KursusObserver;

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
        // Register model observers
        Kursus::observe(KursusObserver::class);
    }
}
