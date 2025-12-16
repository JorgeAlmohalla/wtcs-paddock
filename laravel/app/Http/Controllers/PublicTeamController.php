<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\View\View;

class PublicTeamController extends Controller
{
    public function show(Team $team): View
    {
        // Estadísticas básicas del equipo
        $stats = [
            'drivers' => $team->drivers->count(),
            'points' => \App\Models\RaceResult::where('team_id', $team->id)->sum('points'),
            'wins' => \App\Models\RaceResult::where('team_id', $team->id)->where('position', 1)->count(),
            'podiums' => \App\Models\RaceResult::where('team_id', $team->id)->where('position', '<=', 3)->count(),
        ];

        return view('public-team', [
            'team' => $team->load('drivers'), // Cargar pilotos
            'stats' => $stats,
        ]);
    }
}