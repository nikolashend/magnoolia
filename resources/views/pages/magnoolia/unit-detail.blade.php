@extends('layouts.app')

@php
  use App\Services\Magnoolia\MagnooliaUnitDiscoveryService;

  $locale      = app()->getLocale();
  $base        = rtrim(config('magnoolia.seo.canonical_base', 'https://magnoolia.ee'), '/');
  $addr        = $unit['address'];
  $rooms       = $unit['rooms'] ?? null;
  $area        = $unit['net_area'] ?? null;
  $stage       = (int) ($unit['stage'] ?? 1);
  $status      = $unit['status'] ?? 'available';
  $pricePublic = (bool) ($unit['price_public'] ?? false);
  $priceEur    = ($pricePublic && isset($unit['price'])) ? (int) $unit['price'] : null;
  $priceStr    = $priceEur ? number_format($priceEur, 0, '.', ' ') . ' €' : null;
  $planType    = $unit['plan_type'] ?? null;
  $planLabel   = MagnooliaUnitDiscoveryService::planLabel($planType);
  $completion  = $unit['completion'] ?? ($stage === 1 ? 'kevad 2027' : 'kevad 2028');
  $statusColors = ['available' => '#4caf50', 'reserved' => '#c89443', 'sold' => '#888', 'tbc' => '#9c27b0'];
  $statusColor  = $statusColors[$status] ?? '#888';
  $statusLabels = ['available' => __('magnoolia.statuses.available'), 'reserved' => __('magnoolia.statuses.reserved'), 'sold' => __('magnoolia.statuses.sold'), 'tbc' => 'Tulemas'];
  $statusLabel  = $statusLabels[$status] ?? $status;
  $stageLabel   = $stage === 1 ? 'I etapp' : 'II etapp';
  $unitUrl      = MagnooliaUnitDiscoveryService::unitPageUrl($unit, $locale);

  // Price in meta must be hidden if not public
  $metaPrice    = $priceStr ? ' · ' . $priceStr : '';
  $metaRooms    = $rooms ? $rooms . ' tuba' : '';
  $metaArea     = $area ? $area . ' m²' : '';
  $metaDesc     = $addr . ' — ' . implode(', ', array_filter([$metaRooms, $metaArea])) . $metaPrice . '. A-energiaklassi ridaelamukodu Vaela külas, Kiili vald. Tallinnast 20 km.';

  $floorplan1 = $unit['floorplan_1_pdf'] ?? null;
  $floorplan2 = $unit['floorplan_2_pdf'] ?? null;

  $contactUrl = lroute('magnoolia.contact') . '?unit=' . urlencode($addr) . '&source_component=unit_page_hero#kontaktivorm';
@endphp

{{-- SEO --}}
@section('title', $addr . ' — ' . ($planLabel ? 'Plaan ' . $planLabel . ' — ' : '') . 'A-energiaklassi ridaelamukodu Vaela külas')
@section('meta_description', $metaDesc)
@section('og_title', $addr . ' — Magnoolia ridaelamukodu Vaela külas')
@section('og_description', $metaDesc)
@section('canonical', $unitUrl)

@push('head')
{{-- Hreflang --}}
<link rel="alternate" hreflang="et" href="{{ $hreflang['et'] }}">
<link rel="alternate" hreflang="ru" href="{{ $hreflang['ru'] }}">
<link rel="alternate" hreflang="en" href="{{ $hreflang['en'] }}">
<link rel="alternate" hreflang="x-default" href="{{ $hreflang['et'] }}">

