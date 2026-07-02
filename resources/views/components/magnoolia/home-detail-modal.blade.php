{{--
    components/magnoolia/home-detail-modal.blade.php — Phase 29

    Accessible home-detail dialog for the row → home → detail journey.
    Include ONCE per page that has [data-mg-home-open="<asset_key>"] triggers
    (e.g. /asendiplaan, /kodud-ja-hinnad). Opens client-side via window.mgOpenHome().

    - Facts/assets come from RowhouseSelectionService (no price_cents emitted).
    - Primary "Küsi pakkumist" reuses the existing global inquiry-drawer by
      carrying [data-mg-inquiry-open] + data-unit-* (set on open).
--}}
@php
  use App\Services\Magnoolia\RowhouseSelectionService;
  $rhs    = app(RowhouseSelectionService::class);
  $locale = app()->getLocale();
  $clean  = $rhs->asendiplaanImage();
  $av     = '?v=' . $rhs->assetVersion(); // cache-bust for regenerated assets
  // Higher-res (1600px) so the in-modal location map stays sharp when zoomed on mobile.
  $cleanUrl = $clean ? asset($clean['1600'] ?? $clean['base'] ?? $clean['1024']).$av : null;

  $homesJs = collect($rhs->allHomes())->map(function ($h) use ($av) {
      $img   = $h['image']['768'] ?? $h['image']['base'] ?? null;
      $f1    = $h['floorplan_1_pdf'] ?? null;
      $f2    = $h['floorplan_2_pdf'] ?? null;
      $fp    = $h['floorplans'] ?? null; // per-building floor-plan images (all 6 buildings)
      $hl    = $h['map_highlight'] ?? null;
      $cta   = $h['cta_context'] ?? [];
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
          'floor1'     => ($f1 && file_exists(public_path($f1))) ? asset($f1) : null,
          'floor2'     => ($f2 && file_exists(public_path($f2))) ? asset($f2) : null,
          'floor1_img' => ($fp['floor_1']['base'] ?? null) ? asset($fp['floor_1']['1024'] ?? $fp['floor_1']['base']).$av : null,
          'floor2_img' => ($fp['floor_2']['base'] ?? null) ? asset($fp['floor_2']['1024'] ?? $fp['floor_2']['base']).$av : null,
          'mx'         => $hl['x'] ?? null,
          'my'         => $hl['y'] ?? null,
          'mappoly'    => $h['map_polygon'] ?? null,
          'price_public' => $cta['price_public'] ?? false,
          'price'      => ($h['price_public'] ?? false) ? ($h['price'] ?? null) : null,
      ];
  })->values()->all();

  $homesEnc = json_encode($homesJs, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
  $phone        = config('magnoolia.project.contact_phone', '+37258164078');
  $homesUrl     = lroute('magnoolia.homes');
  $mapUrl       = lroute('magnoolia.homes') . '#mg-masterplan'; // Phase 35: plan lives on Hinnad ja plaanid
@endphp

<div id="mg-hd-overlay" role="presentation"
     style="display:none;position:fixed;inset:0;z-index:9200;background:rgba(20,25,33,.6);">
  <div id="mg-hd-dialog" role="dialog" aria-modal="true" aria-labelledby="mg-hd-title"
       style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);width:min(920px,94vw);max-height:92vh;overflow-y:auto;background:#fff;border-radius:18px;box-shadow:0 24px 64px rgba(20,25,33,.32);">

    {{-- Header --}}
    <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:16px;padding:24px 28px 16px;border-bottom:1px solid rgba(29,36,48,.08);position:sticky;top:0;background:#fff;border-radius:18px 18px 0 0;z-index:20;">
      <div>
        <div style="font-size:11px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#c89443;">{{ __('magnoolia.rowhouse.detail_eyebrow') }}</div>
        <h2 id="mg-hd-title" style="font-size:24px;font-weight:700;color:#1d2430;margin:4px 0 6px;"></h2>
        <div id="mg-hd-subtitle" style="font-size:14px;color:#6f6a61;"></div>
      </div>
      <button id="mg-hd-close" type="button" aria-label="{{ __('magnoolia.rowhouse.modal_close') }}"
              style="flex-shrink:0;background:#f5f0e8;border:none;cursor:pointer;color:#1d2430;font-size:20px;line-height:1;width:44px;height:44px;border-radius:50%;">&#x2715;</button>
    </div>

    <div style="padding:22px 28px 28px;">
      <div class="mg-hd-grid" style="display:grid;grid-template-columns:1.15fr 1fr;gap:24px;">
        {{-- Visuals --}}
        <div>
          <div style="border-radius:14px;overflow:hidden;background:#eee7db;aspect-ratio:4/3;">
            <img id="mg-hd-img" src="" alt="" width="768" height="576" loading="lazy" decoding="async"
                 style="width:100%;height:100%;object-fit:cover;display:block;">
          </div>

          {{-- Mini asendiplaan with marker — Phase 35: hidden by default
               (config magnoolia_rowhouses.show_location_map → true to restore). --}}
          @if($cleanUrl && config('magnoolia_rowhouses.show_location_map'))
          <div style="margin-top:14px;">
            <div style="font-size:11px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#9c8b7e;margin-bottom:6px;">{{ __('magnoolia.rowhouse.map_location') }}</div>
            <div style="display:flex;align-items:center;gap:7px;font-size:12.5px;color:#c89443;font-weight:600;margin-bottom:10px;">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="M21 21l-3.5-3.5M11 8v6M8 11h6"/></svg>
              {{ __('magnoolia.rowhouse.map_hint') }}
            </div>
            <div class="mg-hd-map-wrap" style="position:relative;border-radius:12px;overflow:hidden;border:1px solid rgba(29,36,48,.1);">
              {{-- Scroll viewport: zoom scales the inner width so the container scrolls
                   natively (touch-drag pans, taps still hit the plot zones). --}}
              <div id="mg-hd-map-vp" class="mg-hd-map-vp" style="position:relative;overflow:auto;-webkit-overflow-scrolling:touch;touch-action:pan-x pan-y;">
                <div id="mg-hd-map-inner" style="position:relative;width:100%;">
                  <img src="{{ $cleanUrl }}" alt="{{ __('magnoolia.rowhouse.alt_map') }}" width="1600" height="2243" loading="lazy" decoding="async" style="width:100%;height:auto;display:block;">
                  <svg id="mg-hd-map-svg" viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden="true" hidden
                       style="position:absolute;inset:0;width:100%;height:100%;pointer-events:none;z-index:2;overflow:visible;"></svg>
                  <span id="mg-hd-marker" aria-hidden="true"
                        style="position:absolute;width:26px;height:26px;border-radius:50%;border:3px solid #c89443;background:rgba(200,148,67,.32);box-shadow:0 0 0 6px rgba(200,148,67,.18);transform:translate(-50%,-50%);display:none;z-index:3;"></span>
                </div>
              </div>
              <div class="mg-hd-map-zoom">
                <button type="button" id="mg-hd-zin" aria-label="+">+</button>
                <button type="button" id="mg-hd-zout" aria-label="&minus;">&minus;</button>
              </div>
            </div>
            <div style="font-size:11px;color:#a8a196;margin-top:6px;">{{ __('magnoolia.rowhouse.marker_note') }}</div>
          </div>
          @endif
        </div>

        {{-- Specs + CTAs --}}
        <div>
          {{-- Phase 35: prominent price --}}
          <div id="mg-hd-price-wrap" style="display:none;margin-bottom:16px;">
            <div id="mg-hd-price" style="font-size:30px;font-weight:800;color:#1d2430;line-height:1;"></div>
            <div style="font-size:12px;color:#c0392b;font-weight:700;text-transform:uppercase;margin-top:6px;">{{ __('magnoolia.rowhouse.offer_inline') }}</div>
          </div>
          <div style="display:flex;align-items:center;gap:8px;margin-bottom:16px;">
            <span id="mg-hd-status-dot" style="width:10px;height:10px;border-radius:50%;background:#4caf50;"></span>
            <span id="mg-hd-status" style="font-size:14px;font-weight:700;color:#1d2430;"></span>
          </div>

          <dl style="margin:0 0 22px;display:flex;flex-direction:column;gap:0;">
            @php
              $specRows = [
                ['mg-hd-net', __('magnoolia.rowhouse.spec_net')],
                ['mg-hd-yard', __('magnoolia.rowhouse.spec_yard')],
                ['mg-hd-rooms', __('magnoolia.rowhouse.spec_rooms')],
                ['mg-hd-parking', __('magnoolia.rowhouse.spec_parking')],
                ['mg-hd-stage', __('magnoolia.rowhouse.spec_stage')],
                ['mg-hd-completion', __('magnoolia.rowhouse.spec_completion')],
                ['mg-hd-energy', __('magnoolia.rowhouse.spec_energy')],
              ];
            @endphp
            @foreach($specRows as [$id, $label])
            <div style="display:flex;justify-content:space-between;gap:16px;padding:11px 0;border-bottom:1px solid rgba(29,36,48,.07);">
              <dt style="font-size:13px;color:#888;margin:0;">{{ $label }}</dt>
              <dd id="{{ $id }}" style="font-size:13px;font-weight:600;color:#1d2430;margin:0;text-align:right;"></dd>
            </div>
            @endforeach
          </dl>

          <div style="display:flex;flex-direction:column;gap:10px;">
            {{-- Primary CTA: reuses the global inquiry-drawer (data-* set on open) --}}
            <a id="mg-hd-cta" href="{{ lroute('magnoolia.contact') }}#kontaktivorm"
               data-mg-inquiry-open
               data-mg-analytics="magnoolia_home_detail_inquiry"
               data-source-component="home_detail_modal"
               data-unit-key="" data-unit-slug="" data-unit-address="" data-unit-stage="" data-unit-status="" data-unit-price-public=""
               style="background:#c89443;color:#fff;padding:13px 18px;border-radius:10px;text-decoration:none;font-size:15px;font-weight:700;text-align:center;display:block;"></a>

            <a id="mg-hd-call" href="tel:{{ $phone }}" data-mg-analytics="magnoolia_phone_click"
               style="border:1.5px solid #c89443;color:#c89443;padding:12px 18px;border-radius:10px;text-decoration:none;font-size:14px;font-weight:600;text-align:center;display:block;">{{ __('magnoolia.rowhouse.cta_call') }}</a>
            {{-- Phase 35: "Vaata asendiplaani" link removed — it navigated away and on
                 mobile left the modal impossible to close; the plan is already on this page. --}}
          </div>
        </div>
      </div>

      {{-- Floor plans — Phase 35: full-width row (side by side on desktop),
           click → lightbox with the large image. --}}
      <div id="mg-hd-floors" style="margin-top:22px;display:none;border-top:1px solid rgba(29,36,48,.08);padding-top:20px;">
        <div style="font-size:11px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#9c8b7e;margin-bottom:12px;">{{ __('magnoolia.rowhouse.floorplans_title') }}</div>
        <div class="mg-hd-floors-row" style="display:flex;gap:16px;">
          <figure id="mg-hd-floor1-fig" style="margin:0;flex:1 1 0;min-width:0;">
            <button type="button" class="mg-hd-floor-btn" data-hd-floor-open="1" aria-label="{{ __('magnoolia.rowhouse.open_larger') }}"
                    style="width:100%;border:1px solid rgba(29,36,48,.1);border-radius:12px;background:#fff;padding:12px;cursor:zoom-in;">
              <img id="mg-hd-floor1-img" alt="" loading="lazy" decoding="async" style="width:100%;height:auto;display:block;">
            </button>
            <figcaption style="font-size:12px;color:#6f6a61;text-align:center;margin-top:6px;font-weight:600;">{{ __('magnoolia.rowhouse.floor_1') }}</figcaption>
          </figure>
          <figure id="mg-hd-floor2-fig" style="margin:0;flex:1 1 0;min-width:0;">
            <button type="button" class="mg-hd-floor-btn" data-hd-floor-open="2" aria-label="{{ __('magnoolia.rowhouse.open_larger') }}"
                    style="width:100%;border:1px solid rgba(29,36,48,.1);border-radius:12px;background:#fff;padding:12px;cursor:zoom-in;">
              <img id="mg-hd-floor2-img" alt="" loading="lazy" decoding="async" style="width:100%;height:auto;display:block;">
            </button>
            <figcaption style="font-size:12px;color:#6f6a61;text-align:center;margin-top:6px;font-weight:600;">{{ __('magnoolia.rowhouse.floor_2') }}</figcaption>
          </figure>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Floor-plan lightbox (full-screen, shared by the modal) --}}
