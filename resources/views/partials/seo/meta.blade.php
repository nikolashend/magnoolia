{{--
    SEO Meta Block — Magnoolia
    Include in layouts/app.blade.php <head>
    Provides: meta title, description, OG, Twitter card, hreflang, canonical
--}}
@php
    $project = config('magnoolia.project', []);
    $canonicalRaw = config('magnoolia.seo.canonical_base');
    $canonicalBase = rtrim($canonicalRaw ?: config('app.url', url('/')), '/');
    $locale = app()->getLocale();

    $homeEt = $canonicalBase;
    $homeRu = $canonicalBase . '/ru';
    $homeEn = $canonicalBase . '/en';

    $canonicalUrl = request()->routeIs('home*')
        ? ($locale === 'ru' ? $homeRu : ($locale === 'en' ? $homeEn : $homeEt))
        : url()->current();

    $titles = [
        'et' => 'Magnoolia Kodud — A-energiaklassi ridaelamukodud Vaelas Tallinna lähedal',
        'ru' => 'Magnoolia Kodud — энергоэффективные рядные дома рядом с Таллинном',
        'en' => 'Magnoolia Kodud — A-Class Townhouse Homes Near Tallinn',
    ];
    $descs = [
        'et' => 'Magnoolia Kodud on 19 A-energiaklassi ridaelamukodu Vaela külas, Kiili vallas. Privaatne hooviala, läbimõeldud plaanid, I etapp kevad 2027 ja II etapp kevad 2028. Küsi saadavust ja pakkumist Diana Talilt.',
        'ru' => 'Magnoolia Kodud — 19 энергоэффективных рядных домов класса A в деревне Vaela, волости Kiili, у Таллинна. Личный двор, террасы, балконы. I этап — весна 2027, II этап — весна 2028.',
        'en' => 'Magnoolia Kodud: 19 A-energy-class townhomes in Vaela village, Kiili municipality, near Tallinn. Private yard, terrace and balcony. Stage I ready spring 2027, Stage II spring 2028.',
    ];
    $defaultTitle = $titles[$locale] ?? $titles['et'];
    $defaultDesc  = $descs[$locale]  ?? $descs['et'];
    $defaultOgImg = asset(config('magnoolia.seo.og_image', 'assets/images/magnoolia/Cam001.0000.jpg'));
@endphp

{{-- ── Primary meta ─────────────────────────────────────────────── --}}
<title>@yield('title', $defaultTitle)</title>
<meta name="description" content="@yield('meta_description', $defaultDesc)">

{{-- ── OpenGraph ─────────────────────────────────────────────────── --}}
<meta property="og:type"        content="website">
<meta property="og:site_name"   content="{{ $project['name'] ?? 'Magnoolia Kodud' }}">
<meta property="og:locale"      content="{{ $locale === 'ru' ? 'ru_EE' : ($locale === 'en' ? 'en_EE' : 'et_EE') }}">
<meta property="og:title"       content="@yield('og_title', $defaultTitle)">
<meta property="og:description" content="@yield('og_description', $defaultDesc)">
<meta property="og:url"         content="{{ $canonicalUrl }}">
<meta property="og:image"       content="@yield('og_image', $defaultOgImg)">
<meta property="og:image:width"  content="1200">
<meta property="og:image:height" content="630">
<meta property="og:image:alt"   content="Magnoolia A-energiaklassi kodud Vaela külas Kiili vallas">

{{-- ── Twitter / X card ────────────────────────────────────────── --}}
<meta name="twitter:card"        content="summary_large_image">
<meta name="twitter:title"       content="@yield('og_title', $defaultTitle)">
<meta name="twitter:description" content="@yield('og_description', $defaultDesc)">
<meta name="twitter:image"       content="@yield('og_image', $defaultOgImg)">

{{-- ── Canonical ────────────────────────────────────────────────── --}}
<link rel="canonical" href="{{ $canonicalUrl }}">

{{-- ── hreflang ─────────────────────────────────────────────────── --}}
<link rel="alternate" hreflang="et"        href="{{ $homeEt }}">
<link rel="alternate" hreflang="ru"        href="{{ $homeRu }}">
<link rel="alternate" hreflang="en"        href="{{ $homeEn }}">
<link rel="alternate" hreflang="x-default" href="{{ $homeEt }}">