{{-- JSON-LD --}}
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@graph": [
    {
      "@@type": "BreadcrumbList",
      "itemListElement": [
        { "@@type": "ListItem", "position": 1, "name": "Avaleht", "item": "{{ $base }}" },
        { "@@type": "ListItem", "position": 2, "name": "Kodud ja hinnad", "item": "{{ $base }}/kodud-ja-hinnad" },
        { "@@type": "ListItem", "position": 3, "name": "{{ $addr }}", "item": "{{ $unitUrl }}" }
      ]
    },
    {
      "@@type": "Residence",
      "@@id": "{{ $unitUrl }}#unit",
      "name": "{{ $addr }}",
      "description": "{{ $metaDesc }}",
      "url": "{{ $unitUrl }}",
      "numberOfRooms": {{ $rooms ?? 'null' }},
      "floorSize": { "@@type": "QuantitativeValue", "value": {{ $area ?? 'null' }}, "unitCode": "MTK" },
      "address": {
        "@@type": "PostalAddress",
        "streetAddress": "{{ $addr }}",
        "addressLocality": "Vaela küla, Kiili vald",
        "addressRegion": "Harjumaa",
        "addressCountry": "EE"
      }
    }
  ]
}
</script>
@endpush

@section('content')

{{-- ── Hero ─────────────────────────────────────────────────── --}}
<div class="mg-page-hero" style="background-image:linear-gradient(to right, rgba(29,36,48,.90) 55%, rgba(29,36,48,.55)), url('{{ asset('assets/images/magnoolia/magnoolia_cam09.jpg') }}');background-size:cover;background-position:center;">
  <div class="container">
    @include('partials.seo.breadcrumb', ['items' => [
      ['label' => __('magnoolia.nav.home'), 'url' => route('home')],
      ['label' => __('magnoolia.nav.homes'), 'url' => lroute('magnoolia.homes')],
      ['label' => $addr],
    ]])

    <div style="display:flex;align-items:center;gap:10px;margin-bottom:14px;flex-wrap:wrap;">
      <span class="mg-stage-badge mg-stage-badge--{{ $stage }}">{{ $stageLabel }}</span>
      @if($planLabel)
      <span style="background:rgba(200,148,67,.18);color:#c89443;font-size:12px;font-weight:700;padding:3px 10px;border-radius:20px;letter-spacing:.06em;">Plaan {{ $planLabel }}</span>
      @endif
      <span style="display:flex;align-items:center;gap:6px;font-size:13px;font-weight:600;color:rgba(255,255,255,.8);">
        <span style="width:9px;height:9px;border-radius:50%;background:{{ $statusColor }};flex-shrink:0;"></span>
        {{ $statusLabel }}
      </span>
    </div>

    <h1 class="mg-page-hero__title" style="font-size:clamp(28px,5vw,52px);">{{ $addr }}</h1>
    <p class="mg-page-hero__lead" style="font-size:17px;margin-top:8px;max-width:520px;">
      @if($rooms && $area)
        {{ $rooms }}-toaline A-energiaklassi ridaelamukodu · {{ $area }} m²
      @endif
      · Vaela küla, Kiili vald
    </p>

    @if($priceStr)
    <div style="font-size:32px;font-weight:800;color:#c89443;margin:16px 0 20px;letter-spacing:-.01em;">
      {{ $priceStr }}
    </div>
    @elseif(!$pricePublic)
    <div style="font-size:18px;font-weight:600;color:rgba(255,255,255,.55);margin:16px 0 20px;font-style:italic;">
      {{ __('magnoolia.pricing.price_tbc_inline') }}
    </div>
    @endif

    <div class="mg-page-hero__ctas">
      @if($status !== 'sold')
      <a href="{{ $contactUrl }}"
         class="zoomvilla-btn"
         data-event="unit_detail_cta"
         data-unit="{{ $unit['unit_key'] }}"
         data-source="unit_page_hero">
        {{ __('magnoolia.pricing.cta_inquiry') }} <i class="icon-angle-small-right"></i>
      </a>
      @endif
      @if($floorplan1)
      <a href="{{ asset($floorplan1) }}" target="_blank" rel="noopener"
         class="zoomvilla-btn zoomvilla-btn--border"
         data-event="unit_floorplan_preview"
         data-unit="{{ $unit['unit_key'] }}"
         data-source="unit_page_hero">
        {{ __('magnoolia.floorplan.enlarge') }} <i class="icon-angle-small-right"></i>
      </a>
      @endif
    </div>
  </div>
