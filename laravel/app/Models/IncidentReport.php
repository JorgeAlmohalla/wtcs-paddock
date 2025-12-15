<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncidentReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'race_id', 'reporter_id', 'reported_id', 
        'lap_corner', 'description', 'video_url',
        'status', 'steward_notes', 'penalty_applied'
    ];

    public function race() { return $this->belongsTo(Race::class); }
    public function reporter() { return $this->belongsTo(User::class, 'reporter_id'); }
    public function reported() { return $this->belongsTo(User::class, 'reported_id'); }
}
