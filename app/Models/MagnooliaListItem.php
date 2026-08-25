<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Phase 36, Module C — one entry of an editable list.
 *
 * Translatable fields sit in payload_{locale}; shared ones (icon, URL, badge) in
 * `meta`. `value()` implements Phase 36 decision 4: a language the client left
 * blank shows the Estonian text rather than nothing.
 */
class MagnooliaListItem extends Model
{
    protected $table = 'magnoolia_list_items';

    protected $fillable = [
        'list_id', 'sort_order', 'is_active', 'media_item_id',
        'payload_et', 'payload_ru', 'payload_en', 'meta', 'updated_by',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'payload_et' => 'array',
        'payload_ru' => 'array',
        'payload_en' => 'array',
        'meta'       => 'array',
    ];

    public function list(): BelongsTo
    {
        return $this->belongsTo(MagnooliaList::class, 'list_id');
    }

    public function mediaItem(): BelongsTo
    {
        return $this->belongsTo(MagnooliaMediaItem::class, 'media_item_id');
    }

    /**
     * A translatable field in the given locale, falling back to Estonian.
     *
     * Filling RU/EN is optional (decision 4). Falling back to Estonian shows an
     * untranslated string; falling back to nothing would show a hole, and falling
     * back to the old lang file would show text the client has just corrected away.
     */
    public function value(string $field, string $locale): mixed
    {
        $own = ($this->{'payload_' . $locale} ?? [])[$field] ?? null;
        if (filled($own)) {
            return $own;
        }

        return ($this->payload_et ?? [])[$field] ?? null;
    }
}
