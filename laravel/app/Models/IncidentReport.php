<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncidentReport extends Model
{
    use HasFactory;

protected $fillable = [
        'reporter_id',
        'reported_id',
        'race_id',
        'lap_corner', // <--- CAMBIO: Antes era 'lap'
        'description',
        'video_url',
        'status',
        'penalty_applied',
        'steward_notes'
    ];

    public function race() {
        return $this->belongsTo(Race::class);
    }

    public function reporter() {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    // Relación para la Web y la App (ambas apuntan a reported_id)
    public function accused() {
        return $this->belongsTo(User::class, 'reported_id');
    }
    
    public function reported() {
        return $this->belongsTo(User::class, 'reported_id');
    }
}