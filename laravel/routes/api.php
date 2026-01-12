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

// 2. CALENDARIO
Route::get('/calendar', function () {
    return Cache::remember('api_calendar', 60 * 60, function () {
        $seasonId = Season::where('is_active', true)->value('id');
    
        $races = Race::where('season_id', $seasonId)
            ->with(['track', 'results.driver'])
            ->orderBy('round_number')
            ->orderBy('race_date')
            ->get();

        return RaceResource::collection($races);
    });
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

// 8. DETALLE DE PILOTO (Gráficos)
Route::get('/drivers/{id}', function ($id) {
    $seasonId = Season::where('is_active', true)->value('id');
    $user = User::with('team')->findOrFail($id);

    $stats = [
        'starts' => $user->raceResults()->whereHas('race', fn($q) => $q->where('season_id', $seasonId))->count(),
        'wins' => $user->raceResults()->whereHas('race', fn($q) => $q->where('season_id', $seasonId))->where('position', 1)->count(),
        'points' => (int) $user->raceResults()->whereHas('race', fn($q) => $q->where('season_id', $seasonId))->sum('points'),
    ];

    $qualyResults = QualifyingResult::where('user_id', $id)
        ->with('race')
        ->whereHas('race', fn($q) => $q->where('season_id', $seasonId))
        ->get()
        ->mapWithKeys(function ($item) {
            return [$item->race->round_number => $item];
        });

    $races = Race::where('season_id', $seasonId)
        ->with(['track', 'results' => fn($q) => $q->where('user_id', $id)])
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
            'qualy_time'   => $qualyResult ? $qualyResult->best_time : '-', 
            'qualy_tyre'   => $qualyResult ? $qualyResult->tyre_compound : '-',
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

// 11. DETALLE DE EQUIPO (Completo con Stats y Specs)
Route::get('/teams/{id}', function ($id) {
    $seasonId = \App\Models\Season::where('is_active', true)->value('id');
    $team = \App\Models\Team::with('drivers')->findOrFail($id);

    // Calcular Stats
    $stats = [
        'active_drivers' => $team->drivers->count(),
        'wins' => \App\Models\RaceResult::where('team_id', $id)
            ->whereHas('race', fn($q) => $q->where('season_id', $seasonId))
            ->where('position', 1)->count(),
        'podiums' => \App\Models\RaceResult::where('team_id', $id)
            ->whereHas('race', fn($q) => $q->where('season_id', $seasonId))
            ->where('position', '<=', 3)->count(),
        'total_points' => (int) (
            \App\Models\RaceResult::where('team_id', $id)->whereHas('race', fn($q) => $q->where('season_id', $seasonId))->sum('points') +
            \App\Models\QualifyingResult::where('team_id', $id)->whereHas('race', fn($q) => $q->where('season_id', $seasonId))->sum('points')
        )
    ];

    return response()->json([
        'id' => $team->id,
        'name' => $team->name,
        'color' => $team->primary_color ?? '#666666',
        'car_model' => $team->car_model,
        'type' => $team->type, // works/privateer
        'livery_image' => $team->car_image_url ? asset('storage/'.$team->car_image_url) : null,
        
        'stats' => $stats,
        
        'specs' => [
            'chassis' => 'Unitary steel, built by Prodrive/Matter', // Hardcoded o de DB
            'engine' => 'Cosworth 2.0L V6 N/A',
            'power' => $team->tech_power . 'HP',
            'weight' => $team->tech_weight . 'kg',
            'gearbox' => 'XTrac 6-speed sequential'
        ],
        
        'roster' => $team->drivers->map(fn($d) => [
            'id' => $d->id,
            'name' => $d->name,
            'nationality' => $d->nationality,
            'role' => $d->pivot ? $d->pivot->role : 'Driver', // Ajustar según tu relación DB
            'avatar' => $d->avatar_url ? asset('storage/'.$d->avatar_url) : null,
        ])
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