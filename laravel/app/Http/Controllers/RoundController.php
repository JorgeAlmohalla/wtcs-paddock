<?php

namespace App\Http\Controllers;

use App\Models\Race;
use Illuminate\View\View;

class RoundController extends Controller
{
    public function show($roundNumber): View
    {
        // 1. Obtener temporada actual
        $seasonId = app()->bound('currentSeason') ? app('currentSeason')->id : null;

        // 2. Buscar sesiones
        $sessions = Race::where('round_number', $roundNumber)
            ->where('season_id', $seasonId) // <--- Importante: Filtrar por season
            ->with(['track', 'results.driver', 'results.team', 'qualifyingResults.driver', 'qualifyingResults.team'])
            ->orderBy('race_date', 'asc')
            ->get();

        if ($sessions->isEmpty()) {
            abort(404);
        }

        // 3. Identificar Sprint y Feature
        // Si hay una que se llame Sprint, es sprint. Si no, la primera.
        $sprintRace = $sessions->first(fn($r) => str_contains(strtolower($r->title ?? ''), 'sprint')) ?? $sessions->first();
        
        // Si hay una que se llame Feature, es feature. Si no, la última.
        $featureRace = $sessions->first(fn($r) => str_contains(strtolower($r->title ?? ''), 'feature')) ?? $sessions->last();

        // Si solo hay una carrera (sprint == feature), anulamos feature para que no salga duplicada
        if ($sessions->count() === 1) {
            $featureRace = null;
        } elseif ($sprintRace->id === $featureRace->id) {
            // Si el algoritmo falló y cogió la misma, cogemos la segunda como feature
            $featureRace = $sessions->last();
        }

        // 4. Definir Qualy (Usamos la de la Sprint)
        $qualySession = $sprintRace->qualifyingResults->sortBy('position');

        return view('round', [
            'roundNumber' => $roundNumber,
            'track' => $sprintRace->track,
            'sprint' => $sprintRace,
            'feature' => $featureRace,
            'qualy' => $qualySession,
        ]);
    }
}