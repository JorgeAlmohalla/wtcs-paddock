<?php

namespace App\Http\Controllers;

use App\Models\Race;
use App\Models\User;
use App\Models\IncidentReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function create()
    {
        // 1. Mostrar solo carreras de los últimos 14 días (ventana de reporte)
        // Y ordenarlas de más nueva a más vieja
        $races = Race::where('race_date', '>=', now()->subDays(14))
            ->where('race_date', '<=', now()) // Que ya hayan pasado o estén pasando
            ->orderBy('race_date', 'desc')
            ->get();

        // 2. Pilotos: Mostrar todos los activos (filtrar por carrera es difícil sin JS reactivo)
        $drivers = User::where('role', 'driver')
            ->orderBy('name')
            ->get();

        return view('reports.create', compact('races', 'drivers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'race_id' => 'required|exists:races,id',
            'reported_id' => 'required|exists:users,id|different:reporter_id', // No te puedes reportar a ti mismo
            'lap_corner' => 'required|string|max:50',
            'description' => 'required|string|max:1000',
            'video_url' => 'required|url',
        ]);

        IncidentReport::create([
            'reporter_id' => Auth::id(),
            ...$validated,
            'status' => 'pending'
        ]);

        return redirect()->route('dashboard')->with('status', 'Report submitted successfully.');
    }
}