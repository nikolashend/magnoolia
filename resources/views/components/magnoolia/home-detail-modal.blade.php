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
  $cleanUrl = $clean ? asset($clean['1024'] ?? $clean['base']) : null;

  $homesJs = collect($rhs->allHomes())->map(function ($h) {
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
          'img'        => $img ? asset($img) : null,
          'floor1'     => ($f1 && file_exists(public_path($f1))) ? asset($f1) : null,
          'floor2'     => ($f2 && file_exists(public_path($f2))) ? asset($f2) : null,
          'floor1_img' => ($fp['floor_1']['base'] ?? null) ? asset($fp['floor_1']['1024'] ?? $fp['floor_1']['base']) : null,
          'floor2_img' => ($fp['floor_2']['base'] ?? null) ? asset($fp['floor_2']['1024'] ?? $fp['floor_2']['base']) : null,
          'mx'         => $hl['x'] ?? null,
          'my'         => $hl['y'] ?? null,
          'price_public' => $cta['price_public'] ?? false,
      ];
  })->values()->all();

  $homesEnc = json_encode($homesJs, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
  $phone        = config('magnoolia.project.contact_phone', '+37258164078');
  $homesUrl     = lroute('magnoolia.homes');
  $mapUrl       = lroute('magnoolia.site-plan');
@endphp

<div id="mg-hd-overlay" role="presentation"
     style="display:none;position:fixed;inset:0;z-index:9200;background:rgba(20,25,33,.6);">
  <div id="mg-hd-dialog" role="dialog" aria-modal="true" aria-labelledby="mg-hd-title"
       style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);width:min(920px,94vw);max-height:92vh;overflow-y:auto;background:#fff;border-radius:18px;box-shadow:0 24px 64px rgba(20,25,33,.32);">

    {{-- Header --}}
    <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:16px;padding:24px 28px 16px;border-bottom:1px solid rgba(29,36,48,.08);position:sticky;top:0;background:#fff;border-radius:18px 18px 0 0;z-index:2;">
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

          {{-- Mini asendiplaan with marker --}}
          @if($cleanUrl)
          <div style="margin-top:14px;">
            <div style="font-size:11px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#9c8b7e;margin-bottom:8px;">{{ __('magnoolia.rowhouse.map_location') }}</div>
            <div style="position:relative;border-radius:12px;overflow:hidden;border:1px solid rgba(29,36,48,.1);">
              <img src="{{ $cleanUrl }}" alt="{{ __('magnoolia.rowhouse.alt_map') }}" width="1024" height="1436" loading="lazy" decoding="async" style="width:100%;height:auto;display:block;">
              <span id="mg-hd-marker" aria-hidden="true"
                    style="position:absolute;width:26px;height:26px;border-radius:50%;border:3px solid #c89443;background:rgba(200,148,67,.32);box-shadow:0 0 0 6px rgba(200,148,67,.18);transform:translate(-50%,-50%);display:none;"></span>
            </div>
            <div style="font-size:11px;color:#a8a196;margin-top:6px;">{{ __('magnoolia.rowhouse.marker_note') }}</div>
          </div>
          @endif

          {{-- Floor plans (per-building images for all rows) --}}
          <div id="mg-hd-floors" style="margin-top:14px;display:none;">
            <div style="font-size:11px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#9c8b7e;margin-bottom:8px;">{{ __('magnoolia.rowhouse.floorplans_title') }}</div>
            <div style="display:flex;gap:8px;margin-bottom:10px;">
              <button type="button" class="mg-hd-ftab is-active" data-hd-floor="1" style="padding:7px 14px;border-radius:100px;border:1px solid rgba(29,36,48,.2);background:#1d2430;color:#fff;font-size:12.5px;font-weight:600;cursor:pointer;">{{ __('magnoolia.rowhouse.floor_1') }}</button>
              <button type="button" class="mg-hd-ftab" data-hd-floor="2" style="padding:7px 14px;border-radius:100px;border:1px solid rgba(29,36,48,.2);background:#fff;color:#6f6a61;font-size:12.5px;font-weight:600;cursor:pointer;">{{ __('magnoolia.rowhouse.floor_2') }}</button>
            </div>
            <div style="position:relative;border:1px solid rgba(29,36,48,.1);border-radius:12px;background:#fff;padding:10px;text-align:center;min-height:120px;">
              <img id="mg-hd-floor-img" alt="" loading="lazy" decoding="async" style="max-width:100%;height:auto;display:inline-block;">
              <a id="mg-hd-floor-pdf" target="_blank" rel="noopener" style="display:none;position:absolute;top:10px;right:10px;font-size:11px;font-weight:700;color:#c89443;text-decoration:none;background:rgba(255,255,255,.92);padding:5px 9px;border-radius:8px;">{{ __('magnoolia.rowhouse.open_pdf') }} ↗</a>
            </div>
          </div>
        </div>

        {{-- Specs + CTAs --}}
        <div>
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

            <a id="mg-hd-map" href="{{ $mapUrl }}#mg-masterplan"
               style="color:#6f6a61;padding:6px;text-decoration:none;font-size:13px;font-weight:600;text-align:center;display:block;">{{ __('magnoolia.rowhouse.cta_view_map') }} →</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
