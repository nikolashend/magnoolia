{{--
    sections/magnoolia/interactive-masterplan.blade.php — Phase 30

    PRIMARY /asendiplaan selector built around the perspective render (1.jpg):
    perspective image + SVG row hotspots → row panel → home cards → inline home
    detail → floor plans (by plan type) → synchronized 2D asendiplaan support map.
    Deep-linkable (?row=tee-1&home=tee-1-2). Row cards are the reliable mobile/no-JS
    fallback. Facts/assets from RowhouseSelectionService (no price_cents).
--}}
@php
  use App\Services\Magnoolia\RowhouseSelectionService;
  $rhs   = app(RowhouseSelectionService::class);
  $rows  = $rhs->rows();
  $pers  = $rhs->perspectiveImage();
  $clean = $rhs->asendiplaanImage();
  $enlarge = $rhs->enlargePdf();
  $av    = '?v=' . $rhs->assetVersion(); // cache-bust for regenerated assets

  $persSrc  = $pers ? asset($pers['1280'] ?? $pers['base']).$av : null;
  $persSrcset = $pers ? collect($pers)->filter(fn($v,$k)=>is_numeric($k))->map(fn($v,$k)=>asset($v).$av.' '.$k.'w')->implode(', ') : '';
  $cleanSrc = $clean ? asset($clean['1024'] ?? $clean['base']).$av : null;

  // Phase 30.1 — perspective view switcher set (primary is hotspot-calibrated)
  $views   = $rhs->perspectiveViews();
  $viewsJs = collect($views)->map(function ($v) use ($av) {
    $img = $v['image'] ?? [];
    return [
      'key'      => $v['key'] ?? 'primary',
      'label'    => __('magnoolia.rowhouse.' . ($v['label'] ?? 'view_primary')),
      'src'      => asset($img['1280'] ?? $img['base'] ?? '').$av,
      'srcset'   => collect($img)->filter(fn($x,$k)=>is_numeric($k))->map(fn($x,$k)=>asset($x).$av.' '.$k.'w')->implode(', '),
      'hotspots' => (bool) ($v['hotspots'] ?? false),
    ];
  })->values()->all();
  $viewsEnc = json_encode($viewsJs, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

  $statusLabels = ['available'=>__('magnoolia.statuses.available'),'reserved'=>__('magnoolia.statuses.reserved'),'sold'=>__('magnoolia.statuses.sold'),'tbc'=>__('magnoolia.pricing.status_tbc')];
  $statusColors = ['available'=>'#4caf50','reserved'=>'#c89443','sold'=>'#9a948a','tbc'=>'#9c27b0'];

  // JSON for client-side rendering of panel/detail/floorplans/map
  $rowsJs = collect($rows)->map(function ($r) use ($rhs, $av) {
    return [
      'building'   => $r['building'],
      'pos'        => $r['pos'],
      'title'      => $r['title'],
      'stage'      => $r['stage'],
      'completion' => $r['completion'],
      'count'      => $r['home_count'],
      'counts'     => $r['availability_counts'],
      'marker'     => $r['perspective']['marker'] ?? ($r['map_highlight'] ? [$r['map_highlight']['x'],$r['map_highlight']['y']] : null),
      'map'        => $r['map_highlight'] ? ['x'=>$r['map_highlight']['x'],'y'=>$r['map_highlight']['y']] : null,
      'homes'      => collect($r['homes'])->map(function ($h) use ($av) {
        $img = $h['image']['480'] ?? $h['image']['base'] ?? null;
        $fp  = $h['floorplans'] ?? null;
        return [
          'key'        => $h['asset_key'],
          'unit_key'   => $h['unit_key'],
          'slug'       => $h['slug'],
          'display'    => $h['display_address'],
          'address'    => $h['address'],
          'plan'       => $h['plan_label'],
          'rooms'      => $h['rooms'],
          'net'        => RowhouseSelectionService::formatArea($h['net_area']),
          'yard'       => RowhouseSelectionService::formatArea($h['private_yard_area']),
          'parking'    => $h['parking_spaces'],
          'stage'      => $h['stage'],
          'completion' => $h['completion'],
          'status'     => $h['status'],
          'img'        => $img ? asset($img).$av : null,
          'mapx'       => $h['map_highlight']['x'] ?? null,
          'mapy'       => $h['map_highlight']['y'] ?? null,
          'floor1'     => ($fp['floor_1']['base'] ?? null) ? asset($fp['floor_1']['1024'] ?? $fp['floor_1']['base']).$av : null,
          'floor2'     => ($fp['floor_2']['base'] ?? null) ? asset($fp['floor_2']['1024'] ?? $fp['floor_2']['base']).$av : null,
          'price_public' => $h['cta_context']['price_public'] ?? false,
        ];
      })->values()->all(),
    ];
  })->values()->all();
  $rowsEnc = json_encode($rowsJs, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
  $phone = config('magnoolia.project.contact_phone', '+37258164078');
@endphp

@if(count($rows) && $persSrc)
<section class="mg-page-section mg-page-section--white" id="mg-masterplan" data-mg-masterplan>
  <div class="container">
    <div class="mg-section-heading" style="margin-bottom:24px;">
      <div class="mg-section-heading__eyebrow">{{ __('magnoolia.rowhouse.mp_eyebrow') }}</div>
      <h2 class="mg-section-heading__title">{{ __('magnoolia.rowhouse.mp_title') }}</h2>
      <p class="mg-section-heading__subtitle">{{ __('magnoolia.rowhouse.mp_subtitle') }}</p>
    </div>

    {{-- Perspective selector --}}
    <div class="mg-mp__stage">
      <div class="mg-mp__imgwrap" data-mp-has-hotspots="1">
        <img id="mg-mp-img" class="mg-mp__img" src="{{ $persSrc }}" @if($persSrcset) srcset="{{ $persSrcset }}" sizes="(min-width:992px) 70vw, 100vw" @endif
             width="1280" height="640" alt="{{ __('magnoolia.rowhouse.mp_img_alt') }}" fetchpriority="high" decoding="async">
        <svg class="mg-mp__svg" id="mg-mp-svg" viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden="true">
          @foreach($rows as $r)
            @php $hull = $r['perspective']['hull'] ?? null; @endphp
            @if($hull)
              @php $pts = collect($hull)->map(fn($p)=>round($p[0]*100,2).','.round($p[1]*100,2))->implode(' '); @endphp
              <polygon class="mg-mp__zone" data-mp-zone="{{ $r['pos'] }}" points="{{ $pts }}"></polygon>
            @endif
          @endforeach
        </svg>
        {{-- Row markers (buttons, keyboard-accessible) --}}
        <div id="mg-mp-markers">
        @foreach($rows as $r)
          @php $mk = $r['perspective']['marker'] ?? null; @endphp
          @if($mk)
          <button type="button" class="mg-mp__marker" data-mp-row="{{ $r['pos'] }}"
                  style="left:{{ $mk[0]*100 }}%;top:{{ $mk[1]*100 }}%;"
                  aria-label="{{ __('magnoolia.rowhouse.aria_row', ['row'=>$r['title'], 'count'=>$r['home_count']]) }}">
            <span class="mg-mp__marker-num">{{ $r['building'] }}</span>
            <span class="mg-mp__marker-label">{{ $r['title'] }}</span>
          </button>
          @endif
        @endforeach
        </div>
        {{-- View switcher arrows (desktop) --}}
        @if(count($views) > 1)
        <button type="button" class="mg-mp__arrow mg-mp__arrow--prev" data-mp-view-prev aria-label="{{ __('magnoolia.rowhouse.view_prev') }}">&#x2039;</button>
        <button type="button" class="mg-mp__arrow mg-mp__arrow--next" data-mp-view-next aria-label="{{ __('magnoolia.rowhouse.view_next') }}">&#x203A;</button>
        @endif
      </div>

      {{-- View pills --}}
      @if(count($views) > 1)
      <div class="mg-mp__views" role="tablist" aria-label="{{ __('magnoolia.rowhouse.mp_eyebrow') }}">
        @foreach($viewsJs as $i => $v)
        <button type="button" class="mg-mp__viewpill {{ $i === 0 ? 'is-active' : '' }}" data-mp-view="{{ $i }}" aria-pressed="{{ $i === 0 ? 'true' : 'false' }}">{{ $v['label'] }}</button>
        @endforeach
      </div>
      @endif

      <p class="mg-mp__hint">{{ __('magnoolia.rowhouse.mp_render_note') }}</p>
    </div>

    {{-- Row cards (reliable selector, primary on mobile) --}}
    <div class="mg-mp__rows" role="tablist" aria-label="{{ __('magnoolia.rowhouse.row_select_label') }}">
      @foreach($rows as $r)
      @php $c = $r['availability_counts']; @endphp
      <button type="button" class="mg-rh-card mg-mp__rowbtn" data-mp-row="{{ $r['pos'] }}" role="tab"
              aria-controls="mg-mp-panel" aria-selected="false">
        <div class="mg-rh-card__head">
          <span class="mg-rh-card__title">{{ $r['title'] }}</span>
          <span class="mg-stage-badge mg-stage-badge--{{ $r['stage'] }}">{{ $r['stage']===1 ? __('magnoolia.rowhouse.etapp_1') : __('magnoolia.rowhouse.etapp_2') }}</span>
        </div>
        <div class="mg-rh-card__meta">{{ __('magnoolia.rowhouse.homes_count', ['count'=>$r['home_count']]) }} · {{ __('magnoolia.rowhouse.completes', ['date'=>$r['completion']]) }}</div>
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

    {{-- Selected row panel (JS-populated) --}}
    <div id="mg-mp-panel" class="mg-mp__panel" hidden>
      <div class="mg-mp__panel-head">
        <div>
          <h3 class="mg-mp__panel-title" id="mg-mp-row-title"></h3>
          <div class="mg-mp__panel-meta" id="mg-mp-row-meta"></div>
        </div>
      </div>
      <p class="mg-mp__panel-help">{{ __('magnoolia.rowhouse.row_helper') }}</p>
      <div class="mg-rh-homes__grid" id="mg-mp-homes"></div>
    </div>

    {{-- Selected home detail (JS-populated) --}}
    <div id="mg-mp-detail" class="mg-mp__detail" hidden>
      <div class="mg-mp__detail-grid">
        {{-- Left: identity + specs + CTA --}}
        <div class="mg-mp__detail-main">
          <div class="mg-mp__detail-eyebrow">{{ __('magnoolia.rowhouse.detail_eyebrow') }}</div>
          <h3 class="mg-mp__detail-title" id="mg-d-title"></h3>
          <div class="mg-mp__detail-sub" id="mg-d-sub"></div>
          <div class="mg-mp__status"><span class="mg-mp__status-dot" id="mg-d-dot"></span><span id="mg-d-status"></span></div>

          <p class="mg-mp__detail-desc">{{ __('magnoolia.rowhouse.detail_desc') }}</p>

          <div class="mg-mp__specs" id="mg-d-specs"></div>

          <div class="mg-mp__ctas">
            <a id="mg-d-cta" href="{{ lroute('magnoolia.contact') }}#kontaktivorm"
               data-mg-inquiry-open data-mg-analytics="magnoolia_home_detail_inquiry"
               data-source-component="asendiplaan_home_detail"
               data-unit-key="" data-unit-slug="" data-unit-address="" data-unit-stage="" data-unit-status="" data-unit-price-public=""
               class="mg-btn mg-btn--gold"></a>
            <a id="mg-d-cta2" href="tel:{{ $phone }}" data-mg-analytics="magnoolia_phone_click" class="mg-btn mg-btn--ghost">{{ __('magnoolia.rowhouse.cta_call') }}</a>
            <a id="mg-d-cta3" href="{{ lroute('magnoolia.homes') }}" class="mg-mp__cta-link" data-mg-analytics="magnoolia_home_detail_secondary"></a>
          </div>
          <p class="mg-mp__trust">{{ __('magnoolia.rowhouse.trust_note') }}</p>
        </div>

        {{-- Right: synchronized 2D asendiplaan support map --}}
        @if($cleanSrc)
        <div class="mg-mp__detail-map">
          <div class="mg-mp__map-label">{{ __('magnoolia.rowhouse.map_location') }}</div>
          <div class="mg-mp__map" id="mg-d-map">
            <img src="{{ $cleanSrc }}" alt="{{ __('magnoolia.rowhouse.alt_map') }}" loading="lazy" decoding="async">
            <span class="mg-mp__map-pin" id="mg-d-pin" hidden><span class="mg-mp__map-pin-label">{{ __('magnoolia.rowhouse.sinu_valik') }}</span></span>
          </div>
          @if($enlarge)
          <a href="{{ asset($enlarge) }}" target="_blank" rel="noopener noreferrer" class="mg-mp__map-open" data-mg-analytics="magnoolia_asendiplaan_enlarge">{{ __('magnoolia.rowhouse.open_bigger') }} →</a>
          @endif
          <p class="mg-mp__map-note">{{ __('magnoolia.rowhouse.perspective_note') }}</p>
        </div>
        @endif
      </div>

      {{-- Floor plans --}}
      <div class="mg-mp__floors" id="mg-d-floors">
        <div class="mg-mp__floors-title">{{ __('magnoolia.rowhouse.floorplans_title') }}</div>
        <div class="mg-mp__floors-tabs">
          <button type="button" class="mg-mp__ftab is-active" data-floor="1">{{ __('magnoolia.rowhouse.floor_1') }}</button>
          <button type="button" class="mg-mp__ftab" data-floor="2">{{ __('magnoolia.rowhouse.floor_2') }}</button>
        </div>
        <div class="mg-mp__floor-stage">
          <button type="button" id="mg-d-floor-zoom" class="mg-mp__floor-zoombtn" aria-label="{{ __('magnoolia.rowhouse.open_larger') }}">
            <img id="mg-d-floor-img" alt="" loading="lazy" decoding="async">
          </button>
          <p id="mg-d-floor-empty" class="mg-mp__floor-empty" hidden>{{ __('magnoolia.rowhouse.floor_placeholder') }}</p>
          <a id="mg-d-floor-open" target="_blank" rel="noopener" class="mg-mp__floor-open" hidden>{{ __('magnoolia.rowhouse.open_pdf') }} ↗</a>
        </div>
        <p class="mg-mp__floor-cap" id="mg-d-floor-cap"></p>
      </div>
    </div>
  </div>

  {{-- Floor-plan lightbox --}}
  <div id="mg-mp-lightbox" class="mg-mp__lightbox" role="dialog" aria-modal="true" aria-label="{{ __('magnoolia.rowhouse.floorplans_title') }}" hidden>
    <button type="button" id="mg-mp-lightbox-close" class="mg-mp__lightbox-close" aria-label="{{ __('magnoolia.rowhouse.modal_close') }}">&#x2715;</button>
    <img id="mg-mp-lightbox-img" src="" alt="">
  </div>
</section>

@push('scripts')
<script>
(function () {
  var ROWS = {!! $rowsEnc !!};
  var byRow = {}, byHome = {};
  ROWS.forEach(function (r) { byRow[r.pos] = r; r.homes.forEach(function (h) { byHome[h.key] = h; byHome[h.unit_key] = h; byHome[h.slug] = h; }); });

  var L = {
    available: @json($statusLabels['available']), reserved: @json($statusLabels['reserved']),
    sold: @json($statusLabels['sold']), tbc: @json($statusLabels['tbc']),
    plan: @json(__('magnoolia.rowhouse.plan_prefix')), rooms: @json(__('magnoolia.rowhouse.rooms_unit')),
    energy: @json(__('magnoolia.rowhouse.energy_value')),
    sNet: @json(__('magnoolia.rowhouse.spec_net')), sYard: @json(__('magnoolia.rowhouse.spec_yard')),
    sRooms: @json(__('magnoolia.rowhouse.spec_rooms')), sPark: @json(__('magnoolia.rowhouse.spec_parking')),
    sStage: @json(__('magnoolia.rowhouse.spec_stage')), sCompl: @json(__('magnoolia.rowhouse.spec_completion')),
    sEnergy: @json(__('magnoolia.rowhouse.spec_energy')),
    ctaOffer: @json(__('magnoolia.rowhouse.cta_offer')), ctaAvail: @json(__('magnoolia.rowhouse.cta_availability')),
    ctaFree: @json(__('magnoolia.rowhouse.cta_view_free')),
    homesCount: @json(__('magnoolia.rowhouse.homes_count', ['count'=>'__N__'])),
    etapp1: @json(__('magnoolia.rowhouse.etapp_1')), etapp2: @json(__('magnoolia.rowhouse.etapp_2')),
    completes: @json(__('magnoolia.rowhouse.completes', ['date'=>'__D__'])),
    viewHome: @json(__('magnoolia.rowhouse.view_home')), yardInline: @json(__('magnoolia.rowhouse.yard_inline')),
    planCap: @json(__('magnoolia.rowhouse.floor_caption')),
  };
  var COLORS = { available:'#4caf50', reserved:'#c89443', sold:'#9a948a', tbc:'#9c27b0' };
  var HOMES_URL = @json(lroute('magnoolia.homes'));
  var CONTACT_URL = @json(lroute('magnoolia.contact').'#kontaktivorm');
  var VIEWS = {!! $viewsEnc !!};
  var L2 = {
    pricelist: @json(__('magnoolia.rowhouse.cta_pricelist')),
    similar: @json(__('magnoolia.rowhouse.cta_similar')),
    viewFree: @json(__('magnoolia.rowhouse.cta_view_free')),
    floorEmpty: @json(__('magnoolia.rowhouse.floor_placeholder')),
  };
  // premium thin-line spec icons
  var SVG = 'viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"';
  var ICONS = {
    net:        '<svg ' + SVG + '><path d="M3 9V3h6M21 9V3h-6M3 15v6h6M21 15v6h-6"/></svg>',
    yard:       '<svg ' + SVG + '><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10z"/><path d="M2 21c0-3 1.85-5.36 5.08-6"/></svg>',
    rooms:      '<svg ' + SVG + '><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>',
    parking:    '<svg ' + SVG + '><path d="M5 17h14M6 17V9l2-4h8l2 4v8"/><circle cx="8" cy="17" r="1"/><circle cx="16" cy="17" r="1"/></svg>',
    stage:      '<svg ' + SVG + '><path d="M12 2 2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>',
    completion: '<svg ' + SVG + '><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>',
    energy:     '<svg ' + SVG + '><path d="M13 2 3 14h9l-1 8 10-12h-9z"/></svg>',
  };

  var section = document.querySelector('[data-mg-masterplan]');
  var panel = document.getElementById('mg-mp-panel');
  var homesWrap = document.getElementById('mg-mp-homes');
  var detail = document.getElementById('mg-mp-detail');
  var state = { row: null, home: null, floor: '1', view: 0 };

  function switchView(idx) {
    if (!VIEWS.length) return;
    idx = ((idx % VIEWS.length) + VIEWS.length) % VIEWS.length;
    var v = VIEWS[idx]; if (!v) return;
    state.view = idx;
    var img = document.getElementById('mg-mp-img');
    if (img) { img.src = v.src; if (v.srcset) img.setAttribute('srcset', v.srcset); img.alt = v.label; }
    // hotspot overlay (zones + markers) only on the calibrated view
    var svg = document.getElementById('mg-mp-svg'); var markers = document.getElementById('mg-mp-markers');
    var wrap = document.querySelector('.mg-mp__imgwrap');
    if (svg) svg.style.display = v.hotspots ? '' : 'none';
    if (markers) markers.style.display = v.hotspots ? '' : 'none';
    if (wrap) wrap.setAttribute('data-mp-has-hotspots', v.hotspots ? '1' : '0');
    document.querySelectorAll('[data-mp-view]').forEach(function (p) {
      var on = +p.getAttribute('data-mp-view') === idx;
      p.classList.toggle('is-active', on); p.setAttribute('aria-pressed', on ? 'true' : 'false');
    });
  }

  function setActiveRow(pos) {
    document.querySelectorAll('[data-mp-row]').forEach(function (el) {
      el.classList.toggle('is-active', el.getAttribute('data-mp-row') === pos);
      if (el.hasAttribute('role')) el.setAttribute('aria-selected', el.getAttribute('data-mp-row') === pos ? 'true' : 'false');
    });
    document.querySelectorAll('[data-mp-zone]').forEach(function (z) {
      z.classList.toggle('is-active', z.getAttribute('data-mp-zone') === pos);
    });
  }

  function renderRow(pos, opts) {
    var r = byRow[pos]; if (!r) return;
    state.row = pos;
    setActiveRow(pos);
    document.getElementById('mg-mp-row-title').textContent = r.title;
    document.getElementById('mg-mp-row-meta').textContent =
      L.homesCount.replace('__N__', r.count) + ' · ' + (r.stage === 1 ? L.etapp1 : L.etapp2) + ' · ' + L.completes.replace('__D__', r.completion);
    homesWrap.innerHTML = r.homes.map(function (h) {
      var col = COLORS[h.status] || '#888';
      return '<button type="button" class="mg-rh-home" data-mp-home="' + h.key + '">' +
        (h.img ? '<span class="mg-rh-home__img"><img src="' + h.img + '" alt="' + h.display + '" loading="lazy"></span>' : '') +
        '<span class="mg-rh-home__body">' +
          '<span class="mg-rh-home__addr">' + h.display + '</span>' +
          '<span class="mg-rh-home__spec">' + L.plan + ' ' + (h.plan || '') + ' · ' + (h.net || '') + ' m²</span>' +
          '<span class="mg-rh-home__yard">' + L.yardInline.replace(':area', h.yard || '') + '</span>' +
          '<span class="mg-rh-home__foot"><span class="mg-rh-chip" style="--c:' + col + '">' + (L[h.status] || h.status) + '</span>' +
          '<span class="mg-rh-home__cta">' + L.viewHome + ' →</span></span>' +
        '</span></button>';
    }).join('');
    panel.hidden = false;
    if (!opts || !opts.silent) updateUrl();
    if (opts && opts.scroll) panel.scrollIntoView({ behavior: 'smooth', block: 'start' });
  }

  function specRow(icon, label, val) {
    return val ? '<div class="mg-mp__spec"><dt><span class="mg-mp__spec-ic">' + (ICONS[icon] || '') + '</span>' + label + '</dt><dd>' + val + '</dd></div>' : '';
  }

  function renderHome(key, opts) {
    var h = byHome[key]; if (!h) return;
    if (byRow[h_rowPos(h)] && state.row !== h_rowPos(h)) renderRow(h_rowPos(h), { silent: true });
    state.home = h.key;
    document.querySelectorAll('[data-mp-home]').forEach(function (el) { el.classList.toggle('is-active', el.getAttribute('data-mp-home') === h.key); });

    document.getElementById('mg-d-title').textContent = h.display;
    var sub = []; if (h.plan) sub.push(L.plan + ' ' + h.plan); if (h.rooms) sub.push(h.rooms + ' ' + L.rooms); if (h.net) sub.push(h.net + ' m²');
    document.getElementById('mg-d-sub').textContent = sub.join(' · ');
    var col = COLORS[h.status] || '#888';
    document.getElementById('mg-d-dot').style.background = col;
    document.getElementById('mg-d-status').textContent = L[h.status] || h.status;

    document.getElementById('mg-d-specs').innerHTML = '<dl>' + [
      specRow('net', L.sNet, h.net ? h.net + ' m²' : null),
      specRow('yard', L.sYard, h.yard ? h.yard + ' m²' : null),
      specRow('rooms', L.sRooms, h.rooms),
      specRow('parking', L.sPark, h.parking),
      specRow('stage', L.sStage, h.stage ? (h.stage === 1 ? 'I' : 'II') : null),
      specRow('completion', L.sCompl, h.completion),
      specRow('energy', L.sEnergy, L.energy),
    ].join('') + '</dl>';

    // status-aware CTA panel
    var cta = document.getElementById('mg-d-cta');
    var cta3 = document.getElementById('mg-d-cta3');
    if (h.status === 'sold') {
      cta.textContent = L.ctaFree; cta.setAttribute('href', HOMES_URL); cta.removeAttribute('data-mg-inquiry-open');
      cta3.textContent = L2.similar; cta3.setAttribute('href', CONTACT_URL);
    } else {
      cta.textContent = (h.status === 'reserved') ? L.ctaAvail : L.ctaOffer;
      cta.setAttribute('href', CONTACT_URL); cta.setAttribute('data-mg-inquiry-open', '');
      cta.setAttribute('data-unit-key', h.unit_key || ''); cta.setAttribute('data-unit-slug', h.slug || '');
      cta.setAttribute('data-unit-address', h.address || ''); cta.setAttribute('data-unit-stage', h.stage || '');
      cta.setAttribute('data-unit-status', h.status || ''); cta.setAttribute('data-unit-price-public', h.price_public ? 'true' : 'false');
      cta3.textContent = (h.status === 'reserved') ? L.ctaFree : L2.pricelist;
      cta3.setAttribute('href', HOMES_URL);
    }

    // map pin
    var pin = document.getElementById('mg-d-pin');
    if (pin && h.mapx != null && h.mapy != null) { pin.style.left = (h.mapx * 100) + '%'; pin.style.top = (h.mapy * 100) + '%'; pin.hidden = false; }
    else if (pin) pin.hidden = true;

    // floor plans
    state.floor = '1';
    document.querySelectorAll('.mg-mp__ftab').forEach(function (t) { t.classList.toggle('is-active', t.getAttribute('data-floor') === '1'); });
    showFloor(h, '1');

    detail.hidden = false;
    if (!opts || !opts.silent) updateUrl();
    if (opts && opts.scroll) detail.scrollIntoView({ behavior: 'smooth', block: 'start' });
  }

  function showFloor(h, f) {
    var zoom = document.getElementById('mg-d-floor-zoom');
    var img = document.getElementById('mg-d-floor-img');
    var open = document.getElementById('mg-d-floor-open');
    var empty = document.getElementById('mg-d-floor-empty');
    var cap = document.getElementById('mg-d-floor-cap');
    var src = f === '2' ? h.floor2 : h.floor1;
    var floorLabel = f === '2' ? @json(__('magnoolia.rowhouse.floor_2')) : @json(__('magnoolia.rowhouse.floor_1'));
    if (src) {
      img.src = src; img.alt = h.display + ' — ' + floorLabel;
      zoom.style.display = ''; open.href = src; open.hidden = false; empty.hidden = true;
      zoom.setAttribute('data-floor-src', src);
    } else {
      img.removeAttribute('src'); zoom.style.display = 'none'; open.hidden = true; empty.hidden = false;
    }
    cap.textContent = L.planCap.replace(':plan', (L.plan + ' ' + (h.plan || ''))).replace(':floor', floorLabel);
  }

  function h_rowPos(h) { return 'tee-' + (h.key.split('-')[1]); }

  function updateUrl() {
    var p = new URLSearchParams(window.location.search);
    if (state.row) p.set('row', state.row); else p.delete('row');
    if (state.home) p.set('home', state.home); else p.delete('home');
    history.replaceState({ row: state.row, home: state.home }, '', window.location.pathname + (p.toString() ? '?' + p.toString() : '') + '#mg-masterplan');
  }

  // delegated events
  document.addEventListener('click', function (e) {
    var rowEl = e.target.closest('[data-mp-row]');
    if (rowEl && section.contains(rowEl)) { e.preventDefault(); state.home = null; detail.hidden = true; renderRow(rowEl.getAttribute('data-mp-row'), { scroll: true }); return; }
    var homeEl = e.target.closest('[data-mp-home]');
    if (homeEl) { e.preventDefault(); renderHome(homeEl.getAttribute('data-mp-home'), { scroll: true }); return; }
    var zone = e.target.closest('[data-mp-zone]');
    if (zone) { e.preventDefault(); state.home = null; detail.hidden = true; renderRow(zone.getAttribute('data-mp-zone'), { scroll: true }); return; }
    var ftab = e.target.closest('.mg-mp__ftab');
    if (ftab) { document.querySelectorAll('.mg-mp__ftab').forEach(function (t) { t.classList.remove('is-active'); }); ftab.classList.add('is-active'); state.floor = ftab.getAttribute('data-floor'); var h = byHome[state.home]; if (h) showFloor(h, state.floor); return; }
    // view switcher
    var pill = e.target.closest('[data-mp-view]');
    if (pill) { switchView(+pill.getAttribute('data-mp-view')); return; }
    if (e.target.closest('[data-mp-view-prev]')) { switchView(state.view - 1); return; }
    if (e.target.closest('[data-mp-view-next]')) { switchView(state.view + 1); return; }
    // floor-plan lightbox
    var zoom = e.target.closest('#mg-d-floor-zoom');
    if (zoom) { var s = zoom.getAttribute('data-floor-src'); if (s) openLightbox(s); return; }
    var lb = document.getElementById('mg-mp-lightbox');
    if (e.target === lb || e.target.closest('#mg-mp-lightbox-close')) { closeLightbox(); return; }
  });

  function openLightbox(src) {
    var lb = document.getElementById('mg-mp-lightbox');
    var img = document.getElementById('mg-mp-lightbox-img');
    img.src = src; lb.hidden = false; document.body.style.overflow = 'hidden';
  }
  function closeLightbox() {
    var lb = document.getElementById('mg-mp-lightbox');
    lb.hidden = true; document.body.style.overflow = '';
  }
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') { var lb = document.getElementById('mg-mp-lightbox'); if (lb && !lb.hidden) closeLightbox(); }
  });
  // SVG zones are not <a>/<button>; make them activatable
  document.querySelectorAll('[data-mp-zone]').forEach(function (z) { z.style.cursor = 'pointer'; });

  // deep-link init
  (function init() {
    var p = new URLSearchParams(window.location.search);
    var row = p.get('row'), home = p.get('home');
    if (home && byHome[home]) { renderRow(h_rowPos(byHome[home]), { silent: true }); renderHome(home, { silent: true }); }
    else if (row && byRow[row]) { renderRow(row, { silent: true }); }
  })();

  window.addEventListener('popstate', function (e) {
    var s = e.state || {};
    if (s.home && byHome[s.home]) { renderHome(s.home, { silent: true }); }
    else if (s.row && byRow[s.row]) { state.home = null; detail.hidden = true; renderRow(s.row, { silent: true }); }
  });
})();
</script>
@endpush
@endif
