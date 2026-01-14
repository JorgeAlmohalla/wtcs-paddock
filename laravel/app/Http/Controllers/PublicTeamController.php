<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\View\View;

class PublicTeamController extends Controller
{
    public function show(Team $team): View
    {
        // Estadísticas básicas del equipo
$seasonId = app()->bound('currentSeason') ? app('currentSeason')->id : null;

        $stats = [
            'drivers' => $team->drivers->count(),
            
            // FILTRAR PUNTOS POR TEMPORADA
            'points' => \App\Models\RaceResult::where('team_id', $team->id)
                ->whereHas('race', fn($q) => $q->where('season_id', $seasonId))
                ->sum('points'),
            
            'wins' => \App\Models\RaceResult::where('team_id', $team->id)
                ->whereHas('race', fn($q) => $q->where('season_id', $seasonId))
                ->where('position', 1)->count(),
            
            'podiums' => \App\Models\RaceResult::where('team_id', $team->id)
                ->whereHas('race', fn($q) => $q->where('season_id', $seasonId))
                ->where('position', '<=', 3)->count(),
        ];

        return view('public-team', [
            'team' => $team->load('drivers'), // Cargar pilotos
            'stats' => $stats,
        ]);
    }
}