</div>

{{-- ── Key facts + Floorplans ──────────────────────────────── --}}
<section class="mg-page-section mg-page-section--white">
  <div class="container">
    <div class="row gutter-y-40 align-items-start">

      {{-- Key facts --}}
      <div class="col-lg-6">
        <div class="mg-section-heading" style="margin-bottom:24px;">
          <div class="mg-section-heading__eyebrow">Selle kodu andmed</div>
          <h2 class="mg-section-heading__title" style="font-size:26px;">{{ $addr }}</h2>
        </div>
        <div class="mg-unit-specs" style="display:flex;flex-direction:column;gap:0;">
          @php
            $specs = [
              ['label' => 'Tubade arv',      'value' => $rooms ? $rooms . ' tuba' : null],
              ['label' => 'Netopind',         'value' => $area ? $area . ' m²' : null],
              ['label' => 'Terrass',          'value' => isset($unit['terrace_area']) && $unit['terrace_area'] ? $unit['terrace_area'] . ' m²' : null],
              ['label' => 'Rõdu',             'value' => isset($unit['balcony_area']) && $unit['balcony_area'] ? $unit['balcony_area'] . ' m²' : null],
              ['label' => 'Panipaik',         'value' => isset($unit['storage_area']) && $unit['storage_area'] ? $unit['storage_area'] . ' m²' : null],
              ['label' => 'Eraaed',           'value' => isset($unit['private_yard_area']) && $unit['private_yard_area'] ? $unit['private_yard_area'] . ' m²' : null],
              ['label' => 'Parkimiskohad',    'value' => isset($unit['parking_spaces']) ? $unit['parking_spaces'] . ' kohta' : null],
              ['label' => __('magnoolia.pricing.stage'),      'value' => $stageLabel],
              ['label' => __('magnoolia.pricing.completion'),  'value' => $completion],
              ['label' => __('magnoolia.pricing.cta_site_plan'), 'value' => $planLabel ? 'Plaan ' . $planLabel : null],
              ['label' => __('magnoolia.pricing.area_class'),  'value' => 'A'],
              ['label' => __('magnoolia.pricing.status'),      'value' => $statusLabel],
              ['label' => __('magnoolia.pricing.price'),       'value' => $priceStr ?? ($pricePublic ? null : __('magnoolia.pricing.price_tbc_inline'))],
            ];
          @endphp
          @foreach($specs as $i => $spec)
            @if($spec['value'] !== null)
            <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 0;border-bottom:1px solid rgba(29,36,48,.07);{{ $i === 0 ? 'border-top:1px solid rgba(29,36,48,.07);' : '' }}">
              <span style="font-size:14px;color:#6f6a61;">{{ $spec['label'] }}</span>
              <span style="font-size:14px;font-weight:600;color:#1d2430;text-align:right;">{{ $spec['value'] }}</span>
            </div>
            @endif
          @endforeach
        </div>

        {{-- Compare button --}}
        <div style="margin-top:20px;">
          <button class="mg-compare-add-btn zoomvilla-btn zoomvilla-btn--border"
                  data-unit-key="{{ $unit['unit_key'] }}"
                  data-unit-address="{{ $addr }}"
                  data-unit-slug="{{ $unit['slug'] ?? $unit['unit_key'] }}"
                  style="font-size:13px;padding:8px 16px;"
                  onclick="mgCompareAdd('{{ $unit['unit_key'] }}','{{ $addr }}','{{ $unit['slug'] ?? $unit['unit_key'] }}')">
            <i class="fas fa-balance-scale" style="margin-right:6px;"></i> Lisa võrdlusesse
          </button>
        </div>
      </div>

      {{-- Floorplans --}}
      <div class="col-lg-6">
        <div class="mg-section-heading" style="margin-bottom:24px;">
          <div class="mg-section-heading__eyebrow">Korrusplaanid</div>
          <h3 class="mg-section-heading__title" style="font-size:26px;">Vaata plaane</h3>
        </div>

        <div style="display:flex;flex-direction:column;gap:16px;">
          @foreach([['label' => '1. korrus', 'key' => 'floorplan_1_pdf'], ['label' => '2. korrus', 'key' => 'floorplan_2_pdf']] as $fp)
          @php $fpPath = $unit[$fp['key']] ?? null; @endphp
          <div style="border:1px solid rgba(29,36,48,.1);border-radius:16px;padding:20px;display:flex;align-items:center;justify-content:space-between;gap:16px;background:#faf9f7;">
            <div style="display:flex;align-items:center;gap:14px;">
              <div style="width:44px;height:44px;background:#1d2430;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="fas fa-file-pdf" style="color:#c89443;font-size:20px;"></i>
              </div>
              <div>
                <div style="font-size:15px;font-weight:600;color:#1d2430;">{{ $fp['label'] }}</div>
                <div style="font-size:12px;color:#888;margin-top:2px;">{{ $addr }}</div>
              </div>
            </div>
            @if($fpPath)
            <div style="display:flex;gap:8px;flex-shrink:0;">
              <a href="{{ asset($fpPath) }}"
                 target="_blank" rel="noopener"
                 class="zoomvilla-btn"
                 style="font-size:12px;padding:7px 14px;"
                 data-event="unit_floorplan_preview"
                 data-unit="{{ $unit['unit_key'] }}"
                 data-floor="{{ $fp['label'] }}"
                 data-source="unit_page_floorplan">
                Ava
              </a>
              <a href="{{ asset($fpPath) }}"
                 download
                 class="zoomvilla-btn zoomvilla-btn--border"
                 style="font-size:12px;padding:7px 14px;"
                 data-event="unit_floorplan_download"
                 data-unit="{{ $unit['unit_key'] }}"
                 data-floor="{{ $fp['label'] }}"
                 data-source="unit_page_floorplan">
                Lae alla
              </a>
            </div>
            @else
            <span style="font-size:13px;color:#aaa;font-style:italic;">Lisatakse</span>
            @endif
          </div>
          @endforeach
        </div>

        {{-- Location context --}}
        <div style="margin-top:24px;background:#1d2430;border-radius:16px;padding:20px;">
          <div style="font-size:11px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#c89443;margin-bottom:10px;">Asukoht arenduses</div>
          <div style="font-size:15px;font-weight:600;color:#fff;margin-bottom:6px;">{{ $unit['building'] ?? ('Magnoolia tee ' . ($unit['building_number'] ?? '')) }}</div>
          <div style="font-size:13px;color:rgba(255,255,255,.6);line-height:1.6;">
            {{ $stageLabel }} · {{ $completion }}<br>
            Vaela küla, Kiili vald, Harjumaa<br>
            <span style="font-size:12px;color:rgba(255,255,255,.4);margin-top:4px;display:block;">Täpne krundipositsioon kinnitatakse asendiplaani järgi.</span>
          </div>
          <a href="{{ lroute('magnoolia.site-plan') }}" style="display:inline-block;margin-top:12px;color:#c89443;font-size:13px;font-weight:600;text-decoration:none;">
            Vaata asendiplaani <i class="icon-angle-small-right"></i>
          </a>
        </div>
      </div>

    </div>
  </div>
