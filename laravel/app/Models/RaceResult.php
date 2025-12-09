<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RaceResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'race_id',
        'user_id',
        'team_id',
        'position',
        'points',
        'grid_position',
        'fastest_lap',
        'penalty_seconds',
        'dnf',
    ];

    // Pertenece a una carrera
    public function race(): BelongsTo
    {
        return $this->belongsTo(Race::class);
    }

    // Pertenece a un piloto
    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id'); // Ojo: user_id es la clave foránea
    }

    // Pertenece a un equipo
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}