@extends('layouts.app')

@section('title', 'Magnoolia — A-energiaklassi kodud Tallinna lähedal')
@section('meta_description', 'Magnoolia on premium uusarendus Vaelas, Kiili vallas — 19 A-energiaklassi kodu privaatse hoovi, terrassi ja Tallinna lähedusega. Valmib suvi 2027.')
@section('og_title', 'Magnoolia — A-energiaklassi kodud Tallinna lähedal')
@section('body_class', 'custom-cursor')

@section('content')

    {{-- 1. HERO --}}
    @include('partials.home.slider')

    {{-- 3. FACTS / about-two (index-2 / home2/about.php) --}}
    @include('sections.approved.facts-source')

    {{-- 4. ABOUT / Miks Magnoolia? / about-one section-space (index / home1/about.php) --}}
    @include('sections.approved.about-magnoolia-source')

    {{-- 5. BENEFIT CARDS / services-three section-space (index-2 / home2/services.php) --}}
    @include('sections.approved.benefits-source')

    {{-- 6. GALLERY STRIP / city-house section-space (index / home1/city-house.php) --}}
    @include('sections.approved.gallery-strip-source')

    {{-- 7. Hinnad ja plaanid preview — TODO --}}
    {{-- @include('sections.approved.pricing-preview-source') --}}

    {{-- 8. Asendiplaan / masterplan — TODO --}}
    {{-- @include('sections.approved.masterplan-source') --}}

    {{-- 9. FLOOR PLANS / property-plans--two + process-plan section-space (index + index-4) --}}
    @include('sections.approved.floor-plan-source')

    {{-- 10. EHITUSINFO / apartment-two section-space (index-2 / home2/apartment.php) --}}
    @include('sections.approved.accordion-source')

    {{-- 11. GALLERY / VIDEO / best-project-one section-space-top (index / home1/projects.php) --}}
    @include('sections.approved.video-gallery-source')

    {{-- 12. CONTACT / TEAM / team-one section-space-top (index-4 / home4/team.php) --}}
    @include('sections.approved.contact-team-source')

    {{-- 13. Final CTA — TODO --}}
    {{-- @include('sections.approved.final-cta-source') --}}

@endsection
