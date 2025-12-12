<?php

namespace App\Http\Controllers;

use App\Models\Race;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfController extends Controller
{
    public function downloadRound($roundNumber)
    {
        // 1. Buscar las sesiones de esa ronda
        $sessions = Race::where('round_number', $roundNumber)
            ->with(['track', 'results.driver', 'results.team', 'qualifyingResults.driver'])
            ->orderBy('race_date', 'asc')
            ->get();

        if ($sessions->isEmpty()) {
            abort(404);
        }

        // 2. Identificar sesiones (Igual que en RoundController)
        $sprintRace = $sessions->first();
        $featureRace = $sessions->skip(1)->first();
        $qualySession = $sprintRace->qualifyingResults->sortBy('position');

        // 3. Generar PDF (Hoja horizontal 'landscape' para que quepan las tablas)
        $pdf = Pdf::loadView('pdf.round-report', [
            'roundNumber' => $roundNumber,
            'track' => $sprintRace->track,
            'sprint' => $sprintRace,
            'feature' => $featureRace,
            'qualy' => $qualySession,
            'date' => now()->format('d M Y'),
        ])->setPaper('a4', 'landscape'); // A4 Horizontal

        // 4. Descargar
        return $pdf->download("WTCS_Round{$roundNumber}_Report.pdf");
    }
}