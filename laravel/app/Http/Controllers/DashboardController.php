<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $user = Auth::user();
        $seasonId = app()->bound('currentSeason') ? app('currentSeason')->id : null;

        // 1. ESTADÍSTICAS (Filtradas por Season)
        $stats = [
            'starts' => $user->raceResults()
                ->whereHas('race', fn($q) => $q->where('season_id', $seasonId))
                ->count(),
            'wins' => $user->raceResults()
                ->whereHas('race', fn($q) => $q->where('season_id', $seasonId))
                ->where('position', 1)->count(),
            'podiums' => $user->raceResults()
                ->whereHas('race', fn($q) => $q->where('season_id', $seasonId))
                ->where('position', '<=', 3)->count(),
            'poles' => $user->qualifyingResults()
                ->whereHas('race', fn($q) => $q->where('season_id', $seasonId))
                ->where('position', 1)->count(),
            'points' => $user->raceResults()
                ->whereHas('race', fn($q) => $q->where('season_id', $seasonId))
                ->sum('points'),
        ];

        // 2. HISTORIAL DE QUALY (TABLA) - ¡ESTO ES LO QUE FALTABA!
        $qualyHistory = $user->qualifyingResults()
            ->whereHas('race', fn($q) => $q->where('season_id', $seasonId))
            ->with('race.track')
            ->join('races', 'qualifying_results.race_id', '=', 'races.id')
            ->orderBy('races.race_date', 'desc')
            ->select('qualifying_results.*')
            ->get();

        // 3. GRÁFICAS
        $raceResults = $user->raceResults()
            ->whereHas('race', fn($q) => $q->where('season_id', $seasonId))
            ->join('races', 'race_results.race_id', '=', 'races.id')
            ->orderBy('races.race_date', 'asc')
            ->select('race_results.id', 'race_results.position', 'race_results.points', 'races.round_number') // <--- AÑADIDO 'points' e 'id'
            ->get();

        $raceLabels = $raceResults->map(fn($r) => 'R'.$r->round_number)->toArray();
        $racePositionData = $raceResults->pluck('position')->toArray();
        
        // Calcular acumulado
        $racePointsData = [];
        $total = 0;
        foreach ($raceResults as $r) {
            $total += $r->points;
            $racePointsData[] = $total;
        }

        // Qualy (Igual)
        $qualyResults = $user->qualifyingResults()
            ->whereHas('race', fn($q) => $q->where('season_id', $seasonId))
            ->join('races', 'qualifying_results.race_id', '=', 'races.id')
            ->orderBy('races.race_date', 'asc')
            ->select('qualifying_results.position', 'races.round_number')
            ->get();
        $qualyLabels = $qualyResults->map(fn($q) => 'R'.$q->round_number)->toArray();
        $qualyPositionData = $qualyResults->pluck('position')->toArray();

        // 4. REPORTES
        $myReports = \App\Models\IncidentReport::where(function($q) use ($user) {
                $q->where('reporter_id', $user->id)
                  ->orWhere('reported_id', $user->id);
            })
            ->with(['race.track', 'reporter', 'reported'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard', [
            'user' => $user,
            'stats' => $stats,
            'qualyHistory' => $qualyHistory,
            'raceLabels' => $raceLabels,
            // CORRECCIÓN AQUÍ:
            'raceData' => $racePositionData, // Usamos la variable que SÍ existe ($racePositionData)
            'racePointsData' => $racePointsData,
            
            'qualyLabels' => $qualyLabels,
            // CORRECCIÓN AQUÍ:
            'qualyData' => $qualyPositionData, // Usamos la variable que SÍ existe
            
            'myReports' => $myReports,
        ]);
    }
}