</section>

{{-- ── Similar homes ────────────────────────────────────────── --}}
@if(count($similar) > 0)
<section class="mg-page-section mg-page-section--cream">
  <div class="container">
    <div class="mg-section-heading mg-section-heading--center" style="margin-bottom:32px;">
      <div class="mg-section-heading__eyebrow">Teised kodud</div>
      <h2 class="mg-section-heading__title">Sarnased kodud</h2>
    </div>
    <div class="row gutter-y-24">
      @foreach($similar as $sim)
      @php
        $simStatus   = $sim['status'] ?? 'available';
        $simPricePublic = (bool) ($sim['price_public'] ?? false);
        $simPriceEur = ($simPricePublic && isset($sim['price'])) ? (int) $sim['price'] : null;
        $simColor    = $statusColors[$simStatus] ?? '#888';
        $simLabel    = $statusLabels[$simStatus] ?? $simStatus;
        $simPlanLabel = MagnooliaUnitDiscoveryService::planLabel($sim['plan_type'] ?? null);
        $simUrl      = MagnooliaUnitDiscoveryService::unitPageUrl($sim, $locale);
      @endphp
      <div class="col-lg-4 col-md-6">
        <a href="{{ $simUrl }}"
           class="mg-unit-card"
           style="display:block;background:#fff;border-radius:16px;padding:20px;border:1px solid rgba(29,36,48,.08);text-decoration:none;transition:box-shadow .2s;height:100%;"
           onmouseover="this.style.boxShadow='0 8px 32px rgba(29,36,48,.12)'"
           onmouseout="this.style.boxShadow='none'"
           data-event="unit_detail_cta"
           data-unit="{{ $sim['unit_key'] }}"
           data-source="unit_page_similar">
          <div style="display:flex;align-items:center;gap:8px;margin-bottom:12px;flex-wrap:wrap;">
            <span class="mg-stage-badge mg-stage-badge--{{ $sim['stage'] ?? 1 }}" style="font-size:11px;">{{ ($sim['stage'] ?? 1) === 1 ? 'I etapp' : 'II etapp' }}</span>
            @if($simPlanLabel)
            <span style="background:#f5f0e8;color:#8a7760;font-size:11px;font-weight:700;padding:2px 8px;border-radius:12px;">Plaan {{ $simPlanLabel }}</span>
            @endif
          </div>
          <div style="font-size:18px;font-weight:700;color:#1d2430;margin-bottom:8px;">{{ $sim['address'] }}</div>
          <div style="font-size:13px;color:#888;margin-bottom:12px;">
            {{ $sim['rooms'] ?? '—' }} tuba · {{ $sim['net_area'] ?? '—' }} m²
          </div>
          <div style="display:flex;align-items:center;justify-content:space-between;">
            <div style="font-size:13px;font-weight:600;color:{{ $simColor }};display:flex;align-items:center;gap:6px;">
              <span style="width:8px;height:8px;border-radius:50%;background:{{ $simColor }};"></span>
              {{ $simLabel }}
            </div>
            @if($simPriceEur)
            <div style="font-size:15px;font-weight:700;color:#1d2430;">{{ number_format($simPriceEur, 0, '.', ' ') }} €</div>
            @elseif(!$simPricePublic)
            <div style="font-size:13px;color:#aaa;font-style:italic;">Hind täpsustamisel</div>
            @endif
          </div>
        </a>
      </div>
      @endforeach
    </div>
    <div style="text-align:center;margin-top:28px;">
      <a href="{{ lroute('magnoolia.homes') }}" class="zoomvilla-btn zoomvilla-btn--border">
        Vaata kõiki kodusid <i class="icon-angle-small-right"></i>
      </a>
    </div>
  </div>
