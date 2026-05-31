@extends('layouts.app')

@section('title', __('magnoolia.page.kodudjahinnad.page_title'))
@section('meta_description', $page['description'] ?? '')
@section('og_title', $page['title'] ?? '')
@section('og_description', $page['description'] ?? '')

@section('content')
@php
  $base   = rtrim(config('magnoolia.seo.canonical_base', 'https://magnoolia.ee'), '/');
  $units  = collect(config('magnoolia.units', []));
  $stages = config('magnoolia.stages', []);
@endphp

<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@graph": [
    {
      "@@type": "BreadcrumbList",
      "itemListElement": [
        { "@@type": "ListItem", "position": 1, "name": "Avaleht", "item": "{{ $base }}" },
        { "@@type": "ListItem", "position": 2, "name": "Kodud ja hinnad", "item": "{{ $base }}/kodud-ja-hinnad" }
      ]
    },
    {
      "@@type": "ApartmentComplex",
      "@@id": "{{ $base }}/#apartment-complex",
      "name": "Magnoolia ridaelamukodud",
      "description": "19 A-energiaklassi ridaelamukodu Vaela külas, Kiili vallas, Tallinna lähedal. Plaan A (4 tuba, ~129.6 m²) ja Plaan B (5 tuba, ~143.2 m²).",
      "url": "{{ $base }}/kodud-ja-hinnad",
      "address": {
        "@@type": "PostalAddress",
        "streetAddress": "Magnoolia tee",
        "addressLocality": "Vaela küla, Kiili vald",
        "addressRegion": "Harjumaa",
        "addressCountry": "EE"
      }
    }
  ]
}
</script>

{{-- ── Hero ─────────────────────────────────────────────────── --}}
<div class="mg-page-hero">
  <div class="container">
    @include('partials.seo.breadcrumb', ['items' => [
      ['label' => __('magnoolia.nav.home'), 'url' => route('home')],
      ['label' => __('magnoolia.nav.homes')],
    ]])
    <div class="mg-page-hero__eyebrow">{{ __('magnoolia.page.kodudjahinnad.eyebrow') }}</div>
    <h1 class="mg-page-hero__title">{{ __('magnoolia.page.kodudjahinnad.page_h1') }}</h1>
    <p class="mg-page-hero__lead">{!! __('magnoolia.page.kodudjahinnad.lead') !!}</p>
    <p class="mg-page-hero__note">{{ __('magnoolia.page.kodudjahinnad.note') }}</p>
    <div class="mg-page-hero__ctas">
      <a href="#hinnatabel" class="zoomvilla-btn">{{ __('magnoolia.page.kodudjahinnad.cta_table') }} <i class="icon-angle-small-right"></i></a>
      <a href="{{ lroute('magnoolia.contact') }}" class="zoomvilla-btn zoomvilla-btn--border">{{ __('magnoolia.page.kodudjahinnad.cta_inquiry') }} <i class="icon-angle-small-right"></i></a>
    </div>
  </div>
</div>

