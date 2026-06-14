{{--
    sections/magnoolia/rowhouse-selector.blade.php — Phase 29

    Premium row → home selection. Resolves RowhouseSelectionService, renders the
    clean asendiplaan with row markers, 6 row cards, and per-row home cards that
    open the home-detail modal. Progressive enhancement: with JS one row shows at
    a time; without JS all rows + homes render (no dead ends).
--}}
@php
  use App\Services\Magnoolia\RowhouseSelectionService;
  $rhs   = app(RowhouseSelectionService::class);
  $rows  = $rhs->rows();
  $clean = $rhs->asendiplaanImage();
  $cleanSrc = $clean['1024'] ?? $clean['base'] ?? null;
  $enlarge  = $rhs->enlargePdf();
  $statuses = ['available' => __('magnoolia.statuses.available'), 'reserved' => __('magnoolia.statuses.reserved'), 'sold' => __('magnoolia.statuses.sold'), 'tbc' => __('magnoolia.pricing.status_tbc')];
  $statusColors = ['available' => '#4caf50', 'reserved' => '#c89443', 'sold' => '#9a948a', 'tbc' => '#9c27b0'];
@endphp

@if(count($rows))
<section class="mg-page-section mg-page-section--white" id="mg-rowhouse">
  <div class="container">
    <div class="mg-section-heading" style="margin-bottom:28px;">
      <div class="mg-section-heading__eyebrow">{{ __('magnoolia.rowhouse.section_eyebrow') }}</div>
      <h2 class="mg-section-heading__title">{{ __('magnoolia.rowhouse.section_title') }}</h2>
      <p class="mg-section-heading__subtitle">{{ __('magnoolia.rowhouse.section_subtitle') }}</p>
    </div>

    <div class="mg-rh">
      {{-- Map (left on desktop, below cards on mobile) --}}
      <div class="mg-rh__map">
        @if($cleanSrc)
        <div style="position:relative;border-radius:16px;overflow:hidden;border:1px solid rgba(29,36,48,.1);background:#f3efe7;">
          <img src="{{ asset($cleanSrc) }}" alt="{{ __('magnoolia.rowhouse.alt_map') }}"
               width="1024" height="1436" loading="lazy" decoding="async" style="width:100%;height:auto;display:block;">
          @foreach($rows as $row)
            @php $hl = $row['map_highlight'] ?? null; @endphp
            @if($hl && isset($hl['x'], $hl['y']))
            <button type="button" class="mg-rh__marker" data-mg-row-marker="{{ $row['building'] }}"
                    aria-label="{{ $row['title'] }}"
                    style="position:absolute;left:{{ $hl['x']*100 }}%;top:{{ $hl['y']*100 }}%;transform:translate(-50%,-50%);">
              <span class="mg-rh__marker-dot"></span>
              <span class="mg-rh__marker-num">{{ $row['building'] }}</span>
            </button>
            @endif
          @endforeach
        </div>
        @if($enlarge)
        <a href="{{ asset($enlarge) }}" target="_blank" rel="noopener noreferrer"
           class="zoomvilla-btn zoomvilla-btn--border" style="margin-top:14px;display:inline-block;"
           data-mg-analytics="magnoolia_asendiplaan_enlarge">
          {{ __('magnoolia.rowhouse.enlarge_map') }} <i class="icon-angle-small-right"></i>
        </a>
        @endif
        @endif
      </div>

      {{-- Cards + selected homes (right on desktop, first on mobile) --}}
      <div class="mg-rh__panel">
        <div class="mg-rh__rows" role="tablist" aria-label="{{ __('magnoolia.rowhouse.row_select_label') }}">
          @foreach($rows as $row)
          @php $c = $row['availability_counts']; @endphp
          <button type="button" class="mg-rh-card" data-mg-row="{{ $row['building'] }}"
                  role="tab" aria-controls="mg-rh-homes-{{ $row['building'] }}">
            <div class="mg-rh-card__head">
              <span class="mg-rh-card__title">{{ $row['title'] }}</span>
              <span class="mg-stage-badge mg-stage-badge--{{ $row['stage'] }}">{{ $row['stage'] === 1 ? __('magnoolia.rowhouse.etapp_1') : __('magnoolia.rowhouse.etapp_2') }}</span>
            </div>
            <div class="mg-rh-card__meta">
              {{ __('magnoolia.rowhouse.homes_count', ['count' => $row['home_count']]) }}
              · {{ __('magnoolia.rowhouse.completes', ['date' => $row['completion']]) }}
            </div>
            <div class="mg-rh-card__avail">
              @if($c['available'])<span style="color:#4caf50;">● {{ $c['available'] }} {{ __('magnoolia.statuses.available') }}</span>@endif
              @if($c['reserved'])<span style="color:#c89443;">● {{ $c['reserved'] }} {{ __('magnoolia.statuses.reserved') }}</span>@endif
              @if($c['sold'])<span style="color:#9a948a;">● {{ $c['sold'] }} {{ __('magnoolia.statuses.sold') }}</span>@endif
              @if($c['tbc'])<span style="color:#9c27b0;">● {{ $c['tbc'] }} {{ __('magnoolia.pricing.status_tbc') }}</span>@endif
            </div>
            <span class="mg-rh-card__cta">{{ __('magnoolia.rowhouse.row_cta') }} →</span>
          </button>
          @endforeach
        </div>

        @foreach($rows as $i => $row)
        <div class="mg-rh-homes" id="mg-rh-homes-{{ $row['building'] }}" data-mg-row-homes="{{ $row['building'] }}" role="tabpanel" @if($i>0) hidden @endif>
          <div class="mg-rh-homes__title">{{ $row['title'] }} — {{ __('magnoolia.rowhouse.homes_count', ['count' => $row['home_count']]) }}</div>
          <div class="mg-rh-homes__grid">
            @foreach($row['homes'] as $home)
            @php $st = $home['status']; @endphp
            <button type="button" class="mg-rh-home" data-mg-home-open="{{ $home['asset_key'] }}">
              @if($home['image'])
              <span class="mg-rh-home__img">
                <img src="{{ asset($home['image']['480'] ?? $home['image']['base']) }}"
                     alt="{{ __('magnoolia.rowhouse.alt_home', ['address' => $home['display_address']]) }}"
                     width="240" height="180" loading="lazy" decoding="async">
              </span>
              @endif
              <span class="mg-rh-home__body">
                <span class="mg-rh-home__addr">{{ $home['display_address'] }}</span>
                <span class="mg-rh-home__spec">{{ __('magnoolia.rowhouse.plan_prefix') }} {{ $home['plan_label'] }} · {{ RowhouseSelectionService::formatArea($home['net_area']) }} m²</span>
                <span class="mg-rh-home__yard">{{ __('magnoolia.rowhouse.yard_inline', ['area' => RowhouseSelectionService::formatArea($home['private_yard_area'])]) }}</span>
                <span class="mg-rh-home__foot">
                  <span class="mg-rh-chip" style="--c:{{ $statusColors[$st] ?? '#888' }};">{{ $statuses[$st] ?? $st }}</span>
                  <span class="mg-rh-home__cta">{{ __('magnoolia.rowhouse.view_home') }} →</span>
                </span>
              </span>
            </button>
            @endforeach
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </div>
</section>

