@extends('layouts.app')

@section('title', __('magnoolia.page.kodudjahinnad.page_title'))
@section('meta_description', $page['description'] ?? '')
@section('og_title', $page['title'] ?? '')
@section('og_description', $page['description'] ?? '')

@section('content')
@php
  $base   = rtrim(config('magnoolia.seo.canonical_base', 'https://magnoolia.ee'), '/');
  $units  = collect($mgPublic['units'] ?? []);
  $stages = $mgPublic['stages'] ?? [];
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
<div class="mg-page-hero" style="background-image:linear-gradient(to right, rgba(29,36,48,.88) 60%, rgba(29,36,48,.5)), url('{{ asset('assets/images/magnoolia/Cam004.0000.jpg') }}');background-size:cover;background-position:center;">
  <div class="container">
    @include('partials.seo.breadcrumb', ['items' => [
      ['label' => __('magnoolia.nav.home'), 'url' => route('home')],
      ['label' => __('magnoolia.nav.homes')],
    ]])
    <div class="mg-page-hero__eyebrow">{{ __('magnoolia.page.kodudjahinnad.eyebrow') }}</div>
    <h1 class="mg-page-hero__title">{{ mg_text('page.kodudjahinnad.page_h1') }}</h1>
    <p class="mg-page-hero__lead">{!! mg_text('page.kodudjahinnad.lead') !!}</p>
    <p class="mg-page-hero__note">{{ mg_text('page.kodudjahinnad.note') }}</p>
    <div class="mg-page-hero__ctas">
      <a href="#hinnatabel" class="zoomvilla-btn">{{ __('magnoolia.page.kodudjahinnad.cta_table') }} <i class="icon-angle-small-right"></i></a>
      <a href="{{ lroute('magnoolia.contact') }}" class="zoomvilla-btn zoomvilla-btn--border"
         data-mg-inquiry-open data-source-component="hinnad_hero" data-mg-analytics="magnoolia_cta_click">{{ __('magnoolia.page.kodudjahinnad.cta_inquiry') }} <i class="icon-angle-small-right"></i></a>
    </div>
  </div>
</div>

{{-- ── Phase 35: interactive site plan (asendiplaan) at the top, then prices.
       Selecting a home on the plan opens the same modal as the price table.
       /asendiplaan redirects to this #mg-masterplan anchor. ── --}}
@include('sections.magnoolia.interactive-masterplan')

{{-- ── Hinnatabel (price table) ─────────────────────────────── --}}
<div id="hinnatabel">
  @include('sections.magnoolia.hinnad')
</div>

{{-- ── Plan comparison cards ────────────────────────────────── --}}
<section class="mg-page-section mg-page-section--white" id="plaani-tuubid">
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
            <a href="{{ route('home') }}#plaanid" class="zoomvilla-btn"
               onclick="event.preventDefault(); if(window.mgPlansOpen){window.mgPlansOpen();}">{{ __('magnoolia.page.kodudjahinnad.plan_a_cta_view') }} <i class="icon-angle-small-right"></i></a>
            <a href="{{ lroute('magnoolia.contact') }}" class="zoomvilla-btn zoomvilla-btn--border"
               data-mg-inquiry-open data-source-component="hinnad_plan_a" data-mg-analytics="magnoolia_cta_click">{{ __('magnoolia.page.kodudjahinnad.plan_a_cta_inq') }} <i class="icon-angle-small-right"></i></a>
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
            <a href="{{ route('home') }}#plaanid" class="zoomvilla-btn"
               onclick="event.preventDefault(); if(window.mgPlansOpen){window.mgPlansOpen();}">{{ __('magnoolia.page.kodudjahinnad.plan_b_cta_view') }} <i class="icon-angle-small-right"></i></a>
            <a href="{{ lroute('magnoolia.contact') }}" class="zoomvilla-btn zoomvilla-btn--border"
               data-mg-inquiry-open data-source-component="hinnad_plan_b" data-mg-analytics="magnoolia_cta_click">{{ __('magnoolia.page.kodudjahinnad.plan_b_cta_inq') }} <i class="icon-angle-small-right"></i></a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

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


{{-- ── Answer Unit (AI-citable) ──────────────────────── --}}
@php
  $au = __('magnoolia.answer_unit.homes');
  $au['cta_route'] = lroute('magnoolia.contact');
@endphp
@include('sections.magnoolia.answer-unit', ['unit' => $au])

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
      <a href="#mg-masterplan" class="mg-internal-link"><i class="fas fa-map"></i> {{ __('magnoolia.page.kodudjahinnad.link_plan') }}</a>
      <a href="{{ route('home') }}#plaanid" class="mg-internal-link"
         onclick="event.preventDefault(); if(window.mgPlansOpen){window.mgPlansOpen();}"><i class="fas fa-drafting-compass"></i> {{ __('magnoolia.page.kodudjahinnad.link_floorplans') }}</a>
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
    ['label' => __('magnoolia.page.kodudjahinnad.cta_btn1'), 'url' => lroute('magnoolia.contact'), 'inquiry' => true, 'source' => 'hinnad_page_cta'],
    ['label' => __('magnoolia.page.kodudjahinnad.cta_btn2'), 'url' => 'tel:+37258164078', 'outline' => true, 'icon' => 'fas fa-phone'],
    ['label' => __('magnoolia.page.kodudjahinnad.cta_btn3'), 'url' => '#mg-masterplan', 'outline' => true],
  ]
])

