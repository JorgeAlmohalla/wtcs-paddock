<?php

namespace App\Observers;

use App\Models\RaceResult;

class RaceResultObserver
{
    // Sistema de Puntuación
    protected array $pointsSystem = [
        1 => 15,
        2 => 12,
        3 => 10,
        4 => 8,
        5 => 6,
        6 => 5,
        7 => 4,
        8 => 3,
        9 => 2,
        10 => 1,
    ];

    public function saving(RaceResult $raceResult): void
    {
        // 1. Si es DNF (No terminó), 0 puntos.
        if ($raceResult->dnf) {
            $raceResult->points = 0;
            return;
        }

        // 2. Buscar puntos por posición
        $points = $this->pointsSystem[$raceResult->position] ?? 0; // Si queda el 11º o más, 0 puntos

        // 3. Puntos extra por Vuelta Rápida (+1)
        if ($raceResult->fastest_lap) {
            $points += 1;
        }

        // 4. Guardar el cálculo
        $raceResult->points = $points;
    }
}