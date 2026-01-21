<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\CompressUploads;

class Track extends Model
{
    use HasFactory;
    use CompressUploads;

    protected $fillable = [
        'name',
        'country_code',
        'layout_image_url',
        'length_km',
    ];

    protected $compressImageFields = ['layout_image_url'];

    // Un circuito puede tener muchas carreras (historial)
    public function races(): HasMany
    {
        return $this->hasMany(Race::class);
    }
}