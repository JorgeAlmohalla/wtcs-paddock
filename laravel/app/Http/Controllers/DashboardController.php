<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Str; // Importar para el Str::limit en la vista

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $user = Auth::user();

        // 1. ESTADÍSTICAS
        $stats = [
            'starts' => $user->raceResults()->count(),
            'wins' => $user->raceResults()->where('position', 1)->count(),
            'podiums' => $user->raceResults()->where('position', '<=', 3)->count(),
            'poles' => $user->qualifyingResults()->where('position', 1)->count(),
            'points' => $user->raceResults()->sum('points'),
        ];

        // 2. HISTORIAL DE QUALY
        $qualyHistory = $user->qualifyingResults()
            ->with('race.track')
            ->join('races', 'qualifying_results.race_id', '=', 'races.id')
            ->orderBy('races.race_date', 'desc')
            ->select('qualifying_results.*')
            ->get();

        // 3. GRÁFICA DE RENDIMIENTO
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

        // 4. REPORTES DE INCIDENTES (Esto faltaba o fallaba)
        $myReports = \App\Models\IncidentReport::where('reporter_id', $user->id)
            ->orWhere('reported_id', $user->id)
            ->with(['race.track', 'reporter', 'reported'])
            ->orderBy('created_at', 'desc')
            ->get();

        // 5. PASAR DATOS A LA VISTA
        return view('dashboard', [
            'user' => $user,
            'stats' => $stats,
            'qualyHistory' => $qualyHistory,
            'labels' => $labels,
            'data' => $data,
            'currentPoints' => $total,
            'myReports' => $myReports,
        ]);
    }
}