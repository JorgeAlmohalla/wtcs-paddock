<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use App\Models\User;
use App\Models\Race;
use App\Models\RaceResult;
use App\Models\QualifyingResult;
use App\Models\Team;
use App\Models\Season;
use App\Models\Post;
use App\Http\Resources\RaceResource;
use Intervention\Image\Facades\Image;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// 1. LOGIN
Route::post('/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Credenciales incorrectas'], 401);
    }

    $user->tokens()->delete();
    $token = $user->createToken('android-app')->plainTextToken;

    return response()->json([
        'token' => $token,
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'team' => $user->team ? $user->team->name : 'Free Agent',
            'avatar_url' => $user->avatar_url ? asset('storage/' . $user->avatar_url) : null,
        ]
    ]);
});

// 2. CALENDARIO (SIN Caché para desarrollo)
Route::get('/calendar', function () {
    // Quitamos el Cache::remember y ejecutamos directo
    
    $seasonId = \App\Models\Season::where('is_active', true)->value('id');
    
    // Si no hay temporada activa, cogemos la última para que no de error
    if (!$seasonId) {
        $seasonId = \App\Models\Season::max('id');
    }

    $races = \App\Models\Race::where('season_id', $seasonId)
        ->with(['track', 'results.driver'])
        ->orderBy('round_number')
        ->orderBy('race_date')
        ->get();

    return \App\Http\Resources\RaceResource::collection($races);
});

// 3. STANDINGS: DRIVERS
Route::get('/standings', function () {
    return Cache::remember('api_standings_drivers', 60 * 5, function () {
        $seasonId = Season::where('is_active', true)->value('id');

        $drivers = User::whereJsonContains('roles', 'driver')
            ->with('team')
            ->get()
            ->map(function ($driver) use ($seasonId) {
                $p1 = RaceResult::where('user_id', $driver->id)
                    ->whereHas('race', fn($q) => $q->where('season_id', $seasonId))->sum('points');
                $p2 = QualifyingResult::where('user_id', $driver->id)
                    ->whereHas('race', fn($q) => $q->where('season_id', $seasonId))->sum('points');
                
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
            ->filter(fn ($d) => $d['points'] > 0)
            ->sortByDesc('points')
            ->values();

        return response()->json($drivers);
    });
});

// 4. STANDINGS: TEAMS
Route::get('/standings/teams', function () {
    $seasonId = Season::where('is_active', true)->value('id');

    $teams = Team::get()
        ->map(function ($team) use ($seasonId) {
            $racePoints = RaceResult::where('team_id', $team->id)
                ->whereHas('race', fn($q) => $q->where('season_id', $seasonId))->sum('points');
            
            $qualyPoints = QualifyingResult::where('team_id', $team->id)
                ->whereHas('race', fn($q) => $q->where('season_id', $seasonId))->sum('points');

            return [
                'id' => $team->id,
                'name' => $team->name,
                'car' => $team->car_model,
                'type' => $team->type, 
                'color' => $team->primary_color ?? '#666666',
                'points' => (int) ($racePoints + $qualyPoints),
            ];
        })
        ->filter(fn ($t) => $t['points'] > 0)
        ->sortByDesc('points')
        ->values();

    return response()->json($teams);
});

// 5. STANDINGS: MANUFACTURERS
Route::get('/standings/manufacturers', function () {
    $seasonId = Season::where('is_active', true)->value('id');
    $seasonRaces = Race::where('season_id', $seasonId)->pluck('id');

    $manufacturers = Team::select('car_brand')->distinct()->get()
        ->map(function ($brandEntry) use ($seasonRaces) {
            $brand = $brandEntry->car_brand;
            $brandTeams = Team::where('car_brand', $brand)->pluck('id');

            $totalPoints = 0;
            foreach ($seasonRaces as $raceId) {
                $best = RaceResult::where('race_id', $raceId)
                    ->whereIn('team_id', $brandTeams)->max('points');
                if ($best) $totalPoints += $best;
            }

            return [
                'name' => $brand,
                'points' => (int) $totalPoints,
                'team_count' => $brandTeams->count(),
                'color' => Team::where('car_brand', $brand)->first()->primary_color ?? '#666666',
            ];
        })
        ->filter(fn ($m) => $m['points'] > 0)
        ->sortByDesc('points')
        ->values();

    return response()->json($manufacturers);
});

// 6. PERFIL USUARIO (PROTEGIDO)
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user()->load('team');
});

