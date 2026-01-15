<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DriversController extends Controller
{
    public function __invoke(Request $request): View
    {
        // 1. Iniciar consulta base: Solo usuarios con rol 'driver'
        $query = User::whereJsonContains('roles', 'driver');

        // 2. Aplicar filtro de búsqueda si existe
        if ($request->has('search') && $request->get('search') != '') {
            $search = $request->get('search');
            $query->where('name', 'like', "%{$search}%");
        }

        // 3. Obtener resultados ordenados
        $drivers = $query->with('team')
            ->orderBy('name', 'asc')
            ->get();

        return view('drivers', [
            'drivers' => $drivers,
        ]);
    }
}