</section>
@endif

{{-- ── CTA block ────────────────────────────────────────────── --}}
@if($status !== 'sold')
<section class="mg-page-section mg-page-section--dark">
  <div class="container" style="text-align:center;">
    <div class="mg-section-heading mg-section-heading--center mg-section-heading--light" style="margin-bottom:28px;">
      <div class="mg-section-heading__eyebrow" style="color:rgba(200,148,67,.8);">Huvitab?</div>
      <h2 class="mg-section-heading__title" style="color:#fff;font-size:clamp(24px,4vw,40px);">Kas see kodu võiks sobida?</h2>
      <p style="color:rgba(255,255,255,.65);font-size:16px;max-width:520px;margin:12px auto 0;">
        Diana kinnitab saadavuse, hinna ja järgmised sammud.
      </p>
    </div>
    <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap;">
      <a href="{{ $contactUrl }}"
         class="zoomvilla-btn"
         style="font-size:15px;padding:13px 28px;"
         data-event="unit_detail_cta"
         data-unit="{{ $unit['unit_key'] }}"
         data-source="unit_page_cta">
        Küsi kodu {{ $addr }} kohta <i class="icon-angle-small-right"></i>
      </a>
      <a href="tel:{{ config('magnoolia.project.contact_phone', '+37258164078') }}"
         class="zoomvilla-btn zoomvilla-btn--border"
         style="font-size:15px;padding:13px 28px;">
        <i class="fas fa-phone" style="margin-right:6px;"></i> Helista
      </a>
    </div>
  </div>
