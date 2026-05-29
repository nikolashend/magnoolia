<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:xhtml="http://www.w3.org/1999/xhtml">
@php
    $base = rtrim(config('magnoolia.seo.canonical_base', config('app.url', url('/'))), '/');
    $pages = [
        ['loc' => $base,                        'priority' => '1.0',  'changefreq' => 'weekly',  'hreflang' => ['et' => $base, 'ru' => $base.'/ru', 'en' => $base.'/en']],
        ['loc' => $base.'/kodud-ja-hinnad',     'priority' => '0.9',  'changefreq' => 'weekly'],
        ['loc' => $base.'/asendiplaan',         'priority' => '0.7',  'changefreq' => 'monthly'],
        ['loc' => $base.'/asukoht',             'priority' => '0.7',  'changefreq' => 'monthly'],
        ['loc' => $base.'/ehitusinfo',          'priority' => '0.7',  'changefreq' => 'monthly'],
        ['loc' => $base.'/kontakt',             'priority' => '0.6',  'changefreq' => 'monthly'],
    ];
@endphp
@foreach($pages as $p)
  <url>
    <loc>{{ $p['loc'] }}</loc>
    <changefreq>{{ $p['changefreq'] }}</changefreq>
    <priority>{{ $p['priority'] }}</priority>
    <lastmod>{{ now()->toDateString() }}</lastmod>
    @if(!empty($p['hreflang']))
    @foreach($p['hreflang'] as $lang => $url)
    <xhtml:link rel="alternate" hreflang="{{ $lang }}" href="{{ $url }}"/>
    @endforeach
    <xhtml:link rel="alternate" hreflang="x-default" href="{{ $p['hreflang']['et'] }}"/>
    @endif
  </url>
@endforeach
</urlset>
