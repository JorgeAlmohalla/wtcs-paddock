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
        // 0. Detectar VUELTA RÁPIDA (Fila especial al final)
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

        // CASO 1: DOBLADOS (+1 lap, +2 laps)
        // Buscamos si contiene la palabra "lap"
        if (str_contains($cleanTime, 'lap')) {
            $penalty = 0; // No es sanción de tiempo
            
            // Intentamos separar el tiempo si existe (ej: "22:12.152 +1 lap")
            if (str_contains($rawTime, '+')) {
                $parts = explode('+', $rawTime);
                $time = trim($parts[0]); // Guardamos el tiempo "22:12.152"
                
                // Determinamos cuántas vueltas son
                $lapText = strtolower($parts[1]);
                if (str_contains($lapText, '1')) {
                    $status = '+1 lap';
                } elseif (str_contains($lapText, '2')) {
                    $status = '+2 laps';
                } elseif (str_contains($lapText, '3')) {
                    $status = '+3 laps';
                } else {
                    $status = '+1 lap'; // Fallback por defecto
                }
            } else {
                // Si solo pone "+1 lap" sin tiempo
                $time = null;
                $status = '+1 lap';
            }
        }
        // CASO 2: PENALIZACIÓN DE TIEMPO (+5, +10...)
        elseif (str_contains($rawTime, '+')) {
            $parts = explode('+', $rawTime);
            $time = trim($parts[0]);
            $penalty = (int) filter_var($parts[1], FILTER_SANITIZE_NUMBER_INT); // Extrae solo el número
            $status = 'finished';
        } 
        // CASO 3: DNS
        elseif (str_contains($cleanTime, 'dns') || str_contains($cleanTime, 'did not start')) {
            $status = 'dns';
            $time = null;
        } 
        // CASO 4: DSQ
        elseif (str_contains($cleanTime, 'dsq') || str_contains($cleanTime, 'disqualified')) {
            $status = 'dsq';
            $time = null;
        } 
        // CASO 5: DNF (Explícito o Texto raro sin números)
        elseif (
            str_contains($cleanTime, 'dnf') || 
            str_contains($cleanTime, 'retired') || 
            !preg_match('/[0-9]/', $rawTime)
        ) {
            $status = 'dnf';
            $time = null;
        }

        // 4. Crear Resultado
        return new RaceResult([
            'race_id' => $this->raceId,
            'user_id' => $driver->id,
            'team_id' => $team?->id,
            'car_name' => $team?->car_model, // Snapshot
            'driver_number' => $row['number'], // Snapshot Dorsal
            
            'position' => $row['pos'],
            'grid_position' => $row['grid'],
            'laps_completed' => $row['laps'],
            'race_time' => $time,
            'penalty_seconds' => $penalty,
            'status' => $status,
            'fastest_lap' => false,
        ]);
    }
}