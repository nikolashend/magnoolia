<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TranslationSnapshot extends Model
{
    protected $fillable = ['locale', 'file', 'data', 'label'];

    protected $casts = [
        'data' => 'array',
    ];

    /** Keep only the last N snapshots per locale+file to avoid bloat */
    public static function prune(string $locale, string $file, int $keep = 20): void
    {
        $ids = static::where('locale', $locale)
            ->where('file', $file)
            ->orderByDesc('id')
            ->skip($keep)
            ->pluck('id');

        if ($ids->isNotEmpty()) {
            static::whereIn('id', $ids)->delete();
        }
    }
}