{{-- ── Plans modal: the homepage "Korrusplaanid" section (Plan A/B floor plans),
       opened by "Vaata plaani" — so the plans show in a modal, with no redirect. ── --}}
{{-- The included section uses scroll-in reveal animations that never trigger inside a
     modal: WOW.js (.wow → visibility:hidden) and GSAP SplitText (.sec-title__title /
     .sec-title__tagline / .bw-split-* → each char span set to opacity:0). Force all of
     it visible here, including the split char spans. --}}
<style>
  #mg-plans-overlay .wow,
  #mg-plans-overlay .sec-title__title,
  #mg-plans-overlay .sec-title__tagline,
  #mg-plans-overlay [class*="bw-split"] { visibility:visible !important; opacity:1 !important; animation:none !important; transform:none !important; }
  #mg-plans-overlay .sec-title__title *,
  #mg-plans-overlay .sec-title__tagline *,
  #mg-plans-overlay [class*="bw-split"] * { opacity:1 !important; visibility:visible !important; transform:none !important; }
</style>
<div id="mg-plans-overlay" role="dialog" aria-modal="true" aria-label="{{ __('magnoolia.floorplan.title') }}"
     style="display:none;position:fixed;inset:0;z-index:9050;background:rgba(20,25,33,.6);overflow-y:auto;">
  <div style="position:relative;max-width:1180px;margin:32px auto;background:#fbfaf7;border-radius:18px;overflow:hidden;box-shadow:0 24px 64px rgba(20,25,33,.32);">
    <button type="button" onclick="window.mgPlansClose && window.mgPlansClose()" aria-label="{{ __('magnoolia.floorplan.lightbox_close') }}"
            style="position:absolute;top:16px;right:16px;z-index:5;width:44px;height:44px;border-radius:50%;border:1px solid rgba(29,36,48,.15);background:#fff;cursor:pointer;font-size:22px;line-height:1;color:#1d2430;">&times;</button>
    @include('sections.approved.floor-plan-source')
  </div>
</div>
<script>
(function () {
  var ov = document.getElementById('mg-plans-overlay');
  function open()  {
    if (!ov) return;
    ov.style.display = 'block'; ov.scrollTop = 0; document.body.style.overflow = 'hidden';
    // Reveal WOW.js elements (they stay hidden inside a modal that never scrolls into view).
    ov.querySelectorAll('.wow').forEach(function (el) {
      el.style.visibility = 'visible'; el.style.opacity = '1'; el.style.animation = 'none'; el.classList.add('animated');
    });
  }
  function close() { if (ov) { ov.style.display = 'none'; document.body.style.overflow = ''; } }
  window.mgPlansOpen = open; window.mgPlansClose = close;
  if (ov) ov.addEventListener('click', function (e) { if (e.target === ov) close(); });
  document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && ov && ov.style.display !== 'none') close(); });
})();
</script>

@endsection
