<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Season;
use Illuminate\Support\Facades\View;

class SetSeasonMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. DETECTAR CAMBIO: Si el usuario pincha en el selector (?season_id=X)
        if ($request->has('season_id')) {
            $id = $request->get('season_id');
            // Guardamos en la sesión para recordarlo en las siguientes páginas
            session(['selected_season_id' => $id]);
        }

        // 2. RECUPERAR: Miramos qué ID tenemos guardado (o usamos el de la URL si acabamos de cambiar)
        $seasonId = session('selected_season_id');

        // 3. BUSCAR LA TEMPORADA
        $season = null;
        
        if ($seasonId) {
            $season = Season::find($seasonId);
        }

        // Si no se encuentra (o no hay sesión), buscamos la activa por defecto
        if (!$season) {
            $season = Season::where('is_active', true)->first();
            // La guardamos en sesión para que sea consistente
            if ($season) {
                session(['selected_season_id' => $season->id]);
            }
        }

        // Si la base de datos está vacía, seguimos sin romper nada
        if (!$season) return $next($request);

        // 4. COMPARTIR GLOBALMENTE
        app()->instance('currentSeason', $season);
        
        View::share('currentSeason', $season);
        View::share('allSeasons', Season::orderBy('name', 'desc')->get());

        return $next($request);
    }
}