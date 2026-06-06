@extends('layouts.app')

@php
  use App\Services\Magnoolia\MagnooliaUnitDiscoveryService;
  $locale = app()->getLocale();
@endphp

@section('title', 'Võrdle Magnoolia kodusid — kuni 3 kodu korraga')
@section('meta_description', 'Võrdle Magnoolia ridaelamukodusid kõrvuti: tubade arv, pind, terrass, aed, hind ja plaan.')

@section('content')

<div class="mg-page-hero" style="background:linear-gradient(135deg,#1d2430 60%,#2a3444);min-height:auto;padding:60px 0 40px;">
  <div class="container">
    @include('partials.seo.breadcrumb', ['items' => [
      ['label' => __('magnoolia.nav.home'), 'url' => route('home')],
      ['label' => __('magnoolia.nav.homes'), 'url' => lroute('magnoolia.homes')],
      ['label' => 'Võrdlus'],
    ]])
    <h1 class="mg-page-hero__title" style="font-size:clamp(24px,4vw,44px);margin-top:12px;">Võrdle kodusid</h1>
    <p class="mg-page-hero__lead" style="max-width:480px;">Vali kuni 3 kodu ja võrdle neid kõrvuti.</p>
  </div>
</div>

{{-- ── Unit selector ─────────────────────────────────────────── --}}
<section class="mg-page-section mg-page-section--cream" id="vordle-selector">
  <div class="container">

    <div id="mg-compare-controls" style="margin-bottom:32px;">
      <div style="font-size:14px;color:#6f6a61;margin-bottom:12px;">Valitud kodud (<span id="mg-compare-count">{{ count($compareUnits) }}</span>/3):</div>
      <div id="mg-compare-chips" style="display:flex;gap:8px;flex-wrap:wrap;min-height:40px;">
        {{-- Populated by JS --}}
      </div>
      <div style="margin-top:16px;display:flex;gap:10px;flex-wrap:wrap;">
        <button id="mg-compare-go" onclick="mgCompareGo()"
                class="zoomvilla-btn" style="font-size:13px;padding:8px 18px;">
          Võrdle <i class="icon-angle-small-right"></i>
        </button>
        <button onclick="mgCompareClear()"
                class="zoomvilla-btn zoomvilla-btn--border" style="font-size:13px;padding:8px 18px;">
          Tühjenda
        </button>
      </div>
    </div>

    {{-- Unit picker list --}}
    <div style="margin-bottom:40px;">
      <div style="font-size:13px;font-weight:600;color:#1d2430;margin-bottom:12px;letter-spacing:.05em;text-transform:uppercase;">Vali kodud:</div>
      <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:10px;">
        @foreach($allUnits as $u)
        @php
          $uPlanLabel = MagnooliaUnitDiscoveryService::planLabel($u['plan_type'] ?? null);
          $uPrice = ($u['price_public'] ?? false) && isset($u['price']) ? number_format((int)$u['price'], 0, '.', ' ') . ' €' : null;
          $uStage = (int)($u['stage'] ?? 1);
        @endphp
        <button class="mg-compare-pick-btn"
                data-unit-key="{{ $u['unit_key'] }}"
                data-unit-address="{{ $u['address'] }}"
                data-unit-slug="{{ $u['slug'] ?? $u['unit_key'] }}"
                onclick="mgCompareAdd('{{ $u['unit_key'] }}','{{ $u['address'] }}','{{ $u['slug'] ?? $u['unit_key'] }}')"
                style="text-align:left;background:#fff;border:1px solid rgba(29,36,48,.1);border-radius:12px;padding:12px 14px;cursor:pointer;transition:border-color .2s;width:100%;">
          <div style="display:flex;align-items:center;gap:6px;margin-bottom:4px;">
            <span class="mg-stage-badge mg-stage-badge--{{ $uStage }}" style="font-size:10px;padding:2px 7px;">{{ $uStage === 1 ? 'I' : 'II' }}</span>
            @if($uPlanLabel)<span style="font-size:11px;color:#8a7760;font-weight:600;">Plaan {{ $uPlanLabel }}</span>@endif
          </div>
          <div style="font-size:14px;font-weight:600;color:#1d2430;">{{ $u['address'] }}</div>
          <div style="font-size:12px;color:#888;margin-top:2px;">{{ $u['rooms'] ?? '—' }} tuba · {{ $u['net_area'] ?? '—' }} m²</div>
          @if($uPrice)<div style="font-size:13px;font-weight:700;color:#c89443;margin-top:4px;">{{ $uPrice }}</div>@endif
        </button>
        @endforeach
      </div>
    </div>

  </div>
</section>

