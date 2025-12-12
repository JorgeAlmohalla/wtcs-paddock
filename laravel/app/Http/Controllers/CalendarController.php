<?php

namespace App\Http\Controllers;

use App\Models\Race;
use Illuminate\View\View;

class CalendarController extends Controller
{
    public function __invoke(): View
    {
        // 1. Obtener la temporada actual (inyectada por el Middleware)
        // Si por algún motivo fallase el middleware, no rompe, usa null
        $seasonId = app()->bound('currentSeason') ? app('currentSeason')->id : null;

        // 2. Traer carreras FILTRADAS por temporada
        $rounds = Race::query()
            ->where('season_id', $seasonId) // <--- ESTA ES LA CLAVE
            ->orderBy('round_number', 'asc')
            ->orderBy('race_date', 'asc')
            ->with(['track', 'results' => function($query) {
                $query->where('position', 1)->with('driver');
            }])
            ->get()
            ->groupBy('round_number');

        return view('calendar', [
            'rounds' => $rounds,
        ]);
    }
}