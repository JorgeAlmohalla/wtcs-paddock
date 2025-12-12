<?php

namespace App\Http\Controllers;

use App\Models\RaceResult;
use App\Models\Team;
use App\Models\User;
use Illuminate\View\View;

class StandingsController extends Controller
{
    public function __invoke(): View
    {
        // Obtener temporada seleccionada
        $seasonId = app()->bound('currentSeason') ? app('currentSeason')->id : null;

        // 1. Clasificación de PILOTOS
        $drivers = User::where('role', 'driver')
            ->with('team')
            ->get()
            ->map(function ($driver) use ($seasonId) {
                // Sumar puntos SOLO de carreras de esta temporada
                $driver->total_points = RaceResult::where('user_id', $driver->id)
                    ->whereHas('race', function ($query) use ($seasonId) {
                        $query->where('season_id', $seasonId); // <--- FILTRO MAGISTRAL
                    })
                    ->sum('points');
                return $driver;
            })
            ->filter(fn ($driver) => $driver->total_points > 0) // Opcional: Ocultar pilotos sin puntos en esta season
            ->sortByDesc('total_points')
            ->values();

        // 2. Clasificación de CONSTRUCTORES
        $teams = Team::get()
            ->map(function ($team) use ($seasonId) {
                $team->total_points = RaceResult::where('team_id', $team->id)
                    ->whereHas('race', function ($query) use ($seasonId) {
                        $query->where('season_id', $seasonId); // <--- MISMO FILTRO
                    })
                    ->sum('points');
                return $team;
            })
            ->filter(fn ($team) => $team->total_points > 0)
            ->sortByDesc('total_points')
            ->values();

        return view('standings', [
            'drivers' => $drivers,
            'teams' => $teams,
        ]);
    }
}