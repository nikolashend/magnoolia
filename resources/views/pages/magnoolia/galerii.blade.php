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

  // Pick optimized responsive WebP variants ({stem}-480w/-768w/-1200w.webp) for
  // fast loading; fall back to the original when no variant exists. Returns the
  // grid thumb + srcset (small) and a larger image for the lightbox.
  $pick = function (string $src): array {
    $rel  = ltrim(str_replace(asset(''), '', $src), '/');
    $dir  = trim(dirname($rel), '.');
    $stem = pathinfo($rel, PATHINFO_FILENAME);
    $vars = [];
    foreach ([480, 768, 1200] as $w) {
      $vrel = ($dir ? $dir . '/' : '') . $stem . '-' . $w . 'w.webp';
      if (is_file(public_path($vrel))) { $vars[$w] = asset($vrel); }
    }
    if ($vars) {
      $srcset = implode(', ', array_map(fn ($w) => $vars[$w] . ' ' . $w . 'w', array_keys($vars)));
      return [
        'thumb'  => $vars[768] ?? end($vars),
        'srcset' => $srcset,
        'full'   => $vars[1200] ?? end($vars),
      ];
    }
    return ['thumb' => $src, 'srcset' => '', 'full' => $src];
  };
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
      @php $imgIdx = $loop->index; $v = $pick($img['src']); @endphp
      <div class="mg-gallery-item"
           data-category="{{ $img['cat'] }}"
           tabindex="0"
           role="button"
           aria-label="{{ __('magnoolia.page.galerii.lightbox_open') }}: {{ $img['alt'] }}"
           data-lightbox-src="{{ $v['full'] }}"
           data-lightbox-alt="{{ $img['alt'] }}"
           onclick="mgLightboxOpenEl(this)"
           onkeydown="if(event.key==='Enter'||event.key===' '){event.preventDefault();mgLightboxOpenEl(this);}"
           style="cursor:pointer;">
        <img src="{{ $v['thumb'] }}"
             @if($v['srcset']) srcset="{{ $v['srcset'] }}" sizes="(max-width:576px) 100vw, (max-width:1024px) 50vw, 360px" @endif
             alt="{{ $img['alt'] }}"
             loading="{{ $imgIdx < 6 ? 'eager' : 'lazy' }}"
             decoding="async"
             width="500" height="375">
        <div class="mg-gallery-item__overlay" aria-hidden="true"><i class="fas fa-search-plus"></i></div>
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
<div id="mg-lightbox" onclick="mgLightboxClose()" role="dialog" aria-modal="true" aria-label="{{ __('magnoolia.page.galerii.grid_aria') }}" style="display:none;">
  <button type="button" id="mg-lightbox-close" aria-label="{{ __('magnoolia.rowhouse.modal_close') }}" onclick="event.stopPropagation();mgLightboxClose()">&#x2715;</button>
  <button type="button" class="mg-lightbox-nav mg-lightbox-nav--prev" aria-label="{{ __('magnoolia.rowhouse.view_prev') }}" onclick="event.stopPropagation();mgLightboxStep(-1)">&#x2039;</button>
  <button type="button" class="mg-lightbox-nav mg-lightbox-nav--next" aria-label="{{ __('magnoolia.rowhouse.view_next') }}" onclick="event.stopPropagation();mgLightboxStep(1)">&#x203A;</button>
  <div class="mg-lightbox__inner" onclick="event.stopPropagation()">
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

// Lightbox with prev/next navigation
var mgLbItems = [];   // currently-open list of {src, alt}
var mgLbIndex = 0;

function mgLightboxVisible() {
  // Only the items currently shown by the active filter, in DOM order.
  return Array.prototype.filter.call(
    document.querySelectorAll('#gallery-grid .mg-gallery-item'),
    function (el) { return el.style.display !== 'none'; }
  ).map(function (el) {
    return { src: el.getAttribute('data-lightbox-src'), alt: el.getAttribute('data-lightbox-alt') };
  });
}

function mgLightboxRender() {
  var item = mgLbItems[mgLbIndex];
  if (!item) return;
  document.getElementById('mg-lightbox-img').src = item.src;
  document.getElementById('mg-lightbox-img').alt = item.alt;
  document.getElementById('mg-lightbox-cap').textContent =
    item.alt + '  ·  ' + (mgLbIndex + 1) + ' / ' + mgLbItems.length;
}

function mgLightboxOpenEl(el) {
  mgLbItems = mgLightboxVisible();
  var src = el.getAttribute('data-lightbox-src');
  mgLbIndex = Math.max(0, mgLbItems.findIndex(function (i) { return i.src === src; }));
  var nav = document.querySelectorAll('.mg-lightbox-nav');
  for (var k = 0; k < nav.length; k++) { nav[k].style.display = mgLbItems.length > 1 ? '' : 'none'; }
  mgLightboxRender();
  document.getElementById('mg-lightbox').style.display = 'flex';
  document.body.style.overflow = 'hidden';
}

function mgLightboxStep(d) {
  if (!mgLbItems.length) return;
  mgLbIndex = (mgLbIndex + d + mgLbItems.length) % mgLbItems.length;
  mgLightboxRender();
}

function mgLightboxClose() {
  var lb = document.getElementById('mg-lightbox');
  if (lb) lb.style.display = 'none';
  document.body.style.overflow = '';
}

document.addEventListener('keydown', function (e) {
  var lb = document.getElementById('mg-lightbox');
  if (!lb || lb.style.display === 'none') return;
  if (e.key === 'Escape') mgLightboxClose();
  else if (e.key === 'ArrowRight') mgLightboxStep(1);
  else if (e.key === 'ArrowLeft') mgLightboxStep(-1);
});
</script>
@endpush
