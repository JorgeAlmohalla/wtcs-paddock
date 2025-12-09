<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\RaceResult;
use App\Observers\RaceResultObserver;

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
        RaceResult::observe(RaceResultObserver::class);
    }
}
