<?php

namespace App\Http\Controllers;

use App\Models\Race;
use Illuminate\View\View;

class RoundController extends Controller
{
    public function show($roundNumber): View
    {
        // 1. Buscamos todas las sesiones de esta ronda
        $sessions = Race::where('round_number', $roundNumber)
            ->with(['track', 'results.driver', 'results.team', 'qualifyingResults.driver'])
            ->orderBy('race_date', 'asc') // Orden cronológico (Sprint -> Feature)
            ->get();

        // Si no existe la ronda, error 404
        if ($sessions->isEmpty()) {
            abort(404);
        }

        // 2. Identificamos las sesiones
        // Asumimos que la primera es Sprint y la segunda Feature (o por título si prefieres)
        $sprintRace = $sessions->first(); 
        $featureRace = $sessions->skip(1)->first(); // La segunda

        // Usamos la Qualy de la Sprint como la Qualy del fin de semana
        $qualySession = $sprintRace->qualifyingResults;

        return view('round', [
            'roundNumber' => $roundNumber,
            'track' => $sprintRace->track, // El circuito es el mismo
            'sprint' => $sprintRace,
            'feature' => $featureRace,
            'qualy' => $qualySession,
        ]);
    }
}