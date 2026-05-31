<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

class NavItem extends Model
{
    use SoftDeletes;

    protected $fillable = ['label', 'route_name', 'url', 'sort_order', 'is_active', 'open_blank'];

    protected $casts = [
        'label'      => 'array',
        'is_active'  => 'boolean',
        'open_blank' => 'boolean',
    ];

    protected static function booted(): void
    {
        $clear = fn() => Cache::forget('nav_items_active');
        static::saved($clear);
        static::deleted($clear);
        static::restored($clear);
        static::forceDeleted($clear);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    /** Resolve the URL for the current locale */
    public function getHref(?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();

        if ($this->route_name) {
            // Try locale-prefixed route first, fall back to base route
            $prefixed = $locale !== 'et' ? "{$locale}.{$this->route_name}" : $this->route_name;
            try {
                if (Route::has($prefixed)) {
                    return route($prefixed);
                }
                if (Route::has($this->route_name)) {
                    return route($this->route_name);
                }
            } catch (\Throwable) {}
        }

        return $this->url ?? '#';
    }

    /** Get label for current locale with ET fallback */
    public function getLabel(?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();
        $labels = $this->label ?? [];
        return $labels[$locale] ?? $labels['et'] ?? $labels[array_key_first($labels)] ?? '';
    }
}
