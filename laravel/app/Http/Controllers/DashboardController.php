<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $user = Auth::user();

        // 1. Obtener resultados del piloto ordenados por fecha de carrera
        $results = $user->raceResults()
            ->join('races', 'race_results.race_id', '=', 'races.id')
            ->orderBy('races.race_date', 'asc')
            ->select('race_results.*', 'races.title as race_title', 'races.round_number')
            ->get();

        // 2. Preparar datos para la gráfica (Acumulativo)
        $labels = []; // Eje X: Nombres de carreras
        $data = [];   // Eje Y: Puntos totales
        $total = 0;

        foreach ($results as $result) {
            $total += $result->points; // Sumar puntos actuales al total anterior
            
            $labels[] = 'R' . $result->round_number . ' - ' . ($result->race_title ?? 'GP');
            $data[] = $total;
        }

        return view('dashboard', [
            'labels' => $labels,
            'data' => $data,
            'currentPoints' => $total, // Puntos totales actuales
        ]);
    }
}