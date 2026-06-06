@extends('layouts.app')

@php
  use App\Services\Magnoolia\MagnooliaUnitDiscoveryService;
@endphp

@section('title', __('magnoolia.page.asendiplaan.page_title'))
@section('meta_description', $page['description'] ?? '')
@section('og_title', $page['title'] ?? '')
@section('og_description', $page['description'] ?? '')

@section('content')
@php
  $base  = rtrim(config('magnoolia.seo.canonical_base', 'https://magnoolia.ee'), '/');
  $units = collect($mgPublic['units'] ?? []);

  // Group by building
  $byBuilding = $units->groupBy('building');

  // Stage metadata
  $stageMap = __('magnoolia.page.asendiplaan.stage_map');

  $statuses = config('magnoolia.statuses', []);
@endphp

<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@graph": [
    {
      "@@type": "BreadcrumbList",
      "itemListElement": [
        { "@@type": "ListItem", "position": 1, "name": "Avaleht", "item": "{{ $base }}" },
        { "@@type": "ListItem", "position": 2, "name": "Asendiplaan", "item": "{{ $base }}/asendiplaan" }
      ]
    },
    {
      "@@type": "Place",
      "@@id": "{{ $base }}/#place",
      "name": "Magnoolia ridaelamukodud — Vaela küla",
      "description": "19 ridaelamukodu kahes etapis, Vaela külas, Kiili vallas.",
      "address": {
        "@@type": "PostalAddress",
        "streetAddress": "Magnoolia tee",
        "addressLocality": "Vaela küla, Kiili vald",
        "addressRegion": "Harjumaa",
        "addressCountry": "EE"
      },
      "geo": { "@@type": "GeoCoordinates", "latitude": 59.3488, "longitude": 24.8027 }
    }
  ]
}
</script>

{{-- ── Hero ─────────────────────────────────────────────────── --}}
<div class="mg-page-hero" style="background-image:linear-gradient(to right, rgba(29,36,48,.88) 60%, rgba(29,36,48,.5)), url('{{ asset('assets/images/magnoolia/magnoolia_cam09.jpg') }}');background-size:cover;background-position:center;">
  <div class="container">
    @include('partials.seo.breadcrumb', ['items' => [
      ['label' => __('magnoolia.nav.home'), 'url' => route('home')],
      ['label' => __('magnoolia.nav.masterplan')],
    ]])
    <div class="mg-page-hero__eyebrow">{{ __('magnoolia.page.asendiplaan.eyebrow') }}</div>
    <h1 class="mg-page-hero__title">{{ __('magnoolia.page.asendiplaan.page_h1') }}</h1>
    <p class="mg-page-hero__lead">
      {{ __('magnoolia.page.asendiplaan.lead') }}
    </p>
    <p class="mg-page-hero__note">
      {{ __('magnoolia.page.asendiplaan.note') }}
    </p>
    <div class="mg-page-hero__ctas">
      <a href="#kodud-kaardil" class="zoomvilla-btn">{{ __('magnoolia.page.asendiplaan.cta_view') }} <i class="icon-angle-small-right"></i></a>
      <a href="{{ lroute('magnoolia.contact') }}" class="zoomvilla-btn zoomvilla-btn--border">{{ __('magnoolia.page.asendiplaan.cta_inquiry') }} <i class="icon-angle-small-right"></i></a>
    </div>
  </div>
</div>

