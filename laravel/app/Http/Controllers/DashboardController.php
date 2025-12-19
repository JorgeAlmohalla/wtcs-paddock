<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = Auth::user();
        $seasonId = app()->bound('currentSeason') ? app('currentSeason')->id : null;

        // 1. OBTENER MIS DATOS
        $myData = $this->getDriverData($user, $seasonId);

        // 2. OBTENER DATOS DEL RIVAL (Si se ha seleccionado)
        $rival = null;
        $rivalData = null;

        if ($request->has('compare_with')) {
            $rival = \App\Models\User::find($request->get('compare_with'));
            if ($rival) {
                $rivalData = $this->getDriverData($rival, $seasonId);
            }
        }

        // 3. REPORTES (Esto es personal, no del rival)
        $myReports = \App\Models\IncidentReport::where(function($q) use ($user) {
                $q->where('reporter_id', $user->id)
                  ->orWhere('reported_id', $user->id);
            })
            ->with(['race.track', 'reporter', 'reported'])
            ->orderBy('created_at', 'desc')
            ->get();

        // 4. LISTA DE PILOTOS PARA EL SELECTOR (Excluyéndome a mí)
        $allDrivers = \App\Models\User::whereJsonContains('roles', 'driver')
            ->where('id', '!=', $user->id)
            ->orderBy('name')
            ->get();

        return view('dashboard', [
            'user' => $user,
            
            // Datos Míos
            'stats' => $myData['stats'],
            'qualyHistory' => $myData['qualyHistory'],
            'raceLabels' => $myData['raceLabels'],
            'raceData' => $myData['racePositionData'],
            'racePointsData' => $myData['racePointsData'],
            'qualyLabels' => $myData['qualyLabels'],
            'qualyData' => $myData['qualyPositionData'],
            'championships' => $myData['championships'],
            
            // Datos Rival (Puede ser null)
            'rival' => $rival,
            'rivalRaceData' => $rivalData ? $rivalData['racePositionData'] : null,
            'rivalPointsData' => $rivalData ? $rivalData['racePointsData'] : null,
            'rivalQualyData' => $rivalData ? $rivalData['qualyPositionData'] : null,
            
            // Extras
            'myReports' => $myReports,
            'allDrivers' => $allDrivers,
        ]);
    }

    /**
     * Función auxiliar para calcular todas las estadísticas de un piloto
     * con Eje Maestro sincronizado (Rellena huecos con null)
     */
    private function getDriverData($user, $seasonId)
    {
        // A. ESTADÍSTICAS BÁSICAS
        $stats = [
            'starts' => $user->raceResults()->whereHas('race', fn($q) => $q->where('season_id', $seasonId))->count(),
            'wins' => $user->raceResults()->whereHas('race', fn($q) => $q->where('season_id', $seasonId))->where('position', 1)->count(),
            'podiums' => $user->raceResults()->whereHas('race', fn($q) => $q->where('season_id', $seasonId))->where('position', '<=', 3)->count(),
            'poles' => $user->qualifyingResults()->whereHas('race', fn($q) => $q->where('season_id', $seasonId))->where('position', 1)->count(),
            'points' => $user->raceResults()->whereHas('race', fn($q) => $q->where('season_id', $seasonId))->sum('points'),
        ];

        // B. HISTORIAL QUALY (TABLA - Solo datos reales)
        $qualyHistory = $user->qualifyingResults()
            ->whereHas('race', fn($q) => $q->where('season_id', $seasonId))
            ->with('race.track')
            ->join('races', 'qualifying_results.race_id', '=', 'races.id')
            ->orderBy('races.race_date', 'desc')
            ->select('qualifying_results.*')
            ->get()
            ->unique(fn($q) => $q->race->round_number);

        // --- C. CALENDARIO MAESTRO (Eje X Común) ---
        // Obtenemos todas las carreras de la temporada para pintar el eje completo
        $allRaces = \App\Models\Race::where('season_id', $seasonId)
            ->orderBy('race_date', 'asc')
            ->get(); // R1 Sprint, R1 Feature, R2 Sprint...

        $raceLabels = $allRaces->map(fn($r) => 'R'.$r->round_number)->toArray();

        // --- D. MAPEAR DATOS CARRERA (Con Huecos) ---
        $racePositionData = [];
        $racePointsData = [];
        $runningTotal = 0;

        foreach ($allRaces as $race) {
            // Buscar resultado del piloto en esta carrera concreta
            $result = \App\Models\RaceResult::where('race_id', $race->id)
                ->where('user_id', $user->id)
                ->first();

            if ($result) {
                $racePositionData[] = $result->position;
                $runningTotal += $result->points; // Sumamos puntos
            } else {
                $racePositionData[] = null; // Hueco visual en la línea
                // No sumamos puntos, pero mantenemos el total acumulado
            }
            // Guardamos el total acumulado (incluso si no corrió, mantiene sus puntos)
            $racePointsData[] = $runningTotal;
        }

        // --- E. MAPEAR DATOS QUALY (Con Huecos y Eje Único) ---
        $allRounds = $allRaces->unique('round_number')->values(); // R1, R2, R3... hasta el final de temporada
        
        $qualyLabels = $allRounds->map(fn($r) => 'R'.$r->round_number)->toArray();
        $qualyPositionData = [];

        foreach ($allRounds as $round) {
            // Buscamos si el piloto tiene qualy en esta ronda concreta
            // Usamos first() porque solo debería haber una qualy por ronda
            $qResult = \App\Models\QualifyingResult::where('user_id', $user->id)
                ->whereHas('race', fn($q) => $q->where('round_number', $round->round_number)->where('season_id', $seasonId))
                ->first();

            // Si hay resultado, lo ponemos. Si no, NULL (para que Chart.js sepa que ahí no hay dato pero el eje sigue)
            $qualyPositionData[] = $qResult ? $qResult->position : null;
        }

        // F. TROFEOS
        $championships = \App\Models\Season::where('is_active', false)->get()->filter(function($s) use ($user) {
            return $s->champion?->id === $user->id;
        });

        return [
            'stats' => $stats,
            'qualyHistory' => $qualyHistory,
            'raceLabels' => $raceLabels,
            'racePositionData' => $racePositionData,
            'racePointsData' => $racePointsData,
            'qualyLabels' => $qualyLabels,
            'qualyPositionData' => $qualyPositionData,
            'championships' => $championships,
        ];
    }
}