<div id="mg-hd-lightbox" role="dialog" aria-modal="true"
     style="display:none;position:fixed;inset:0;z-index:9300;background:rgba(15,18,24,.9);align-items:center;justify-content:center;cursor:zoom-out;">
  <button type="button" id="mg-hd-lb-close" aria-label="{{ __('magnoolia.rowhouse.modal_close') }}"
          style="position:absolute;top:18px;right:22px;background:rgba(255,255,255,.14);border:none;color:#fff;font-size:24px;line-height:1;width:46px;height:46px;border-radius:50%;cursor:pointer;">&#x2715;</button>
  <img id="mg-hd-lb-img" src="" alt="" style="max-width:94vw;max-height:92vh;object-fit:contain;border-radius:8px;">
</div>

@push('scripts')
<script>
(function () {
  var HOMES = {!! $homesEnc !!};
  var byKey = {}, POLY_HOMES = [];
  HOMES.forEach(function (h) {
    byKey[h.key] = h; byKey[h.unit_key] = h; byKey[h.slug] = h;
    if (h.mappoly && h.mappoly.length > 2) POLY_HOMES.push(h);
  });

  // Draw every home plot as a clickable/hoverable zone on the mini asendiplaan;
  // the open home is emphasised. Clicks reuse the [data-mg-home-open] delegation.
  function renderHdMapZones(activeKey) {
    var svg = document.getElementById('mg-hd-map-svg');
    if (!svg) return;
    if (!POLY_HOMES.length) { svg.innerHTML = ''; svg.setAttribute('hidden', ''); return; }
    svg.innerHTML = POLY_HOMES.map(function (h) {
      var pts = h.mappoly.map(function (p) { return (p[0] * 100).toFixed(2) + ',' + (p[1] * 100).toFixed(2); }).join(' ');
      var cls = 'mg-hd-map-zone' + (h.key === activeKey ? ' is-active' : '');
      return '<polygon class="' + cls + '" data-mg-home-open="' + h.key + '" tabindex="0" role="button" aria-label="' + h.display + '" points="' + pts + '"></polygon>';
    }).join('');
    svg.removeAttribute('hidden'); // SVG ignores .hidden=false; remove the attribute
  }

  var overlay = document.getElementById('mg-hd-overlay');
  var dialog  = document.getElementById('mg-hd-dialog');
  var closeBtn = document.getElementById('mg-hd-close');
  if (!overlay) return;

  var L = {
    available: @json(__('magnoolia.statuses.available')),
    reserved:  @json(__('magnoolia.statuses.reserved')),
    sold:      @json(__('magnoolia.statuses.sold')),
    tbc:       @json(__('magnoolia.pricing.status_tbc')),
    plan:      @json(__('magnoolia.rowhouse.plan_prefix')),
    rooms:     @json(__('magnoolia.rowhouse.rooms_unit')),
    energy:    @json(__('magnoolia.rowhouse.energy_value')),
    ctaOffer:  @json(__('magnoolia.rowhouse.cta_offer')),
    ctaAvail:  @json(__('magnoolia.rowhouse.cta_availability')),
    ctaFree:   @json(__('magnoolia.rowhouse.cta_view_free')),
    yardInline:@json(__('magnoolia.rowhouse.yard_inline')),
  };
  var COLORS = { available:'#4caf50', reserved:'#c89443', sold:'#9a948a', tbc:'#9c27b0' };
  var HOMES_URL = @json($homesUrl);
  var MAP_URL = @json($mapUrl);
  var CONTACT_URL = @json(lroute('magnoolia.contact') . '#kontaktivorm');

  var set = function (id, txt) { var el = document.getElementById(id); if (el) el.textContent = (txt == null || txt === '') ? '—' : txt; };
  var lastFocus = null;
  var hdActiveHome = null;

  // In-modal location-map zoom (mobile). Scaling the inner wrapper's WIDTH makes the
  // overflow viewport scroll natively: touch-drag pans, and single taps still reach
  // the plot zones (no manual coordinate math / no drag-vs-tap conflict).
  var hdMapVp = document.getElementById('mg-hd-map-vp');
  var hdMapInner = document.getElementById('mg-hd-map-inner');
  var hdZoom = 1;
  function applyHdZoom() { if (hdMapInner) hdMapInner.style.width = (hdZoom * 100) + '%'; }
  function setHdZoom(z) {
    if (!hdMapVp || !hdMapInner) return;
    var oldW = hdMapInner.offsetWidth || 1, oldH = hdMapInner.offsetHeight || 1;
    var fx = (hdMapVp.scrollLeft + hdMapVp.clientWidth / 2) / oldW;   // keep the current
    var fy = (hdMapVp.scrollTop + hdMapVp.clientHeight / 2) / oldH;   // centre fixed
    hdZoom = Math.min(4, Math.max(1, z));
    applyHdZoom();
    var nW = hdMapInner.offsetWidth, nH = hdMapInner.offsetHeight;
    hdMapVp.scrollLeft = fx * nW - hdMapVp.clientWidth / 2;
    hdMapVp.scrollTop  = fy * nH - hdMapVp.clientHeight / 2;
  }
  function resetHdZoom() { hdZoom = 1; applyHdZoom(); if (hdMapVp) { hdMapVp.scrollTop = 0; hdMapVp.scrollLeft = 0; } }
  (function () {
    var zin = document.getElementById('mg-hd-zin'), zout = document.getElementById('mg-hd-zout');
    if (zin) zin.addEventListener('click', function () { setHdZoom(hdZoom * 1.5); });
    if (zout) zout.addEventListener('click', function () { setHdZoom(hdZoom / 1.5); });
  })();

  window.mgOpenHome = function (key) {
    var h = byKey[key];
    if (!h) return;
    // Re-selecting a home from the modal's mini-map re-runs this while the modal is
    // already open — only the FIRST open should capture focus / lock the scroll.
    var wasOpen = overlay.style.display !== 'none';
    if (!wasOpen) lastFocus = document.activeElement;

    set('mg-hd-title', h.display);
    var sub = [];
    if (h.plan) sub.push(L.plan + ' ' + h.plan);
    if (h.rooms) sub.push(h.rooms + ' ' + L.rooms);
    if (h.net) sub.push(h.net + ' m²');
    document.getElementById('mg-hd-subtitle').textContent = sub.join(' · ') + (h.yard ? '  ·  ' + L.yardInline.replace(':area', h.yard) : '');

    // Phase 35 — prominent price
    var priceWrap = document.getElementById('mg-hd-price-wrap');
    if (h.price) {
      document.getElementById('mg-hd-price').textContent = '€ ' + String(h.price).replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
      priceWrap.style.display = '';
    } else { priceWrap.style.display = 'none'; }

    var statusLabel = L[h.status] || h.status;
    var statusColor = COLORS[h.status] || '#888';
    set('mg-hd-status', statusLabel);
    document.getElementById('mg-hd-status-dot').style.background = statusColor;

    set('mg-hd-net', h.net ? h.net + ' m²' : null);
    set('mg-hd-yard', h.yard ? h.yard + ' m²' : null);
    set('mg-hd-rooms', h.rooms || null);
    set('mg-hd-parking', h.parking || null);
    set('mg-hd-stage', h.stage ? (h.stage === 1 ? 'I' : 'II') : null);
    set('mg-hd-completion', h.completion || null);
    set('mg-hd-energy', L.energy);

    var img = document.getElementById('mg-hd-img');
    if (h.img) { img.src = h.img; img.alt = h.display; img.style.display = ''; } else { img.removeAttribute('src'); }

    // plot zones (all homes) + pin fallback when the open home has no polygon
    var hasPoly = h.mappoly && h.mappoly.length > 2;
    renderHdMapZones(h.key);
    resetHdZoom(); // each home opens at 1× so it never stays zoomed/scrolled off
    var marker = document.getElementById('mg-hd-marker');
    if (marker && !hasPoly && h.mx != null && h.my != null) {
      marker.style.left = (h.mx * 100) + '%';
      marker.style.top  = (h.my * 100) + '%';
      marker.style.display = '';
    } else if (marker) { marker.style.display = 'none'; }

    var floors = document.getElementById('mg-hd-floors');
    floors.style.display = (h.floor1_img || h.floor2_img) ? '' : 'none';
    hdActiveHome = h;
    hdShowFloors(h);

    // Primary CTA — status aware
    var cta = document.getElementById('mg-hd-cta');
    if (h.status === 'sold') {
      cta.textContent = L.ctaFree;
      cta.setAttribute('href', HOMES_URL);
      cta.removeAttribute('data-mg-inquiry-open');
    } else {
      cta.textContent = (h.status === 'reserved') ? L.ctaAvail : L.ctaOffer;
      cta.setAttribute('href', CONTACT_URL);
      cta.setAttribute('data-mg-inquiry-open', '');
      cta.setAttribute('data-unit-key', h.unit_key || '');
      cta.setAttribute('data-unit-slug', h.slug || '');
      cta.setAttribute('data-unit-address', h.address || '');
      cta.setAttribute('data-unit-stage', h.stage || '');
      cta.setAttribute('data-unit-status', h.status || '');
      cta.setAttribute('data-unit-price-public', h.price_public ? 'true' : 'false');
    }

    overlay.style.display = '';
    if (dialog) dialog.scrollTop = 0; // always open scrolled to the top, not where the last home left off
    if (!wasOpen) lockScroll();
    closeBtn.focus({ preventScroll: true });

    if (window.dataLayer) {
      window.dataLayer.push({ event: 'magnoolia_home_detail_open', unit_key: h.unit_key, asset_key: h.key, status: h.status });
    }
    // Tell the interactive map (desktop + fullscreen) to highlight this plot.
    document.dispatchEvent(new CustomEvent('mg:home-selected', { detail: { key: h.key } }));
  };

  // Phase 35: render both floor plans at once; hide a figure with no image.
  function hdShowFloors(h) {
    var L1 = @json(__('magnoolia.rowhouse.floor_1'));
    var L2 = @json(__('magnoolia.rowhouse.floor_2'));
    [['1', h.floor1_img, L1], ['2', h.floor2_img, L2]].forEach(function (f) {
      var fig = document.getElementById('mg-hd-floor' + f[0] + '-fig');
      var img = document.getElementById('mg-hd-floor' + f[0] + '-img');
      if (f[1]) { img.src = f[1]; img.alt = (h.display || '') + ' — ' + f[2]; fig.style.display = ''; }
      else { img.removeAttribute('src'); fig.style.display = 'none'; }
    });
  }

  // iOS-safe scroll lock: pin the body so the fixed overlay stays at the viewport
  // top even when the page was scrolled (e.g. opened via a #hash). The position:fixed
  // guard makes it cooperate when this modal is opened OVER the fullscreen map (or the
  // map over it): only the first locker pins/restores the body, so closing this modal
  // does not unlock a still-open map.
  var scrollLockY = 0, hdDidLock = false, hdMode = '';
  function lockScroll() {
    if (document.body.style.position === 'fixed' || document.body.style.overflow === 'hidden') { hdDidLock = false; return; }
    hdDidLock = true;
    // Only mobile needs the position:fixed trick (iOS keeps the fixed overlay in place).
    // On desktop that trick causes a scroll jump on close, so just hide overflow there.
    hdMode = window.matchMedia('(max-width: 768px)').matches ? 'fixed' : 'overflow';
    var b = document.body;
    if (hdMode === 'fixed') {
      scrollLockY = window.scrollY || window.pageYOffset || 0;
      b.style.position = 'fixed'; b.style.top = (-scrollLockY) + 'px';
      b.style.left = '0'; b.style.right = '0'; b.style.width = '100%'; b.style.overflow = 'hidden';
    } else {
      b.style.overflow = 'hidden';
    }
  }
  function unlockScroll() {
    if (!hdDidLock) return;
    hdDidLock = false;
    var b = document.body;
    if (hdMode === 'fixed') {
      b.style.position = ''; b.style.top = ''; b.style.left = ''; b.style.right = ''; b.style.width = ''; b.style.overflow = '';
      window.scrollTo(0, scrollLockY);
    } else {
      b.style.overflow = '';
    }
  }

  function close() {
    overlay.style.display = 'none';
    unlockScroll();
    if (lastFocus && lastFocus.focus) lastFocus.focus({ preventScroll: true });
  }

  // Floor-plan lightbox
  var lightbox = document.getElementById('mg-hd-lightbox');
  var lbImg = document.getElementById('mg-hd-lb-img');
  function openLightbox(src, alt) { if (!src) return; lbImg.src = src; lbImg.alt = alt || ''; lightbox.style.display = 'flex'; }
  function closeLightbox() { lightbox.style.display = 'none'; lbImg.removeAttribute('src'); }

  // Delegated triggers
  document.addEventListener('click', function (e) {
    var fb = e.target.closest('[data-hd-floor-open]');
    if (fb) {
      e.preventDefault();
      var im = document.getElementById('mg-hd-floor' + fb.getAttribute('data-hd-floor-open') + '-img');
      if (im && im.getAttribute('src')) openLightbox(im.src, im.alt);
      return;
    }
    if (e.target === lightbox || e.target.closest('#mg-hd-lb-close')) { closeLightbox(); return; }
    var t = e.target.closest('[data-mg-home-open]');
    if (t) { e.preventDefault(); window.mgOpenHome(t.getAttribute('data-mg-home-open')); return; }
    if (e.target === overlay) close();
  });
  closeBtn.addEventListener('click', close);

  // ESC + simple focus trap
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && lightbox.style.display === 'flex') { closeLightbox(); return; }
    if (overlay.style.display === 'none') return;
    if (e.key === 'Escape') { close(); return; }
    if (e.key === 'Enter' || e.key === ' ') {
      var z = e.target.closest && e.target.closest('.mg-hd-map-zone[data-mg-home-open]');
      if (z) { e.preventDefault(); window.mgOpenHome(z.getAttribute('data-mg-home-open')); return; }
    }
    if (e.key === 'Tab') {
      var f = dialog.querySelectorAll('a[href],button,[tabindex]:not([tabindex="-1"])');
      f = Array.prototype.filter.call(f, function (el) { return el.offsetParent !== null; });
      if (!f.length) return;
      var first = f[0], last = f[f.length - 1];
      if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
      else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
    }
  });
})();
</script>
<style>
@media (max-width: 720px) {
  #mg-hd-dialog .mg-hd-grid { grid-template-columns: 1fr !important; }
}
/* In-modal location map zoom — controls + scroll cap are mobile-only; desktop
   keeps the map exactly as before (controls hidden, no height cap, no scroll). */
