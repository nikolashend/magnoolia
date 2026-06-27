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

  // Per-view hotspots. Phase 35: a view with per-HOME boxes
  // (config perspective_boxes.{key}) renders clickable per-home zones (boxes-only,
  // click → home detail). Otherwise it falls back to per-ROW hotspots (manifest
  // hulls for the calibrated view, or config perspective_views.{key}).
  $hotViewsCfg  = (array) config('magnoolia_hotspots.perspective_views', []);
  $persBoxesCfg = (array) config('magnoolia_hotspots.perspective_boxes', []);
  $viewHotspots = [];
  foreach ($viewsJs as $v) {
    $boxesCfg = $persBoxesCfg[$v['key']] ?? [];
    if (!empty($boxesCfg)) {
      $items = [];
      foreach ($rows as $r) {
        foreach ($r['homes'] as $h) {
          $b = $boxesCfg[$h['asset_key']] ?? null;
          if ($b && !empty($b['polygon'])) {
            $items[] = ['key' => $h['asset_key'], 'label' => $h['display_address'], 'status' => $h['status'], 'marker' => $b['marker'] ?? null, 'hull' => $b['polygon']];
          }
        }
      }
      $viewHotspots[] = ['mode' => 'home', 'items' => $items];
    } elseif (($v['hotspots'] ?? false)) {
      $set = [];
      foreach ($rows as $r) {
        $p = $r['perspective'] ?? null;
        if ($p && !empty($p['marker'])) {
          $set[] = ['pos' => $r['pos'], 'building' => $r['building'], 'title' => $r['title'], 'marker' => $p['marker'], 'hull' => $p['hull'] ?? null];
        }
      }
      $viewHotspots[] = ['mode' => 'row', 'items' => $set];
    } else {
      $cfg = $hotViewsCfg[$v['key']] ?? [];
      $set = [];
      foreach ($rows as $r) {
        $h = $cfg[$r['pos']] ?? null;
        if ($h && !empty($h['marker'])) {
          $set[] = ['pos' => $r['pos'], 'building' => $r['building'], 'title' => $r['title'], 'marker' => $h['marker'], 'hull' => $h['polygon'] ?? null];
        }
      }
      $viewHotspots[] = ['mode' => 'row', 'items' => $set];
    }
  }
  $viewHotspotsEnc = json_encode($viewHotspots, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
  $statusColorsEnc = json_encode(['available'=>'#4caf50','reserved'=>'#c89443','sold'=>'#9a948a','tbc'=>'#9c27b0']);

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
          'mappoly'    => $h['map_polygon'] ?? null,
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
        {{-- Zones + markers are populated by JS (renderHotspots) for the active view
             — per-home boxes on the main view, per-row on the calibrated alternate
             view. Empty server-side so no markers flash in the wrong position; the
             row cards below are the no-JS / mobile fallback. --}}
        <svg class="mg-mp__svg" id="mg-mp-svg" viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden="true"></svg>
        <div id="mg-mp-markers"></div>
        {{-- Coordinate picker for hand-setting perspective hotspots: /asendiplaan?mp_grid=1 --}}
        @if(request()->boolean('mp_grid'))
        <svg class="mg-mp__grid" viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden="true"
             style="position:absolute;inset:0;width:100%;height:100%;pointer-events:none;z-index:3;">
          @for($i = 1; $i < 10; $i++)
            <line x1="{{ $i*10 }}" y1="0" x2="{{ $i*10 }}" y2="100" stroke="#ffd24a" stroke-opacity=".55" stroke-width=".15"/>
            <line x1="0" y1="{{ $i*10 }}" x2="100" y2="{{ $i*10 }}" stroke="#ffd24a" stroke-opacity=".55" stroke-width=".15"/>
            <text x="{{ $i*10 + 0.4 }}" y="2.8" fill="#ffd24a" font-size="2.4">.{{ $i }}</text>
            <text x="0.4" y="{{ $i*10 - 0.6 }}" fill="#ffd24a" font-size="2.4">.{{ $i }}</text>
          @endfor
        </svg>
        {{-- transparent click-capture layer (sits above markers in picker mode) --}}
        <div id="mg-mp-pick" style="position:absolute;inset:0;z-index:5;cursor:crosshair;"></div>
        <svg id="mg-mp-pickpts" viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden="true"
             style="position:absolute;inset:0;width:100%;height:100%;pointer-events:none;z-index:6;"></svg>
        @endif

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

    @if(request()->boolean('mp_grid'))
    {{-- Coordinate picker readout (dev/admin helper, only with ?mp_grid=1) --}}
    <div class="mg-mp__picker" id="mg-mp-picker">
      <div class="mg-mp__picker-row">
        <strong>Koordinaadipicker</strong> — kliki pildil, et saada punkti [x, y]. Viimane punkt:
        <span id="mg-mp-pick-last" class="mg-mp__picker-last">—</span>
      </div>
      <textarea id="mg-mp-pick-out" class="mg-mp__picker-out" rows="2" readonly placeholder="polygon => [ ... ]  (kliki nurki)"></textarea>
      <div class="mg-mp__picker-btns">
        <button type="button" id="mg-mp-pick-copy" class="mg-btn mg-btn--ghost">Kopeeri polygon</button>
        <button type="button" id="mg-mp-pick-undo" class="mg-btn mg-btn--ghost">Võta tagasi</button>
        <button type="button" id="mg-mp-pick-clear" class="mg-btn mg-btn--ghost">Tühjenda</button>
      </div>
    </div>
    <script>
    (function () {
      var pick = document.getElementById('mg-mp-pick'); if (!pick) return;
      var ptsSvg = document.getElementById('mg-mp-pickpts');
      var lastEl = document.getElementById('mg-mp-pick-last');
      var outEl  = document.getElementById('mg-mp-pick-out');
      var pts = [];
      function render() {
        outEl.value = 'polygon => [' + pts.map(function (p) { return '[' + p[0] + ', ' + p[1] + ']'; }).join(', ') + ']';
        ptsSvg.innerHTML = pts.map(function (p) { return '<circle cx="' + (p[0]*100) + '" cy="' + (p[1]*100) + '" r="0.8" fill="#ffd24a" stroke="#1d2430" stroke-width="0.2"/>'; }).join('') +
          (pts.length > 1 ? '<polygon points="' + pts.map(function (p) { return (p[0]*100) + ',' + (p[1]*100); }).join(' ') + '" fill="#ffd24a" fill-opacity="0.18" stroke="#ffd24a" stroke-width="0.3"/>' : '');
      }
      pick.addEventListener('click', function (e) {
        var r = pick.getBoundingClientRect();
        var x = +(Math.min(1, Math.max(0, (e.clientX - r.left) / r.width))).toFixed(3);
        var y = +(Math.min(1, Math.max(0, (e.clientY - r.top) / r.height))).toFixed(3);
        pts.push([x, y]); lastEl.textContent = '[' + x + ', ' + y + ']'; render();
      });
      document.getElementById('mg-mp-pick-undo').addEventListener('click', function () { pts.pop(); render(); });
      document.getElementById('mg-mp-pick-clear').addEventListener('click', function () { pts = []; lastEl.textContent = '—'; render(); });
      document.getElementById('mg-mp-pick-copy').addEventListener('click', function () {
        outEl.select(); try { navigator.clipboard.writeText(outEl.value); } catch (e) { document.execCommand('copy'); }
        this.textContent = '✓ Kopeeritud';
      });
    })();
    </script>
    @endif

    @if(request()->boolean('my_grid') && $cleanSrc)
    {{-- Phase 35 PER-HOME plot editor over the CLEAN 2D asendiplaan:
         /asendiplaan?my_grid=1 — pick a home, click its plot corners, Save, repeat,
         then Copy and paste into config/magnoolia_hotspots.php → 'asendiplaan' => [ … ]. --}}
    @php
      $myHomes = collect($rows)->flatMap(fn($r)=>collect($r['homes'])->map(fn($h)=>[
        'key'=>$h['asset_key'], 'label'=>$h['display_address'],
      ]))->values()->all();
    @endphp
    {{-- Wide breakout so the 2D plan is as large as possible for precise tracing. --}}
    <div class="mg-mp__map-picker" style="margin-top:20px;width:min(96vw,1500px);position:relative;left:50%;transform:translateX(-50%);">
      <div class="mg-mp__map-picker-stage" id="mg-my-stage" style="position:relative;width:100%;">
        <img src="{{ $cleanSrc }}" alt="" decoding="async" style="width:100%;display:block;">
        <svg class="mg-mp__grid" viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden="true"
             style="position:absolute;inset:0;width:100%;height:100%;pointer-events:none;z-index:3;">
          @for($i = 1; $i < 20; $i++)
            <line x1="{{ $i*5 }}" y1="0" x2="{{ $i*5 }}" y2="100" stroke="#ffd24a" stroke-opacity=".35" stroke-width=".08"/>
            <line x1="0" y1="{{ $i*5 }}" x2="100" y2="{{ $i*5 }}" stroke="#ffd24a" stroke-opacity=".35" stroke-width=".08"/>
          @endfor
        </svg>
        <div id="mg-my-pick" style="position:absolute;inset:0;z-index:5;cursor:crosshair;"></div>
        <svg id="mg-my-svg" viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden="true"
             style="position:absolute;inset:0;width:100%;height:100%;pointer-events:none;z-index:6;overflow:visible;"></svg>
      </div>
      <div class="mg-mp__picker" id="mg-my-panel" style="margin-top:12px;">
        <div class="mg-mp__picker-row" style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
          <strong>Asendiplaani plaani-redaktor</strong>
          <label>Kodu:
            <select id="mg-my-home" style="padding:6px 8px;border-radius:8px;">
              @foreach($myHomes as $mh)
                <option value="{{ $mh['key'] }}">{{ $mh['label'] }}</option>
              @endforeach
            </select>
          </label>
          <span id="mg-my-progress" style="font-weight:600;color:#9a6b1f;">0/{{ count($myHomes) }}</span>
          <span style="color:#6f6a61;">— vali kodu, klõpsa krundi nurki (4+), siis “Salvesta krunt”.</span>
        </div>
        <textarea id="mg-my-out" class="mg-mp__picker-out" rows="6" readonly
                  placeholder="'asendiplaan' => [ ... ]  — täida ja kopeeri config/magnoolia_hotspots.php-i"></textarea>
        <div class="mg-mp__picker-btns" style="display:flex;gap:8px;flex-wrap:wrap;">
          <button type="button" id="mg-my-save" class="mg-btn mg-btn--gold">Salvesta krunt</button>
          <button type="button" id="mg-my-undo" class="mg-btn mg-btn--ghost">Võta punkt tagasi</button>
          <button type="button" id="mg-my-clear" class="mg-btn mg-btn--ghost">Tühjenda praegune</button>
          <button type="button" id="mg-my-copy" class="mg-btn mg-btn--ghost">Kopeeri config</button>
          <button type="button" id="mg-my-reset" class="mg-btn mg-btn--ghost" style="color:#b71c1c;">Lähtesta kõik</button>
        </div>
      </div>
    </div>
    <script>
    (function () {
      var pick = document.getElementById('mg-my-pick'); if (!pick) return;
      var svg = document.getElementById('mg-my-svg');
      var sel = document.getElementById('mg-my-home');
      var out = document.getElementById('mg-my-out');
      var prog = document.getElementById('mg-my-progress');
      var TOTAL = {{ count($myHomes) }};
      var plots = {};   // key -> [[x,y], ...]
      var cur = [];     // current points

      function centroid(pts) { var x=0,y=0; pts.forEach(function(p){x+=p[0];y+=p[1];}); return [+(x/pts.length).toFixed(3), +(y/pts.length).toFixed(3)]; }
      // vector-effect keeps strokes/dots crisp & tiny regardless of the stretched viewBox
      function poly(pts, fill) { return '<polygon points="' + pts.map(function (p) { return (p[0]*100) + ',' + (p[1]*100); }).join(' ') + '" fill="' + fill + '" stroke="#e0b052" stroke-width="1.2" vector-effect="non-scaling-stroke"/>'; }
      function dots(pts) { return pts.map(function (p) { return '<circle cx="' + (p[0]*100) + '" cy="' + (p[1]*100) + '" r="0.22" fill="#ffd24a" stroke="#1d2430" stroke-width="0.6" vector-effect="non-scaling-stroke"/>'; }).join(''); }
      function redraw() {
        var html = '';
        Object.keys(plots).forEach(function (k) {
          var pts = plots[k]; html += poly(pts, 'rgba(76,175,80,0.16)');
          var c = centroid(pts);
          html += '<text x="' + (c[0]*100) + '" y="' + (c[1]*100) + '" fill="#1d2430" font-size="1.3" text-anchor="middle" stroke="#fff" stroke-width="0.3" paint-order="stroke">' + k.replace('tee-', '') + '</text>';
        });
        if (cur.length) html += (cur.length > 1 ? poly(cur, 'rgba(200,148,67,0.28)') : '') + dots(cur);
        svg.innerHTML = html;
        prog.textContent = Object.keys(plots).length + '/' + TOTAL;
        [].forEach.call(sel.options, function (o) { o.textContent = o.textContent.replace(/ ✓$/, '') + (plots[o.value] ? ' ✓' : ''); });
        buildOut();
      }
      function buildOut() {
        var keys = Object.keys(plots);
        if (!keys.length) { out.value = ''; return; }
        var lines = keys.map(function (k) {
          var poly = plots[k].map(function (p) { return '[' + p[0] + ', ' + p[1] + ']'; }).join(', ');
          return "        '" + k + "' => ['polygon' => [" + poly + "]],";
        });
        out.value = "'asendiplaan' => [\n" + lines.join('\n') + "\n    ],";
      }
      pick.addEventListener('click', function (e) {
        var r = pick.getBoundingClientRect();
        var x = +(Math.min(1, Math.max(0, (e.clientX - r.left) / r.width))).toFixed(3);
        var y = +(Math.min(1, Math.max(0, (e.clientY - r.top) / r.height))).toFixed(3);
        cur.push([x, y]); redraw();
      });
      document.getElementById('mg-my-save').addEventListener('click', function () {
        if (cur.length < 3) { alert('Klõpsa vähemalt 3 nurka.'); return; }
        plots[sel.value] = cur.slice(); cur = [];
        var next = [].slice.call(sel.options).find(function (o) { return !plots[o.value]; });
        if (next) sel.value = next.value;
        redraw();
      });
      document.getElementById('mg-my-undo').addEventListener('click', function () { cur.pop(); redraw(); });
      document.getElementById('mg-my-clear').addEventListener('click', function () { cur = []; redraw(); });
      document.getElementById('mg-my-reset').addEventListener('click', function () { if (confirm('Kustuta kõik krundid?')) { plots = {}; cur = []; redraw(); } });
      document.getElementById('mg-my-copy').addEventListener('click', function () {
        out.select(); try { navigator.clipboard.writeText(out.value); } catch (e) { document.execCommand('copy'); }
        this.textContent = '✓ Kopeeritud';
      });
      // preload existing plots from config so you can refine instead of restart
      var EXISTING = @json(config('magnoolia_hotspots.asendiplaan', []));
      Object.keys(EXISTING || {}).forEach(function (k) { if (EXISTING[k] && EXISTING[k].polygon) plots[k] = EXISTING[k].polygon; });
      redraw();
    })();
    </script>
    @endif

    @if(request()->boolean('box_grid'))
    {{-- Phase 35 PER-HOME BOX editor over the MAIN perspective view (3.jpg):
         /asendiplaan?box_grid=1 — pick a home, click its box corners, Save, repeat
         for all 19, then Copy and paste into
         config/magnoolia_hotspots.php → 'perspective_boxes' => ['secondary' => [ … ]]. --}}
    @php
      $boxHomes = collect($rows)->flatMap(fn($r)=>collect($r['homes'])->map(fn($h)=>[
        'key'=>$h['asset_key'], 'label'=>$h['display_address'],
      ]))->values()->all();
      $boxMainKey = collect($views)->first()['key'] ?? 'secondary'; // current main view key
    @endphp
    <div class="mg-mp__map-picker" style="margin-top:20px;">
      <div class="mg-mp__map-picker-stage" id="mg-box-stage" style="position:relative;">
        <img src="{{ $persSrc }}" @if($persSrcset) srcset="{{ $persSrcset }}" @endif alt="" decoding="async" style="width:100%;display:block;">
        <svg class="mg-mp__grid" viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden="true"
             style="position:absolute;inset:0;width:100%;height:100%;pointer-events:none;z-index:3;">
          @for($i = 1; $i < 20; $i++)
            <line x1="{{ $i*5 }}" y1="0" x2="{{ $i*5 }}" y2="100" stroke="#ffd24a" stroke-opacity=".35" stroke-width=".08"/>
            <line x1="0" y1="{{ $i*5 }}" x2="100" y2="{{ $i*5 }}" stroke="#ffd24a" stroke-opacity=".35" stroke-width=".08"/>
          @endfor
        </svg>
        <div id="mg-box-pick" style="position:absolute;inset:0;z-index:5;cursor:crosshair;"></div>
        <svg id="mg-box-svg" viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden="true"
             style="position:absolute;inset:0;width:100%;height:100%;pointer-events:none;z-index:6;overflow:visible;"></svg>
      </div>
      <div class="mg-mp__picker" id="mg-box-panel" style="margin-top:12px;">
        <div class="mg-mp__picker-row" style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
          <strong>Boksi-redaktor</strong>
          <label>Kodu:
            <select id="mg-box-home" style="padding:6px 8px;border-radius:8px;">
              @foreach($boxHomes as $bh)
                <option value="{{ $bh['key'] }}">{{ $bh['label'] }}</option>
              @endforeach
            </select>
          </label>
          <span id="mg-box-progress" style="font-weight:600;color:#9a6b1f;">0/{{ count($boxHomes) }}</span>
          <span style="color:#6f6a61;">— vali kodu, klõpsa boksi nurki (4+), siis “Salvesta boks”.</span>
        </div>
        <textarea id="mg-box-out" class="mg-mp__picker-out" rows="6" readonly
                  placeholder="'perspective_boxes' => ['{{ $boxMainKey }}' => [ ... ]]  — täida ja kopeeri config/magnoolia_hotspots.php-i"></textarea>
        <div class="mg-mp__picker-btns" style="display:flex;gap:8px;flex-wrap:wrap;">
          <button type="button" id="mg-box-save" class="mg-btn mg-btn--gold">Salvesta boks</button>
          <button type="button" id="mg-box-undo" class="mg-btn mg-btn--ghost">Võta punkt tagasi</button>
          <button type="button" id="mg-box-clear" class="mg-btn mg-btn--ghost">Tühjenda praegune</button>
          <button type="button" id="mg-box-copy" class="mg-btn mg-btn--ghost">Kopeeri config</button>
          <button type="button" id="mg-box-reset" class="mg-btn mg-btn--ghost" style="color:#b71c1c;">Lähtesta kõik</button>
        </div>
      </div>
    </div>
    <script>
    (function () {
      var pick = document.getElementById('mg-box-pick'); if (!pick) return;
      var svg = document.getElementById('mg-box-svg');
      var sel = document.getElementById('mg-box-home');
      var out = document.getElementById('mg-box-out');
      var prog = document.getElementById('mg-box-progress');
      var MAIN_KEY = @json($boxMainKey);
      var TOTAL = {{ count($boxHomes) }};
      var boxes = {};   // key -> [[x,y], ...]
      var cur = [];     // current points

      function centroid(pts) {
        var x = 0, y = 0; pts.forEach(function (p) { x += p[0]; y += p[1]; });
        return [ +(x / pts.length).toFixed(3), +(y / pts.length).toFixed(3) ];
      }
      function poly(pts, cls, fill) {
        return '<polygon points="' + pts.map(function (p) { return (p[0]*100) + ',' + (p[1]*100); }).join(' ') +
               '" fill="' + fill + '" stroke="#ffd24a" stroke-width="0.3"/>';
      }
      function dots(pts, color) {
        return pts.map(function (p) { return '<circle cx="' + (p[0]*100) + '" cy="' + (p[1]*100) + '" r="0.7" fill="' + color + '" stroke="#1d2430" stroke-width="0.15"/>'; }).join('');
      }
      function redraw() {
        var html = '';
        Object.keys(boxes).forEach(function (k) {
          var pts = boxes[k];
          html += poly(pts, 'saved', 'rgba(76,175,80,0.18)');
          var c = centroid(pts);
          html += '<text x="' + (c[0]*100) + '" y="' + (c[1]*100) + '" fill="#1d2430" font-size="2.2" text-anchor="middle" stroke="#fff" stroke-width="0.4" paint-order="stroke">' + k.replace('tee-', '') + '</text>';
        });
        if (cur.length) { html += (cur.length > 1 ? poly(cur, 'cur', 'rgba(200,148,67,0.28)') : '') + dots(cur, '#ffd24a'); }
        svg.innerHTML = html;
        var done = Object.keys(boxes).length;
        prog.textContent = done + '/' + TOTAL;
        // mark done homes in the select
        [].forEach.call(sel.options, function (o) {
          o.textContent = o.textContent.replace(/ ✓$/, '') + (boxes[o.value] ? ' ✓' : '');
        });
        buildOut();
      }
      function buildOut() {
        var keys = Object.keys(boxes);
        if (!keys.length) { out.value = ''; return; }
        var lines = keys.map(function (k) {
          var pts = boxes[k];
          var c = centroid(pts);
          var poly = pts.map(function (p) { return '[' + p[0] + ', ' + p[1] + ']'; }).join(', ');
          return "            '" + k + "' => ['marker' => [" + c[0] + ', ' + c[1] + "], 'polygon' => [" + poly + "]],";
        });
        out.value = "'perspective_boxes' => [\n        '" + MAIN_KEY + "' => [\n" + lines.join('\n') + "\n        ],\n    ],";
      }
      pick.addEventListener('click', function (e) {
        var r = pick.getBoundingClientRect();
        var x = +(Math.min(1, Math.max(0, (e.clientX - r.left) / r.width))).toFixed(3);
        var y = +(Math.min(1, Math.max(0, (e.clientY - r.top) / r.height))).toFixed(3);
        cur.push([x, y]); redraw();
      });
      document.getElementById('mg-box-save').addEventListener('click', function () {
        if (cur.length < 3) { alert('Klõpsa vähemalt 3 nurka.'); return; }
        boxes[sel.value] = cur.slice(); cur = [];
        // auto-advance to next undone home
        var opts = [].slice.call(sel.options);
        var next = opts.find(function (o) { return !boxes[o.value]; });
        if (next) sel.value = next.value;
        redraw();
      });
      document.getElementById('mg-box-undo').addEventListener('click', function () { cur.pop(); redraw(); });
      document.getElementById('mg-box-clear').addEventListener('click', function () { cur = []; redraw(); });
      document.getElementById('mg-box-reset').addEventListener('click', function () {
        if (confirm('Kustuta kõik boksid?')) { boxes = {}; cur = []; redraw(); }
      });
      document.getElementById('mg-box-copy').addEventListener('click', function () {
        out.select(); try { navigator.clipboard.writeText(out.value); } catch (e) { document.execCommand('copy'); }
        this.textContent = '✓ Kopeeritud';
      });
      // load existing boxes from config (so you can refine instead of restart)
      var EXISTING = @json(config('magnoolia_hotspots.perspective_boxes.'.$boxMainKey, []));
      Object.keys(EXISTING || {}).forEach(function (k) { if (EXISTING[k] && EXISTING[k].polygon) boxes[k] = EXISTING[k].polygon; });
      redraw();
    })();
    </script>
    @endif

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
    @php $showLocator = (bool) config('magnoolia_rowhouses.show_location_map'); @endphp
    <div id="mg-mp-detail" class="mg-mp__detail" hidden>
      <div class="mg-mp__detail-grid {{ $showLocator ? '' : 'mg-mp__detail-grid--nomap' }}">
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

        {{-- Right: synchronized 2D asendiplaan support map — Phase 35: hidden by
             default (config magnoolia_rowhouses.show_location_map → true to restore). --}}
        @if($cleanSrc && $showLocator)
        <div class="mg-mp__detail-map">
          <div class="mg-mp__map-label">{{ __('magnoolia.rowhouse.map_location') }}</div>
          <div class="mg-mp__map" id="mg-d-map">
            <img src="{{ $cleanSrc }}" alt="{{ __('magnoolia.rowhouse.alt_map') }}" loading="lazy" decoding="async">
            <svg class="mg-mp__map-svg" id="mg-d-map-svg" viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden="true" hidden
                 style="position:absolute;inset:0;width:100%;height:100%;pointer-events:none;z-index:2;overflow:visible;"></svg>
            <span class="mg-mp__map-pin" id="mg-d-pin" hidden><span class="mg-mp__map-pin-label">{{ __('magnoolia.rowhouse.sinu_valik') }}</span></span>
          </div>
          @if($enlarge)
          <a href="{{ asset($enlarge) }}" target="_blank" rel="noopener noreferrer" class="mg-mp__map-open" data-mg-analytics="magnoolia_asendiplaan_enlarge">{{ __('magnoolia.rowhouse.open_bigger') }} →</a>
          @endif
          <p class="mg-mp__map-note">{{ __('magnoolia.rowhouse.perspective_note') }}</p>
        </div>
        @endif
      </div>

      {{-- Floor plans — Phase 35: both floors shown together (no tab toggle) --}}
      <div class="mg-mp__floors" id="mg-d-floors">
        <div class="mg-mp__floors-title">{{ __('magnoolia.rowhouse.floorplans_title') }}</div>
        <div class="mg-mp__floors-both">
          <figure class="mg-mp__floor-fig" id="mg-d-floor1-fig">
            <button type="button" class="mg-mp__floor-zoombtn" data-floor-open="1" aria-label="{{ __('magnoolia.rowhouse.open_larger') }}">
              <img id="mg-d-floor1-img" alt="" loading="lazy" decoding="async">
              <span class="mg-mp__floor-zoomhint">{{ __('magnoolia.rowhouse.open_larger') }} ⤢</span>
            </button>
            <figcaption>{{ __('magnoolia.rowhouse.floor_1') }}</figcaption>
          </figure>
          <figure class="mg-mp__floor-fig" id="mg-d-floor2-fig">
            <button type="button" class="mg-mp__floor-zoombtn" data-floor-open="2" aria-label="{{ __('magnoolia.rowhouse.open_larger') }}">
              <img id="mg-d-floor2-img" alt="" loading="lazy" decoding="async">
              <span class="mg-mp__floor-zoomhint">{{ __('magnoolia.rowhouse.open_larger') }} ⤢</span>
            </button>
            <figcaption>{{ __('magnoolia.rowhouse.floor_2') }}</figcaption>
          </figure>
        </div>
        <p id="mg-d-floor-empty" class="mg-mp__floor-empty" hidden>{{ __('magnoolia.rowhouse.floor_placeholder') }}</p>
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
  var byRow = {}, byHome = {}, POLY_HOMES = [];
  ROWS.forEach(function (r) {
    byRow[r.pos] = r;
    r.homes.forEach(function (h) {
      byHome[h.key] = h; byHome[h.unit_key] = h; byHome[h.slug] = h;
      if (h.mappoly && h.mappoly.length > 2) POLY_HOMES.push(h);
    });
  });

  // Draw every home plot as a clickable/hoverable zone on the clean asendiplaan
  // support map; `activeKey` gets the emphasised style. Clicks bubble to the
  // existing [data-mp-home] delegation, so selecting a plot switches homes.
  function renderMapZones(activeKey) {
    var mapSvg = document.getElementById('mg-d-map-svg');
    if (!mapSvg) return;
    if (!POLY_HOMES.length) { mapSvg.innerHTML = ''; mapSvg.setAttribute('hidden', ''); return; }
    mapSvg.innerHTML = POLY_HOMES.map(function (h) {
      var pts = h.mappoly.map(function (p) { return (p[0] * 100).toFixed(2) + ',' + (p[1] * 100).toFixed(2); }).join(' ');
      var cls = 'mg-mp__map-zone' + (h.key === activeKey ? ' is-active' : '');
      return '<polygon class="' + cls + '" data-mp-home="' + h.key + '" tabindex="0" role="button" aria-label="' + h.display + '" points="' + pts + '"></polygon>';
    }).join('');
    mapSvg.removeAttribute('hidden'); // SVG ignores .hidden=false; remove the attribute
  }

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
  var VIEW_HOTSPOTS = {!! $viewHotspotsEnc !!};
  var state = { row: null, home: null, floor: '1', view: 0 };

  // (Re)build the SVG zones + markers for a given view from its hotspot set.
  // mode 'home' → per-box zones/markers (click opens the home detail directly);
  // mode 'row'  → per-row zones/markers (click opens the row panel).
  function renderHotspots(idx) {
    var entry = VIEW_HOTSPOTS[idx] || { mode: 'row', items: [] };
    var set = entry.items || [];
    var isHome = entry.mode === 'home';
    var svg = document.getElementById('mg-mp-svg');
    var markers = document.getElementById('mg-mp-markers');
    var wrap = document.querySelector('.mg-mp__imgwrap');
    var has = set.length > 0;
    if (svg) {
      svg.style.display = has ? '' : 'none';
      svg.innerHTML = set.map(function (h) {
        if (!h.hull) return '';
        var pts = h.hull.map(function (p) { return (p[0] * 100).toFixed(2) + ',' + (p[1] * 100).toFixed(2); }).join(' ');
        if (isHome) return '<polygon class="mg-mp__zone mg-mp__zone--home" data-mp-home="' + h.key + '" points="' + pts + '"></polygon>';
        return '<polygon class="mg-mp__zone" data-mp-zone="' + h.pos + '" points="' + pts + '"></polygon>';
      }).join('');
    }
    if (markers) {
      markers.style.display = has ? '' : 'none';
      markers.innerHTML = set.map(function (h) {
        if (!h.marker) return '';
        if (isHome) {
          var col = COLORS[h.status] || '#888';
          var num = (h.key || '').replace('tee-', '');
          return '<button type="button" class="mg-mp__marker mg-mp__marker--home" data-mp-home="' + h.key + '" style="left:' + (h.marker[0] * 100) + '%;top:' + (h.marker[1] * 100) + '%;" aria-label="' + h.label + '"><span class="mg-mp__marker-num" style="background:' + col + '">' + num + '</span></button>';
        }
        return '<button type="button" class="mg-mp__marker" data-mp-row="' + h.pos + '" style="left:' + (h.marker[0] * 100) + '%;top:' + (h.marker[1] * 100) + '%;" aria-label="' + h.title + '"><span class="mg-mp__marker-num">' + h.building + '</span><span class="mg-mp__marker-label">' + h.title + '</span></button>';
      }).join('');
    }
    if (wrap) wrap.setAttribute('data-mp-has-hotspots', has ? '1' : '0');
    if (isHome && state.home) setActiveHomeZones(state.home);
    else if (state.row) setActiveRow(state.row);
  }
  function setActiveHomeZones(key) {
    document.querySelectorAll('[data-mp-home]').forEach(function (el) {
      el.classList.toggle('is-active', el.getAttribute('data-mp-home') === key);
    });
  }

  function switchView(idx) {
    if (!VIEWS.length) return;
    idx = ((idx % VIEWS.length) + VIEWS.length) % VIEWS.length;
    var v = VIEWS[idx]; if (!v) return;
    state.view = idx;
    var img = document.getElementById('mg-mp-img');
    if (img) { img.src = v.src; if (v.srcset) img.setAttribute('srcset', v.srcset); img.alt = v.label; }
    renderHotspots(idx); // per-view zones + markers (alternate views via config)
    document.querySelectorAll('[data-mp-view]').forEach(function (p) {
      var on = +p.getAttribute('data-mp-view') === idx;
      p.classList.toggle('is-active', on); p.setAttribute('aria-pressed', on ? 'true' : 'false');
    });
  }

  // Hover sync: hovering a marker highlights its polygon (and vice-versa).
  function setHover(id, on) {
    document.querySelectorAll('.mg-mp__zone[data-mp-zone="' + id + '"], .mg-mp__marker[data-mp-row="' + id + '"], .mg-mp__zone--home[data-mp-home="' + id + '"], .mg-mp__marker--home[data-mp-home="' + id + '"]')
      .forEach(function (el) { el.classList.toggle('is-hover', on); });
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

    // map plot polygons — ALL homes are drawn as clickable zones on the clean
    // asendiplaan (hand-set in config/magnoolia_hotspots.php → asendiplaan);
    // the selected home is emphasised. Clicks reuse the [data-mp-home] handler.
    var hasPoly = h.mappoly && h.mappoly.length > 2;
    renderMapZones(h.key);

    // map pin — only as a FALLBACK when the selected home has NO plot polygon.
    var pin = document.getElementById('mg-d-pin');
    if (pin) {
      if (!hasPoly && h.mapx != null && h.mapy != null) { pin.style.left = (h.mapx * 100) + '%'; pin.style.top = (h.mapy * 100) + '%'; pin.hidden = false; }
      else pin.hidden = true;
    }

    // floor plans — both floors shown together
    showFloors(h);

    detail.hidden = false;
    if (!opts || !opts.silent) updateUrl();
    if (opts && opts.scroll) detail.scrollIntoView({ behavior: 'smooth', block: 'start' });
  }

  // Phase 35: render both floors at once; hide a figure with no image, show the
  // placeholder only when neither floor has an image.
  function showFloors(h) {
    var L1 = @json(__('magnoolia.rowhouse.floor_1'));
    var L2 = @json(__('magnoolia.rowhouse.floor_2'));
    var empty = document.getElementById('mg-d-floor-empty');
    var any = false;
    [['1', h.floor1, L1], ['2', h.floor2, L2]].forEach(function (f) {
      var fig = document.getElementById('mg-d-floor' + f[0] + '-fig');
      var btn = fig.querySelector('.mg-mp__floor-zoombtn');
      var img = document.getElementById('mg-d-floor' + f[0] + '-img');
      if (f[1]) {
        img.src = f[1]; img.alt = h.display + ' — ' + f[2];
        btn.setAttribute('data-floor-src', f[1]); fig.style.display = ''; any = true;
      } else {
        img.removeAttribute('src'); fig.style.display = 'none';
      }
    });
    empty.hidden = any;
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
    // view switcher
    var pill = e.target.closest('[data-mp-view]');
    if (pill) { switchView(+pill.getAttribute('data-mp-view')); return; }
    if (e.target.closest('[data-mp-view-prev]')) { switchView(state.view - 1); return; }
    if (e.target.closest('[data-mp-view-next]')) { switchView(state.view + 1); return; }
    // floor-plan lightbox (either floor)
    var zoom = e.target.closest('[data-floor-open]');
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
  // Marker ↔ polygon hover sync (only the map markers/zones, not the row cards)
  section.addEventListener('mouseover', function (e) {
    var el = e.target.closest('.mg-mp__marker[data-mp-row], .mg-mp__zone[data-mp-zone], .mg-mp__marker--home[data-mp-home], .mg-mp__zone--home[data-mp-home]');
    if (el) setHover(el.getAttribute('data-mp-row') || el.getAttribute('data-mp-zone') || el.getAttribute('data-mp-home'), true);
  });
  section.addEventListener('mouseout', function (e) {
    var el = e.target.closest('.mg-mp__marker[data-mp-row], .mg-mp__zone[data-mp-zone], .mg-mp__marker--home[data-mp-home], .mg-mp__zone--home[data-mp-home]');
    if (el) setHover(el.getAttribute('data-mp-row') || el.getAttribute('data-mp-zone') || el.getAttribute('data-mp-home'), false);
  });

  // deep-link init
  (function init() {
    renderHotspots(0); // JS-manage hotspots from the start (enables per-view + hover)
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