// 7. DETALLE DE RONDA (Race Center)
Route::get('/rounds/{round}', function ($roundNumber) {
    $seasonId = Season::where('is_active', true)->value('id');

    $sessions = Race::where('season_id', $seasonId)
        ->where('round_number', $roundNumber)
        ->with(['track', 'results.driver', 'results.team', 'qualifyingResults.driver', 'qualifyingResults.team'])
        ->orderBy('race_date', 'asc')
        ->get();

    if ($sessions->isEmpty()) {
        return response()->json(['message' => 'Round not found'], 404);
    }

    $sprint = $sessions->first(fn($r) => str_contains(strtolower($r->title ?? ''), 'sprint')) ?? $sessions->first();
    $feature = $sessions->first(fn($r) => str_contains(strtolower($r->title ?? ''), 'feature')) ?? $sessions->last();
    
    if ($sessions->count() === 1) $feature = null;
    elseif ($sprint->id === $feature->id) $feature = $sessions->last();

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
                'driver_id' => $r->user_id,
                'team' => $r->team->name ?? 'Privateer',
                'team_color' => $r->team->primary_color ?? '#666',
                'time' => $r->status === 'finished' ? $r->race_time : strtoupper($r->status),
                'points' => (int)$r->points,
                'fastest_lap' => (bool)$r->fastest_lap,
                'car' => $r->car_name ?? $r->team->car_model,
                'team_type' => $r->team->type, // Para saber si es 'privateer'
                'penalty' => $r->penalty_seconds > 0 ? "+{$r->penalty_seconds}s pen" : null, // Ajusta 'penalty_seconds' al nombre real de tu columna
            ])
        ];
    };

    $qualyData = $sprint->qualifyingResults->sortBy('position')->values()->map(fn($q) => [
        'pos' => $q->position,
        'driver' => $q->driver->name,
        'driver_id' => $q->user_id,
        'team' => $q->team->name ?? 'Privateer',
        'team_color' => $q->team->primary_color ?? '#666',
        'time' => $q->best_time,
        'tyre' => ucfirst($q->tyre_compound ?? '-'),
        'team_type' => $q->team->type,
    ]);

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

    // 8. DETALLE DE PILOTO (Gráficos + Historial CORREGIDO)
    Route::get('/drivers/{id}', function ($id) {
    $seasonId = \App\Models\Season::where('is_active', true)->value('id');
    $user = \App\Models\User::with('team')->findOrFail($id);

    $stats = [
        // Starts: Carreras donde ha participado
        'starts' => $user->raceResults()
            ->whereHas('race', fn($q) => $q->where('season_id', $seasonId))
            ->count(),

        // Wins: Posición exacta 1
        'wins' => $user->raceResults()
            ->whereHas('race', fn($q) => $q->where('season_id', $seasonId))
            ->where('position', 1)
            ->count(),

        // PODIOS: Posición menor o igual a 3 (CORREGIDO)
        'podiums' => $user->raceResults()
            ->whereHas('race', fn($q) => $q->where('season_id', $seasonId))
            ->where('position', '<=', 3) // <--- ESTO ES LA CLAVE
            ->count(),

        // Poles: Posición 1 en Clasificación
        'poles' => \App\Models\QualifyingResult::where('user_id', $id)
            ->whereHas('race', fn($q) => $q->where('season_id', $seasonId))
            ->where('position', 1)
            ->count(),

        // Puntos totales
        'points' => (int) $user->raceResults()
            ->whereHas('race', fn($q) => $q->where('season_id', $seasonId))
            ->sum('points'),
    ];

    // Historial para gráficas
    // CORRECCIÓN: Usamos 'user_id' en lugar de 'driver_id'
    $qualyResults = \App\Models\QualifyingResult::where('user_id', $id)
        ->with('race')
        ->whereHas('race', fn($q) => $q->where('season_id', $seasonId))
        ->get()
        ->mapWithKeys(function ($item) {
            return [$item->race->round_number => $item];
        });

    $races = \App\Models\Race::where('season_id', $seasonId)
        ->with([
            'track',
            // CORRECCIÓN: Usamos 'user_id' aquí también
            'results' => fn($q) => $q->where('user_id', $id) 
        ])
        ->orderBy('round_number')
        ->orderBy('id')
        ->get();

    $history = [];
    $runningPoints = 0;

    foreach ($races as $race) {
        $raceResult = $race->results->first();
        $qualyResult = $qualyResults[$race->round_number] ?? null;

        if (!$raceResult && !$qualyResult) continue;

        $runningPoints += ($raceResult ? $raceResult->points : 0);

        $history[] = [
            'round_number' => $race->round_number,
            'round_name'   => $race->track->name,
            'race_type'    => str_contains(strtolower($race->title), 'sprint') ? 'Sprint' : 'Feature',
            'race_pos'     => $raceResult ? $raceResult->position : 0,
            'qualy_pos'    => $qualyResult ? $qualyResult->position : 0,
            
            // Usamos nombres seguros por si acaso (best_time / time)
            'qualy_time'   => $qualyResult ? ($qualyResult->best_time ?? $qualyResult->time) : '-', 
            'qualy_tyre'   => $qualyResult ? ($qualyResult->tyre_compound ?? $qualyResult->tyre) : '-',
            
            'points_after_round' => $runningPoints
        ];  
    }

    return response()->json([
        'driver' => [
            'name' => $user->name,
            'number' => $user->driver_number,
            'nationality' => $user->nationality,
            'team' => $user->team ? $user->team->name : 'Free Agent',
            'team_color' => $user->team->primary_color ?? '#666666',
            'avatar' => $user->avatar_url ? asset('storage/' . $user->avatar_url) : null,
            'equipment' => $user->equipment,
            'bio' => $user->bio, // Añadido para que se vea en el perfil
        ],
        'stats' => $stats,
        'history' => $history
    ]);
});