{{-- ── Masterplan visual + stage overview ─────────────────── --}}
<section class="mg-page-section mg-page-section--cream" id="kodud-kaardil">
  <div class="container">
    <div class="mg-section-heading mg-section-heading--center" style="margin-bottom:40px;">
      <div class="mg-section-heading__eyebrow">{{ __('magnoolia.page.asendiplaan.section_eyebrow') }}</div>
      <h2 class="mg-section-heading__title">{{ __('magnoolia.page.asendiplaan.section_title') }}</h2>
    </div>

    <div class="row gutter-y-40 align-items-start">
      {{-- Visual --}}
      <div class="col-lg-8">
        <div class="mg-image-card" style="min-height:420px;">
          @php
            $masterImg = public_path('assets/images/magnoolia/Cam004.0000.jpg');
          @endphp
          @if(file_exists($masterImg))
            <img src="{{ asset('assets/images/magnoolia/Cam004.0000.jpg') }}"
                 alt="Magnoolia ridaelamukodude asendiplaan Vaela külas, 19 kodu"
                 width="900" height="600" loading="lazy" style="min-height:420px;object-fit:cover;">
          @else
            <div style="min-height:420px;display:flex;align-items:center;justify-content:center;background:#e8e3db;">
              <div style="text-align:center;color:#a09a8e;">
                <i class="fas fa-map" style="font-size:48px;margin-bottom:12px;display:block;"></i>
                <p style="font-size:14px;margin:0;">Asendiplaanikuva lisatakse</p>
              </div>
            </div>
          @endif
          <div class="mg-image-card__caption">{{ __('magnoolia.section.asendiplaan_page_caption') }}</div>
        </div>
        <p class="mg-seo-note" style="margin-top:16px;">
          {{ __('magnoolia.section.asendiplaan_page_note') }}
        </p>
        <a href="{{ asset('assets/magnoolia/asendiplaan/asendiplaan.pdf') }}"
           target="_blank"
           rel="noopener noreferrer"
           class="zoomvilla-btn zoomvilla-btn--border"
           style="margin-top:12px;">
          {{ __('magnoolia.modal.download') }} PDF <i class="icon-angle-small-right"></i>
        </a>
      </div>

      {{-- Stage summary --}}
      <div class="col-lg-4">
        <div style="display:flex;flex-direction:column;gap:20px;">
          @foreach($stageMap as $stageNum => $stage)
          @php $stageUnits = $units->where('stage', $stageNum); @endphp
          <div style="background:#fff;border-radius:16px;padding:24px;border:1px solid rgba(29,36,48,.08);">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:14px;">
              <span class="mg-stage-badge mg-stage-badge--{{ $stageNum }}">{{ $stage['label'] }}</span>
              <span style="font-size:13px;color:#888;">{{ $stage['completion'] }}</span>
            </div>
            <div style="font-size:22px;font-weight:700;color:#1d2430;margin-bottom:4px;">
              {{ $stageUnits->count() }} {{ __('magnoolia.page.asendiplaan.homes_unit') }}
            </div>
            <div style="font-size:13px;color:#6f6a61;margin-bottom:16px;">
              @php $buildings = $stageUnits->pluck('building')->unique()->values(); @endphp
              {{ $buildings->implode(' · ') }}
            </div>
            <div style="display:flex;gap:12px;font-size:13px;">
              @php
                $avail    = $stageUnits->where('status', 'available')->count();
                $reserved = $stageUnits->where('status', 'reserved')->count();
                $sold     = $stageUnits->where('status', 'sold')->count();
              @endphp
              @if($avail)   <span style="color:#4caf50;font-weight:600;">{{ $avail }} {{ __('magnoolia.statuses.available') }}</span> @endif
              @if($reserved)<span style="color:#c89443;font-weight:600;">{{ $reserved }} {{ __('magnoolia.statuses.reserved') }}</span> @endif
              @if($sold)    <span style="color:#aaa;font-weight:600;">{{ $sold }} {{ __('magnoolia.statuses.sold') }}</span> @endif
            </div>
          </div>
          @endforeach

          {{-- What map helps assess --}}
          <div style="background:#1d2430;border-radius:16px;padding:24px;">
            <div style="font-size:11px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#c89443;margin-bottom:12px;">{{ __('magnoolia.page.asendiplaan.assess_title') }}</div>
            <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:8px;">
              @foreach(__('magnoolia.page.asendiplaan.assess_items') as $tip)
              <li style="display:flex;align-items:flex-start;gap:8px;font-size:13px;color:rgba(255,255,255,.65);">
                <i class="fas fa-check" style="color:#c89443;margin-top:2px;flex-shrink:0;font-size:11px;"></i>
                {{ $tip }}
              </li>
              @endforeach
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ── Unit list by building ─────────────────────────────── --}}
<section class="mg-page-section mg-page-section--white">
  <div class="container">
    <div class="mg-section-heading">
      <div class="mg-section-heading__eyebrow">{{ __('magnoolia.page.asendiplaan.list_eyebrow') }}</div>
      <h2 class="mg-section-heading__title">{{ __('magnoolia.page.asendiplaan.list_title') }}</h2>
      <p class="mg-section-heading__subtitle">
        {{ __('magnoolia.page.asendiplaan.list_sub') }}
      </p>
    </div>

    <div class="row gutter-y-32">
      @foreach($byBuilding as $building => $buildingUnits)
        @php
          $stageNum = $buildingUnits->first()['stage'];
          $completion = $buildingUnits->first()['completion'];
        @endphp
        <div class="col-lg-4 col-md-6">
          <div class="mg-unit-group">
            <div class="mg-unit-group__address">{{ $building }}</div>
            <div style="font-size:12px;color:#888;margin-bottom:10px;">
              <span class="mg-stage-badge mg-stage-badge--{{ $stageNum }}">{{ $stageMap[$stageNum]['label'] ?? '' }}</span>
              &nbsp;{{ $completion }}
            </div>
            <div class="mg-unit-group__items">
              @foreach($buildingUnits as $unit)
                @php
                  $chipClass = 'mg-unit-chip--' . ($unit['status'] === 'sold' ? 'sold' : ($unit['status'] === 'reserved' ? 'reserved' : 'available'));
                  $planLabel = (($unit['plan_type'] ?? null) === 'type-b') ? 'B' : 'A';
                  $canOpen   = in_array($unit['status'], ['available', 'reserved']);
                @endphp
                <button
                  class="mg-unit-chip {{ $chipClass }}"
                  style="border:none;cursor:{{ $canOpen ? 'pointer' : 'default' }};background:none;"
                  @if($canOpen)
                    onclick="mgOpenUnit('{{ $unit['unit_key'] }}')"
                    onkeydown="if(event.key==='Enter'||event.key===' ')mgOpenUnit('{{ $unit['unit_key'] }}')"
                    aria-label="Kodu {{ $unit['address'] }} — {{ $unit['status'] === 'available' ? 'saadaval' : ($unit['status'] === 'reserved' ? 'broneeritud' : 'müüdud') }}"
                    tabindex="0"
                  @else
                    aria-label="Kodu {{ $unit['address'] }}"
                    tabindex="-1"
                  @endif
                >
                  <span class="mg-unit-chip__dot"></span>
                  {{ $unit['section'] }}
                  <span style="font-size:11px;opacity:.7;">P{{ $planLabel }}</span>
                </button>
              @endforeach
            </div>
          </div>
        </div>
      @endforeach
    </div>

    <div class="mg-seo-note" style="margin-top:32px;">
      <strong>{{ __('magnoolia.page.asendiplaan.legend_title') }}</strong>
      <span style="color:#4caf50;font-weight:600;">● {{ __('magnoolia.statuses.available') }}</span> &nbsp;
      <span style="color:#c89443;font-weight:600;">● {{ __('magnoolia.statuses.reserved') }}</span> &nbsp;
      <span style="color:#aaa;font-weight:600;">● {{ __('magnoolia.statuses.sold') }}</span>. &nbsp;
      {{ __('magnoolia.page.asendiplaan.legend_note') }}
    </div>
  </div>