{{-- ── Comparison table ─────────────────────────────────────── --}}
@if(count($compareUnits) >= 2)
<section class="mg-page-section mg-page-section--white" id="vordle-tabel">
  <div class="container">
    <div class="mg-section-heading mg-section-heading--center" style="margin-bottom:32px;">
      <div class="mg-section-heading__eyebrow">Võrdlus</div>
      <h2 class="mg-section-heading__title">Kodude võrdlus kõrvuti</h2>
    </div>

    <div style="overflow-x:auto;">
      <table style="width:100%;border-collapse:collapse;min-width:500px;">
        <thead>
          <tr>
            <th style="text-align:left;padding:12px 16px;font-size:13px;color:#6f6a61;font-weight:600;border-bottom:2px solid rgba(29,36,48,.1);min-width:140px;">Parameeter</th>
            @foreach($compareUnits as $cu)
            @php $cuPlanLabel = MagnooliaUnitDiscoveryService::planLabel($cu['plan_type'] ?? null); @endphp
            <th style="text-align:left;padding:12px 16px;border-bottom:2px solid rgba(29,36,48,.1);min-width:180px;">
              <div style="font-size:16px;font-weight:700;color:#1d2430;">{{ $cu['address'] }}</div>
              @if($cuPlanLabel)<div style="font-size:12px;color:#c89443;font-weight:600;margin-top:2px;">Plaan {{ $cuPlanLabel }}</div>@endif
            </th>
            @endforeach
          </tr>
        </thead>
        <tbody>
          @php
            $rows = [
              ['key' => 'status',           'label' => 'Staatus',        'format' => 'status'],
              ['key' => 'stage',            'label' => 'Etapp',          'format' => 'stage'],
              ['key' => 'completion',       'label' => 'Valmimisaeg',    'format' => 'raw'],
              ['key' => 'rooms',            'label' => 'Tubade arv',     'format' => 'rooms'],
              ['key' => 'net_area',         'label' => 'Netopind',       'format' => 'm2'],
              ['key' => 'terrace_area',     'label' => 'Terrass',        'format' => 'm2'],
              ['key' => 'balcony_area',     'label' => 'Rõdu',           'format' => 'm2'],
              ['key' => 'storage_area',     'label' => 'Panipaik',       'format' => 'm2'],
              ['key' => 'private_yard_area','label' => 'Eraaed',         'format' => 'm2'],
              ['key' => 'parking_spaces',   'label' => 'Parkimiskohad',  'format' => 'parking'],
              ['key' => 'plan_type',        'label' => 'Plaan',          'format' => 'plan'],
              ['key' => 'price',            'label' => 'Hind',           'format' => 'price'],
            ];
            $statusLabels = ['available' => 'Saadaval', 'reserved' => 'Broneeritud', 'sold' => 'Müüdud', 'tbc' => 'Tulemas'];
          @endphp
          @foreach($rows as $i => $row)
          <tr style="background:{{ $i % 2 === 0 ? '#fff' : '#faf9f7' }};">
            <td style="padding:12px 16px;font-size:13px;color:#6f6a61;border-bottom:1px solid rgba(29,36,48,.05);font-weight:600;">{{ $row['label'] }}</td>
            @foreach($compareUnits as $cu)
            @php
              $val = $cu[$row['key']] ?? null;
              $pricePublic = (bool)($cu['price_public'] ?? false);
              switch($row['format']) {
                case 'status':   $display = $statusLabels[$val] ?? $val; break;
                case 'stage':    $display = (int)$val === 1 ? 'I etapp' : 'II etapp'; break;
                case 'rooms':    $display = $val ? $val . ' tuba' : null; break;
                case 'm2':       $display = $val ? $val . ' m²' : null; break;
                case 'parking':  $display = $val ? $val . ' kohta' : null; break;
                case 'plan':     $display = $val ? 'Plaan ' . MagnooliaUnitDiscoveryService::planLabel($val) : null; break;
                case 'price':
                  if(!$pricePublic) { $display = 'Hind täpsustamisel'; }
                  elseif($val) { $display = number_format((int)$val, 0, '.', ' ') . ' €'; }
                  else { $display = null; }
                  break;
                default:         $display = $val;
              }
            @endphp
            <td style="padding:12px 16px;font-size:14px;font-weight:600;color:#1d2430;border-bottom:1px solid rgba(29,36,48,.05);">
              {{ $display ?? '—' }}
            </td>
            @endforeach
          </tr>
          @endforeach

          {{-- CTA row --}}
          <tr>
            <td style="padding:16px;border-top:2px solid rgba(29,36,48,.1);"></td>
            @foreach($compareUnits as $cu)
            @php
              $cuUrl = MagnooliaUnitDiscoveryService::unitPageUrl($cu, $locale);
              $cuContactUrl = lroute('magnoolia.contact') . '?unit=' . urlencode($cu['address']) . '&source_component=compare_cta#kontaktivorm';
            @endphp
            <td style="padding:16px;border-top:2px solid rgba(29,36,48,.1);">
              <div style="display:flex;flex-direction:column;gap:8px;">
                <a href="{{ $cuContactUrl }}"
                   class="zoomvilla-btn"
                   style="font-size:13px;padding:8px 14px;text-align:center;"
                   data-event="unit_detail_cta"
                   data-unit="{{ $cu['unit_key'] }}"
                   data-source="compare_cta">
                  Küsi infot
                </a>
                <a href="{{ $cuUrl }}"
                   class="zoomvilla-btn zoomvilla-btn--border"
                   style="font-size:13px;padding:8px 14px;text-align:center;">
                  Vaata kodu
                </a>
              </div>
            </td>
            @endforeach
          </tr>
        </tbody>
      </table>
    </div>

  </div>
