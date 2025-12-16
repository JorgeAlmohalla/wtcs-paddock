<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;

class DriversController extends Controller
{
    public function __invoke(): View
    {
        // Buscamos usuarios que tengan "driver" dentro del array JSON de roles
        $drivers = User::whereJsonContains('roles', 'driver')
            ->with('team')
            ->orderBy('team_id', 'desc')
            ->orderBy('name', 'asc')
            ->get();

        return view('drivers', [
            'drivers' => $drivers,
        ]);
    }
}