@extends('layouts.app')

@section('title', __('magnoolia.page.asukoht.page_title'))
@section('meta_description', $page['description'] ?? '')
@section('og_title', $page['title'] ?? '')
@section('og_description', $page['description'] ?? '')

@section('content')
@php
  $base = rtrim(config('magnoolia.seo.canonical_base', 'https://magnoolia.ee'), '/');
@endphp

<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@graph": [
    {
      "@@type": "BreadcrumbList",
      "itemListElement": [
        { "@@type": "ListItem", "position": 1, "name": "Avaleht", "item": "{{ $base }}" },
        { "@@type": "ListItem", "position": 2, "name": "Asukoht", "item": "{{ $base }}/asukoht" }
      ]
    },
    {
      "@@type": "Place",
      "@@id": "{{ $base }}/#place",
      "name": "Magnoolia tee, Vaela küla, Kiili vald",
      "address": {
        "@@type": "PostalAddress",
        "streetAddress": "Magnoolia tee",
        "addressLocality": "Vaela küla, Kiili vald",
        "addressRegion": "Harjumaa",
        "addressCountry": "EE"
      },
      "geo": { "@@type": "GeoCoordinates", "latitude": 59.3488, "longitude": 24.8027 }
    },
    {
      "@@type": "FAQPage",
      "mainEntity": [
        {
          "@@type": "Question",
          "name": "Kui kaugel on Magnoolia Tallinnast?",
          "acceptedAnswer": { "@@type": "Answer", "text": "Magnoolia tee asub Vaela külas, Kiili vallas. Tallinna kesklinnani on sõltuvalt marsruudist ja liiklusolukorrast ligikaudu 20–30 minutit." }
        },
        {
          "@@type": "Question",
          "name": "Kas Vaela külas on lasteaed ja kool?",
          "acceptedAnswer": { "@@type": "Answer", "text": "Kiili vald on kasvav piirkond, kus haridusasutuste täpne paiknemine on täpsustamisel. Küsige Diana käest hetkel kehtiva info kohta." }
        },
        {
          "@@type": "Question",
          "name": "Millised teed viivad Magnoolia juurde?",
          "acceptedAnswer": { "@@type": "Answer", "text": "Asukoht on hea ühendusteedega: Tallinna–Tartu maantee ja Tallinna–Pärnu maantee on mõlemad kiiresti käeulatuses. Täpne juurdepääsutee kinnitatakse ehitusprojektis." }
        }
      ]
    }
  ]
}
</script>

{{-- ── Hero ─────────────────────────────────────────────────── --}}
<div class="mg-page-hero">
  <div class="container">
    @include('partials.seo.breadcrumb', ['items' => [
      ['label' => __('magnoolia.nav.home'), 'url' => route('home')],
      ['label' => __('magnoolia.nav.location')],
    ]])
    <div class="mg-page-hero__eyebrow">{{ __('magnoolia.page.asukoht.eyebrow') }}</div>
    <h1 class="mg-page-hero__title">{{ __('magnoolia.page.asukoht.page_h1') }}</h1>
    <p class="mg-page-hero__lead">
      {{ __('magnoolia.page.asukoht.lead') }}
    </p>
    <div class="mg-page-hero__ctas">
      <a href="https://maps.google.com/?q=Magnoolia+tee,Vaela,Kiili+vald" target="_blank" rel="noopener"
         class="zoomvilla-btn">
        {{ __('magnoolia.page.asukoht.cta_map') }} <i class="fas fa-external-link-alt" style="font-size:12px;"></i>
      </a>
      <a href="{{ lroute('magnoolia.contact') }}" class="zoomvilla-btn zoomvilla-btn--border">{{ __('magnoolia.page.asukoht.cta_inquiry') }}</a>
    </div>
  </div>
</div>

{{-- ── Map fallback ─────────────────────────────────────────── --}}
<section class="mg-page-section mg-page-section--white">
  <div class="container">
    <div class="mg-location-fallback">
      <div class="mg-location-fallback__icon">
        <i class="fas fa-map-marker-alt"></i>
      </div>
      <div class="mg-location-fallback__body">
        <div class="mg-location-fallback__label">{{ __('magnoolia.page.asukoht.address_label') }}</div>
        <div class="mg-location-fallback__address">Magnoolia tee, Vaela küla, Kiili vald, Harjumaa</div>
        <a href="https://maps.google.com/?q=Magnoolia+tee,Vaela,Kiili+vald"
           target="_blank" rel="noopener"
           class="zoomvilla-btn" style="margin-top:20px;display:inline-flex;">
          <i class="fas fa-directions" style="margin-right:8px;"></i> {{ __('magnoolia.page.asukoht.address_map_btn') }}
        </a>
      </div>
      <div class="mg-location-fallback__img" aria-hidden="true">
        <img src="{{ asset('assets/images/magnoolia/Magnoolia tee_ES_7.jpg') }}"
             alt="Magnoolia tee keskkond Vaela külas"
             width="560" height="380" loading="lazy">
      </div>
    </div>
  </div>
</section>

