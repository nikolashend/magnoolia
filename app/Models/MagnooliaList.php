<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Phase 36, Module C — one editable repeating block on the site.
 *
 * The label, page and field set come from config (magnoolia_lists +
 * magnoolia_list_types); this row only records that the block has been taken
 * over by the admin, and owns its entries.
 */
class MagnooliaList extends Model
{
    protected $table = 'magnoolia_lists';

    protected $fillable = ['list_key', 'type', 'page', 'is_active', 'updated_by'];

    protected $casts = ['is_active' => 'boolean'];

    public function items(): HasMany
    {
        return $this->hasMany(MagnooliaListItem::class, 'list_id')->orderBy('sort_order')->orderBy('id');
    }

    /** Registered lists, flattened: key => definition + key. */
    public static function definitions(): array
    {
        $out = [];
        foreach (config('magnoolia_lists', []) as $key => $definition) {
            $out[$key] = $definition + ['key' => $key];
        }

        return $out;
    }

    public static function definition(string $key): ?array
    {
        return static::definitions()[$key] ?? null;
    }

    /**
     * Field set for this list: the type's fields, plus any this particular list
     * adds (the KKK questions carry a heading; the architecture FAQ does not).
     */
    public function fields(): array
    {
        $fields = config('magnoolia_list_types', [])[$this->type]['fields'] ?? [];

        return $fields + (static::definition($this->list_key)['extra_fields'] ?? []);
    }

    /** Choices for a `select` field, resolved through the shared option sets. */
    public static function options(string $set): array
    {
        return config('magnoolia_list_types', [])['_options'][$set] ?? [];
    }
}
