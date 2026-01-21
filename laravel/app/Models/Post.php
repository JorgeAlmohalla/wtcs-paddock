<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\CompressUploads;

class Post extends Model
{
    use HasFactory;
    use CompressUploads;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'image_url',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'date',
    ];

    protected $compressImageFields = ['image_url'];
}