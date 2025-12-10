<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\View\View;

class TeamsController extends Controller
{
    public function __invoke(): View
    {
        // Traemos equipos con sus pilotos cargados
        $teams = Team::with('drivers')->get();

        return view('teams', [
            'teams' => $teams,
        ]);
    }
}