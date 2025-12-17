<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QualifyingResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'race_id',
        'user_id',
        'team_id',
        'car_name',
        'position',
        'best_time',
        'tyre_compound',
        'driver_number',
        'points',
    ];

    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function race()
    {
        return $this->belongsTo(Race::class);
    }
}