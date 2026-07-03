@extends('layouts.app')

@section('title', __('home.meta_title'))
@section('meta_description', __('home.meta_description'))
@section('og_title', __('home.meta_title'))
@section('body_class', 'custom-cursor')

@section('content')

    {{-- 1. HERO --}}
    @include('partials.home.slider')

    {{-- 3. FACTS / about-two (index-2 / home2/about.php) --}}
    @include('sections.approved.facts-source')

    {{-- 4. ABOUT / Miks Magnoolia? / about-one section-space (index / home1/about.php) --}}
    @include('sections.approved.about-magnoolia-source')

    {{-- 4b. INTRO NARRATIVE — prepared client copy "Eksklusiivsed A-energiaklassi ridaelamud" --}}
    @include('sections.magnoolia.intro-narrative')

    {{-- 5. BENEFIT CARDS / services-three section-space (index-2 / home2/services.php) --}}
    @include('sections.approved.benefits-source')

    {{-- 5b. Location teaser: "Looduse rahu, linna lähedus" + key distances --}}
    @include('sections.magnoolia.location-teaser')

    {{-- 6. GALLERY STRIP / city-house section-space (index / home1/city-house.php) --}}
    @include('sections.approved.gallery-strip-source')

    {{-- Phase 26: Lifestyle proof block — 4 cards with real location images --}}
    @include('sections.magnoolia.lifestyle-proof')

    {{-- Phase 26: Compact pricing/availability teaser --}}
    @include('sections.magnoolia.pricing-teaser')

    {{-- Phase 35: the "Saadavuse ülevaade" board was removed from the homepage — the
         full price table with availability (below) covers the same data, no dupes. --}}

    {{-- 7. HINNAD JA PLAANID — pricing table --}}
    @include('sections.magnoolia.hinnad')

    {{-- 8. ASENDIPLAAN — masterplan overview --}}
    @include('sections.magnoolia.asendiplaan')

    {{-- 9. FLOOR PLANS / property-plans--two + process-plan section-space (index + index-4) --}}
    @include('sections.approved.floor-plan-source')

    {{-- 10. SISEDISAIN / VIDEO — gallery preview (index / home1/projects.php) --}}
    @include('sections.approved.video-gallery-source')

    {{-- 11. EHITUSINFO / apartment-two section-space (index-2 / home2/apartment.php) --}}
    @include('sections.approved.accordion-source')

    {{-- 12. KKK / AI Answer block --}}
    @include('sections.magnoolia.ai-answer')

    {{-- 12b. ANSWER UNIT — AI-citable Magnoolia summary --}}
    @php
      $au = __('magnoolia.answer_unit.home');
      $au['cta_route'] = lroute('magnoolia.homes');
    @endphp
    @include('sections.magnoolia.answer-unit', ['unit' => $au])

    {{-- 12c. ARENDAJA — Estlanda developer strip with large logo --}}
    @include('sections.magnoolia.developer-strip')

    {{-- 13. KONTAKT — Diana Tali, inquiry form --}}
    @include('sections.magnoolia.contact')

@endsection
