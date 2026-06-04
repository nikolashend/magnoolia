<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:xhtml="http://www.w3.org/1999/xhtml">
@php
  $base = rtrim(config('magnoolia.canonical_domain', config('magnoolia.seo.canonical_base', config('app.url', url('/')))), '/');

    // All slugs: [et-slug, ru-slug, en-slug, priority, changefreq]
    // Locale prefixes: ET = no prefix, RU = /ru/slug, EN = /en/slug
    // Home pages handled separately
    $slugPages = [
        // slug               priority  changefreq
        ['kodud-ja-hinnad',   '0.9',    'weekly'],
        ['asendiplaan',       '0.8',    'monthly'],
        ['asukoht',           '0.7',    'monthly'],
        ['ehitusinfo',        '0.7',    'monthly'],
        ['kontakt',           '0.6',    'monthly'],
        ['sisedisain',        '0.6',    'monthly'],
        ['arhitektuur-ja-valisdisain', '0.6', 'monthly'],
        ['galerii',           '0.6',    'monthly'],
        ['ostuprotsess',      '0.6',    'monthly'],
        ['finantseerimine',   '0.5',    'monthly'],
        ['kkk',               '0.7',    'monthly'],
    ];
@endphp
  {{-- ── Homepage ── --}}
  <url>
    <loc>{{ $base }}</loc>
    <changefreq>weekly</changefreq>
    <priority>1.0</priority>
    <lastmod>{{ now()->toDateString() }}</lastmod>
    <xhtml:link rel="alternate" hreflang="et"        href="{{ $base }}"/>
    <xhtml:link rel="alternate" hreflang="ru"        href="{{ $base }}/ru"/>
    <xhtml:link rel="alternate" hreflang="en"        href="{{ $base }}/en"/>
    <xhtml:link rel="alternate" hreflang="x-default" href="{{ $base }}"/>
  </url>
  <url>
    <loc>{{ $base }}/ru</loc>
    <changefreq>weekly</changefreq>
    <priority>0.9</priority>
    <lastmod>{{ now()->toDateString() }}</lastmod>
    <xhtml:link rel="alternate" hreflang="et"        href="{{ $base }}"/>
    <xhtml:link rel="alternate" hreflang="ru"        href="{{ $base }}/ru"/>
    <xhtml:link rel="alternate" hreflang="en"        href="{{ $base }}/en"/>
    <xhtml:link rel="alternate" hreflang="x-default" href="{{ $base }}"/>
  </url>
  <url>
    <loc>{{ $base }}/en</loc>
    <changefreq>weekly</changefreq>
    <priority>0.9</priority>
    <lastmod>{{ now()->toDateString() }}</lastmod>
    <xhtml:link rel="alternate" hreflang="et"        href="{{ $base }}"/>
    <xhtml:link rel="alternate" hreflang="ru"        href="{{ $base }}/ru"/>
    <xhtml:link rel="alternate" hreflang="en"        href="{{ $base }}/en"/>
    <xhtml:link rel="alternate" hreflang="x-default" href="{{ $base }}"/>
  </url>
@foreach($slugPages as [$slug, $priority, $changefreq])
  {{-- ET --}}
  <url>
    <loc>{{ $base }}/{{ $slug }}</loc>
    <changefreq>{{ $changefreq }}</changefreq>
    <priority>{{ $priority }}</priority>
    <lastmod>{{ now()->toDateString() }}</lastmod>
    <xhtml:link rel="alternate" hreflang="et"        href="{{ $base }}/{{ $slug }}"/>
    <xhtml:link rel="alternate" hreflang="ru"        href="{{ $base }}/ru/{{ $slug }}"/>
    <xhtml:link rel="alternate" hreflang="en"        href="{{ $base }}/en/{{ $slug }}"/>
    <xhtml:link rel="alternate" hreflang="x-default" href="{{ $base }}/{{ $slug }}"/>
  </url>
  {{-- RU --}}
  <url>
    <loc>{{ $base }}/ru/{{ $slug }}</loc>
    <changefreq>{{ $changefreq }}</changefreq>
    <priority>{{ (string)((float)$priority - 0.1) }}</priority>
    <lastmod>{{ now()->toDateString() }}</lastmod>
    <xhtml:link rel="alternate" hreflang="et"        href="{{ $base }}/{{ $slug }}"/>
    <xhtml:link rel="alternate" hreflang="ru"        href="{{ $base }}/ru/{{ $slug }}"/>
    <xhtml:link rel="alternate" hreflang="en"        href="{{ $base }}/en/{{ $slug }}"/>
    <xhtml:link rel="alternate" hreflang="x-default" href="{{ $base }}/{{ $slug }}"/>
  </url>
  {{-- EN --}}
  <url>
    <loc>{{ $base }}/en/{{ $slug }}</loc>
    <changefreq>{{ $changefreq }}</changefreq>
    <priority>{{ (string)((float)$priority - 0.1) }}</priority>
    <lastmod>{{ now()->toDateString() }}</lastmod>
    <xhtml:link rel="alternate" hreflang="et"        href="{{ $base }}/{{ $slug }}"/>
    <xhtml:link rel="alternate" hreflang="ru"        href="{{ $base }}/ru/{{ $slug }}"/>
    <xhtml:link rel="alternate" hreflang="en"        href="{{ $base }}/en/{{ $slug }}"/>
    <xhtml:link rel="alternate" hreflang="x-default" href="{{ $base }}/{{ $slug }}"/>
  </url>
@endforeach
</urlset>
