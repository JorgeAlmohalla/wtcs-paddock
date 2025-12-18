<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;

class PublicProfileController extends Controller
{
    public function show(User $user): View
    {
        $seasonId = app()->bound('currentSeason') ? app('currentSeason')->id : null;

        // 1. Stats
        $stats = [
            'starts' => $user->raceResults()->whereHas('race', fn($q) => $q->where('season_id', $seasonId))->count(),
            'wins' => $user->raceResults()->whereHas('race', fn($q) => $q->where('season_id', $seasonId))->where('position', 1)->count(),
            'podiums' => $user->raceResults()->whereHas('race', fn($q) => $q->where('season_id', $seasonId))->where('position', '<=', 3)->count(),
            'poles' => $user->qualifyingResults()->whereHas('race', fn($q) => $q->where('season_id', $seasonId))->where('position', 1)->count(),
            'points' => $user->raceResults()->whereHas('race', fn($q) => $q->where('season_id', $seasonId))->sum('points'),
        ];

        // 2. Historial Qualy (Tabla)
        $qualyHistory = $user->qualifyingResults()
            ->whereHas('race', fn($q) => $q->where('season_id', $seasonId))
            ->with('race.track')
            ->join('races', 'qualifying_results.race_id', '=', 'races.id')
            ->orderBy('races.race_date', 'desc')
            ->select('qualifying_results.*')
            ->get()
            ->unique(fn($q) => $q->race->round_number);

        // 3. Datos Gráficas Carrera
        $raceResults = $user->raceResults()
            ->whereHas('race', fn($q) => $q->where('season_id', $seasonId))
            ->join('races', 'race_results.race_id', '=', 'races.id')
            ->orderBy('races.race_date', 'asc')
            ->select('race_results.position', 'race_results.points', 'races.round_number')
            ->get();

        $raceLabels = $raceResults->map(fn($r) => 'R'.$r->round_number)->toArray();
        $racePositionData = $raceResults->pluck('position')->toArray();
        
        $racePointsData = [];
        $total = 0;
        foreach ($raceResults as $r) {
            $total += $r->points;
            $racePointsData[] = $total;
        }

        // 4. Datos Gráfica Qualy
        $qualyResults = $user->qualifyingResults()
            ->whereHas('race', fn($q) => $q->where('season_id', $seasonId))
            ->join('races', 'qualifying_results.race_id', '=', 'races.id')
            ->orderBy('races.race_date', 'asc')
            ->select('qualifying_results.position', 'races.round_number')
            ->get()
            ->unique('round_number'); // <--- ESTO ES LA CLAVE PARA QUITAR DUPLICADOS
        
        // ¡ESTA ES LA LÍNEA QUE FALTA O ESTÁ MAL!
        $qualyPositionData = $qualyResults->pluck('position')->toArray();
        $qualyLabels = $qualyResults->map(fn($q) => 'R'.$q->round_number)->toArray();

        return view('public-profile', [
            'user' => $user,
            'stats' => $stats,
            'qualyHistory' => $qualyHistory,
            'raceLabels' => $raceLabels,       // Etiquetas X (R1, R2...)
            'raceData' => $racePositionData,   // Posiciones Carrera
            'racePointsData' => $racePointsData, // Puntos Acumulados
            'qualyData' => $qualyPositionData, // Posiciones Qualy
            'qualyLabels' => $qualyLabels,
        ]);
    }
}