@extends('layouts.app')

@section('title', __('magnoolia.page.arhitektuur.page_title'))
@section('meta_description', $page['description'] ?? '')
@section('og_title', $page['title'] ?? '')
@section('og_description', $page['description'] ?? '')

@section('content')
@php
  $base = rtrim(config('magnoolia.seo.canonical_base', 'https://magnoolia.ee'), '/');
  $exteriorImages = [
    ['file' => 'Cam001.0000.jpg',         'alt' => 'Magnoolia ridaelamukodude välisvaade lõunast',        'cat' => 'välisvaated'],
    ['file' => 'Cam004.0000.jpg',         'alt' => 'Magnoolia ridaelamukodud — fassaadivaade',             'cat' => 'välisvaated'],
    ['file' => 'Cam005.0000.jpg',         'alt' => 'Magnoolia ridaelamukodud — terrass ja hooviala',       'cat' => 'välisvaated'],
    ['file' => 'Cam014.0000.jpg',         'alt' => 'Magnoolia ridaelamukodud — sissepääsuvaade',           'cat' => 'välisvaated'],
    ['file' => 'magnoolia_cam07.jpg',     'alt' => 'Magnoolia ridaelamukodu tänavaperspektiivist',         'cat' => 'välisvaated'],
    ['file' => 'magnoolia_cam09.jpg',     'alt' => 'Magnoolia ridaelamukodu — hooviala render',            'cat' => 'välisvaated'],
    ['file' => 'Magnoolia tee_ES_1_15.jpg','alt' => 'Magnoolia tee eskiis — välisvaade',                  'cat' => 'välisvaated'],
    ['file' => 'Magnoolia tee_ES_2.jpg',  'alt' => 'Magnoolia ridaelamukodu fassaad — mahuuuring',         'cat' => 'välisvaated'],
  ];
@endphp

