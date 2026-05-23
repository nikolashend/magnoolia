<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Faq extends Model
{
    use HasFactory;

    protected $fillable = ['question', 'answer', 'is_published', 'sort_order'];

    protected $casts = [
        'is_published' => 'boolean',
    ];
}
