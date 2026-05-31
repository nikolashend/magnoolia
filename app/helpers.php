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
