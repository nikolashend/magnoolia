{{--
    SEO Meta Block — Magnoolia
    Include in layouts/app.blade.php <head>
    Provides: meta title, description, OG, Twitter card, hreflang, canonical
--}}

{{-- ── Primary meta ─────────────────────────────────────────────── --}}
<title>@yield('title', 'Magnoolia Kodud — A-energiaklassi ridaelamud Tallinna lähedal')</title>
<meta name="description" content="@yield('meta_description', 'Magnoolia Kodud on 19 uue A-energiaklassi koduga ridaelamuarendus Vaela külas, Kiili vallas. Privaatsed hoovialad, rõdud, terrassid ja läbimõeldud energialahendused. Valmib suvi 2027.')">

{{-- ── OpenGraph ─────────────────────────────────────────────────── --}}
<meta property="og:type"        content="website">
<meta property="og:site_name"   content="Magnoolia Kodud">
<meta property="og:locale"      content="et_EE">
<meta property="og:title"       content="@yield('og_title', 'Magnoolia Kodud — A-energiaklassi ridaelamud Tallinna lähedal')">
<meta property="og:description" content="@yield('og_description', 'Magnoolia Kodud on 19 uue A-energiaklassi koduga ridaelamuarendus Vaela külas, Kiili vallas. Privaatsed hoovialad, rõdud, terrassid. Valmib suvi 2027.')">
<meta property="og:url"         content="{{ url()->current() }}">
<meta property="og:image"       content="@yield('og_image', asset('assets/images/magnoolia/Cam001.0000.jpg'))">
<meta property="og:image:width"  content="1200">
<meta property="og:image:height" content="630">
<meta property="og:image:alt"   content="Magnoolia A-energiaklassi kodud Vaela külas Kiili vallas">

{{-- ── Twitter / X card ────────────────────────────────────────── --}}
<meta name="twitter:card"        content="summary_large_image">
<meta name="twitter:title"       content="@yield('og_title', 'Magnoolia Kodud — A-energiaklassi ridaelamud Tallinna lähedal')">
<meta name="twitter:description" content="@yield('og_description', 'Magnoolia Kodud on 19 uue A-energiaklassi koduga ridaelamuarendus Vaela külas, Kiili vallas.')">
<meta name="twitter:image"       content="@yield('og_image', asset('assets/images/magnoolia/Cam001.0000.jpg'))">

{{-- ── Canonical ────────────────────────────────────────────────── --}}
<link rel="canonical" href="{{ url()->current() }}">

{{-- ── hreflang ─────────────────────────────────────────────────── --}}
<link rel="alternate" hreflang="et"       href="{{ route('home') }}">
<link rel="alternate" hreflang="ru"       href="{{ route('home.ru') }}">
<link rel="alternate" hreflang="en"       href="{{ route('home.en') }}">
<link rel="alternate" hreflang="x-default" href="{{ route('home') }}">
