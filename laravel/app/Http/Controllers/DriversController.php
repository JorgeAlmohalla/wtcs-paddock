<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;

class DriversController extends Controller
{
    public function __invoke(): View
    {
        // Sacamos solo los pilotos, ordenados primero por Equipo y luego por Nombre
        $drivers = User::where('role', 'driver')
            ->with('team')
            ->orderBy('team_id', 'desc') // Agrupa equipos primero
            ->orderBy('name', 'asc')
            ->get();

        return view('drivers', [
            'drivers' => $drivers,
        ]);
    }
}