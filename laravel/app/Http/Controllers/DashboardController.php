<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $user = Auth::user();

        // --- 1. ESTADÍSTICAS GLOBALES ---
        $stats = [
            'starts' => $user->raceResults()->count(),
            'wins' => $user->raceResults()->where('position', 1)->count(),
            'podiums' => $user->raceResults()->where('position', '<=', 3)->count(),
            'poles' => $user->qualifyingResults()->where('position', 1)->count(),
            'points' => $user->raceResults()->sum('points'),
        ];

        // --- 2. TABLA DE QUALY ---
        $qualyHistory = $user->qualifyingResults()
            ->with('race.track') // Cargar carrera y circuito
            ->join('races', 'qualifying_results.race_id', '=', 'races.id')
            ->orderBy('races.race_date', 'desc') // Las más recientes primero
            ->select('qualifying_results.*') // Evitar conflictos de ID con races
            ->get();

        // --- 3. GRÁFICA (Ya la tenías) ---
        $results = $user->raceResults()
            ->join('races', 'race_results.race_id', '=', 'races.id')
            ->orderBy('races.race_date', 'asc')
            ->select('race_results.*', 'races.title as race_title', 'races.round_number')
            ->get();

        $labels = [];
        $data = [];
        $total = 0;

        foreach ($results as $result) {
            $total += $result->points;
            $labels[] = 'R' . $result->round_number;
            $data[] = $total;
        }

        return view('dashboard', [
            'user' => $user,
            'stats' => $stats,
            'qualyHistory' => $qualyHistory,
            'labels' => $labels,
            'data' => $data,
            'currentPoints' => $total,
        ]);
    }
}