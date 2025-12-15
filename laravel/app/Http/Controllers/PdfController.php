<?php

namespace App\Http\Controllers;

use App\Models\Race;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\View\View; // Importante para el return view

class PdfController extends Controller
{
    // MÉTODO 1: Descargar el PDF de la Ronda completa (El botón de arriba)
    public function downloadRound($roundNumber)
    {
        $sessions = Race::where('round_number', $roundNumber)
            ->with(['track', 'results.driver', 'results.team', 'qualifyingResults.driver'])
            ->orderBy('race_date', 'asc')
            ->get();

        if ($sessions->isEmpty()) {
            abort(404);
        }

        $sprintRace = $sessions->first();
        $featureRace = $sessions->skip(1)->first();
        $qualySession = $sprintRace->qualifyingResults->sortBy('position');

        $pdf = Pdf::loadView('pdf.round-report', [
            'roundNumber' => $roundNumber,
            'track' => $sprintRace->track,
            'sprint' => $sprintRace,
            'feature' => $featureRace,
            'qualy' => $qualySession,
            'date' => now()->format('d M Y'),
        ])->setPaper('a4', 'landscape');

        return $pdf->download("WTCS_Round{$roundNumber}_Report.pdf");
    }

    // MÉTODO 2: Ver el Documento de Sanciones (El botón de Steward)
    public function showPenaltyDoc($raceId)
    {
        $race = \App\Models\Race::with(['track', 'season'])->findOrFail($raceId);
        
        $penalties = \App\Models\IncidentReport::where('race_id', $raceId)
            ->where('status', 'resolved')
            ->whereNotNull('penalty_applied')
            ->with(['reported', 'reported.team'])
            ->get();

        // Devolvemos una VISTA HTML, no un PDF descargable
        return view('pdf.penalty-document', [
            'race' => $race,
            'penalties' => $penalties,
            'docNumber' => 28, 
            'date' => now()->subYears(26)->format('F jS Y'),
            'time' => now()->format('H:i'),
        ]);
    }
}