{{-- ── Plan comparison cards ────────────────────────────────── --}}
<section class="mg-page-section mg-page-section--white" id="plaanid">
  <div class="container">
    <div class="mg-section-heading mg-section-heading--center">
      <div class="mg-section-heading__eyebrow">{{ __('magnoolia.page.kodudjahinnad.plans_eyebrow') }}</div>
      <h2 class="mg-section-heading__title">{{ __('magnoolia.page.kodudjahinnad.plans_title') }}</h2>
      <p class="mg-section-heading__subtitle">{{ __('magnoolia.page.kodudjahinnad.plans_sub') }}</p>
    </div>
    <div class="row gutter-y-24">
      <div class="col-lg-6 wow fadeInUp" data-wow-duration="700ms" data-wow-delay="0ms">
        <div class="mg-comparison-card">
          <div class="mg-comparison-card__plan">{{ __('magnoolia.page.kodudjahinnad.plan_a_name') }}</div>
          <div class="mg-comparison-card__title">{{ __('magnoolia.page.kodudjahinnad.plan_a_title') }}</div>
          <div class="mg-comparison-card__size">{{ __('magnoolia.page.kodudjahinnad.plan_a_size') }}</div>
          <ul class="mg-comparison-card__specs">
            <li><span>{{ __('magnoolia.page.kodudjahinnad.spec_rooms') }}</span><strong>4</strong></li>
            <li><span>{{ __('magnoolia.page.kodudjahinnad.spec_area') }}</span><strong>ca 129,6 m²</strong></li>
            <li><span>{{ __('magnoolia.page.kodudjahinnad.spec_terrace') }}</span><strong>{{ __('magnoolia.page.kodudjahinnad.spec_check') }}</strong></li>
            <li><span>{{ __('magnoolia.page.kodudjahinnad.spec_yard') }}</span><strong>{{ __('magnoolia.page.kodudjahinnad.spec_check') }}</strong></li>
            <li><span>{{ __('magnoolia.page.kodudjahinnad.spec_parking') }}</span><strong>2</strong></li>
            <li><span>{{ __('magnoolia.page.kodudjahinnad.spec_stage') }}</span><strong>{{ __('magnoolia.page.kodudjahinnad.spec_stage_ab') }}</strong></li>
          </ul>
          <p class="mg-comparison-card__pitch">{{ __('magnoolia.page.kodudjahinnad.plan_a_pitch') }}</p>
          <div class="mg-comparison-card__ctas">
            <a href="{{ route('home') }}#plaanid" class="zoomvilla-btn">{{ __('magnoolia.page.kodudjahinnad.plan_a_cta_view') }} <i class="icon-angle-small-right"></i></a>
            <a href="{{ lroute('magnoolia.contact') }}" class="zoomvilla-btn zoomvilla-btn--border">{{ __('magnoolia.page.kodudjahinnad.plan_a_cta_inq') }} <i class="icon-angle-small-right"></i></a>
          </div>
        </div>
      </div>
      <div class="col-lg-6 wow fadeInUp" data-wow-duration="700ms" data-wow-delay="120ms">
        <div class="mg-comparison-card mg-comparison-card--featured">
          <div class="mg-comparison-card__badge">{{ __('magnoolia.page.kodudjahinnad.plan_b_badge') }}</div>
          <div class="mg-comparison-card__plan">{{ __('magnoolia.page.kodudjahinnad.plan_b_name') }}</div>
          <div class="mg-comparison-card__title">{{ __('magnoolia.page.kodudjahinnad.plan_b_title') }}</div>
          <div class="mg-comparison-card__size">{{ __('magnoolia.page.kodudjahinnad.plan_b_size') }}</div>
          <ul class="mg-comparison-card__specs">
            <li><span>{{ __('magnoolia.page.kodudjahinnad.spec_rooms') }}</span><strong>5</strong></li>
            <li><span>{{ __('magnoolia.page.kodudjahinnad.spec_area') }}</span><strong>ca 143,2 m²</strong></li>
            <li><span>{{ __('magnoolia.page.kodudjahinnad.spec_terrace') }}</span><strong>{{ __('magnoolia.page.kodudjahinnad.plan_b_terrass') }}</strong></li>
            <li><span>{{ __('magnoolia.page.kodudjahinnad.spec_yard') }}</span><strong>{{ __('magnoolia.page.kodudjahinnad.spec_check') }}</strong></li>
            <li><span>{{ __('magnoolia.page.kodudjahinnad.spec_parking') }}</span><strong>2</strong></li>
            <li><span>{{ __('magnoolia.page.kodudjahinnad.spec_stage') }}</span><strong>{{ __('magnoolia.page.kodudjahinnad.spec_stage_ab') }}</strong></li>
          </ul>
          <p class="mg-comparison-card__pitch">{{ __('magnoolia.page.kodudjahinnad.plan_b_pitch') }}</p>
          <div class="mg-comparison-card__ctas">
            <a href="{{ route('home') }}#plaanid" class="zoomvilla-btn">{{ __('magnoolia.page.kodudjahinnad.plan_b_cta_view') }} <i class="icon-angle-small-right"></i></a>
            <a href="{{ lroute('magnoolia.contact') }}" class="zoomvilla-btn zoomvilla-btn--border">{{ __('magnoolia.page.kodudjahinnad.plan_b_cta_inq') }} <i class="icon-angle-small-right"></i></a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ── Hinnatabel ───────────────────────────────────────────── --}}
