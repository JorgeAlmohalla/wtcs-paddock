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
use App\Models\Season;


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

// Endpoint: Detalle de una Ronda (Qualy + Sprint + Feature)
Route::get('/rounds/{round}', function ($roundNumber) {
    $seasonId = \App\Models\Season::where('is_active', true)->value('id');

    // 1. Buscar las carreras de esa ronda
    $sessions = \App\Models\Race::where('season_id', $seasonId)
        ->where('round_number', $roundNumber)
        ->with(['track', 'results.driver', 'results.team', 'qualifyingResults.driver', 'qualifyingResults.team'])
        ->orderBy('race_date', 'asc')
        ->get();

    if ($sessions->isEmpty()) {
        return response()->json(['message' => 'Round not found'], 404);
    }

    // 2. Identificar Sesiones (Misma lógica que en Web)
    $sprint = $sessions->first(fn($r) => str_contains(strtolower($r->title ?? ''), 'sprint')) ?? $sessions->first();
    $feature = $sessions->first(fn($r) => str_contains(strtolower($r->title ?? ''), 'feature')) ?? $sessions->last();
    
    // Evitar duplicados si solo hay una carrera
    if ($sessions->count() === 1) $feature = null;
    elseif ($sprint->id === $feature->id) $feature = $sessions->last();

    // 3. Helper para formatear resultados de carrera
    $formatResults = function ($race) {
        if (!$race) return null;
        return [
            'id' => $race->id,
            'title' => $race->title,
            'date' => $race->race_date->format('d M Y - H:i'),
            'status' => $race->status,
            'results' => $race->results->sortBy('position')->values()->map(fn($r) => [
                'pos' => $r->position,
                'driver' => $r->driver->name,
                'driver_number' => $r->driver_number ?? $r->driver->driver_number,
                'team' => $r->team->name ?? 'Privateer',
                'team_color' => $r->team->primary_color ?? '#666',
                'time' => $r->status === 'finished' ? $r->race_time : strtoupper($r->status),
                'points' => (int)$r->points,
                'fastest_lap' => (bool)$r->fastest_lap,
                'car' => $r->car_name ?? $r->team->car_model,
            ])
        ];
    };

    // 4. Helper para formatear Qualy
    $qualyData = $sprint->qualifyingResults->sortBy('position')->values()->map(fn($q) => [
        'pos' => $q->position,
        'driver' => $q->driver->name,
        'team' => $q->team->name ?? 'Privateer',
        'team_color' => $q->team->primary_color ?? '#666',
        'time' => $q->best_time,
        'tyre' => ucfirst($q->tyre_compound ?? '-'),
    ]);

    // 5. Respuesta JSON Estructurada
    return response()->json([
        'round_number' => (int)$roundNumber,
        'track' => [
            'name' => $sprint->track->name,
            'country' => $sprint->track->country_code,
            'image' => $sprint->track->layout_image_url ? asset('storage/'.$sprint->track->layout_image_url) : null,
        ],
        'sprint_race' => $formatResults($sprint),
        'feature_race' => $formatResults($feature),
        'qualifying' => $qualyData,
    ]);
});

