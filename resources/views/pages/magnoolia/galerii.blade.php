@extends('layouts.app')

@section('title', __('magnoolia.page.galerii.page_title'))
@section('meta_description', $page['description'] ?? '')

@section('content')
@php
  $base = rtrim(config('magnoolia.seo.canonical_base', 'https://magnoolia.ee'), '/');

  $allImages = [
    // Exterior
    ['file' => 'Cam001.0000.jpg',          'alt' => 'Magnoolia ridaelamukodud — lõunaküljelt',          'cat' => 'valised',  'label' => 'Välisvaade'],
    ['file' => 'Cam004.0000.jpg',          'alt' => 'Magnoolia ridaelamukodud — fassaadivaade',          'cat' => 'valised',  'label' => 'Välisvaade'],
    ['file' => 'Cam005.0000.jpg',          'alt' => 'Magnoolia ridaelamukodud — terrass',                'cat' => 'valised',  'label' => 'Terrass'],
    ['file' => 'Cam014.0000.jpg',          'alt' => 'Magnoolia ridaelamukodud — sissepääsuvaade',        'cat' => 'valised',  'label' => 'Välisvaade'],
    ['file' => 'magnoolia_cam07.jpg',      'alt' => 'Magnoolia ridaelamukodu — perspektiivvaade',        'cat' => 'valised',  'label' => 'Välisvaade'],
    ['file' => 'magnoolia_cam09.jpg',      'alt' => 'Magnoolia ridaelamukodu — hooviala',                'cat' => 'valised',  'label' => 'Hooviala'],
    ['file' => 'Magnoolia tee_ES_1_15.jpg','alt' => 'Magnoolia tee eskiis',                              'cat' => 'valised',  'label' => 'Eskiis'],
    ['file' => 'Magnoolia tee_ES_2.jpg',   'alt' => 'Magnoolia ridaelamukodu mahuuuring',                'cat' => 'valised',  'label' => 'Eskiis'],
    ['file' => 'Magnoolia tee_ES_7.jpg',   'alt' => 'Magnoolia tee — keskkonna eskiis',                  'cat' => 'valised',  'label' => 'Eskiis'],
    ['file' => 'Magnoolia tee_ES_8.jpg',   'alt' => 'Magnoolia tee — tänavaperspektiiv',                 'cat' => 'valised',  'label' => 'Eskiis'],
    ['file' => 'Magnoolia tee_ES_10.jpg',  'alt' => 'Magnoolia tee — looduskeskkond',                    'cat' => 'valised',  'label' => 'Eskiis'],
    // Interior
    ['file' => 'Interior 1.jpg',           'alt' => 'Magnoolia kodu sisekujunduse näidis — elutuba',     'cat' => 'interjer', 'label' => 'Elutuba'],
    ['file' => 'Interior 2.jpg',           'alt' => 'Magnoolia kodu avatud plaan — elutuba ja köök',     'cat' => 'interjer', 'label' => 'Elutuba / Köök'],
    ['file' => 'Interior 3.jpg',           'alt' => 'Magnoolia ridaelamukodu magamistuba',                'cat' => 'interjer', 'label' => 'Magamistuba'],
    ['file' => 'Interior 4.jpg',           'alt' => 'Magnoolia kodu vannituba',                          'cat' => 'interjer', 'label' => 'Vannituba'],
    ['file' => 'Interior 5-2.jpg',         'alt' => 'Magnoolia ridaelamukodu sisevaade',                 'cat' => 'interjer', 'label' => 'Sisevaade'],
    ['file' => 'Interior 5_1.jpg',         'alt' => 'Magnoolia ridaelamukodu detailvaade',               'cat' => 'interjer', 'label' => 'Detailvaade'],
    // Floor plans
    ['file' => 'PR03023_PP_AR-5-01_Esimese korruse plaan_page-0001.jpg',
                                           'alt' => 'Magnoolia ridaelamukodu I korruse plaan',           'cat' => 'plaanid',  'label' => 'I korrus'],
    ['file' => 'PR03023_PP_AR-5-02_Teise korruse plaan_page-0001.jpg',
                                           'alt' => 'Magnoolia ridaelamukodu II korruse plaan',          'cat' => 'plaanid',  'label' => 'II korrus'],
  ];

  // Filter to existing files only
  $images = collect($allImages)->filter(fn($img) =>
    file_exists(public_path('assets/images/magnoolia/' . $img['file']))
  );
@endphp

<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@graph": [
    {
      "@@type": "BreadcrumbList",
      "itemListElement": [
        { "@@type": "ListItem", "position": 1, "name": "Avaleht", "item": "{{ $base }}" },
        { "@@type": "ListItem", "position": 2, "name": "Galerii", "item": "{{ $base }}/galerii" }
      ]
    }
  ]
}
</script>