</section>
@endif

{{-- ── Navigation ───────────────────────────────────────────── --}}
<section class="mg-page-section--cream mg-page-section--sm">
  <div class="container">
    <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:16px;">
      @if($adjacent['prev'])
      <a href="{{ MagnooliaUnitDiscoveryService::unitPageUrl($adjacent['prev'], $locale) }}"
         style="display:flex;align-items:center;gap:8px;color:#6f6a61;text-decoration:none;font-size:14px;font-weight:600;">
        <i class="icon-angle-small-left"></i> {{ $adjacent['prev']['address'] }}
      </a>
      @else
      <span></span>
      @endif

      <a href="{{ lroute('magnoolia.homes') }}"
         style="font-size:13px;color:#c89443;text-decoration:none;font-weight:600;">
        Kõik kodud
      </a>

      @if($adjacent['next'])
      <a href="{{ MagnooliaUnitDiscoveryService::unitPageUrl($adjacent['next'], $locale) }}"
         style="display:flex;align-items:center;gap:8px;color:#6f6a61;text-decoration:none;font-size:14px;font-weight:600;">
        {{ $adjacent['next']['address'] }} <i class="icon-angle-small-right"></i>
      </a>
      @else
      <span></span>
      @endif
    </div>
  </div>
</section>

{{-- ── Analytics ────────────────────────────────────────────── --}}
<script>
(function() {
  window.dataLayer = window.dataLayer || [];
  window.dataLayer.push({
    'event': 'unit_page_view',
    'unit_key':    '{{ $unit['unit_key'] }}',
    'unit_slug':   '{{ $unit['slug'] ?? $unit['unit_key'] }}',
    'address':     '{{ $addr }}',
    'stage':       {{ $stage }},
    'status':      '{{ $status }}',
    'price_public': {{ $pricePublic ? 'true' : 'false' }},
    'price':       {{ $priceEur ?? 'null' }},
    'plan_type':   '{{ $planType ?? '' }}',
    'source_component': 'unit_page',
    'locale':      '{{ $locale }}',
    'published_version': {{ $publishedVersion ?? 'null' }},
  });

  // CTA click tracking
  document.addEventListener('click', function(e) {
    var el = e.target.closest('[data-event]');
    if (!el) return;
    window.dataLayer.push({
      'event': el.dataset.event,
      'unit_key':    el.dataset.unit || '{{ $unit['unit_key'] }}',
      'unit_slug':   '{{ $unit['slug'] ?? $unit['unit_key'] }}',
      'address':     '{{ $addr }}',
      'stage':       {{ $stage }},
      'status':      '{{ $status }}',
      'price_public': {{ $pricePublic ? 'true' : 'false' }},
      'source_component': el.dataset.source || 'unknown',
      'locale':      '{{ $locale }}',
      'published_version': {{ $publishedVersion ?? 'null' }},
    });
  });
})();
</script>

@endsection