// 9. LISTA DE PILOTOS (Para el buscador)
Route::get('/drivers', function () {
    return User::whereJsonContains('roles', 'driver')
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

// 10. LISTA DE EQUIPOS (Para la pantalla Teams que haremos luego)
Route::get('/teams', function () {
    return Team::orderBy('name')->get()->map(function ($team) {
        return [
            'id' => $team->id,
            'name' => $team->name,
            'logo' => $team->logo_url ? asset('storage/'.$team->logo_url) : null,
            'color' => $team->primary_color,
            'car' => $team->car_brand . ' ' . $team->car_model,
        ];
    });
});

// 11. DETALLE DE EQUIPO (Lógica integrada para evitar error de redelcaring)
Route::get('/teams/{id}', function ($id) {
    $seasonId = \App\Models\Season::where('is_active', true)->value('id');
    
    // Cargamos equipo y pilotos
    $team = \App\Models\Team::with('drivers')->findOrFail($id);

    // Stats
    $racePoints = \App\Models\RaceResult::where('team_id', $id)
        ->whereHas('race', fn($q) => $q->where('season_id', $seasonId))->sum('points');
    $qualyPoints = \App\Models\QualifyingResult::where('team_id', $id)
        ->whereHas('race', fn($q) => $q->where('season_id', $seasonId))->sum('points');

    $stats = [
        'active_drivers' => $team->drivers->count(),
        'wins' => \App\Models\RaceResult::where('team_id', $id)
            ->whereHas('race', fn($q) => $q->where('season_id', $seasonId))
            ->where('position', 1)->count(),
        'podiums' => \App\Models\RaceResult::where('team_id', $id)
            ->whereHas('race', fn($q) => $q->where('season_id', $seasonId))
            ->where('position', '<=', 3)->count(),
        'total_points' => (int) ($racePoints + $qualyPoints)
    ];

    return response()->json([
        'id' => $team->id,
        'name' => $team->name,
        'color' => $team->primary_color ?? '#666666',
        'car_model' => $team->car_model,
        'type' => $team->type, 
        'livery_image' => $team->car_image_url ? asset('storage/'.$team->car_image_url) : null,
        'stats' => $stats,
        'bio' => $team->bio,
        'logo' => $team->logo_url ? asset('storage/'.$team->logo_url) : null,
        
        'specs' => [
            'chassis' => $team->tech_chassis ?? 'N/A', 
            'engine'  => $team->tech_engine ?? 'N/A',
            'power'   => $team->tech_power ?? 'N/A', 
            'layout'  => $team->tech_drivetrain ?? 'N/A', 
            'gearbox' => $team->tech_gearbox ?? 'N/A'
        ],
        
        // --- AQUÍ ESTÁ LA LÓGICA INTEGRADA (Sin función externa) ---
        'roster' => $team->drivers->map(function ($d) {
            $finalRole = 'Primary'; // Valor por defecto

            // 1. INTENTO A: Columna 'role' (String simple)
            if (!empty($d->role) && is_string($d->role)) {
                if (stripos($d->role, 'Principal') !== false) {
                    $finalRole = 'Team Principal';
                }
            }

            // 2. INTENTO B: Columna 'roles' (JSON o Array)
            if ($finalRole === 'Primary' && !empty($d->roles)) {
                $rolesData = $d->roles;
                if (is_string($rolesData)) $rolesData = json_decode($rolesData, true);

                if (is_array($rolesData)) {
                    if (in_array('Team Principal', $rolesData)) {
                        $finalRole = 'Team Principal';
                    } else {
                        foreach ($rolesData as $r) {
                            if (stripos($r, 'Principal') !== false) {
                                $finalRole = 'Team Principal';
                                break;
                            }
                        }
                    }
                }
            }

            // 3. INTENTO C: Chequeo de contrato (Reserve)
            if ($finalRole !== 'Team Principal' && ($d->contract_type ?? '') === 'reserve') {
                $finalRole = 'Reserve';
            }

            return [
                'id' => $d->id,
                'name' => $d->name,
                'nationality' => $d->nationality,
                'role' => $finalRole,
                'avatar' => $d->avatar_url ? asset('storage/'.$d->avatar_url) : null,
            ];
        })
    ]);
});



// 12. NOTICIAS LISTA
Route::get('/news', function () {
    return Post::orderBy('published_at', 'desc')->take(10)->get()->map(function ($post) {
        return [
            'id' => $post->id,
            'title' => $post->title,
            'date' => $post->published_at->format('d M Y'),
            'image' => $post->image_url ? asset('storage/'.$post->image_url) : null,
            'excerpt' => \Illuminate\Support\Str::limit(strip_tags($post->content), 100),
        ];
    });
});

// 13. NOTICIA DETALLE
Route::get('/news/{id}', function ($id) {
    $post = Post::findOrFail($id);
    return [
        'title' => $post->title,
        'date' => $post->published_at->format('d M Y'),
        'image' => $post->image_url ? asset('storage/'.$post->image_url) : null,
        'content' => $post->content,
    ];
});

// --- ENDPOINTS PRIVADOS (Requieren Token) ---
Route::middleware('auth:sanctum')->group(function () {

// ACTUALIZAR PERFIL COMPLETO (Con Foto)
    Route::post('/user/update-profile', function (Illuminate\Http\Request $request) {
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'steam_id' => 'nullable|string',
            'nationality' => 'nullable|string|size:2',
            'equipment' => 'nullable|string', // wheel, pad, keyboard
            'bio' => 'nullable|string',
            'driver_number' => 'nullable|integer',
            'avatar' => 'nullable|image|max:2048', // Max 2MB
        ]);

        // Actualizar textos
        $user->fill($request->except(['avatar', 'password'])); // Ignoramos password y avatar aquí

        // Subir Avatar si viene uno nuevo
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = uniqid() . '.webp';
            
            // Redimensionar a 400x400, convertir a WebP, calidad 80%
            $image = Image::read($file)
                ->resize(400, 400, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })
                ->encode(new \Intervention\Image\Encoders\WebpEncoder(quality: 80));

            // Guardar manualmente en Storage
            \Illuminate\Support\Facades\Storage::disk('public')->put('avatars/' . $filename, (string) $image);
            
            $user->avatar_url = 'avatars/' . $filename;
        }

        $user->save();

        return response()->json(['message' => 'Profile updated successfully', 'user' => $user]);
    });

    // CAMBIAR CONTRASEÑA
    Route::post('/user/change-password', function (Illuminate\Http\Request $request) {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|confirmed|min:8',
        ]);

        $request->user()->update([
            'password' => Illuminate\Support\Facades\Hash::make($request->password),
        ]);

        return response()->json(['message' => 'Password updated']);
    });

    // REPORTAR INCIDENTE
    Route::post('/incidents', function (Illuminate\Http\Request $request) {
        // Validación básica
        $request->validate([
            'race_id' => 'required|exists:races,id',
            'accused_driver_id' => 'required|exists:users,id',
            'lap' => 'required|integer',
            'description' => 'required|string',
            'video_url' => 'nullable|url'
        ]);

 \App\Models\IncidentReport::create([
        'reporter_id' => $request->user()->id,
        'reported_id' => $request->accused_driver_id, // <--- CORREGIDO (reported_id)
        'race_id' => $request->race_id,
        'lap' => $request->lap,
        'description' => $request->description,
        'video_url' => $request->video_url,
        'status' => 'pending'
    ]);

        return response()->json(['message' => 'Report submitted successfully']);
    });

    // HISTORIAL DE REPORTES
    Route::get('/user/reports', function (Illuminate\Http\Request $request) {
        $user = $request->user();
        // Asumiendo modelo IncidentReport
        // Obtenemos reportes donde soy el que reporta O el acusado
        $reports = \App\Models\IncidentReport::with(['race.track', 'accused', 'reporter'])
            ->where('reporter_id', $user->id)
            ->orWhere('accused_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($r) use ($user) {
                return [
                    'id' => $r->id,
                    'status' => strtoupper($r->status), // PENDING, RESOLVED
                    'race_name' => $r->race->track->name ?? 'Unknown',
                    'role' => ($r->reporter_id == $user->id) ? 'You Reported' : 'You were Reported',
                    'involved_name' => ($r->reporter_id == $user->id) ? $r->accused->name : $r->reporter->name,
                    'decision' => $r->penalty ?? 'Under Review'
                ];
            });
            
        return response()->json($reports);
    });

    // DATOS PARA EL FORMULARIO (Races y Drivers)
    Route::get('/form-data', function () {
        $seasonId = \App\Models\Season::where('is_active', true)->value('id');
        
        $races = \App\Models\Race::where('season_id', $seasonId)
            ->with('track')
            ->orderBy('round_number')
            ->get()
            ->map(fn($r) => ['id' => $r->id, 'name' => "R" . $r->round_number . " - " . $r->track->name]);

        $drivers = \App\Models\User::whereJsonContains('roles', 'driver')
            ->orderBy('name')
            ->get()
            ->map(fn($d) => ['id' => $d->id, 'name' => $d->name]);

        return ['races' => $races, 'drivers' => $drivers];
    });

