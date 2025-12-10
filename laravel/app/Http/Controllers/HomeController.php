<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Race;
use App\Models\RaceResult;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        // 1. Próxima Carrera
        $nextRace = Race::where('status', 'scheduled')
            ->where('race_date', '>=', now())
            ->orderBy('race_date', 'asc')
            ->with('track')
            ->first();

        // 2. Líder del Mundial
        $leaderData = RaceResult::selectRaw('user_id, sum(points) as total_points')
            ->groupBy('user_id')
            ->orderByDesc('total_points')
            ->with('driver.team')
            ->first();

        // 3. Última Noticia
        $latestPost = Post::orderBy('published_at', 'desc')->first();

        // 4. PASAR DATOS A LA VISTA (Aquí estaba el fallo seguramente)
        return view('welcome', [
            'nextRace' => $nextRace,
            'leader' => $leaderData ? $leaderData->driver : null,
            'leaderPoints' => $leaderData ? $leaderData->total_points : 0,
            'latestPost' => $latestPost, // <--- ESTA LÍNEA ES VITAL
        ]);
    }
}