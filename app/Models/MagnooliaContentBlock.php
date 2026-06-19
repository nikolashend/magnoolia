<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MagnooliaContentBlock extends Model
{
    protected $table = 'magnoolia_content_blocks';

    protected $fillable = [
        'key', 'page', 'label', 'group', 'et', 'ru', 'en', 'is_active', 'sort_order', 'updated_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'updated_by' => 'integer',
    ];

    /** Pages shown in the content editor (key prefix → human label). */
    public const PAGES = [
        'home' => 'Avaleht (Homepage)',
        'kodud' => 'Kodud ja hinnad',
        'asendiplaan' => 'Asendiplaan',
        'ehitusinfo' => 'Ehitusinfo',
        'kontakt' => 'Kontakt',
    ];

    public function getRuMissingAttribute(): bool
    {
        return $this->is_active && blank($this->ru);
    }

    public function getEnMissingAttribute(): bool
    {
        return $this->is_active && blank($this->en);
    }
}
