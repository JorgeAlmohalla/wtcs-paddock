<?php

namespace App\Http\Controllers;

use App\Models\Race;
use Illuminate\View\View;

class CalendarController extends Controller
{
    public function __invoke(): View
    {
        // 1. Traemos todas las carreras ordenadas
        // 2. Cargamos el ganador (posición 1) para evitar N+1 queries
        // 3. AGRUPAMOS por número de ronda
        $rounds = Race::orderBy('round_number', 'asc')
            ->orderBy('race_date', 'asc')
            ->with(['track', 'results' => function($query) {
                $query->where('position', 1)->with('driver');
            }])
            ->get()
            ->groupBy('round_number');

        // Pasamos la variable $rounds a la vista (NO $races)
        return view('calendar', [
            'rounds' => $rounds,
        ]);
    }
}