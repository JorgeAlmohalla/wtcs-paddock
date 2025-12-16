<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;

class PublicProfileController extends Controller
{
    public function show(User $user): View
    {
        // 1. Estadísticas
        $stats = [
            'starts' => $user->raceResults()->count(),
            'wins' => $user->raceResults()->where('position', 1)->count(),
            'podiums' => $user->raceResults()->where('position', '<=', 3)->count(),
            'poles' => $user->qualifyingResults()->where('position', 1)->count(),
            'points' => $user->raceResults()->sum('points'),
        ];

        // 2. Historial Qualy
        $qualyHistory = $user->qualifyingResults()
            ->with('race.track')
            ->join('races', 'qualifying_results.race_id', '=', 'races.id')
            ->orderBy('races.race_date', 'desc')
            ->select('qualifying_results.*')
            ->get();

        // 3. Gráfica
        $results = $user->raceResults()
            ->join('races', 'race_results.race_id', '=', 'races.id')
            ->orderBy('races.race_date', 'asc')
            ->select('race_results.*', 'races.round_number')
            ->get();

        $labels = [];
        $data = [];
        $total = 0;
        foreach ($results as $result) {
            $total += $result->points;
            $labels[] = 'R' . $result->round_number;
            $data[] = $total;
        }

        return view('public-profile', [
            'user' => $user,
            'stats' => $stats,
            'qualyHistory' => $qualyHistory,
            'labels' => $labels,
            'data' => $data,
        ]);
    }
}