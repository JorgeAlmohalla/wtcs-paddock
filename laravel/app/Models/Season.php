<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Season extends Model
{
    protected $fillable = [
        'name',
        'is_active',
    ];
    public function races()
    {
        return $this->hasMany(Race::class); 
    }
}