(function () {
  var HOMES = {!! $homesEnc !!};
  var byKey = {}; HOMES.forEach(function (h) { byKey[h.key] = h; byKey[h.unit_key] = h; byKey[h.slug] = h; });

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

  window.mgOpenHome = function (key) {
    var h = byKey[key];
    if (!h) return;
    lastFocus = document.activeElement;

    set('mg-hd-title', h.display);
    var sub = [];
    if (h.plan) sub.push(L.plan + ' ' + h.plan);
    if (h.rooms) sub.push(h.rooms + ' ' + L.rooms);
    if (h.net) sub.push(h.net + ' m²');
    document.getElementById('mg-hd-subtitle').textContent = sub.join(' · ') + (h.yard ? '  ·  ' + L.yardInline.replace(':area', h.yard) : '');

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

    var marker = document.getElementById('mg-hd-marker');
    if (marker && h.mx != null && h.my != null) {
      marker.style.left = (h.mx * 100) + '%';
      marker.style.top  = (h.my * 100) + '%';
      marker.style.display = '';
    } else if (marker) { marker.style.display = 'none'; }

    // "Vaata asendiplaani" deep-links into the masterplan with this home selected
    var mapLink = document.getElementById('mg-hd-map');
    if (mapLink) mapLink.href = MAP_URL + '?home=' + encodeURIComponent(h.key) + '#mg-masterplan';

    var floors = document.getElementById('mg-hd-floors');
    floors.style.display = (h.floor1_img || h.floor2_img) ? '' : 'none';
    hdActiveHome = h;
    hdSetFloor('1');

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
    document.body.style.overflow = 'hidden';
    closeBtn.focus();

    if (window.dataLayer) {
      window.dataLayer.push({ event: 'magnoolia_home_detail_open', unit_key: h.unit_key, asset_key: h.key, status: h.status });
    }
  };

  function hdSetFloor(f) {
    var h = hdActiveHome; if (!h) return;
    var img = document.getElementById('mg-hd-floor-img');
    var pdf = document.getElementById('mg-hd-floor-pdf');
    var src = f === '2' ? h.floor2_img : h.floor1_img;
    var pdfSrc = f === '2' ? h.floor2 : h.floor1;
    var label = f === '2' ? @json(__('magnoolia.rowhouse.floor_2')) : @json(__('magnoolia.rowhouse.floor_1'));
    if (src) { img.src = src; img.alt = (h.display || '') + ' — ' + label; img.style.display = ''; }
    else { img.removeAttribute('src'); img.style.display = 'none'; }
    if (pdfSrc) { pdf.href = pdfSrc; pdf.style.display = ''; } else { pdf.style.display = 'none'; }
    document.querySelectorAll('.mg-hd-ftab').forEach(function (t) {
      var on = t.getAttribute('data-hd-floor') === f;
      t.classList.toggle('is-active', on);
      t.style.background = on ? '#1d2430' : '#fff';
      t.style.color = on ? '#fff' : '#6f6a61';
    });
  }

  function close() {
    overlay.style.display = 'none';
    document.body.style.overflow = '';
    if (lastFocus && lastFocus.focus) lastFocus.focus();
  }

  // Delegated triggers
  document.addEventListener('click', function (e) {
    var ft = e.target.closest('.mg-hd-ftab');
    if (ft && dialog.contains(ft)) { hdSetFloor(ft.getAttribute('data-hd-floor')); return; }
    var t = e.target.closest('[data-mg-home-open]');
    if (t) { e.preventDefault(); window.mgOpenHome(t.getAttribute('data-mg-home-open')); return; }
    if (e.target === overlay) close();
  });
  closeBtn.addEventListener('click', close);

  // ESC + simple focus trap
  document.addEventListener('keydown', function (e) {
    if (overlay.style.display === 'none') return;
    if (e.key === 'Escape') { close(); return; }
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
</style>
@endpush
