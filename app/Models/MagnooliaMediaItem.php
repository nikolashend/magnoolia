<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MagnooliaMediaItem extends Model
{
    protected $table = 'magnoolia_media_items';

    protected $fillable = [
        'title', 'category', 'original_name', 'mime', 'size_bytes', 'width', 'height',
        'original_path', 'public_path', 'thumb_path',
        'alt_et', 'alt_ru', 'alt_en', 'assignment_target', 'uploaded_by',
    ];

    protected $casts = [
        'size_bytes' => 'integer',
        'width' => 'integer',
        'height' => 'integer',
        'uploaded_by' => 'integer',
    ];

    public const CATEGORIES = [
        'hero' => 'Hero',
        'exterior' => 'Exterior renders',
        'interior' => 'Interior renders',
        'asendiplaan' => 'Asendiplaan',
        'floorplan' => 'Floor plans',
        'gallery' => 'Gallery',
        'location' => 'Location',
        'materials' => 'Siseviimistlus / materials',
        'campaign' => 'Campaign',
        'logo' => 'Logo / brand',
        'other' => 'Other',
    ];

    /** True when this item lacks any alt text (public images should always have one). */
    public function getAltMissingAttribute(): bool
    {
        return blank($this->alt_et) && blank($this->alt_ru) && blank($this->alt_en);
    }

    public function getDisplayUrlAttribute(): ?string
    {
        $p = $this->public_path ?: $this->thumb_path;
        return $p ? asset($p) : null;
    }

    public function getThumbUrlAttribute(): ?string
    {
        $p = $this->thumb_path ?: $this->public_path;
        return $p ? asset($p) : null;
    }
}
