<?php

namespace App\Imports;

use App\Models\QualifyingResult;
use App\Models\User;
use App\Models\Team;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class QualifyingResultsImport implements ToModel, WithHeadingRow
{
    protected $raceId;

    public function __construct($raceId)
    {
        $this->raceId = $raceId;
    }

    public function model(array $row)
    {
        // 1. Buscar Piloto (Nombre o Número)
        $driverName = $row['driver'];
        $driver = User::whereRaw('LOWER(name) = ?', [strtolower($driverName)])->first();

        if (!$driver) return null;

        // 2. Buscar Equipo
        $team = Team::where('name', 'LIKE', '%' . $row['team'] . '%')->first() 
                ?? $driver->team;

        // 3. Crear Resultado
        return new QualifyingResult([
            'race_id' => $this->raceId,
            'user_id' => $driver->id,
            'driver_number' => $row['number'],
            'team_id' => $team?->id,
            'car_name' => $row['car'] ?? $team?->car_model, // Usar columna CAR del CSV si existe
            
            'position' => $row['pos'],
            'best_time' => $row['time'], // "1:09.355"
            'tyre_compound' => ucfirst(strtolower($row['tyre'])), // "Soft" -> "Soft"
        ]);
    }
}