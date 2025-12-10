<?php

namespace App\Http\Controllers;

use App\Models\Race;
use Illuminate\View\View;

class CalendarController extends Controller
{
    public function __invoke(): View
    {
        // Traemos todas las carreras ordenadas por ronda
        // Cargamos también el circuito (track) y el ganador (si lo hay)
        $races = Race::orderBy('round_number', 'asc')
            ->with(['track', 'results' => function($query) {
                $query->where('position', 1)->with('driver'); // Sacamos solo al ganador
            }])
            ->get();

        return view('calendar', [
            'races' => $races,
        ]);
    }
}