@extends('layouts.app')

@section('title', 'Magnoolia — A-energiaklassi kodud Tallinna lähedal')
@section('meta_description', 'Magnoolia on premium uusarendus Vaelas, Kiili vallas — 19 A-energiaklassi kodu privaatse hoovi, terrassi ja Tallinna lähedusega. Valmib suvi 2027.')
@section('og_title', 'Magnoolia — A-energiaklassi kodud Tallinna lähedal')
@section('body_class', 'custom-cursor')

@section('content')

    {{-- 1. HERO --}}
    @include('partials.home.slider')

    {{-- 2. FACTS BAR — .funfact-one__item (source: home4/funfact.php) --}}
    @include('sections.approved.facts-source')

    {{-- 3. ABOUT / MIKS MAGNOOLIA — .about-two (source: home2/about.php) --}}
    @include('sections.approved.about-magnoolia-source')

    {{-- 4. BENEFIT CARDS — .feature-two__item (source: home2/features.php) --}}
    @include('sections.approved.benefits-source')

    {{-- 5. GALLERY STRIP — CSS marquee (source: home3/slider-area.php) --}}
    @include('sections.approved.gallery-strip-source')

    {{-- 6. TODO: Hinnad ja plaanid preview — apartment cards/pricing teaser --}}
    {{-- @include('sections.approved.pricing-preview-source') --}}

    {{-- 7. TODO: Asendiplaan / masterplan preview --}}
    {{-- @include('sections.approved.masterplan-source') --}}

    {{-- 8. FLOOR PLANS — .process-plan / .property-plans (source: home4/process-plan.php) --}}
    @include('sections.approved.floor-plan-source')

    {{-- 9. EHITUSINFO / FAQ — .zoomvilla-accordion (source: home5/faq.php) --}}
    @include('sections.approved.accordion-source')

    {{-- 10. VIDEO PREVIEW — .video-three (source: home3/video.php) --}}
    @include('sections.approved.video-gallery-source')

    {{-- 11. TEAM + CONTACT — .team-card-two + .contact-two (source: home2/team.php + contact.php) --}}
    @include('sections.approved.contact-team-source')

    {{-- 12. TODO: Final CTA banner --}}
    {{-- @include('sections.approved.final-cta-source') --}}

@endsection
