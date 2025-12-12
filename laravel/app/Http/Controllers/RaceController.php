<?php

namespace App\Http\Controllers;

use App\Models\Race;
use Illuminate\View\View;

class RaceController extends Controller
{
    public function show(Race $race): View
    {
        // Cargamos la carrera con sus resultados ordenados
        $race->load([
            'track',
            'results' => fn($q) => $q->orderBy('position', 'asc'),
            'results.driver',
            'results.team',
            'qualifyingResults' => fn($q) => $q->orderBy('position'),
            'qualifyingResults.driver',
            'qualifyingResults.team',
        ]);

        return view('race', ['race' => $race]);
    }
}