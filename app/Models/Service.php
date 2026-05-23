<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'description', 'icon', 'thumbnail', 'is_published', 'sort_order',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];
}
