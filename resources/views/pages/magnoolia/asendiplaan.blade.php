@extends('layouts.app')

@section('title', __('magnoolia.page.asendiplaan.page_title'))
@section('meta_description', $page['description'] ?? '')
@section('og_title', $page['title'] ?? '')
@section('og_description', $page['description'] ?? '')

@section('content')
@php
  $base  = rtrim(config('magnoolia.seo.canonical_base', 'https://magnoolia.ee'), '/');
  $units = collect(config('magnoolia.units', []));

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
<div class="mg-page-hero">
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
          <div class="mg-image-card__caption">Magnoolia ridaelamukodud · Vaela küla, Kiili vald</div>
        </div>
        <p class="mg-seo-note" style="margin-top:16px;">
          Illustratiivne välisvaade. Interaktiivne asendiplaan täieneb koos projekti valmimisega.
        </p>
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
                  $planLabel = $unit['plan_type'] === 'type-b' ? 'B' : 'A';
                  $canOpen   = in_array($unit['status'], ['available', 'reserved']);
                @endphp
                <span
                  class="mg-unit-chip {{ $chipClass }}"
                  @if($canOpen) onclick="mgOpenUnit('{{ $unit['id'] }}')" title="Kodu {{ $unit['address'] }}" @endif
                >
                  <span class="mg-unit-chip__dot"></span>
                  {{ $unit['section'] }}
                  <span style="font-size:11px;opacity:.7;">P{{ $planLabel }}</span>
                </span>
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

@endsection
