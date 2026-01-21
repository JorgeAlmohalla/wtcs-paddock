<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\CompressUploads;

class Team extends Model
{
    use HasFactory;
    use CompressUploads;

    // Estos son los campos que permitimos rellenar desde el formulario
    protected $fillable = [
        'name',
        'short_name',
        'type',
        'car_brand',
        'logo_url',
        'bio',
        'primary_color',
        'car_model',
        'car_image_url',
        'tech_chassis',
        'tech_engine',
        'tech_power',
        'tech_drivetrain',
        'tech_gearbox',
    ];

    protected $compressImageFields = ['logo_url', 'car_image_url'];

    // Relación: Un equipo tiene muchos pilotos
    public function drivers(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function raceResults()
    {
        return $this->hasMany(RaceResult::class);
    }
}