// --- GESTIÓN DE INCIDENTES Y REPORTES ---

// 14. HISTORIAL DE REPORTES (CORREGIDO)
    Route::middleware('auth:sanctum')->get('/user/reports', function (Illuminate\Http\Request $request) {
        $user = $request->user();
        
        $reports = \App\Models\IncidentReport::with(['race.track', 'accused', 'reporter'])
            ->where('reporter_id', $user->id)
            ->orWhere('reported_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($r) use ($user) {
                // Seguridad con optional chaining
                $raceName = $r->race?->track?->name ?? 'Unknown Race';
                $accusedName = $r->accused?->name ?? 'Unknown Driver';
                $reporterName = $r->reporter?->name ?? 'Unknown Driver';

                return [
                    'id' => $r->id,
                    'status' => strtoupper($r->status),
                    'race_name' => $raceName,
                    'role' => ($r->reporter_id == $user->id) ? 'You Reported' : 'You were Reported',
                    'involved_name' => ($r->reporter_id == $user->id) ? $accusedName : $reporterName,
                    'steward_notes' => $r->steward_notes,
                    
                    // --- CORRECCIÓN AQUÍ ---
                    'decision' => $r->penalty_applied ?? 'Under Review', 
                    // -----------------------

                    'lap' => $r->lap_corner,
                    'description' => $r->description,
                    'video_url' => $r->video_url,
                    'created_at' => $r->created_at->format('d M Y')
                ];
            });
            
        return response()->json($reports);
    });

    // 15. DATOS PARA EL FORMULARIO (Se mantiene igual)
    Route::get('/form-data', function () {
        $seasonId = \App\Models\Season::where('is_active', true)->value('id');
        
        $races = \App\Models\Race::where('season_id', $seasonId)
            ->with('track')
            ->orderBy('round_number')
            ->get()
            ->map(fn($r) => ['id' => $r->id, 'name' => "R" . $r->round_number . " - " . $r->track->name]);

        $drivers = \App\Models\User::whereJsonContains('roles', 'driver')
            ->orderBy('name')
            ->get()
            ->map(fn($d) => ['id' => $d->id, 'name' => $d->name]);

        return ['races' => $races, 'drivers' => $drivers];
    });

    // 16. ENVIAR REPORTE (Corregido para lap_corner)
    Route::middleware('auth:sanctum')->post('/incidents', function (Illuminate\Http\Request $request) {
        $request->validate([
            'race_id' => 'required',
            'accused_driver_id' => 'required',
            'lap_corner' => 'required|string', // <--- Acepta texto
            'description' => 'required',
        ]);

        \App\Models\IncidentReport::create([
            'reporter_id' => $request->user()->id,
            'reported_id' => $request->accused_driver_id,
            'race_id' => $request->race_id,
            'lap_corner' => $request->lap_corner, // <--- CAMBIO
            'description' => $request->description,
            'video_url' => $request->video_url,
            'status' => 'pending'
        ]);

        return response()->json(['message' => 'Report sent']);
    });
});