</section>

{{-- ── Existing asendiplaan section ────────────────────────── --}}
@include('sections.magnoolia.asendiplaan')

{{-- ── Answer Unit (AI-citable) ──────────────────────── --}}
@php
  $au = __('magnoolia.answer_unit.asendiplaan');
  $au['cta_route'] = lroute('magnoolia.contact');
@endphp
@include('sections.magnoolia.answer-unit', ['unit' => $au])

{{-- ── FAQ ─────────────────────────────────────────────────── --}}
@include('sections.magnoolia.page-faq', [
  'eyebrow' => __('magnoolia.page.asendiplaan.faq_eyebrow'),
  'title'   => __('magnoolia.page.asendiplaan.faq_title'),
  'bg'      => 'cream',
  'faqs'    => __('magnoolia.page.asendiplaan.faq_items'),
])

{{-- ── Internal links ──────────────────────────────────────── --}}
<section class="mg-page-section--cream mg-page-section--sm">
  <div class="container">
    <div class="mg-internal-links">
      <a href="{{ lroute('magnoolia.homes') }}" class="mg-internal-link"><i class="fas fa-table"></i> {{ __('magnoolia.page.asendiplaan.link_homes') }}</a>
      <a href="{{ lroute('magnoolia.location') }}" class="mg-internal-link"><i class="fas fa-map-marker-alt"></i> {{ __('magnoolia.page.asendiplaan.link_loc') }}</a>
      <a href="{{ lroute('magnoolia.arhitektuur') }}" class="mg-internal-link"><i class="fas fa-building"></i> {{ __('magnoolia.page.asendiplaan.link_arch') }}</a>
      <a href="{{ lroute('magnoolia.contact') }}" class="mg-internal-link"><i class="fas fa-envelope"></i> {{ __('magnoolia.page.asendiplaan.link_cont') }}</a>
    </div>
  </div>
</section>

@include('sections.magnoolia.page-cta', [
  'title'   => __('magnoolia.page.asendiplaan.cta_title'),
  'sub'     => __('magnoolia.page.asendiplaan.cta_sub'),
  'buttons' => [
    ['label' => __('magnoolia.page.asendiplaan.cta_inquiry2'), 'url' => lroute('magnoolia.contact')],
    ['label' => __('magnoolia.page.asendiplaan.cta_homes'), 'url' => lroute('magnoolia.homes'), 'outline' => true],
  ]
])