Route::get('/drivers/{id}', function ($id) {
    $seasonId = \App\Models\Season::where('is_active', true)->value('id');
    $user = \App\Models\User::with('team')->findOrFail($id);

    // 1. Stats Generales
    $stats = [
        'starts' => $user->raceResults()->whereHas('race', fn($q) => $q->where('season_id', $seasonId))->count(),
        'wins' => $user->raceResults()->whereHas('race', fn($q) => $q->where('season_id', $seasonId))->where('position', 1)->count(),
        'points' => (int) $user->raceResults()->whereHas('race', fn($q) => $q->where('season_id', $seasonId))->sum('points'),
    ];

    // 2. CORRECCIÓN: Obtener Clasificaciones mapeadas por Ronda
    $qualyResults = \App\Models\QualifyingResult::where('user_id', $id)
        ->with('race') // Cargamos la carrera para leer el round_number
        ->whereHas('race', fn($q) => $q->where('season_id', $seasonId))
        ->get()
        ->mapWithKeys(function ($item) {
            // Usamos el round_number de la RELACIÓN 'race', no de la tabla de resultados
            return [$item->race->round_number => $item];
        });

    // 3. Obtener resultados de carrera
    $races = \App\Models\Race::where('season_id', $seasonId)
        ->with(['track', 'results' => fn($q) => $q->where('user_id', $id)])
        ->orderBy('round_number')
        ->orderBy('id') // Para que salga primero Sprint y luego Feature
        ->get();

    $history = [];
    $runningPoints = 0;

    foreach ($races as $race) {
        $raceResult = $race->results->first();
        
        // Buscamos la qualy de ESTA ronda en el mapa que creamos antes
        $qualyResult = $qualyResults[$race->round_number] ?? null;

        if (!$raceResult && !$qualyResult) continue;

        $runningPoints += ($raceResult ? $raceResult->points : 0);

$history[] = [
            'round_number' => $race->round_number,
            'round_name'   => $race->track->name,
            'race_type'    => str_contains(strtolower($race->title), 'sprint') ? 'Sprint' : 'Feature',
            'race_pos'     => $raceResult ? $raceResult->position : 0,
            'qualy_pos'    => $qualyResult ? $qualyResult->position : 0,
            
            // --- CORRECCIÓN FINAL (Usando los nombres reales) ---
            'qualy_time'   => $qualyResult ? $qualyResult->best_time : '-', 
            'qualy_tyre'   => $qualyResult ? $qualyResult->tyre_compound : '-',
            // ----------------------------------------------------
            
            'points_after_round' => $runningPoints
        ];
    }

    return response()->json([
        'driver' => [
            'name' => $user->name,
            'number' => $user->driver_number,
            'nationality' => $user->nationality,
            'team' => $user->team ? $user->team->name : 'Free Agent',
            'team_color' => $user->team->primary_color ?? '#666',
            'avatar' => $user->avatar_url ? asset('storage/' . $user->avatar_url) : null,
            'equipment' => $user->equipment,
        ],
        'stats' => $stats,
        'history' => $history
    ]);
});

// Endpoint: Detalle de Equipo
Route::get('/teams/{id}', function ($id) {
    $team = \App\Models\Team::with('drivers')->findOrFail($id);

    return response()->json([
        'name' => $team->name,
        'car' => $team->car_model,
        'manufacturer' => $team->car_brand,
        'color' => $team->primary_color,
        'image' => $team->car_image_url ? asset('storage/'.$team->car_image_url) : null,
        'drivers' => $team->drivers->map(fn($d) => [
            'id' => $d->id,
            'name' => $d->name,
            'role' => $d->isTeamPrincipal() ? 'Principal' : 'Driver'
        ])
    ]);
});

// Endpoint Lista de equipos
Route::get('/standings/teams', function () {
    $activeSeasonId = \App\Models\Season::where('is_active', true)->value('id');

    $teams = \App\Models\Team::get()
        ->map(function ($team) use ($activeSeasonId) {
            // Calcular puntos (Carrera + Qualy)
            $racePoints = \App\Models\RaceResult::where('team_id', $team->id)
                ->whereHas('race', fn($q) => $q->where('season_id', $activeSeasonId))->sum('points');
            
            $qualyPoints = \App\Models\QualifyingResult::where('team_id', $team->id)
                ->whereHas('race', fn($q) => $q->where('season_id', $activeSeasonId))->sum('points');

            return [
                'id' => $team->id,
                'name' => $team->name,
                'car' => $team->car_model,
                'type' => $team->type, // <--- ESTO ES LO QUE NECESITAS ('works' o 'privateer')
                'color' => $team->primary_color ?? '#666',
                'points' => (int) ($racePoints + $qualyPoints),
            ];
        })
        ->filter(fn ($t) => $t['points'] > 0)
        ->sortByDesc('points')
        ->values();

    return response()->json($teams);
});

