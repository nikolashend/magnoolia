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

if (! function_exists('mg_is_indexable')) {
    /**
     * Single source of truth for "may search engines index this site?" (Phase 35.0).
     *
     * `indexable` is the modern switch; `noindex` is the legacy one. They are OR-ed
     * so that MAGNOOLIA_INDEXABLE=true wins over a stale MAGNOOLIA_NOINDEX=true —
     * exactly the situation that kept production silently deindexed.
     *
     * Individual pages that must stay out of the index (the /aitah thank-you page,
     * admin) opt out at their own level, not here.
     */
    function mg_is_indexable(): bool
    {
        return (bool) config('magnoolia.seo.indexable', true)
            || ! config('magnoolia.seo.noindex', false);
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

            // 1) This locale was edited — use it.
            $override = $payload['content'][$locale][$key] ?? null;
            if (is_string($override) && $override !== '') {
                return $override;
            }

            // 2) Phase 36 — filling every language is optional, so an edit made only
            //    in Estonian must win over the older translation sitting in the lang
            //    file. Otherwise leaving RU/EN blank silently keeps stale text that
            //    now contradicts the Estonian: exactly what happened to
            //    "Ridaelamu mugavus, eramaja kogemus", corrected in ET while RU/EN
            //    still promised "privaatsus". Falling back to the edited Estonian is
            //    an untranslated string; falling back to the lang file is a wrong one.
            if ($locale !== 'et') {
                $estonian = $payload['content']['et'][$key] ?? null;
                if (is_string($estonian) && $estonian !== '') {
                    return $estonian;
                }
            }
        } catch (\Throwable $e) {
            // fall through to lang value
        }

        // 3) Never edited — the lang file translation for this locale is correct.
        return $default ?? (string) __('magnoolia.' . $key);
    }
}

if (! function_exists('mg_img')) {
    /**
     * Responsive <img> attributes (src + srcset + sizes) for a Magnoolia render
     * that has pre-generated webp variants (-480w/-768w/-1200w/-1600w.webp)
     * sitting next to the original in assets/images/magnoolia/. Falls back to the
     * original file untouched when no variants exist (no regression).
     *
     * Echo with {!! mg_img('Cam005.0000.jpg', '(max-width:991px) 100vw, 50vw') !!}
     */
    function mg_img(string $file, string $sizes = '100vw', string $dir = 'assets/images/magnoolia'): string
    {
        $name = pathinfo($file, PATHINFO_FILENAME);
        if (! is_file(public_path($dir . '/' . $name . '-1200w.webp'))) {
            return 'src="' . e(asset($dir . '/' . $file)) . '"';
        }
        $widths = [480, 768, 1200, 1600];
        $srcset = [];
        foreach ($widths as $w) {
            $srcset[] = e(asset($dir . '/' . $name . '-' . $w . 'w.webp')) . ' ' . $w . 'w';
        }

        return 'src="' . e(asset($dir . '/' . $name . '-1200w.webp')) . '"'
            . ' srcset="' . implode(', ', $srcset) . '"'
            . ' sizes="' . e($sizes) . '"';
    }
}

if (! function_exists('mg_img_path')) {
    /**
     * mg_img() for a full public path rather than a bare file name.
     *
     * Needed by the Module C lists: an entry stores its picture as a media path,
     * and without this the home-page cards would go from serving 1200w webp to
     * serving the full-size JPEG the moment the list is published — a performance
     * regression caused by nothing the client did.
     */
    function mg_img_path(string $path, string $sizes = '100vw'): string
    {
        $path = ltrim($path, '/');
        $dir = trim(dirname($path), '.');

        return mg_img(basename($path), $sizes, $dir !== '' ? $dir : 'assets/images/magnoolia');
    }
}

if (! function_exists('mg_slot')) {
    /**
     * Phase 36, Module B — resolve a named image slot.
     *
     * Returns ready-to-print attributes for an <img>: src, alt, and width/height
     * when the media item knows them. When the slot is unbound (or anything at all
     * goes wrong) it falls back to the file the template shipped with, so a missing
     * assignment can never leave a hole on the page.
     *
     * Usage in a template (note the long @php form — this Laravel dropped the
     * @php(...) short form, which compiles to an unterminated "<?php(" and takes
     * the rest of the template down with it):
     *     @php $img = mg_slot('home.intro.image'); @endphp
     *     <img src="{{ $img['src'] }}" alt="{{ $img['alt'] }}" loading="lazy">
     *
     * @return array{src: string, alt: string, width: int|null, height: int|null, bound: bool}
     */
    function mg_slot(string $key): array
    {
        // NB: slot keys contain dots ("home.intro.image"), which config() would read
        // as nesting — so the registry is fetched whole and indexed directly.
        $definition = config('magnoolia_slots', [])[$key] ?? [];

        // Some shipped renders have spaces in their file names ("Interior 1.jpg").
        // The registry stores the real on-disk name so the file can be verified;
        // only the URL needs the space encoded. Encoding just the space (rather
        // than the whole path) leaves an already-encoded media path untouched.
        $url = fn (string $path): string => str_replace(' ', '%20', asset($path));

        $fallback = [
            'src'    => $url($definition['default'] ?? ''),
            'alt'    => (string) ($definition['alt'] ?? ''),
            'width'  => null,
            'height' => null,
            'bound'  => false,
        ];

        try {
            $slot = app(\App\Services\Magnoolia\MagnooliaPublicDataRepository::class)
                ->getPublicPayload()['slots'][$key] ?? null;

            if (! is_array($slot) || blank($slot['src'] ?? null)) {
                return $fallback;
            }

            $locale = app()->getLocale();
            // The media item carries its own alt text; the registry alt is only the
            // safety net for an unbound slot.
            $alt = $slot['alt_' . $locale] ?? $slot['alt_et'] ?? null;

            return [
                'src'    => $url($slot['src']),
                'alt'    => filled($alt) ? $alt : $fallback['alt'],
                'width'  => $slot['width'] ?? null,
                'height' => $slot['height'] ?? null,
                'bound'  => true,
            ];
        } catch (\Throwable $e) {
            return $fallback;
        }
    }
}