{{-- ── Asendiplaan side panel & unit JS ──────────────────────── --}}
@php
  $locale = app()->getLocale();
  $unitsJson = json_encode(collect($mgPublic['units'] ?? [])->values()->map(function($u) use ($locale) {
    return [
      'key'        => $u['unit_key'],
      'slug'       => $u['slug'] ?? $u['unit_key'],
      'address'    => $u['address'],
      'building'   => $u['building'] ?? '',
      'stage'      => $u['stage'] ?? 1,
      'status'     => $u['status'] ?? 'available',
      'rooms'      => $u['rooms'] ?? null,
      'net_area'   => $u['net_area'] ?? null,
      'price'      => ($u['price_public'] ?? false) ? ($u['price'] ?? null) : null,
      'price_public' => $u['price_public'] ?? false,
      'plan_type'  => $u['plan_type'] ?? null,
      'completion' => $u['completion'] ?? null,
      'page_url'   => \App\Services\Magnoolia\MagnooliaUnitDiscoveryService::unitPageUrl($u, $locale),
    ];
  })->all(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
@endphp

{{-- Side panel (slides in from right on desktop, bottom sheet on mobile) --}}
<div id="mg-unit-panel"
     aria-label="Valitud kodu info"
     style="position:fixed;top:0;right:0;width:360px;max-width:100vw;height:100vh;background:#fff;box-shadow:-4px 0 32px rgba(29,36,48,.18);z-index:9999;transform:translateX(100%);transition:transform .3s ease;overflow-y:auto;display:flex;flex-direction:column;">
  <div style="padding:20px 20px 12px;border-bottom:1px solid rgba(29,36,48,.08);display:flex;align-items:center;justify-content:space-between;">
    <div style="font-size:13px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#c89443;">Valitud kodu</div>
    <button onclick="mgClosePanel()" aria-label="Sulge"
            style="background:none;border:none;cursor:pointer;color:#888;font-size:22px;line-height:1;padding:0;">×</button>
  </div>
  <div id="mg-unit-panel-body" style="padding:20px;flex:1;">
    {{-- Populated by JS --}}
  </div>
</div>
<div id="mg-unit-panel-overlay"
     onclick="mgClosePanel()"
     style="position:fixed;inset:0;background:rgba(29,36,48,.4);z-index:9998;display:none;"></div>

<script>
(function() {
  var UNITS = {!! $unitsJson !!};

  var statusLabels = {
    'available': 'Saadaval', 'reserved': 'Broneeritud', 'sold': 'Müüdud', 'tbc': 'Tulemas'
  };
  var statusColors = {
    'available': '#4caf50', 'reserved': '#c89443', 'sold': '#888', 'tbc': '#9c27b0'
  };

  function findUnit(key) {
    return UNITS.find(function(u) { return u.key === key || u.slug === key; }) || null;
  }

  window.mgOpenUnit = function(key) {
    var unit = findUnit(key);
    if (!unit) return;

    // Update hash
    history.pushState(null, '', '#' + unit.slug);

    // Render panel
    var panel = document.getElementById('mg-unit-panel');
    var body  = document.getElementById('mg-unit-panel-body');
    var overlay = document.getElementById('mg-unit-panel-overlay');

    var planMap = {'type-a': 'A', 'type-b': 'B'};
    var planLabel = unit.plan_type ? 'Plaan ' + (planMap[unit.plan_type] || '') : '';
    var priceStr  = unit.price ? unit.price.toLocaleString('et-EE') + ' €' : (unit.price_public ? null : 'Hind täpsustamisel');
    var statusLabel = statusLabels[unit.status] || unit.status;
    var statusColor = statusColors[unit.status] || '#888';

    body.innerHTML = [
      '<div style="margin-bottom:16px;">',
        '<div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:10px;">',
          '<span style="background:' + (unit.stage===1?'#c89443':'#5b8dd9') + ';color:#fff;font-size:11px;font-weight:700;padding:3px 10px;border-radius:20px;">' + (unit.stage===1?'I etapp':'II etapp') + '</span>',
          planLabel ? '<span style="background:#f5f0e8;color:#8a7760;font-size:11px;font-weight:600;padding:3px 8px;border-radius:12px;">' + planLabel + '</span>' : '',
        '</div>',
        '<div style="font-size:22px;font-weight:700;color:#1d2430;margin-bottom:6px;">' + unit.address + '</div>',
        '<div style="display:flex;align-items:center;gap:6px;font-size:13px;font-weight:600;color:' + statusColor + ';">',
          '<span style="width:8px;height:8px;border-radius:50%;background:' + statusColor + ';"></span>' + statusLabel,
        '</div>',
      '</div>',

      '<div style="display:flex;flex-direction:column;gap:0;margin-bottom:20px;">',
        unit.rooms ? '<div style="display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid rgba(29,36,48,.06);"><span style="font-size:13px;color:#888;">Toad</span><span style="font-size:13px;font-weight:600;color:#1d2430;">' + unit.rooms + ' tuba</span></div>' : '',
        unit.net_area ? '<div style="display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid rgba(29,36,48,.06);"><span style="font-size:13px;color:#888;">Pind</span><span style="font-size:13px;font-weight:600;color:#1d2430;">' + unit.net_area + ' m²</span></div>' : '',
        unit.completion ? '<div style="display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid rgba(29,36,48,.06);"><span style="font-size:13px;color:#888;">Valmimine</span><span style="font-size:13px;font-weight:600;color:#1d2430;">' + unit.completion + '</span></div>' : '',
        priceStr ? '<div style="display:flex;justify-content:space-between;padding:10px 0;"><span style="font-size:13px;color:#888;">Hind</span><span style="font-size:' + (unit.price ? '16px' : '13px') + ';font-weight:' + (unit.price ? '700' : '500') + ';color:' + (unit.price ? '#c89443' : '#aaa') + ';font-style:' + (unit.price ? 'normal' : 'italic') + ';">' + priceStr + '</span></div>' : '',
      '</div>',

      '<div style="display:flex;flex-direction:column;gap:10px;">',
        unit.status !== 'sold' ? '<a href="' + unit.page_url + '" style="background:#c89443;color:#fff;padding:11px 16px;border-radius:10px;text-decoration:none;font-size:14px;font-weight:600;text-align:center;display:block;" data-event="unit_map_panel_open" data-unit="' + unit.key + '">Vaata kodu <span style="margin-left:4px;">→</span></a>' : '',
        unit.status !== 'sold' ? '<a href="{{ lroute('magnoolia.contact') }}?unit=' + encodeURIComponent(unit.address) + '&source_component=asendiplaan_side_panel#kontaktivorm" style="border:1px solid #c89443;color:#c89443;padding:11px 16px;border-radius:10px;text-decoration:none;font-size:14px;font-weight:600;text-align:center;display:block;" data-event="unit_detail_cta" data-unit="' + unit.key + '" data-source="asendiplaan_side_panel">Küsi selle kodu kohta</a>' : '',
        '<button onclick="mgCompareAdd(\'' + unit.key + '\',\'' + unit.address.replace(/'/g,"\\'") + '\',\'' + unit.slug + '\'); return false;" style="border:1px solid rgba(29,36,48,.15);background:#faf9f7;color:#1d2430;padding:11px 16px;border-radius:10px;font-size:13px;font-weight:600;cursor:pointer;width:100%;">Lisa võrdlusesse</button>',
      '</div>',
    ].join('');

    // Show panel
    panel.style.transform = 'translateX(0)';
    panel.setAttribute('aria-hidden', 'false');
    overlay.style.display = 'block';
    document.body.style.overflow = 'hidden';

    // Analytics
    window.dataLayer = window.dataLayer || [];
    window.dataLayer.push({
      'event': 'unit_map_select',
      'unit_key': unit.key, 'unit_slug': unit.slug,
      'address': unit.address, 'stage': unit.stage,
      'status': unit.status, 'price_public': unit.price_public,
      'source_component': 'asendiplaan_chip',
      'locale': '{{ $locale }}',
    });
  };

  window.mgClosePanel = function() {
    var panel = document.getElementById('mg-unit-panel');
    var overlay = document.getElementById('mg-unit-panel-overlay');
    panel.style.transform = 'translateX(100%)';
    panel.setAttribute('aria-hidden', 'true');
    overlay.style.display = 'none';
    document.body.style.overflow = '';
    // Remove hash without scroll
    history.pushState(null, '', window.location.pathname + window.location.search);
  };

  // Keyboard: Escape closes panel
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') mgClosePanel();
  });

  // Initialize from hash
  document.addEventListener('DOMContentLoaded', function() {
    var hash = window.location.hash.replace('#', '');
    if (hash) {
      var unit = findUnit(hash);
      if (unit) {
        setTimeout(function() {
          mgOpenUnit(unit.key);
          var map = document.getElementById('kodud-kaardil');
          if (map) map.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }, 400);
      }
    }
  });

})();
</script>

@endsection
