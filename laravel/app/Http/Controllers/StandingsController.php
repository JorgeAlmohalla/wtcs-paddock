<?php

namespace App\Http\Controllers;

use App\Models\RaceResult;
use App\Models\QualifyingResult; // Importante
use App\Models\Team;
use App\Models\User;
use Illuminate\View\View;

class StandingsController extends Controller
{
    public function __invoke(): View
    {
        $seasonId = app()->bound('currentSeason') ? app('currentSeason')->id : null;

        // 1. Clasificación de PILOTOS (Suma Race + Qualy)
        $drivers = User::whereJsonContains('roles', 'driver')
            ->with('team')
            ->get()
            ->map(function ($driver) use ($seasonId) {
                // Puntos Carrera
                $racePoints = RaceResult::where('user_id', $driver->id)
                    ->whereHas('race', fn($q) => $q->where('season_id', $seasonId))
                    ->sum('points');
                
                // Puntos Qualy
                $qualyPoints = QualifyingResult::where('user_id', $driver->id)
                    ->whereHas('race', fn($q) => $q->where('season_id', $seasonId))
                    ->sum('points');

                $driver->total_points = $racePoints + $qualyPoints;
                return $driver;
            })
            ->filter(fn ($driver) => $driver->total_points > 0)
            ->sortByDesc('total_points')
            ->values();

        // 2. Clasificación de CONSTRUCTORES (Suma Race + Qualy de sus pilotos)
        $teams = Team::get()
            ->map(function ($team) use ($seasonId) {
                // Puntos Carrera del equipo
                $racePoints = RaceResult::where('team_id', $team->id)
                    ->whereHas('race', fn($q) => $q->where('season_id', $seasonId))
                    ->sum('points');
                
                // Puntos Qualy del equipo
                $qualyPoints = QualifyingResult::where('team_id', $team->id)
                    ->whereHas('race', fn($q) => $q->where('season_id', $seasonId))
                    ->sum('points');

                $team->total_points = $racePoints + $qualyPoints;
                return $team;
            })
            ->filter(fn ($team) => $team->total_points > 0)
            ->sortByDesc('total_points')
            ->values();

        // 3. Clasificación de MANUFACTURERS (Solo mejor resultado de carrera)
        // (Si quieres sumar Qualy aquí también, avísame, pero por ahora lo dejo como estaba para no romperlo)
        $seasonRaces = \App\Models\Race::where('season_id', $seasonId)->pluck('id');

        $manufacturers = Team::select('car_brand')
            ->distinct()
            ->get()
            ->map(function ($brandEntry) use ($seasonRaces) {
                $brand = $brandEntry->car_brand;
                $brandTeams = Team::where('car_brand', $brand)->pluck('id');

                $totalPoints = 0;

                foreach ($seasonRaces as $raceId) {
                    // Mejor resultado en carrera
                    $bestResultPoints = RaceResult::where('race_id', $raceId)
                        ->whereIn('team_id', $brandTeams)
                        ->max('points');
                    
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

        return view('standings', [
            'drivers' => $drivers,
            'teams' => $teams,
            'manufacturers' => $manufacturers,
        ]);
    }
}