if (! function_exists('mg_list')) {
    /**
     * Phase 36, Module C — resolve a named editable list for the current locale.
     *
     * Returns [] when the list has never been published, and the template then
     * uses the array it ships with. That is the whole safety contract: this can
     * go live before anything is seeded and the site does not change.
     *
     * Each returned entry is a flat array of the type's fields, with `image`
     * already resolved to a URL and `image_alt` alongside it, so a Blade template
     * reads it exactly like the literal array it replaces.
     *
     * @return array<int, array<string, mixed>>
     */
    function mg_list(string $key): array
    {
        try {
            $items = app(\App\Services\Magnoolia\MagnooliaPublicDataRepository::class)
                ->getPublicPayload()['lists'][$key] ?? null;

            if (! is_array($items) || $items === []) {
                return [];
            }

            $locale = app()->getLocale();

            return array_values(array_map(function (array $item) use ($locale) {
                $out = ($item['meta'] ?? []) + [];

                // Translatable fields: this locale, else Estonian (decision 4).
                // Keys are taken from both, so a field filled only in the current
                // language is not dropped for lacking an Estonian counterpart.
                $estonian = $item['et'] ?? [];
                $own = $item[$locale] ?? [];
                foreach (array_keys($own + $estonian) as $field) {
                    $value = $own[$field] ?? null;
                    $out[$field] = filled($value) ? $value : ($estonian[$field] ?? null);
                }

                if (filled($item['image'] ?? null)) {
                    // Both forms: `image` to print, `image_path` for mg_img_path()
                    // when the template wants the responsive variants.
                    $out['image_path'] = $item['image'];
                    $out['image']      = str_replace(' ', '%20', asset($item['image']));
                    $out['image_alt']  = $item['image_alt_' . $locale] ?? $item['image_alt_et'] ?? ($out['alt'] ?? '');
                }

                return $out;
            }, $items));
        } catch (\Throwable $e) {
            return [];
        }
    }
}

if (! function_exists('mg_faq')) {
    /**
     * Phase 36, Module C — question/answer pairs for a FAQ block.
     *
     * The visible FAQ and the Google FAQ snippet must be generated from the same
     * call. They were two hand-maintained copies before and had already drifted
     * (Phase 35.1 item 12), which is a correctness problem for search results,
     * not a cosmetic one.
     *
     * Falls back to the given lang key so a page keeps working before seeding.
     *
     * @return array<int, array{q: string, a: string}>
     */
    function mg_faq(string $listKey, ?string $langKey = null): array
    {
        $items = mg_list($listKey);

        if ($items !== []) {
            return array_values(array_filter(array_map(
                fn (array $i) => ['q' => (string) ($i['q'] ?? ''), 'a' => (string) ($i['a'] ?? '')],
                $items
            ), fn (array $i) => $i['q'] !== '' && $i['a'] !== ''));
        }

        if ($langKey === null) {
            return [];
        }

        $lang = __($langKey);
        if (! is_array($lang)) {
            return [];
        }

        // Two shipped shapes: a list of ['q'=>..,'a'=>..] and a flat q1/a1/q2/a2 map.
        if (array_is_list($lang)) {
            return array_values(array_filter($lang, fn ($i) => is_array($i) && filled($i['q'] ?? null)));
        }

        $out = [];
        for ($n = 1; isset($lang['q' . $n]); $n++) {
            if (filled($lang['a' . $n] ?? null)) {
                $out[] = ['q' => $lang['q' . $n], 'a' => $lang['a' . $n]];
            }
        }

        return $out;
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
        // Phase 36 Module C — when the gallery list is published it decides both
        // which pictures appear and in what order (Phase 35.1 item 8). Without it
        // the media library's own id order is used, as before.
        $ordered = mg_list('galerii.items');
        if ($ordered !== []) {
            return array_values(array_map(fn (array $i) => [
                'src'   => $i['image'] ?? '',
                'alt'   => $i['alt'] ?? ($i['image_alt'] ?? ''),
                'cat'   => $i['cat'] ?? 'valised',
                'label' => '',
            ], array_filter($ordered, fn (array $i) => filled($i['image'] ?? null))));
        }

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