@push('scripts')
<script>
(function () {
  var section = document.getElementById('mg-rowhouse');
  if (!section) return;
  var cards   = section.querySelectorAll('[data-mg-row]');
  var markers = section.querySelectorAll('[data-mg-row-marker]');
  var panels  = section.querySelectorAll('[data-mg-row-homes]');

  function select(building) {
    panels.forEach(function (p) { p.hidden = (p.getAttribute('data-mg-row-homes') !== String(building)); });
    cards.forEach(function (c) { c.classList.toggle('is-active', c.getAttribute('data-mg-row') === String(building)); });
    markers.forEach(function (m) { m.classList.toggle('is-active', m.getAttribute('data-mg-row-marker') === String(building)); });
    section.classList.toggle('mg-rh--dimmed', true);
  }

  cards.forEach(function (c) { c.addEventListener('click', function () { select(c.getAttribute('data-mg-row')); }); });
  markers.forEach(function (m) { m.addEventListener('click', function () {
    var b = m.getAttribute('data-mg-row-marker');
    select(b);
    var p = section.querySelector('[data-mg-row-homes="' + b + '"]');
    if (p) p.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
  }); });

  // default: first row active
  if (cards.length) cards[0].classList.add('is-active');
  if (markers.length) markers[0].classList.add('is-active');
})();
</script>
@endpush
@endif