.mg-hd-map-zoom { display: none; position: absolute; top: 8px; right: 8px; z-index: 5; gap: 6px; }
.mg-hd-map-zoom button { width: 36px; height: 36px; border-radius: 8px; border: none; background: rgba(29,36,48,.85); color: #fff; font-size: 20px; line-height: 1; cursor: pointer; box-shadow: 0 1px 5px rgba(0,0,0,.3); }
@media (max-width: 720px) {
  .mg-hd-map-zoom { display: flex; }
  .mg-hd-map-vp { max-height: 60vh; }
}
.mg-hd-map-zone { fill: rgba(200,148,67,.2); stroke: rgba(185,128,43,.75); stroke-width: 1.5px; stroke-linejoin: round; vector-effect: non-scaling-stroke; pointer-events: auto; cursor: pointer; outline: none; transition: fill .15s ease, stroke-width .15s ease; }
.mg-hd-map-zone:hover, .mg-hd-map-zone:focus-visible { fill: rgba(200,148,67,.42); stroke: #b9802b; stroke-width: 2px; }
.mg-hd-map-zone.is-active { fill: rgba(200,148,67,.55); stroke: #b9802b; stroke-width: 2.5px; filter: drop-shadow(0 1px 4px rgba(185,128,43,.55)); }
</style>
@endpush
