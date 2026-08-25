<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Phase 36, Module B — one row per assigned image slot.
 *
 * Definition (label, page, fallback file) stays in config/magnoolia_slots.php;
 * this table only records which media item the client picked.
 */
class MagnooliaMediaSlot extends Model
{
    protected $table = 'magnoolia_media_slots';

    protected $fillable = ['slot_key', 'media_item_id', 'updated_by'];

    public function mediaItem(): BelongsTo
    {
        return $this->belongsTo(MagnooliaMediaItem::class, 'media_item_id');
    }

    /** Slot definitions, flattened: key => definition + key. */
    public static function definitions(): array
    {
        $out = [];
        foreach (config('magnoolia_slots', []) as $key => $definition) {
            $out[$key] = $definition + ['key' => $key];
        }

        return $out;
    }
}