</section>
@elseif(count($compareUnits) === 1)
<section class="mg-page-section mg-page-section--white">
  <div class="container" style="text-align:center;color:#888;padding:40px 0;">
    Vali vähemalt 2 kodu võrdluseks.
  </div>
</section>
@endif

{{-- ── Compare JS ───────────────────────────────────────────── --}}
<script>
(function() {
  var COMPARE_KEY = 'mg_compare_v1';
  var MAX = 3;

  function getSelected() {
    try { return JSON.parse(localStorage.getItem(COMPARE_KEY) || '[]'); } catch(e) { return []; }
  }
  function setSelected(arr) {
    localStorage.setItem(COMPARE_KEY, JSON.stringify(arr.slice(0, MAX)));
  }

  window.mgCompareAdd = function(key, address, slug) {
    var sel = getSelected();
    var exists = sel.find(function(u) { return u.key === key; });
    if (exists) {
      sel = sel.filter(function(u) { return u.key !== key; });
    } else if (sel.length < MAX) {
      sel.push({ key: key, address: address, slug: slug });
    } else {
      alert('Kuni 3 kodu korraga võrrelda.');
      return;
    }
    setSelected(sel);
    mgRenderChips();

    window.dataLayer = window.dataLayer || [];
    window.dataLayer.push({
      'event': exists ? 'unit_compare_remove' : 'unit_compare_add',
      'unit_key': key, 'unit_slug': slug, 'address': address,
      'locale': '{{ $locale }}'
    });
  };

  window.mgCompareClear = function() {
    setSelected([]);
    mgRenderChips();
  };

  window.mgCompareGo = function() {
    var sel = getSelected();
    if (sel.length < 2) { alert('Vali vähemalt 2 kodu.'); return; }
    var slugs = sel.map(function(u) { return u.slug; }).join(',');
    var baseUrl = '{{ lroute('magnoolia.compare') }}';
    window.dataLayer = window.dataLayer || [];
    window.dataLayer.push({ 'event': 'unit_compare_open', 'slugs': slugs, 'locale': '{{ $locale }}' });
    window.location.href = baseUrl + '?units=' + encodeURIComponent(slugs);
  };

  function mgRenderChips() {
    var sel = getSelected();
    var chips = document.getElementById('mg-compare-chips');
    var count = document.getElementById('mg-compare-count');
    if (!chips) return;
    if (count) count.textContent = sel.length;
    chips.innerHTML = sel.map(function(u) {
      return '<span style="display:inline-flex;align-items:center;gap:6px;background:#1d2430;color:#fff;padding:6px 12px;border-radius:20px;font-size:13px;">'
        + u.address
        + '<button onclick="mgCompareAdd(\'' + u.key + '\',\'' + u.address + '\',\'' + u.slug + '\')" style="background:none;border:none;color:rgba(255,255,255,.6);cursor:pointer;font-size:16px;padding:0;line-height:1;">×</button>'
        + '</span>';
    }).join('');

    // Highlight selected buttons
    document.querySelectorAll('.mg-compare-pick-btn').forEach(function(btn) {
      var isSelected = sel.find(function(u) { return u.key === btn.dataset.unitKey; });
      btn.style.borderColor = isSelected ? '#c89443' : 'rgba(29,36,48,.1)';
      btn.style.background = isSelected ? '#fdf6ea' : '#fff';
    });
  }

  // Init
  document.addEventListener('DOMContentLoaded', function() {
    mgRenderChips();
    @foreach($compareUnits as $cu)
    // Pre-populate from URL if compare units loaded from server
    (function() {
      var sel = getSelected();
      var key = '{{ $cu['unit_key'] }}';
      if (!sel.find(function(u) { return u.key === key; })) {
        if (sel.length < MAX) {
          sel.push({ key: key, address: '{{ $cu['address'] }}', slug: '{{ $cu['slug'] ?? $cu['unit_key'] }}' });
          setSelected(sel);
        }
      }
    })();
    @endforeach
    mgRenderChips();
  });
})();
</script>

@endsection