<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@graph": [
    {
      "@@type": "BreadcrumbList",
      "itemListElement": [
        { "@@type": "ListItem", "position": 1, "name": "Avaleht", "item": "{{ $base }}" },
        { "@@type": "ListItem", "position": 2, "name": "Arhitektuur ja välisdisain", "item": "{{ $base }}/arhitektuur-ja-valisdisain" }
      ]
    },
    {
      "@@type": "ApartmentComplex",
      "@@id": "{{ $base }}/#complex",
      "name": "Magnoolia ridaelamukodud",
      "description": "19 ridaelamukodu kahekorruseliste hoonetega, A-energiaklass, Vaela küla",
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
<div class="mg-page-hero" style="background-image:linear-gradient(to right, rgba(29,36,48,.88) 60%, rgba(29,36,48,.5)), url('{{ asset('assets/images/magnoolia/Cam001.0000.jpg') }}'); background-size:cover; background-position:center;">
  <div class="container">
    @include('partials.seo.breadcrumb', ['items' => [
      ['label' => __('magnoolia.nav.home'), 'url' => route('home')],
      ['label' => __('magnoolia.nav.architecture')],
    ]])
    <div class="mg-page-hero__eyebrow">{{ __('magnoolia.page.arhitektuur.eyebrow') }}</div>
    <h1 class="mg-page-hero__title">{{ __('magnoolia.page.arhitektuur.page_h1') }}</h1>
    <p class="mg-page-hero__lead">{{ __('magnoolia.page.arhitektuur.lead') }}</p>
    <div class="mg-page-hero__ctas">
      <a href="#fassaad" class="zoomvilla-btn">{{ __('magnoolia.page.arhitektuur.cta_gallery') }} <i class="icon-angle-small-right"></i></a>
      <a href="{{ lroute('magnoolia.galerii') }}" class="zoomvilla-btn zoomvilla-btn--border">{{ __('magnoolia.page.arhitektuur.cta_galerii') }}</a>
    </div>
  </div>
</div>

{{-- ── Editorial — maja tunne ──────────────────────────────── --}}
<section class="mg-page-section mg-page-section--white" id="fassaad">
  <div class="container">
    <div class="mg-editorial-row">
      <div class="mg-editorial-row__img">
        <img src="{{ asset('assets/images/magnoolia/Cam001.0000.jpg') }}"
             alt="Magnoolia ridaelamukodude välisvaade"
             width="580" height="420" loading="eager">
      </div>
      <div class="mg-editorial-row__content">
      <div class="mg-editorial-row__kicker">{{ __('magnoolia.page.arhitektuur.ed_kicker') }}</div>
        <h2 class="mg-editorial-row__title">{{ __('magnoolia.page.arhitektuur.ed_title') }}</h2>
        <p class="mg-editorial-row__body">{{ __('magnoolia.page.arhitektuur.ed_body1') }}</p>
        <p class="mg-editorial-row__body">{{ __('magnoolia.page.arhitektuur.ed_body2') }}</p>
      </div>
    </div>
  </div>
</section>

{{-- ── Feature rows: Fassaad, Terrass, Hooviala, Parkimine ── --}}
<section class="mg-page-section mg-page-section--cream">
  <div class="container">
    <div class="mg-section-heading" style="margin-bottom:40px;">
      <div class="mg-section-heading__eyebrow">{{ __('magnoolia.page.arhitektuur.feat_eyebrow') }}</div>
      <h2 class="mg-section-heading__title">{{ __('magnoolia.page.arhitektuur.feat_title') }}</h2>
    </div>

    @foreach(__('magnoolia.page.arhitektuur.features') as $row)
    <div class="mg-feature-row {{ $row['reverse'] ? 'mg-feature-row--reverse' : '' }}" style="margin-bottom:60px;">
      <div class="mg-feature-row__img">
        <img src="{{ asset('assets/images/magnoolia/' . $row['img']) }}"
             alt="{{ $row['alt'] ?? $row['title'] ?? 'Magnoolia' }}"
             loading="lazy" width="560" height="400">
      </div>
      <div class="mg-feature-row__content">
        <div class="mg-feature-row__kicker">{{ $row['kicker'] }}</div>
        <h3 class="mg-feature-row__title">{{ $row['title'] }}</h3>
        <p class="mg-feature-row__body">{{ $row['body'] }}</p>
        <ul class="mg-feature-row__list">
          @foreach($row['list'] as $li)
          <li>{{ $li }}</li>
          @endforeach
        </ul>
      </div>
    </div>
    @endforeach
  </div>
</section>

{{-- ── More exterior renders ───────────────────────────────── --}}
<section class="mg-page-section mg-page-section--white">
  <div class="container">
    <div class="mg-section-heading" style="margin-bottom:32px;">
      <div class="mg-section-heading__eyebrow">{{ __('magnoolia.page.arhitektuur.renders_eyebrow') }}</div>
      <h2 class="mg-section-heading__title">{{ __('magnoolia.page.arhitektuur.renders_title') }}</h2>
    </div>

    <div class="mg-gallery-grid">
      @foreach($exteriorImages as $i => $img)
        @php $exists = file_exists(public_path('assets/images/magnoolia/' . $img['file'])); @endphp
        @if($exists)
        <div class="mg-gallery-item"
             onclick="mgLightboxOpen('{{ asset('assets/images/magnoolia/' . $img['file']) }}', '{{ $img['alt'] }}')"
             style="cursor:pointer;">
          <img src="{{ asset('assets/images/magnoolia/' . $img['file']) }}"
               alt="{{ $img['alt'] }}"
               loading="lazy" width="500" height="380" style="width:100%;height:100%;object-fit:cover;">
        </div>
        @endif
      @endforeach
    </div>
    <p class="mg-seo-note" style="margin-top:20px;">{{ __('magnoolia.page.arhitektuur.renders_note') }}</p>
  </div>
</section>

{{-- ── FAQ ─────────────────────────────────────────────────── --}}
@include('sections.magnoolia.page-faq', [
  'eyebrow' => __('magnoolia.page.arhitektuur.faq_eyebrow'),
  'title'   => __('magnoolia.page.arhitektuur.faq_title'),
  'bg'      => 'cream',
  'faqs'    => __('magnoolia.page.arhitektuur.faq_items'),
])

{{-- ── Internal links ──────────────────────────────────────── --}}
<section class="mg-page-section--cream mg-page-section--sm">
  <div class="container">
    <div class="mg-internal-links">
      <a href="{{ lroute('magnoolia.sisedisain') }}" class="mg-internal-link"><i class="fas fa-couch"></i> {{ __('magnoolia.page.arhitektuur.link_int') }}</a>
      <a href="{{ lroute('magnoolia.galerii') }}" class="mg-internal-link"><i class="fas fa-images"></i> {{ __('magnoolia.page.arhitektuur.link_gallery') }}</a>
      <a href="{{ lroute('magnoolia.construction') }}" class="mg-internal-link"><i class="fas fa-hard-hat"></i> {{ __('magnoolia.page.arhitektuur.link_constr') }}</a>
      <a href="{{ lroute('magnoolia.contact') }}" class="mg-internal-link"><i class="fas fa-envelope"></i> {{ __('magnoolia.page.arhitektuur.link_cont') }}</a>
    </div>
  </div>
</section>

@include('sections.magnoolia.page-cta', [
  'title'   => __('magnoolia.page.arhitektuur.cta_title'),
  'sub'     => __('magnoolia.page.arhitektuur.cta_sub'),
  'buttons' => [
    ['label' => __('magnoolia.page.arhitektuur.cta_btn1'), 'url' => lroute('magnoolia.contact')],
    ['label' => __('magnoolia.page.arhitektuur.cta_btn2'), 'url' => lroute('magnoolia.galerii'), 'outline' => true],
  ]
])

{{-- Lightbox --}}
<div id="mg-lightbox" onclick="this.style.display='none'" style="display:none;">
  <div class="mg-lightbox__inner">
    <img id="mg-lightbox-img" src="" alt="">
    <div id="mg-lightbox-cap"></div>
  </div>
</div>

@endsection

@push('scripts')
<script>
function mgLightboxOpen(src, alt) {
  var lb = document.getElementById('mg-lightbox');
  document.getElementById('mg-lightbox-img').src = src;
  document.getElementById('mg-lightbox-img').alt = alt;
  document.getElementById('mg-lightbox-cap').textContent = alt;
  lb.style.display = 'flex';
}
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') { var lb = document.getElementById('mg-lightbox'); if(lb) lb.style.display='none'; }
});
</script>
@endpush
