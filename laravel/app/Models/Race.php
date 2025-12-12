<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Race extends Model
{
    use HasFactory;

    protected $fillable = [
        'track_id',
        'round_number',
        'title',
        'race_date',
        'total_laps',
        'status',
    ];

    protected $casts = [
        'race_date' => 'datetime', // Importante para el calendario
    ];

    // Relación: Una carrera pertenece a un circuito
    public function track(): BelongsTo
    {
        return $this->belongsTo(Track::class);
    }

    // Relación: Una carrera tiene muchos resultados
    public function results(): HasMany
    {
        return $this->hasMany(RaceResult::class);
    }

    public function qualifyingResults(): HasMany
    {
        return $this->hasMany(QualifyingResult::class);
    }
}