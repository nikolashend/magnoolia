@extends('layouts.app')

@section('title', __('magnoolia.page.galerii.page_title'))
@section('meta_description', $page['description'] ?? '')

@section('content')
@php
  $base   = rtrim(config('magnoolia.seo.canonical_base', 'https://magnoolia.ee'), '/');
  $locale = app()->getLocale();

  // Phase 26 — updated gallery structure
  $allImages = [
    // ── Exterior (3D renders) ─────────────────────────────────────
    ['src' => asset('assets/magnoolia/gallery/exterior/Cam001.jpg'),   'alt' => __('magnoolia.page.galerii.alt_cam001'),   'cat' => 'valised',  'label' => __('magnoolia.page.galerii.label_ext')],
    ['src' => asset('assets/magnoolia/gallery/exterior/Cam004.jpg'),   'alt' => __('magnoolia.page.galerii.alt_cam004'),   'cat' => 'valised',  'label' => __('magnoolia.page.galerii.label_ext')],
    ['src' => asset('assets/magnoolia/gallery/exterior/Cam005.jpg'),   'alt' => __('magnoolia.page.galerii.alt_cam005'),   'cat' => 'valised',  'label' => __('magnoolia.page.galerii.label_terr')],
    ['src' => asset('assets/magnoolia/gallery/exterior/Cam014.jpg'),   'alt' => __('magnoolia.page.galerii.alt_cam014'),   'cat' => 'valised',  'label' => __('magnoolia.page.galerii.label_ext')],
    ['src' => asset('assets/magnoolia/gallery/exterior/magnoolia_cam07.jpg'), 'alt' => __('magnoolia.page.galerii.alt_cam07'), 'cat' => 'valised', 'label' => __('magnoolia.page.galerii.label_ext')],
    ['src' => asset('assets/magnoolia/gallery/exterior/magnoolia_cam09.jpg'), 'alt' => __('magnoolia.page.galerii.alt_cam09'), 'cat' => 'valised', 'label' => __('magnoolia.page.galerii.label_yard')],
    // ── Interior ─────────────────────────────────────────────────
    ['src' => asset('assets/magnoolia/gallery/interior/Interior-1.jpg'),   'alt' => __('magnoolia.page.galerii.alt_int1'), 'cat' => 'interjer', 'label' => __('magnoolia.page.galerii.label_living')],
    ['src' => asset('assets/magnoolia/gallery/interior/Interior-2.jpg'),   'alt' => __('magnoolia.page.galerii.alt_int2'), 'cat' => 'interjer', 'label' => __('magnoolia.page.galerii.label_kitchen')],
    ['src' => asset('assets/magnoolia/gallery/interior/Interior-3.jpg'),   'alt' => __('magnoolia.page.galerii.alt_int3'), 'cat' => 'interjer', 'label' => __('magnoolia.page.galerii.label_bedroom')],
    ['src' => asset('assets/magnoolia/gallery/interior/Interior-4.jpg'),   'alt' => __('magnoolia.page.galerii.alt_int4'), 'cat' => 'interjer', 'label' => __('magnoolia.page.galerii.label_bath')],
    ['src' => asset('assets/magnoolia/gallery/interior/Interior-5-1.jpg'), 'alt' => __('magnoolia.page.galerii.alt_int5a'),'cat' => 'interjer', 'label' => __('magnoolia.page.galerii.label_int')],
    ['src' => asset('assets/magnoolia/gallery/interior/Interior-5-2.jpg'), 'alt' => __('magnoolia.page.galerii.alt_int5b'),'cat' => 'interjer', 'label' => __('magnoolia.page.galerii.label_terr')],
    // ── Environment ───────────────────────────────────────────────
    ['src' => asset('assets/magnoolia/gallery/environment/vaela-lasteaed.webp'), 'alt' => __('magnoolia.page.galerii.alt_env_lasteaed'), 'cat' => 'keskkond', 'label' => __('magnoolia.page.galerii.label_env')],
    ['src' => asset('assets/magnoolia/gallery/environment/kiili-cycling.jpg'),   'alt' => __('magnoolia.page.galerii.alt_env_cycling'),  'cat' => 'keskkond', 'label' => __('magnoolia.page.galerii.label_env')],
    ['src' => asset('assets/magnoolia/gallery/environment/kiili-loodus.jpg'),    'alt' => __('magnoolia.page.galerii.alt_env_loodus'),   'cat' => 'keskkond', 'label' => __('magnoolia.page.galerii.label_env')],
    ['src' => asset('assets/magnoolia/gallery/environment/hea-uhendus-tallinnaga.avif'), 'alt' => __('magnoolia.page.galerii.alt_env_uhendus'), 'cat' => 'keskkond', 'label' => __('magnoolia.page.galerii.label_env')],
    ['src' => asset('assets/magnoolia/gallery/environment/turvaline-keskkond.avif'),     'alt' => __('magnoolia.page.galerii.alt_env_turvaline'), 'cat' => 'keskkond', 'label' => __('magnoolia.page.galerii.label_env')],
  ];

  // Only include images that actually exist on disk
  $images = collect($allImages)->filter(fn($img) => file_exists(public_path(
    str_replace(asset(''), '', $img['src'])
  )));

  // Phase 33.1: if a published gallery exists in the active publication, use it
  // (managed in the admin Media Library). Otherwise keep the built-in list above
  // (safe fallback — zero regression).
  $managedGallery = collect(mg_gallery())->filter(fn($img) => file_exists(public_path(
    str_replace(asset(''), '', $img['src'])
  )));
  if ($managedGallery->isNotEmpty()) {
    $images = $managedGallery;
  }
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

    {{-- Phase 26: Category tabs (keyboard accessible) --}}
    <div class="mg-gallery-filter"
         role="tablist"
         aria-label="{{ __('magnoolia.page.galerii.filter_aria') }}"
         style="margin-bottom:36px;display:flex;gap:8px;flex-wrap:wrap;"
         id="gallery-filter">
      @foreach([
        ['cat' => 'kõik',    'label' => __('magnoolia.page.galerii.filter_all')],
        ['cat' => 'valised', 'label' => __('magnoolia.page.galerii.filter_ext')],
        ['cat' => 'interjer','label' => __('magnoolia.page.galerii.filter_int')],
        ['cat' => 'keskkond','label' => __('magnoolia.page.galerii.filter_env')],
      ] as $idx => $filter)
      <button class="mg-gallery-filter__btn {{ $filter['cat'] === 'kõik' ? 'is-active' : '' }}"
              role="tab"
              aria-selected="{{ $filter['cat'] === 'kõik' ? 'true' : 'false' }}"
              data-filter="{{ $filter['cat'] }}"
              type="button"
              tabindex="{{ $filter['cat'] === 'kõik' ? '0' : '-1' }}"
              id="gallery-tab-{{ $filter['cat'] }}">
        {{ $filter['label'] }}
      </button>
      @endforeach
    </div>

    {{-- Grid --}}
    <div class="mg-gallery-grid" id="gallery-grid" aria-live="polite" aria-label="{{ __('magnoolia.page.galerii.grid_aria') }}">
      @foreach($images as $i => $img)
      @php $imgIdx = is_int($i) ? $i : $loop->index; @endphp
      <div class="mg-gallery-item {{ $imgIdx < 2 ? 'mg-gallery-item--wide' : '' }}"
           data-category="{{ $img['cat'] }}"
           tabindex="0"
           role="button"
           aria-label="{{ __('magnoolia.page.galerii.lightbox_open') }}: {{ $img['alt'] }}"
           data-lightbox-src="{{ $img['src'] }}"
           data-lightbox-alt="{{ $img['alt'] }}"
           onclick="mgLightboxOpen('{{ $img['src'] }}', '{{ addslashes($img['alt']) }}')"
           onkeydown="if(event.key==='Enter'||event.key===' ')mgLightboxOpen('{{ $img['src'] }}', '{{ addslashes($img['alt']) }}')"
           style="cursor:pointer;">
        <img src="{{ $img['src'] }}"
             alt="{{ $img['alt'] }}"
             loading="{{ $imgIdx < 4 ? 'eager' : 'lazy' }}"
             decoding="async"
             width="500" height="380"
             style="width:100%;height:100%;object-fit:cover;">
        <div class="mg-gallery-item__caption" aria-hidden="true">{{ $img['label'] }}</div>
      </div>
      @endforeach
    </div>

    <p class="mg-seo-note" style="margin-top:24px;text-align:center;">
      {{ __('magnoolia.page.galerii.seo_note') }}
    </p>
    <p style="font-size:11px;color:#aaa;text-align:center;margin-top:8px;">
      *{{ __('magnoolia.disclaimer.images') }}
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
    <img id="mg-lightbox-img" src="data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" alt="" aria-hidden="true">
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
