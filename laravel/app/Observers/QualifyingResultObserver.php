<?php

namespace App\Observers;

use App\Models\QualifyingResult;

class QualifyingResultObserver
{
    public function saving(QualifyingResult $result): void
    {
        // Copiar nombre del coche si no existe
        if ($result->team_id && empty($result->car_name)) {
            $team = \App\Models\Team::find($result->team_id);
            if ($team) {
                $result->car_name = $team->car_model;
            }
        }

        if ($result->position == 1) {
            $result->points = 1; // 1 Punto por Pole
        } else {
            $result->points = 0;
        }
    }
}