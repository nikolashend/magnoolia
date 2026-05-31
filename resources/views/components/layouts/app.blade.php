@props([
    'title'           => null,
    'metaDescription' => null,
])<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" data-locale="{{ app()->getLocale() }}" data-env="{{ app()->environment() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Title --}}
    <title>{{ $title ?? config('magnoolia.project.brand_name', 'Magnoolia') }}</title>

    {{-- Meta description placeholder — Phase 9 will implement full SEO --}}
    <meta name="description" content="{{ $metaDescription ?? 'Magnoolia Kodud — premium ridaelamud Tallinna lähedal.' }}">

    {{-- Canonical placeholder — Phase 9 --}}
    {{-- <link rel="canonical" href="@yield('canonical', url()->current())"> --}}

    {{-- OpenGraph placeholder — Phase 9 --}}
    {{-- <meta property="og:title" content="..."> --}}
    {{-- <meta property="og:description" content="..."> --}}
    {{-- <meta property="og:image" content="..."> --}}
    {{-- <meta property="og:url" content="..."> --}}

    {{-- Favicon placeholder — add real icons to public/images/magnoolia/logos/ --}}
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    {{-- Vite-compiled CSS & JS --}}
    @php
        try {
            $viteHtml = Illuminate\Support\Facades\Vite::withEntryPoints(['resources/css/app.css', 'resources/js/app.js'])->toHtml();
        } catch (\Exception $e) {
            $viteHtml = null;
        }
    @endphp
    @if($viteHtml)
        {!! $viteHtml !!}
    @else
        {{-- Dev fallback: direct CSS links (no Vite build) --}}
        <link rel="stylesheet" href="{{ asset('css/base.css') }}">
        <link rel="stylesheet" href="{{ asset('css/tokens.css') }}">
        <link rel="stylesheet" href="{{ asset('css/components.css') }}">
        <link rel="stylesheet" href="{{ asset('css/sections.css') }}">
        {{-- JS stub so Magnoolia.init() won't throw --}}
        <script>window.Magnoolia = { init(){}, trackCta(){} };</script>
    @endif

    {{-- Page-specific head additions --}}
    @stack('head')
</head>
<body class="locale-{{ app()->getLocale() }}">

    {{-- Site header --}}
    <x-site-header />

    {{-- Main content --}}
    <main id="main-content" class="page-body">
        {{ $slot }}
    </main>

    {{-- Site footer --}}
    <x-site-footer />

    {{-- Mobile sticky CTA (hidden on desktop) --}}
    <x-mobile-sticky-cta />

    {{-- Page-specific scripts --}}
    @stack('scripts')

</body>
</html>
