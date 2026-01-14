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
        if (!$user->isTeamPrincipal() || !$user->team) abort(403);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'car_model' => 'required|string|max:255',
            'primary_color' => 'required|string|size:7',
            'car_image' => 'nullable|image|max:5120', // Max 5MB
            'bio' => 'nullable|string|max:500',
            'team_logo' => 'nullable|image|max:2048',
            'tech_chassis' => 'nullable|string|max:100',
            'tech_engine' => 'nullable|string|max:100',
            'tech_power' => 'nullable|string|max:50',
            'tech_drivetrain' => 'nullable|string|in:FF,FR,MR,RR,4WD', // Validamos que sea uno de la lista
            'tech_gearbox' => 'nullable|string|max:100',
        ]);

        $data = $request->except('car_image');

        // SUBIDA DE IMAGEN
        if ($request->hasFile('car_image')) {
            // Borrar antigua si existe (Opcional, pero recomendado)
            // if ($user->team->car_image_url) Storage::disk('public')->delete($user->team->car_image_url);
            
            $path = $request->file('car_image')->store('team-cars', 'public');
            $data['car_image_url'] = $path;
        }

        // SUBIDA LOGO
        if ($request->hasFile('team_logo')) {
            $path = $request->file('team_logo')->store('team-logos', 'public');
            $data['logo_url'] = $path;
        }

        $user->team->update($data);

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