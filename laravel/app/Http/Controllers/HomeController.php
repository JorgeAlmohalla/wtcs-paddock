<?php

namespace App\Http\Controllers;

use App\Models\Race;
use App\Models\RaceResult;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        // 1. Buscar la PRÓXIMA carrera
        // Buscamos carreras cuya fecha sea mayor o igual a AHORA, ordenadas por fecha
        $nextRace = Race::where('status', 'scheduled')
            ->where('race_date', '>=', now())
            ->orderBy('race_date', 'asc')
            ->with('track') // Cargamos el circuito para tener la foto
            ->first();

        // 2. Buscar al LÍDER del campeonato
        // Sumamos los puntos de la tabla race_results agrupados por piloto
        $leaderData = RaceResult::selectRaw('user_id, sum(points) as total_points')
            ->groupBy('user_id')
            ->orderByDesc('total_points')
            ->with('driver.team') // Cargamos datos del piloto y su equipo
            ->first();

        return view('welcome', [
            'nextRace' => $nextRace,
            'leader' => $leaderData ? $leaderData->driver : null,
            'leaderPoints' => $leaderData ? $leaderData->total_points : 0,
        ]);
    }
}