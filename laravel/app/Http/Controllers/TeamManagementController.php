<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class TeamManagementController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        // ... checks ...
        
        // Buscar pilotos SIN equipo
        $freeAgents = User::whereNull('team_id')
            ->orderBy('name')
            ->get();

        return view('teams.manage', [
            'team' => $user->team->load('drivers'),
            'freeAgents' => $freeAgents, // <--- NUEVO
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->isTeamPrincipal() || !$user->team) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'car_model' => 'required|string|max:255',
            'primary_color' => 'required|string|size:7', // #RRGGBB
            // logo_url iría aquí si implementas subida
        ]);

        $user->team->update($validated);

        return back()->with('status', 'Team updated successfully.');
    }

    // Fichar piloto (Agente Libre)
    public function addDriver(Request $request)
    {
        $user = Auth::user();
        if (!$user->isTeamPrincipal() || !$user->team) abort(403);

        $validated = $request->validate([
            'driver_id' => 'required|exists:users,id',
            'contract_type' => 'required|in:primary,reserve', // Validar el nuevo campo
        ]);

        $driver = User::find($validated['driver_id']);

        if ($driver->team_id) {
            return back()->withErrors(['driver' => 'This driver already has a team.']);
        }

        $driver->team_id = $user->team->id;
        $driver->contract_type = $validated['contract_type']; // Guardar tipo de contrato
        $driver->save();

        return back()->with('status', 'Driver signed successfully.');
    }

    // Despedir piloto
    public function removeDriver(User $driver)
    {
        $manager = Auth::user();
        
        // Seguridad: Solo puedes echar a gente de TU equipo
        if (!$manager->isTeamPrincipal() || $driver->team_id !== $manager->team_id) {
            abort(403);
        }

        // Seguridad: No puedes echarte a ti mismo si eres el único jefe
        if ($driver->id === $manager->id) {
            return back()->withErrors(['roster' => 'You cannot remove yourself.']);
        }

        $driver->team_id = null;
        $driver->contract_type = 'primary'; // Resetear contrato
        $driver->save();

        return back()->with('status', 'Driver removed from team.');
    }
}