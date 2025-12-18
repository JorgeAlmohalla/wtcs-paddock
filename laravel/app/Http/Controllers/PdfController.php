<?php

namespace App\Http\Controllers;

use App\Models\Race;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\View\View; // Importante para el return view
use App\Models\RaceResult;
use App\Models\QualifyingResult;
use App\Models\Team;
use App\Models\User;

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
        // 1. Buscamos la carrera
        $race = \App\Models\Race::with(['track', 'season'])->findOrFail($raceId);
        
        // 2. Buscamos reportes resueltos
        $penalties = \App\Models\IncidentReport::where('race_id', $raceId)
            ->where('status', 'resolved')
            ->whereNotNull('penalty_applied')
            ->with(['reported', 'reported.team'])
            ->get();

        // 3. Calculamos la fecha simulada
        $seasonName = $race->season->name;
        $simulatedYear = null;
        if (preg_match('/\((\d{4})\)/', $seasonName, $matches)) {
            $simulatedYear = $matches[1];
        }
        $dateObj = now();
        if ($simulatedYear) {
            $dateObj = $dateObj->setYear((int)$simulatedYear);
        }

        // 4. Enviamos a la vista
        return view('pdf.penalty-document', [
            'race' => $race,
            'penalties' => $penalties,
            'docNumber' => rand(10, 99),
            'date' => $dateObj->format('F jS Y'),
            'time' => now()->format('H:i'),
        ]);
    }

     public function downloadStandings()
    {
        // 1. Obtener temporada activa
        // Como no pasamos por el middleware en una descarga directa a veces, mejor asegurarnos
        $seasonId = app()->bound('currentSeason') ? app('currentSeason')->id : \App\Models\Season::where('is_active', true)->value('id');
        $seasonName = \App\Models\Season::find($seasonId)->name;

        // 2. Calcular PILOTOS (Copiado de StandingsController)
        $drivers = User::whereJsonContains('roles', 'driver')
            ->with('team')
            ->get()
            ->map(function ($driver) use ($seasonId) {
                $racePoints = RaceResult::where('user_id', $driver->id)
                    ->whereHas('race', fn($q) => $q->where('season_id', $seasonId))->sum('points');
                $qualyPoints = QualifyingResult::where('user_id', $driver->id)
                    ->whereHas('race', fn($q) => $q->where('season_id', $seasonId))->sum('points');
                $driver->total_points = $racePoints + $qualyPoints;
                return $driver;
            })
            ->filter(fn ($d) => $d->total_points > 0)
            ->sortByDesc('total_points')
            ->values();

        // 3. Calcular EQUIPOS
        $teams = Team::get()
            ->map(function ($team) use ($seasonId) {
                $racePoints = RaceResult::where('team_id', $team->id)
                    ->whereHas('race', fn($q) => $q->where('season_id', $seasonId))->sum('points');
                $qualyPoints = QualifyingResult::where('team_id', $team->id)
                    ->whereHas('race', fn($q) => $q->where('season_id', $seasonId))->sum('points');
                $team->total_points = $racePoints + $qualyPoints;
                return $team;
            })
            ->filter(fn ($t) => $t->total_points > 0)
            ->sortByDesc('total_points')
            ->values();

        // 4. Calcular MANUFACTURERS
        $seasonRaces = \App\Models\Race::where('season_id', $seasonId)->pluck('id');
        $manufacturers = Team::select('car_brand')->distinct()->get()
            ->map(function ($brandEntry) use ($seasonRaces) {
                $brand = $brandEntry->car_brand;
                $brandTeams = Team::where('car_brand', $brand)->pluck('id');
                $totalPoints = 0;
                foreach ($seasonRaces as $raceId) {
                    $best = RaceResult::where('race_id', $raceId)->whereIn('team_id', $brandTeams)->max('points');
                    if ($best) $totalPoints += $best;
                }
                return (object) ['name' => $brand, 'total_points' => $totalPoints];
            })
            ->filter(fn ($m) => $m->total_points > 0)
            ->sortByDesc('total_points')
            ->values();

        // 5. Generar PDF
        $pdf = Pdf::loadView('pdf.standings', [
            'drivers' => $drivers,
            'teams' => $teams,
            'manufacturers' => $manufacturers,
            'seasonName' => $seasonName,
            'date' => now()->format('d M Y'),
        ]);

        return $pdf->download('WTCS_Standings.pdf');
    }
}