{{-- ── Distance table ───────────────────────────────────────── --}}
<section class="mg-page-section mg-page-section--cream">
  <div class="container">
    <div class="row gutter-y-40 align-items-start">
      <div class="col-lg-6">
        <div class="mg-section-heading" style="margin-bottom:28px;">
          <div class="mg-section-heading__eyebrow">{{ __('magnoolia.page.asukoht.dist_eyebrow') }}</div>
          <h2 class="mg-section-heading__title">{{ __('magnoolia.page.asukoht.dist_title') }}</h2>
          <p class="mg-section-heading__subtitle">
            {{ __('magnoolia.page.asukoht.dist_note') }}
          </p>
        </div>
        <table class="mg-distance-table">
          <thead>
            <tr><th>{{ __('magnoolia.page.asukoht.dist_col_dest') }}</th><th>{{ __('magnoolia.page.asukoht.dist_col_km') }}</th><th>{{ __('magnoolia.page.asukoht.dist_col_time') }}</th></tr>
          </thead>
          <tbody>
            @foreach(__('magnoolia.page.asukoht.distances') as $row)
            <tr>
              <td>{{ $row['dest'] }}</td>
              <td>{{ $row['dist'] }}</td>
              <td>{{ $row['time'] }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
        <p class="mg-seo-note" style="margin-top:14px;">
          {{ __('magnoolia.page.asukoht.seo_note') }}
        </p>
      </div>

      <div class="col-lg-6">
        <div class="row gutter-y-20">
          @foreach([
            ['icon' => 'fas fa-tree',       'title' => __('magnoolia.page.asukoht.card1_title'), 'body' => __('magnoolia.page.asukoht.card1_body')],
            ['icon' => 'fas fa-car',        'title' => __('magnoolia.page.asukoht.card2_title'), 'body' => __('magnoolia.page.asukoht.card2_body')],
            ['icon' => 'fas fa-baby',       'title' => __('magnoolia.page.asukoht.card3_title'), 'body' => __('magnoolia.page.asukoht.card3_body')],
            ['icon' => 'fas fa-chart-line', 'title' => __('magnoolia.page.asukoht.card4_title'), 'body' => __('magnoolia.page.asukoht.card4_body')],
          ] as $card)
          <div class="col-12">
            <div class="mg-proof-card" style="padding:20px 24px;">
              <div class="mg-proof-card__icon"><i class="{{ $card['icon'] }}"></i></div>
              <div>
                <div class="mg-proof-card__title">{{ $card['title'] }}</div>
                <div class="mg-proof-card__body">{{ $card['body'] }}</div>
              </div>
            </div>
          </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ── Neighbourhood editorial ──────────────────────────────── --}}
<section class="mg-page-section mg-page-section--white">
  <div class="container">
    <div class="mg-editorial-row">
      <div class="mg-editorial-row__img">
        <img src="{{ asset('assets/images/magnoolia/Magnoolia tee_ES_8.jpg') }}"
             alt="Vaela küla keskkond — Magnoolia ridaelamukodud"
             width="580" height="420" loading="lazy">
      </div>
      <div class="mg-editorial-row__content">
        <div class="mg-editorial-row__kicker">{{ __('magnoolia.page.asukoht.edit_kicker') }}</div>
        <h2 class="mg-editorial-row__title">{{ __('magnoolia.page.asukoht.edit_title') }}</h2>
        <p class="mg-editorial-row__body">
          {{ __('magnoolia.page.asukoht.edit_body1') }}
        </p>
        <p class="mg-editorial-row__body">
          {{ __('magnoolia.page.asukoht.edit_body2') }}
        </p>
        <a href="{{ lroute('magnoolia.contact') }}" class="zoomvilla-btn" style="margin-top:20px;">
          {{ __('magnoolia.page.asukoht.edit_cta') }} <i class="icon-angle-small-right"></i>
        </a>
      </div>
    </div>
  </div>
</section>

{{-- ── FAQ ─────────────────────────────────────────────────── --}}
@include('sections.magnoolia.page-faq', [
  'eyebrow' => __('magnoolia.page.asukoht.faq_eyebrow'),
  'title'   => __('magnoolia.page.asukoht.faq_title'),
  'bg'      => 'cream',
  'faqs'    => __('magnoolia.page.asukoht.faq_items'),
])

{{-- ── Internal links ──────────────────────────────────────── --}}
<section class="mg-page-section--cream mg-page-section--sm">
  <div class="container">
    <div class="mg-internal-links">
      <a href="{{ lroute('magnoolia.site-plan') }}" class="mg-internal-link"><i class="fas fa-map"></i> {{ __('magnoolia.page.asukoht.link_plan') }}</a>
      <a href="{{ lroute('magnoolia.homes') }}" class="mg-internal-link"><i class="fas fa-table"></i> {{ __('magnoolia.page.asukoht.link_homes') }}</a>
      <a href="{{ lroute('magnoolia.construction') }}" class="mg-internal-link"><i class="fas fa-hard-hat"></i> {{ __('magnoolia.page.asukoht.link_constr') }}</a>
      <a href="{{ lroute('magnoolia.contact') }}" class="mg-internal-link"><i class="fas fa-envelope"></i> {{ __('magnoolia.page.asukoht.link_cont') }}</a>
    </div>
  </div>
</section>

@include('sections.magnoolia.page-cta', [
  'title'   => __('magnoolia.page.asukoht.cta_title'),
  'sub'     => __('magnoolia.page.asukoht.cta_sub'),
  'buttons' => [
    ['label' => __('magnoolia.page.asukoht.cta_btn1'), 'url' => lroute('magnoolia.contact')],
    ['label' => __('magnoolia.page.asukoht.cta_btn2'), 'url' => lroute('magnoolia.homes'), 'outline' => true],
  ]
])

@endsection
