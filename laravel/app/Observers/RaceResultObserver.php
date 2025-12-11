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
        // CARGAR RELACIÓN SI NO ESTÁ
        if (!$raceResult->relationLoaded('race')) {
            $raceResult->load('race');
        }

        // 2. Si no terminó (DNF...), 0 puntos.
        if (in_array($raceResult->status, ['dnf', 'dns', 'dsq'])) {
            $raceResult->points = 0;
            return;
        }

        // 3. Calcular puntos normales
        $points = $this->pointsSystem[$raceResult->position] ?? 0;

        if ($raceResult->fastest_lap) {
            $points += 1;
        }

        $raceResult->points = $points;
    }
}