{{-- ── Hero ─────────────────────────────────────────────────── --}}
<div class="mg-page-hero" style="background-image:linear-gradient(to right, rgba(29,36,48,.88) 60%, rgba(29,36,48,.5)), url('{{ asset('assets/images/magnoolia/Cam005.0000.jpg') }}');background-size:cover;background-position:center;">
  <div class="container">
    @include('partials.seo.breadcrumb', ['items' => [
      ['label' => __('magnoolia.nav.home'), 'url' => route('home')],
      ['label' => __('magnoolia.nav.gallery')],
    ]])
    <div class="mg-page-hero__eyebrow">{{ __('magnoolia.page.galerii.eyebrow') }}</div>
    <h1 class="mg-page-hero__title">{{ __('magnoolia.page.galerii.page_h1') }}</h1>
    <p class="mg-page-hero__lead">
      {{ __('magnoolia.page.galerii.lead') }}
    </p>
    <p class="mg-page-hero__note">
      {{ __('magnoolia.page.galerii.note') }}
    </p>
  </div>
</div>

{{-- ── Gallery with filter ─────────────────────────────────── --}}
<section class="mg-page-section mg-page-section--white">
  <div class="container">

    {{-- Filter bar --}}
    <div class="mg-gallery-filter" style="margin-bottom:36px;" id="gallery-filter">
      @foreach([
        ['cat' => 'kõik',    'label' => __('magnoolia.page.galerii.filter_all')],
        ['cat' => 'valised', 'label' => __('magnoolia.page.galerii.filter_ext')],
        ['cat' => 'interjer','label' => __('magnoolia.page.galerii.filter_int')],
        ['cat' => 'plaanid', 'label' => __('magnoolia.page.galerii.filter_pl')],
      ] as $filter)
      <button class="mg-gallery-filter__btn {{ $filter['cat'] === 'kõik' ? 'is-active' : '' }}"
              data-filter="{{ $filter['cat'] }}"
              type="button">
        {{ $filter['label'] }}
      </button>
      @endforeach
    </div>

    {{-- Grid --}}
    <div class="mg-gallery-grid" id="gallery-grid">
      @foreach($images as $i => $img)
      <div class="mg-gallery-item {{ $i < 2 ? 'mg-gallery-item--wide' : '' }}"
           data-category="{{ $img['cat'] }}"
           onclick="mgLightboxOpen('{{ asset('assets/images/magnoolia/' . $img['file']) }}', '{{ $img['alt'] }}')"
           style="cursor:pointer;">
        <img src="{{ asset('assets/images/magnoolia/' . $img['file']) }}"
             alt="{{ $img['alt'] }}"
             loading="{{ $i < 4 ? 'eager' : 'lazy' }}"
             width="500" height="380"
             style="width:100%;height:100%;object-fit:cover;">
        <div class="mg-gallery-item__caption">{{ $img['label'] }}</div>
      </div>
      @endforeach
    </div>

    <p class="mg-seo-note" style="margin-top:24px;text-align:center;">
      {{ __('magnoolia.page.galerii.seo_note') }}
    </p>
  </div>
</section>

{{-- ── Internal links ──────────────────────────────────────── --}}
<section class="mg-page-section--cream mg-page-section--sm">
  <div class="container">
    <div class="mg-internal-links">
      <a href="{{ lroute('magnoolia.sisedisain') }}" class="mg-internal-link"><i class="fas fa-couch"></i> {{ __('magnoolia.page.galerii.link_int') }}</a>
      <a href="{{ lroute('magnoolia.arhitektuur') }}" class="mg-internal-link"><i class="fas fa-building"></i> {{ __('magnoolia.page.galerii.link_arch') }}</a>
      <a href="{{ lroute('magnoolia.homes') }}" class="mg-internal-link"><i class="fas fa-table"></i> {{ __('magnoolia.page.galerii.link_homes') }}</a>
      <a href="{{ lroute('magnoolia.contact') }}" class="mg-internal-link"><i class="fas fa-envelope"></i> {{ __('magnoolia.page.galerii.link_cont') }}</a>
    </div>
  </div>
</section>

@include('sections.magnoolia.page-cta', [
  'title'   => __('magnoolia.page.galerii.cta_title'),
  'sub'     => __('magnoolia.page.galerii.cta_sub'),
  'buttons' => [
    ['label' => __('magnoolia.page.galerii.cta_btn1'), 'url' => lroute('magnoolia.contact')],
    ['label' => __('magnoolia.page.galerii.cta_btn2'), 'url' => lroute('magnoolia.homes'), 'outline' => true],
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
// Filter
document.querySelectorAll('.mg-gallery-filter__btn').forEach(function(btn) {
  btn.addEventListener('click', function() {
    var filter = this.dataset.filter;
    document.querySelectorAll('.mg-gallery-filter__btn').forEach(function(b) { b.classList.remove('is-active'); });
    this.classList.add('is-active');

    document.querySelectorAll('#gallery-grid .mg-gallery-item').forEach(function(item) {
      if (filter === 'kõik' || item.dataset.category === filter) {
        item.style.display = '';
      } else {
        item.style.display = 'none';
      }
    });
  });
});

// Lightbox
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
