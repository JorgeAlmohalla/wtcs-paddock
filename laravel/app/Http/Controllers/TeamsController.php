<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\View\View;

class TeamsController extends Controller
{
public function __invoke(): View
    {
        $seasonId = app()->bound('currentSeason') ? app('currentSeason')->id : null;

        $teams = \App\Models\Team::with('drivers')
            ->get()
            ->map(function ($team) use ($seasonId) {
                // Calcular stats de la temporada actual
                $team->stats = [
                    'points' => $team->raceResults()->whereHas('race', fn($q) => $q->where('season_id', $seasonId))->sum('points'),
                    'wins' => $team->raceResults()->whereHas('race', fn($q) => $q->where('season_id', $seasonId))->where('position', 1)->count(),
                    'podiums' => $team->raceResults()->whereHas('race', fn($q) => $q->where('season_id', $seasonId))->where('position', '<=', 3)->count(),
                ];
                return $team;
            })
            // Opcional: Ordenar por puntos de campeonato
            ->sortByDesc('stats.points');

        return view('teams', [
            'teams' => $teams,
        ]);
    }
}