<div id="hinnatabel">
  @include('sections.magnoolia.hinnad')
</div>

{{-- ── How to choose ────────────────────────────────────────── --}}
<section class="mg-page-section mg-page-section--white">
  <div class="container">
    <div class="mg-how-to">
      <div class="mg-section-heading" style="margin-bottom:0;">
        <div class="mg-section-heading__eyebrow">{{ __('magnoolia.page.kodudjahinnad.how_eyebrow') }}</div>
        <h2 class="mg-section-heading__title">{{ __('magnoolia.page.kodudjahinnad.how_title') }}</h2>
      </div>
      <div class="mg-how-to__grid">
        @foreach(__('magnoolia.page.kodudjahinnad.how_steps') as $s)
        <div class="mg-how-to__step">
          <div class="mg-how-to__num">{{ $s['n'] }}</div>
          <div class="mg-how-to__label">{{ $s['t'] }}</div>
          <div class="mg-how-to__desc">{{ $s['d'] }}</div>
        </div>
        @endforeach
      </div>
    </div>
  </div>
</section>

{{-- ── FAQ ─────────────────────────────────────────────────── --}}
@include('sections.magnoolia.page-faq', [
  'eyebrow' => __('magnoolia.page.kodudjahinnad.faq_eyebrow'),
  'title'   => __('magnoolia.page.kodudjahinnad.faq_title'),
  'bg'      => 'cream',
  'faqs'    => __('magnoolia.page.kodudjahinnad.faq_items'),
])

{{-- ── Internal links ──────────────────────────────────────── --}}
<section class="mg-page-section--cream mg-page-section--sm">
  <div class="container">
    <div class="mg-internal-links">
      <a href="{{ lroute('magnoolia.site-plan') }}" class="mg-internal-link"><i class="fas fa-map"></i> {{ __('magnoolia.page.kodudjahinnad.link_plan') }}</a>
      <a href="{{ route('home') }}#plaanid" class="mg-internal-link"><i class="fas fa-drafting-compass"></i> {{ __('magnoolia.page.kodudjahinnad.link_floorplans') }}</a>
      <a href="{{ lroute('magnoolia.location') }}" class="mg-internal-link"><i class="fas fa-map-marker-alt"></i> {{ __('magnoolia.page.kodudjahinnad.link_loc') }}</a>
      <a href="{{ lroute('magnoolia.ostuprotsess') }}" class="mg-internal-link"><i class="fas fa-list-ol"></i> {{ __('magnoolia.page.kodudjahinnad.link_proc') }}</a>
      <a href="{{ lroute('magnoolia.contact') }}" class="mg-internal-link"><i class="fas fa-envelope"></i> {{ __('magnoolia.page.kodudjahinnad.link_cont') }}</a>
    </div>
  </div>
</section>

{{-- ── CTA ──────────────────────────────────────────────────── --}}
@include('sections.magnoolia.page-cta', [
  'title'   => __('magnoolia.page.kodudjahinnad.cta_title'),
  'sub'     => __('magnoolia.page.kodudjahinnad.cta_sub'),
  'buttons' => [
    ['label' => __('magnoolia.page.kodudjahinnad.cta_btn1'), 'url' => lroute('magnoolia.contact')],
    ['label' => __('magnoolia.page.kodudjahinnad.cta_btn2'), 'url' => 'tel:+37258164078', 'outline' => true, 'icon' => 'fas fa-phone'],
    ['label' => __('magnoolia.page.kodudjahinnad.cta_btn3'), 'url' => lroute('magnoolia.site-plan'), 'outline' => true],
  ]
])

@endsection
