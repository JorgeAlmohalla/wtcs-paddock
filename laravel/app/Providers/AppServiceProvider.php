<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\RaceResult;
use App\Observers\RaceResultObserver;
use App\Models\QualifyingResult;
use App\Observers\QualifyingResultObserver;
use Illuminate\Support\Facades\View; // <--- Importante
use Illuminate\Support\Facades\DB; // <--- Importante
use App\Models\Season;

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
        // 1. Observers
        RaceResult::observe(RaceResultObserver::class);
        QualifyingResult::observe(QualifyingResultObserver::class);

        // 2. Lógica del Selector de Temporada (Middleware Global)
        // Ya lo tienes en el Middleware, pero si usas View::share aquí para el menú, mantenlo.
        // Si usas el SetSeasonMiddleware, este bloque quizás sobre, pero el system status sí va aquí.

        // 3. System Status (Para el Footer)
        try {
            DB::connection()->getPdo();
            $status = 'All Systems Operational';
            $color = 'green';
        } catch (\Exception $e) {
            $status = 'Database Error';
            $color = 'red';
        }
        
        // Compartir la variable $systemStatus con TODAS las vistas
        View::share('systemStatus', ['text' => $status, 'color' => $color]);
    }
}