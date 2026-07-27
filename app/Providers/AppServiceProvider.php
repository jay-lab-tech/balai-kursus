<?php

namespace App\Providers;

use App\Models\Kursus;
use App\Models\Score;
use App\Observers\KursusObserver;
use App\Observers\ScoreObserver;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

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
        // Force HTTPS when app URL is configured with https (ngrok tunnel)
        if (config('app.url') && str_starts_with(config('app.url'), 'https://')) {
            URL::forceScheme('https');
            URL::forceRootUrl(config('app.url'));
        }

        // Register model observers
        Kursus::observe(KursusObserver::class);
        Score::observe(ScoreObserver::class);
    }
}
