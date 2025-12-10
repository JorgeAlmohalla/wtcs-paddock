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
        // 1. Clasificación de PILOTOS
        $drivers = User::where('role', 'driver')
            ->with('team') // Cargar equipo para mostrar el logo/nombre
            ->get()
            ->map(function ($driver) {
                // Sumar puntos de la tabla race_results para este piloto
                $driver->total_points = RaceResult::where('user_id', $driver->id)->sum('points');
                return $driver;
            })
            ->sortByDesc('total_points') // Ordenar del primero al último
            ->values(); // Resetear índices del array (0, 1, 2...)

        // 2. Clasificación de CONSTRUCTORES (Equipos)
        // Solo cogemos equipos que tengan puntos
        $teams = Team::get()
            ->map(function ($team) {
                // Sumar puntos de todos los resultados conseguidos con este equipo
                $team->total_points = RaceResult::where('team_id', $team->id)->sum('points');
                return $team;
            })
            ->filter(fn ($team) => $team->total_points > 0) // Opcional: Ocultar equipos con 0 puntos
            ->sortByDesc('total_points')
            ->values();

        return view('standings', [
            'drivers' => $drivers,
            'teams' => $teams,
        ]);
    }
}