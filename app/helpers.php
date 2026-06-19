<?php

if (! function_exists('locale_url')) {
    /**
     * Return the current URL rewritten for a given locale.
     *
     * ET  → no prefix:  /kodud-ja-hinnad
     * RU  → /ru prefix: /ru/kodud-ja-hinnad
     * EN  → /en prefix: /en/kodud-ja-hinnad
     *
     * Works by stripping any existing locale prefix, then re-adding the desired one.
     */
    function locale_url(string $locale): string
    {
        $supportedLocales = ['ru', 'en']; // ET has no prefix

        // Strip existing non-ET locale prefix from current path
        $path = request()->path(); // e.g. 'ru/kodud-ja-hinnad' or 'kodud-ja-hinnad'
        $path = preg_replace('#^(ru|en)(/|$)#', '', $path);
        $path = ltrim($path, '/');

        if ($locale === 'et') {
            // ET: no prefix, just /path
            return $path === '' ? url('/') : url('/' . $path);
        }

        if (in_array($locale, $supportedLocales)) {
            return $path === '' ? url('/' . $locale) : url('/' . $locale . '/' . $path);
        }

        return url('/' . $path);
    }
}

if (! function_exists('lroute')) {
    /**
     * Generate a locale-aware route URL.
     *
     * On ET pages, behaves identically to route().
     * On RU/EN pages, prefixes the generated URL with the current locale segment.
     */
    function lroute(string $name, array $params = []): string
    {
        $locale = app()->getLocale();

        // Generate the base route URL
        $url = route($name, $params);

        if ($locale === 'et') {
            return $url;
        }

        // Prepend locale prefix to the path portion
        $parsed = parse_url($url);
        $path   = ltrim($parsed['path'] ?? '/', '/');

        // Avoid double-prefixing
        if (str_starts_with($path, $locale . '/') || $path === $locale) {
            return $url;
        }

        $base = ($parsed['scheme'] ?? 'http') . '://' . ($parsed['host'] ?? request()->getHost());
        if (isset($parsed['port'])) {
            $base .= ':' . $parsed['port'];
        }

        return $base . '/' . $locale . '/' . $path;
    }
}

if (! function_exists('magnoolia_locale_code')) {
    /**
     * Return locale code for HTML / OG / schema usage.
     */
    function magnoolia_locale_code(?string $locale = null): string
    {
        $locale = $locale ?: app()->getLocale();

        return match ($locale) {
            'ru' => 'ru-EE',
            'en' => 'en-EE',
            default => 'et-EE',
        };
    }
}

if (! function_exists('magnoolia_url')) {
    /**
     * Build an absolute URL on the configured canonical domain.
     */
    function magnoolia_url(string $path = ''): string
    {
        $base = rtrim(config('magnoolia.canonical_domain', config('magnoolia.seo.canonical_base', config('app.url', url('/')))), '/');
        $path = '/' . ltrim($path, '/');

        return $base . ($path === '/' ? '' : $path);
    }
}

if (! function_exists('mg_text')) {
    /**
     * Page-Texts CMS read helper (Phase 33.1).
     *
     * Returns a PUBLISHED content override for the given magnoolia key (without
     * the "magnoolia." prefix) in the current locale, falling back to the existing
     * lang-file value. Draft edits never appear here — only what the active
     * publication snapshot carries — so this is safe to use directly in Blade.
     */
    function mg_text(string $key, ?string $default = null): string
    {
        $locale = app()->getLocale();
        try {
            $payload = app(\App\Services\Magnoolia\MagnooliaPublicDataRepository::class)->getPublicPayload();
            $override = $payload['content'][$locale][$key] ?? null;
            if (is_string($override) && $override !== '') {
                return $override;
            }
        } catch (\Throwable $e) {
            // fall through to lang value
        }

        return $default ?? (string) __('magnoolia.' . $key);
    }
}

if (! function_exists('mg_gallery')) {
    /**
     * Published gallery items (Phase 33.1) for the public /galerii page, resolved
     * to the current locale. Returns [] when nothing is published → the page uses
     * its built-in image list (safe fallback, no regression).
     *
     * @return array<int, array{src:string, alt:string, cat:string, label:string}>
     */
    function mg_gallery(): array
    {
        try {
            $items = app(\App\Services\Magnoolia\MagnooliaPublicDataRepository::class)->getPublicPayload()['gallery'] ?? [];
            $loc = app()->getLocale();
            return array_values(array_map(function ($i) use ($loc) {
                return [
                    'src' => asset($i['src'] ?? ''),
                    'alt' => $i['alt_' . $loc] ?? $i['alt_et'] ?? ($i['title'] ?? ''),
                    'cat' => $i['cat'] ?? 'valised',
                    'label' => '',
                ];
            }, $items));
        } catch (\Throwable $e) {
            return [];
        }
    }
}
