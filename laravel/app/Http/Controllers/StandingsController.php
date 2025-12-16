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
        $seasonId = app()->bound('currentSeason') ? app('currentSeason')->id : null;

        // 1. Clasificación de PILOTOS
        $drivers = User::whereJsonContains('roles', 'driver')
            ->with('team')
            ->get()
            ->map(function ($driver) use ($seasonId) {
                $driver->total_points = RaceResult::where('user_id', $driver->id)
                    ->whereHas('race', fn($q) => $q->where('season_id', $seasonId))
                    ->sum('points');
                return $driver;
            })
            ->filter(fn ($driver) => $driver->total_points > 0)
            ->sortByDesc('total_points')
            ->values();

        // 2. Clasificación de CONSTRUCTORES (Equipos)
        $teams = Team::get()
            ->map(function ($team) use ($seasonId) {
                $team->total_points = RaceResult::where('team_id', $team->id)
                    ->whereHas('race', fn($q) => $q->where('season_id', $seasonId))
                    ->sum('points');
                return $team;
            })
            ->filter(fn ($team) => $team->total_points > 0)
            ->sortByDesc('total_points')
            ->values();

        // 3. Clasificación de MANUFACTURERS (Marcas)
        // Regla: Solo cuenta el mejor resultado de la marca en cada carrera
        
        // Primero obtenemos todas las carreras de la temporada para iterar sobre ellas
        $seasonRaces = \App\Models\Race::where('season_id', $seasonId)->pluck('id');

        $manufacturers = Team::select('car_brand')
            ->distinct()
            ->get()
            ->map(function ($brandEntry) use ($seasonRaces, $seasonId) {
                $brand = $brandEntry->car_brand;
                
                // Buscar equipos de esta marca
                $brandTeams = Team::where('car_brand', $brand)->pluck('id');

                $totalPoints = 0;

                // Para cada carrera de la temporada...
                foreach ($seasonRaces as $raceId) {
                    // Buscamos el MEJOR resultado de cualquier coche de esta marca en esa carrera
                    $bestResultPoints = RaceResult::where('race_id', $raceId)
                        ->whereIn('team_id', $brandTeams)
                        ->max('points'); // Cogemos solo el máximo (ej: 25 si ganó uno)
                    
                    if ($bestResultPoints) {
                        $totalPoints += $bestResultPoints;
                    }
                }

                return (object) [
                    'name' => $brand,
                    'total_points' => $totalPoints,
                    'team_count' => $brandTeams->count(),
                    'primary_color' => Team::where('car_brand', $brand)->first()->primary_color ?? '#666',
                ];
            })
            ->filter(fn ($m) => $m->total_points > 0)
            ->sortByDesc('total_points')
            ->values();

        // 4. RETORNO ÚNICO
        return view('standings', [
            'drivers' => $drivers,
            'teams' => $teams,
            'manufacturers' => $manufacturers,
        ]);
    }
}