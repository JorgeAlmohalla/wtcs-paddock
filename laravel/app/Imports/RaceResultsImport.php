<?php

namespace App\Imports;

use App\Models\RaceResult;
use App\Models\User;
use App\Models\Team;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class RaceResultsImport implements ToModel, WithHeadingRow
{
    protected $raceId;

    public function __construct($raceId)
    {
        $this->raceId = $raceId;
    }

public function model(array $row)
    {
        // 0. Detectar VUELTA RÁPIDA
        $fullRow = implode(' ', $row);
        if (str_contains(strtolower($fullRow), 'fastest lap')) {
            if (preg_match('/-\s*(.*?)\s*-/', $fullRow, $matches)) {
                $fastestDriverName = $matches[1];
                $driver = User::whereRaw('LOWER(name) = ?', [strtolower($fastestDriverName)])->first();
                if ($driver) {
                    RaceResult::where('race_id', $this->raceId)
                        ->where('user_id', $driver->id)
                        ->update(['fastest_lap' => true]);
                }
            }
            return null;
        }

        // 1. Buscar Piloto
        $driverName = $row['driver'];
        $driver = User::whereRaw('LOWER(name) = ?', [strtolower($driverName)])->first();
        if (!$driver) return null;

        // 2. Buscar Equipo
        $team = Team::where('name', 'LIKE', '%' . $row['team'] . '%')->first() 
                ?? $driver->team;

        // 3. Procesar Tiempo y Penalización
        $rawTime = $row['timeretired'];
        $cleanTime = strtolower(trim($rawTime));
        $penalty = 0;
        $status = 'finished';
        $time = $rawTime;

        if (str_contains($cleanTime, 'lap')) {
            $penalty = 0;
            if (str_contains($rawTime, '+')) {
                $parts = explode('+', $rawTime);
                $time = trim($parts[0]);
                $lapText = strtolower($parts[1]);
                if (str_contains($lapText, '1')) $status = '+1 lap';
                elseif (str_contains($lapText, '2')) $status = '+2 laps';
                else $status = '+1 lap';
            } else {
                $time = null;
                $status = '+1 lap';
            }
        } elseif (str_contains($rawTime, '+')) {
            $parts = explode('+', $rawTime);
            $time = trim($parts[0]);
            $penalty = (int) filter_var($parts[1], FILTER_SANITIZE_NUMBER_INT);
            $status = 'finished';
        } elseif (str_contains($cleanTime, 'dns') || str_contains($cleanTime, 'did not start') || str_contains($cleanTime, 'withdrew')) {
            $status = 'dns';
            $time = null;
        } elseif (str_contains($cleanTime, 'dsq') || str_contains($cleanTime, 'disqualified')) {
            $status = 'dsq';
            $time = null;
        } elseif (str_contains($cleanTime, 'dnf') || str_contains($cleanTime, 'retired') || !preg_match('/^[0-9]/', $rawTime)) {
            $status = 'dnf';
            $time = null;
        }

        // 4. LIMPIEZA DE GRID POSITION (NUEVO)
        $gridPos = $row['grid'];
        if (!is_numeric($gridPos)) {
            $gridPos = null; // Si es "-" o texto, guarda NULL
        }

        // 5. Crear Resultado
        return new RaceResult([
            'race_id' => $this->raceId,
            'user_id' => $driver->id,
            'team_id' => $team?->id,
            'car_name' => $team?->car_model,
            'driver_number' => $row['number'],
            
            'position' => $row['pos'],
            'grid_position' => $gridPos, // Usamos la variable limpia
            'laps_completed' => $row['laps'],
            'race_time' => $time,
            'penalty_seconds' => $penalty,
            'status' => $status,
            'fastest_lap' => false,
        ]);
    }
}