// Enpoint manufacturers
Route::get('/standings/manufacturers', function () {
    $activeSeasonId = \App\Models\Season::where('is_active', true)->value('id');
    $seasonRaces = \App\Models\Race::where('season_id', $activeSeasonId)->pluck('id');

    $manufacturers = \App\Models\Team::select('car_brand')->distinct()->get()
        ->map(function ($brandEntry) use ($seasonRaces) {
            $brand = $brandEntry->car_brand;
            $brandTeams = \App\Models\Team::where('car_brand', $brand)->pluck('id');

            $totalPoints = 0;
            foreach ($seasonRaces as $raceId) {
                // Sumar el mejor resultado de carrera
                $best = \App\Models\RaceResult::where('race_id', $raceId)
                    ->whereIn('team_id', $brandTeams)->max('points');
                if ($best) $totalPoints += $best;
            }

            return [
                'name' => $brand,
                'points' => (int) $totalPoints,
                'team_count' => $brandTeams->count(),
                // Usamos el color del primer equipo de la marca
                'color' => \App\Models\Team::where('car_brand', $brand)->first()->primary_color ?? '#666',
            ];
        })
        ->filter(fn ($m) => $m['points'] > 0)
        ->sortByDesc('points')
        ->values();

    return response()->json($manufacturers);
});

use App\Models\Post;

// --- ENDPOINTS PÚBLICOS ---

// 1. LISTA DE PILOTOS (Grid)
Route::get('/drivers', function () {
    return \App\Models\User::whereJsonContains('roles', 'driver')
        ->with('team:id,name,primary_color')
        ->orderBy('name')
        ->get()
        ->map(function ($driver) {
            return [
                'id' => $driver->id,
                'name' => $driver->name,
                'number' => $driver->driver_number,
                'nationality' => $driver->nationality,
                'team_name' => $driver->team->name ?? 'Free Agent',
                'team_color' => $driver->team->primary_color ?? '#666',
                'avatar' => $driver->avatar_url ? asset('storage/'.$driver->avatar_url) : null,
            ];
        });
});

// 2. LISTA DE EQUIPOS
Route::get('/teams', function () {
    return \App\Models\Team::orderBy('name')->get()->map(function ($team) {
        return [
            'id' => $team->id,
            'name' => $team->name,
            'logo' => $team->logo_url ? asset('storage/'.$team->logo_url) : null,
            'color' => $team->primary_color,
            'car' => $team->car_brand . ' ' . $team->car_model,
        ];
    });
});

// 3. DETALLE DE EQUIPO (Para ver pilotos y coche)
Route::get('/teams/{id}', function ($id) {
    $team = \App\Models\Team::with('drivers')->findOrFail($id);
    
    return [
        'id' => $team->id,
        'name' => $team->name,
        'car_image' => $team->car_image_url ? asset('storage/'.$team->car_image_url) : null,
        'specs' => [
            'power' => $team->tech_power,
            'weight' => $team->tech_weight,
        ],
        'drivers' => $team->drivers->map(fn($d) => [
            'id' => $d->id,
            'name' => $d->name,
            'avatar' => $d->avatar_url ? asset('storage/'.$d->avatar_url) : null,
        ])
    ];
});

// 4. NOTICIAS (Lista)
Route::get('/news', function () {
    return Post::orderBy('published_at', 'desc')->take(10)->get()->map(function ($post) {
        return [
            'id' => $post->id,
            'title' => $post->title,
            'date' => $post->published_at->format('d M Y'),
            'image' => $post->image_url ? asset('storage/'.$post->image_url) : null,
            // Enviamos un extracto del texto para la lista
            'excerpt' => \Illuminate\Support\Str::limit(strip_tags($post->content), 100),
        ];
    });
});

// 5. NOTICIA DETALLE (Para leerla entera)
Route::get('/news/{id}', function ($id) {
    $post = Post::findOrFail($id);
    return [
        'title' => $post->title,
        'date' => $post->published_at->format('d M Y'),
        'image' => $post->image_url ? asset('storage/'.$post->image_url) : null,
        'content' => $post->content, // HTML completo para renderizar en WebView o limpiar
    ];
});


