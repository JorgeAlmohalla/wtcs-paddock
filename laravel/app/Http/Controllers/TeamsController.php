<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TeamsController extends Controller
{
    public function __invoke(Request $request): View
    {
        $seasonId = app()->bound('currentSeason') ? app('currentSeason')->id : null;

        // 1. Consulta base
        $query = Team::with('drivers');

        // 2. Filtro de búsqueda
        if ($request->has('search') && $request->get('search') != '') {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('short_name', 'like', "%{$search}%")
                  ->orWhere('car_brand', 'like', "%{$search}%");
            });
        }

        // 3. Procesar stats y ordenar
        $teams = $query->get()
            ->map(function ($team) use ($seasonId) {
                $team->stats = [
                    'points' => $team->raceResults()->whereHas('race', fn($q) => $q->where('season_id', $seasonId))->sum('points'),
                    'wins' => $team->raceResults()->whereHas('race', fn($q) => $q->where('season_id', $seasonId))->where('position', 1)->count(),
                    'podiums' => $team->raceResults()->whereHas('race', fn($q) => $q->where('season_id', $seasonId))->where('position', '<=', 3)->count(),
                ];
                return $team;
            })
            ->sortByDesc('stats.points')
            ->values();

        return view('teams', [
            'teams' => $teams,
        ]);
    }
}