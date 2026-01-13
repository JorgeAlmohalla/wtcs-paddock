<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Race;
use App\Models\RaceResult;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $seasonId = app()->bound('currentSeason') ? app('currentSeason')->id : null;

        // 1. Próxima Carrera (De la temporada actual)
        $nextRace = Race::where('season_id', $seasonId) // <--- FILTRO
            ->where('status', 'scheduled')
            ->where('race_date', '>=', now())
            ->orderBy('race_date', 'asc')
            ->with('track')
            ->first();

        // 2. Líder del Mundial (De la temporada actual)
        $leaderData = RaceResult::whereHas('race', fn($q) => $q->where('season_id', $seasonId)) // <--- FILTRO
            ->selectRaw('user_id, sum(points) as total_points')
            ->groupBy('user_id')
            ->orderByDesc('total_points')
            ->with('driver.team')
            ->first();

        // 3. Última Noticia (Las noticias NO se filtran por temporada normalmente, son globales)
        $latestPost = Post::orderBy('published_at', 'desc')->first();

                // 4. CAMPEONES HISTÓRICOS
        $pastChampions = \App\Models\Season::where('is_active', false)->get()->map(function($s) {
            
            // Buscar piloto con más puntos en esa season
            $champion = \App\Models\User::whereHas('raceResults.race', function($q) use ($s) {
                $q->where('season_id', $s->id);
            })->get()->sortByDesc(function($driver) use ($s) {
                // Sumar puntos totales
                return \App\Models\RaceResult::where('user_id', $driver->id)
                    ->whereHas('race', fn($q) => $q->where('season_id', $s->id))
                    ->sum('points');
            })->first();

            return $champion ? ['season' => $s->name, 'driver' => $champion] : null;
        })->filter();

        return view('welcome', [
            'nextRace' => $nextRace,
            'leader' => $leaderData ? $leaderData->driver : null,
            'leaderPoints' => $leaderData ? $leaderData->total_points : 0,
            'latestPost' => $latestPost,
            'pastChampions' => $pastChampions, 
        ]);
    }
}