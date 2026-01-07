<?php

use App\Models\Race;
use App\Http\Resources\RaceResource;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use App\Models\RaceResult;
use App\Models\QualifyingResult;
use App\Models\Team;


// Endpoint: LOGIN PARA APP MÓVIL
Route::post('/login', function (Request $request) {
    // 1. Validar datos
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    // 2. Buscar usuario
    $user = User::where('email', $request->email)->first();

    // 3. Comprobar contraseña
    if (! $user || ! Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Credenciales incorrectas'], 401);
    }

    // 4. GENERAR TOKEN (Aquí está la magia de Sanctum)
    // Borramos tokens viejos para limpiar
    $user->tokens()->delete();
    
    // Creamos uno nuevo para el móvil
    $token = $user->createToken('android-app')->plainTextToken;

    // 5. Devolver el token y los datos del usuario al móvil
    return response()->json([
        'token' => $token,
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'team' => $user->team ? $user->team->name : 'Free Agent',
            'avatar' => $user->avatar_url ? asset('storage/' . $user->avatar_url) : null,
        ]
    ]);
});


// Endpoint Público: Calendario de la Temporada Activa
Route::get('/calendar', function () {
    $activeSeasonId = \App\Models\Season::where('is_active', true)->value('id');
    
    $races = Race::where('season_id', $activeSeasonId)
        ->with(['track', 'results.driver'])
        ->orderBy('round_number')
        ->orderBy('race_date')
        ->get();

    return RaceResource::collection($races);
});


// Endpoint para Standings
Route::get('/standings', function () {
    $activeSeasonId = \App\Models\Season::where('is_active', true)->value('id');

    $drivers = User::whereJsonContains('roles', 'driver')
        ->with('team')
        ->get()
        ->map(function ($driver) use ($activeSeasonId) {
            // Calcular puntos
            $p1 = RaceResult::where('user_id', $driver->id)
                ->whereHas('race', fn($q) => $q->where('season_id', $activeSeasonId))->sum('points');
            $p2 = QualifyingResult::where('user_id', $driver->id)
                ->whereHas('race', fn($q) => $q->where('season_id', $activeSeasonId))->sum('points');
            
            // Devolver ARRAY LIMPIO (JSON Structure)
            return [
                'id' => $driver->id,
                'name' => $driver->name,
                'team' => $driver->team ? $driver->team->name : 'Privateer',
                'team_color' => $driver->team->primary_color ?? '#666666',
                'points' => (int) ($p1 + $p2),
                'avatar_url' => $driver->avatar_url ? asset('storage/' . $driver->avatar_url) : null,
                'nationality' => $driver->nationality,
            ];
        })
        ->filter(fn ($d) => $d['points'] > 0) // Filtrar los que tienen 0 puntos
        ->sortByDesc('points')
        ->values(); // Reindexar el array para JSON

    return response()->json($drivers);
});


// Endpoint de perfil protegido
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    // Devolvemos el usuario con su equipo cargado
    return $request->user()->load('team');
});