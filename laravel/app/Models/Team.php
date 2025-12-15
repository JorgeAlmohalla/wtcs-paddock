<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Team extends Model
{
    use HasFactory;

    // Estos son los campos que permitimos rellenar desde el formulario
    protected $fillable = [
        'name',
        'short_name',
        'type',
        'car_brand',
        'logo_url',
        'primary_color',
        'car_model',
    ];

    // Relación: Un equipo tiene muchos pilotos
    public function drivers(): HasMany
    {
        return $this->hasMany(User::class);
    }
}