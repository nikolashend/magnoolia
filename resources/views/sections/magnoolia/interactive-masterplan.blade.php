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
      'full'     => asset($img['base'] ?? $img['2048'] ?? $img['1280'] ?? '').$av, // full-res for the zoomable fullscreen map
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
<section class="mg-page-section mg-page-section--white" id="mg-masterplan" data-mg-masterplan style="scroll-margin-top:150px;">
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
        {{-- Variant B (mobile): the inline markers are just a visual hint; tapping the
             map opens a fullscreen, zoomable view where homes are big enough to tap. --}}
        <button type="button" class="mg-mp__fsbtn" data-mp-fs aria-label="{{ __('magnoolia.rowhouse.open_fs') }}">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M15 3h6v6M9 21H3v-6M21 3l-7 7M3 21l7-7"/></svg>
          <span>{{ __('magnoolia.rowhouse.open_fs') }}</span>
        </button>
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
    {{-- Wide breakout so the 3D render is as large as possible for precise tracing. --}}
    <div class="mg-mp__map-picker" style="margin-top:20px;width:min(96vw,1700px);position:relative;left:50%;transform:translateX(-50%);">
      <div class="mg-mp__map-picker-stage" id="mg-box-stage" style="position:relative;width:100%;">
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
      // vector-effect keeps strokes/dots crisp & thin regardless of the stretched viewBox
      function poly(pts, cls, fill) {
        return '<polygon points="' + pts.map(function (p) { return (p[0]*100) + ',' + (p[1]*100); }).join(' ') +
               '" fill="' + fill + '" stroke="#e0b052" stroke-width="1.2" vector-effect="non-scaling-stroke"/>';
      }
      function dots(pts, color) {
        return pts.map(function (p) { return '<circle cx="' + (p[0]*100) + '" cy="' + (p[1]*100) + '" r="0.22" fill="' + color + '" stroke="#1d2430" stroke-width="0.6" vector-effect="non-scaling-stroke"/>'; }).join('');
      }
      function redraw() {
        var html = '';
        Object.keys(boxes).forEach(function (k) {
          var pts = boxes[k];
          html += poly(pts, 'saved', 'rgba(76,175,80,0.16)');
          var c = centroid(pts);
          html += '<text x="' + (c[0]*100) + '" y="' + (c[1]*100) + '" fill="#1d2430" font-size="1.3" text-anchor="middle" stroke="#fff" stroke-width="0.3" paint-order="stroke">' + k.replace('tee-', '') + '</text>';
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

    {{-- Phase 35: selecting a home (a per-home box on the render) opens the SHARED
         home-detail modal (window.mgOpenHome) — the exact same modal the price table
         uses. The row cards, the expandable row panel and the old inline detail were
         all removed so there is one detail surface (the modal) and no duplicates. --}}
  </div>

  {{-- Variant B: mobile fullscreen zoomable map (pinch/pan). Tapping a numbered home
       closes this and opens the shared home-detail modal. --}}
  <div id="mg-mp-fs" class="mg-mp-fs" hidden>
    <div class="mg-mp-fs__bar">
      <span class="mg-mp-fs__title">{{ __('magnoolia.rowhouse.mp_title') }}</span>
      <button type="button" class="mg-mp-fs__close" data-mp-fs-close aria-label="{{ __('magnoolia.rowhouse.modal_close') }}">&#x2715;</button>
    </div>
    <div class="mg-mp-fs__viewport" id="mg-mp-fs-vp">
      <div class="mg-mp-fs__stage" id="mg-mp-fs-stage">
        <img id="mg-mp-fs-img" class="mg-mp-fs__img" alt="{{ __('magnoolia.rowhouse.mp_img_alt') }}" decoding="async" draggable="false">
        {{-- Clickable plot zones (the whole house/box is tappable, not just the marker). --}}
        <svg class="mg-mp-fs__svg" id="mg-mp-fs-svg" viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden="true"></svg>
        <div class="mg-mp-fs__markers" id="mg-mp-fs-markers"></div>
      </div>
    </div>
    <div class="mg-mp-fs__hint">{{ __('magnoolia.rowhouse.fs_hint') }}</div>
    <div class="mg-mp-fs__zoom">
      <button type="button" data-mp-fs-zoom="1" aria-label="+">+</button>
      <button type="button" data-mp-fs-zoom="-1" aria-label="−">&minus;</button>
    </div>
  </div>
</section>

<style>
  .mg-mp__fsbtn { display:none; }
  @media (max-width: 768px) {
    /* No markers on the small inline map — it is just a tappable preview that opens
       the fullscreen, zoomable map. */
    .mg-mp__imgwrap #mg-mp-markers,
    .mg-mp__imgwrap #mg-mp-svg { display:none !important; }
    .mg-mp__fsbtn {
      display:inline-flex; align-items:center; gap:6px; position:absolute; right:10px; bottom:10px; z-index:7;
      background:rgba(29,36,48,.86); color:#fff; border:none; border-radius:100px; padding:8px 14px;
      font-size:12px; font-weight:600; cursor:pointer; -webkit-backdrop-filter:blur(2px); backdrop-filter:blur(2px);
    }
  }
  .mg-mp-fs { position:fixed; inset:0; z-index:9180; background:#0e1218; display:flex; flex-direction:column; }
  .mg-mp-fs[hidden] { display:none; }
  .mg-mp-fs__bar { display:flex; align-items:center; justify-content:space-between; gap:12px; padding:12px 16px; color:#fff; flex:0 0 auto; }
  .mg-mp-fs__title { font-size:14px; font-weight:700; }
  .mg-mp-fs__close { width:40px; height:40px; border-radius:50%; border:1px solid rgba(255,255,255,.25); background:rgba(255,255,255,.08); color:#fff; font-size:16px; cursor:pointer; flex:0 0 auto; }
  .mg-mp-fs__viewport { position:relative; flex:1 1 auto; overflow:hidden; touch-action:none; -webkit-user-select:none; user-select:none; }
  .mg-mp-fs__stage { position:absolute; top:0; left:0; transform-origin:0 0; will-change:transform; }
  .mg-mp-fs__img { display:block; width:100%; height:auto; pointer-events:none; -webkit-user-drag:none; }
  /* Status colour for plot zones — identical scheme on desktop inline + mobile
     fullscreen: available = green, reserved = gold, sold = grey, tbc = purple. */
  .mg-z--available { fill:#4caf50 !important; }
  .mg-z--reserved  { fill:#c89443 !important; }
  .mg-z--sold      { fill:#9a948a !important; }
  .mg-z--tbc       { fill:#9c27b0 !important; }
  /* Desktop inline plots: subtle status tint; selected = stronger + white outline. */
  .mg-mp__zone--home { fill-opacity:.10; transition:fill-opacity .15s; }
  .mg-mp__imgwrap:hover .mg-mp__zone--home { fill-opacity:.20; }
  .mg-mp__zone--home.is-active { fill-opacity:.52 !important; stroke:#fff !important; stroke-width:1.4 !important; }
  /* Mobile fullscreen clickable plot zones. */
  .mg-mp-fs__svg { position:absolute; inset:0; width:100%; height:100%; overflow:visible; }
  .mg-mp-fs__zone { fill-opacity:.16; cursor:pointer; pointer-events:fill; }
  .mg-mp-fs__zone.is-selected { fill-opacity:.55; stroke:#fff; stroke-width:2.4; }
  /* markers layer lets taps pass through to the zones; only the pills are interactive. */
  .mg-mp-fs__markers { position:absolute; inset:0; pointer-events:none; }
  /* Markers live inside the scaled stage, so counter-scale them (--mk-inv = 1/zoom)
     to keep a constant, compact on-screen size at any zoom level. */
  .mg-mp-fs__marker { position:absolute; transform:translate(-50%,-50%) scale(var(--mk-inv,1)); transform-origin:center; background:none; border:none; padding:6px; margin:-6px; cursor:pointer; pointer-events:auto; }
  .mg-mp-fs__marker .num { display:flex; align-items:center; justify-content:center; min-width:23px; height:23px; padding:0 5px; border-radius:100px; color:#fff; font-size:11px; font-weight:700; white-space:nowrap; border:1.5px solid rgba(255,255,255,.92); box-shadow:0 1px 6px rgba(0,0,0,.45); }
  .mg-mp-fs__marker.is-selected .num { border-color:#ffd24a; box-shadow:0 0 0 3px rgba(255,210,74,.95), 0 1px 6px rgba(0,0,0,.45); }
  .mg-mp-fs__hint { position:absolute; left:50%; transform:translateX(-50%); bottom:76px; background:rgba(0,0,0,.6); color:#fff; font-size:12px; padding:7px 14px; border-radius:100px; pointer-events:none; max-width:90%; text-align:center; }
  .mg-mp-fs__zoom { position:absolute; right:16px; bottom:16px; display:flex; flex-direction:column; gap:8px; }
  .mg-mp-fs__zoom button { width:44px; height:44px; border-radius:12px; border:1px solid rgba(255,255,255,.25); background:rgba(255,255,255,.12); color:#fff; font-size:22px; line-height:1; cursor:pointer; }
</style>

@push('scripts')
<script>
(function () {
  var ROWS = {!! $rowsEnc !!};
  var byHome = {};
  ROWS.forEach(function (r) {
    r.homes.forEach(function (h) { byHome[h.key] = h; byHome[h.unit_key] = h; byHome[h.slug] = h; });
  });

  var COLORS = { available:'#4caf50', reserved:'#c89443', sold:'#9a948a', tbc:'#9c27b0' };
  var VIEWS = {!! $viewsEnc !!};
  var VIEW_HOTSPOTS = {!! $viewHotspotsEnc !!};
  var section = document.querySelector('[data-mg-masterplan]');
  var state = { view: 0 };

  // The first view whose hotspots are per-HOME boxes — the one users pick homes on.
  var HOME_VIEW = 0;
  for (var i = 0; i < VIEW_HOTSPOTS.length; i++) { if (VIEW_HOTSPOTS[i] && VIEW_HOTSPOTS[i].mode === 'home') { HOME_VIEW = i; break; } }

  // Phase 35: clicking a home box opens the SHARED home-detail modal
  // (window.mgOpenHome) — the exact same modal the price table uses.
  function openHome(key) {
    var h = byHome[key]; if (!h) return;
    if (typeof window.mgOpenHome === 'function') { window.mgOpenHome(h.key); }
    else { window.location.href = @json(lroute('magnoolia.homes')) + '#hinnatabel'; }
  }

  // Selected-plot highlight, shared by the desktop inline map (.is-active) and the
  // mobile fullscreen map (.is-selected). The home-detail modal dispatches
  // 'mg:home-selected' on open, so this also fires when a home is opened from the
  // price table — the chosen plot lights up on the map.
  var selectedKey = null;
  function applySelection() {
    document.querySelectorAll('.mg-mp__zone--home, .mg-mp__marker--home').forEach(function (el) {
      el.classList.toggle('is-active', !!selectedKey && el.getAttribute('data-mp-home') === selectedKey);
    });
    document.querySelectorAll('.mg-mp-fs__zone, .mg-mp-fs__marker').forEach(function (el) {
      el.classList.toggle('is-selected', !!selectedKey && el.getAttribute('data-mp-fs-marker') === selectedKey);
    });
  }
  document.addEventListener('mg:home-selected', function (e) {
    selectedKey = (e.detail && e.detail.key) || null;
    applySelection();
  });

  // (Re)build the SVG zones + markers for a given view from its hotspot set.
  // mode 'home' → per-box zones/markers (click opens the home-detail modal);
  // mode 'row'  → per-row markers (orientation only on the calibrated alt view).
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
        if (isHome) return '<polygon class="mg-mp__zone mg-mp__zone--home mg-z--' + (h.status || 'tbc') + '" data-mp-home="' + h.key + '" points="' + pts + '" vector-effect="non-scaling-stroke"></polygon>';
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
    applySelection();
  }

  function switchView(idx) {
    if (!VIEWS.length) return;
    idx = ((idx % VIEWS.length) + VIEWS.length) % VIEWS.length;
    var v = VIEWS[idx]; if (!v) return;
    state.view = idx;
    var img = document.getElementById('mg-mp-img');
    if (img) { img.src = v.src; if (v.srcset) img.setAttribute('srcset', v.srcset); img.alt = v.label; }
    renderHotspots(idx);
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

  // ── Variant B: mobile fullscreen zoomable map (pinch + pan) ────────────────
  var isMobile = function () { return window.matchMedia('(max-width: 768px)').matches; };
  var fs = document.getElementById('mg-mp-fs');
  var fsVp = document.getElementById('mg-mp-fs-vp');
  var fsStage = document.getElementById('mg-mp-fs-stage');
  var fsImg = document.getElementById('mg-mp-fs-img');
  var fsMk = document.getElementById('mg-mp-fs-markers');
  var baseW = 0, baseH = 0, s = 2, tx = 0, ty = 0, MIN = 1, MAX = 6;
  var pts = new Map(), base = {}, downX = 0, downY = 0, tap = false;

  function clamp(v, a, b) { return Math.max(a, Math.min(b, v)); }
  function fsApply() {
    fsStage.style.transform = 'translate(' + tx + 'px,' + ty + 'px) scale(' + s + ')';
    if (fsMk) fsMk.style.setProperty('--mk-inv', String(1 / s)); // keep markers a constant size
  }
  function clampPan() {
    var W = fsVp.clientWidth, H = fsVp.clientHeight, sw = baseW * s, sh = baseH * s;
    tx = sw <= W ? (W - sw) / 2 : clamp(tx, W - sw, 0);
    ty = sh <= H ? (H - sh) / 2 : clamp(ty, H - sh, 0);
  }
  function fsCentroid() {
    var entry = VIEW_HOTSPOTS[HOME_VIEW] || {}, set = entry.mode === 'home' ? (entry.items || []) : [];
    var n = 0, x = 0, y = 0;
    set.forEach(function (h) { if (h.marker) { x += h.marker[0]; y += h.marker[1]; n++; } });
    return n ? [x / n, y / n] : [0.5, 0.5];
  }
  function fsMinDistPx(nW, nH) {
    var entry = VIEW_HOTSPOTS[HOME_VIEW] || {}, set = (entry.mode === 'home' ? (entry.items || []) : []).filter(function (h) { return h.marker; });
    var min = Infinity;
    for (var i = 0; i < set.length; i++) for (var j = i + 1; j < set.length; j++) {
      var d = Math.hypot((set[i].marker[0] - set[j].marker[0]) * nW, (set[i].marker[1] - set[j].marker[1]) * nH);
      if (d < min) min = d;
    }
    return isFinite(min) && min > 0 ? min : nW * 0.03;
  }
  function fsInit() {
    if (!fsVp.clientWidth) return;
    // The stage is the FULL-RES image at its natural pixel size (≈4000px). The transform
    // DOWN-scales it to fit the screen, so the browser samples from the high-res source
    // → crisp at every zoom. (Previously the img was only viewport-wide, so scaling up
    // blew up a tiny raster.) MAX ≈ 1 = native 1:1 pixels (sharpest).
    var nW = fsImg.naturalWidth || 4000, nH = fsImg.naturalHeight || Math.round(nW * 0.5);
    baseW = nW; baseH = nH;
    fsStage.style.width = nW + 'px';
    var W = fsVp.clientWidth, H = fsVp.clientHeight;
    MIN = Math.max(0.02, Math.min(W / nW, H / nH) * 0.92); // whole plan fits
    MAX = 1.15;                                            // ~native resolution
    // Zoom so the closest pair of homes is ~46px apart on screen, centred on the cluster.
    s = clamp(46 / fsMinDistPx(nW, nH), MIN, MAX);
    var c = fsCentroid();
    tx = W / 2 - c[0] * nW * s;
    ty = H / 2 - c[1] * nH * s;
    clampPan(); fsApply();
  }
  function renderFsZones() {
    var svg = document.getElementById('mg-mp-fs-svg'); if (!svg) return;
    var entry = VIEW_HOTSPOTS[HOME_VIEW] || {};
    var set = entry.mode === 'home' ? (entry.items || []) : [];
    svg.innerHTML = set.map(function (h) {
      if (!h.hull) return '';
      var pts = h.hull.map(function (p) { return (p[0] * 100).toFixed(2) + ',' + (p[1] * 100).toFixed(2); }).join(' ');
      return '<polygon class="mg-mp-fs__zone mg-z--' + (h.status || 'tbc') + '" data-mp-fs-marker="' + h.key + '" points="' + pts + '" vector-effect="non-scaling-stroke"></polygon>';
    }).join('');
  }
  function renderFsMarkers() {
    var entry = VIEW_HOTSPOTS[HOME_VIEW] || {};
    var set = entry.mode === 'home' ? (entry.items || []) : [];
    fsMk.innerHTML = set.map(function (h) {
      if (!h.marker) return '';
      var col = COLORS[h.status] || '#888', num = (h.key || '').replace('tee-', '');
      return '<button type="button" class="mg-mp-fs__marker" data-mp-fs-marker="' + h.key + '" style="left:' + (h.marker[0] * 100) + '%;top:' + (h.marker[1] * 100) + '%;" aria-label="' + h.label + '"><span class="num" style="background:' + col + '">' + num + '</span></button>';
    }).join('');
    applySelection();
  }
  // iOS-safe scroll lock (cooperates with the home-detail modal via the position:fixed
  // guard — so opening the modal over the map doesn't double-lock, and closing the
  // modal leaves the map's lock intact).
  var fsLockY = 0, fsDidLock = false;
  function fsLock() {
    if (document.body.style.position === 'fixed') { fsDidLock = false; return; }
    fsDidLock = true; fsLockY = window.scrollY || window.pageYOffset || 0;
    var b = document.body; b.style.position = 'fixed'; b.style.top = (-fsLockY) + 'px';
    b.style.left = '0'; b.style.right = '0'; b.style.width = '100%'; b.style.overflow = 'hidden';
  }
  function fsUnlock() {
    if (!fsDidLock) return; fsDidLock = false;
    var b = document.body; b.style.position = ''; b.style.top = ''; b.style.left = ''; b.style.right = ''; b.style.width = ''; b.style.overflow = '';
    window.scrollTo(0, fsLockY);
  }
  function openFs() {
    if (!fs) return;
    var v = VIEWS[HOME_VIEW] || VIEWS[0] || {};
    // Use the full-res render (≈4000px) and no srcset, so it stays sharp when zoomed.
    if (v.full || v.src) { fsImg.removeAttribute('srcset'); fsImg.removeAttribute('sizes'); fsImg.src = v.full || v.src; }
    renderFsZones(); renderFsMarkers();
    fs.hidden = false; fsLock();
    requestAnimationFrame(fsInit);
  }
  function closeFs() { if (fs) { fs.hidden = true; fsUnlock(); pts.clear(); } }
  function zoomBy(f) {
    var cx = fsVp.clientWidth / 2, cy = fsVp.clientHeight / 2;
    var sx = (cx - tx) / s, sy = (cy - ty) / s;
    s = clamp(s * f, MIN, MAX); tx = cx - sx * s; ty = cy - sy * s; clampPan(); fsApply();
  }
  if (fsImg) fsImg.addEventListener('load', function () { if (fs && !fs.hidden) fsInit(); });

  function snapshot() {
    var arr = Array.from(pts.values());
    base = { tx: tx, ty: ty, s: s };
    if (arr.length >= 1) { base.x0 = arr[0].x; base.y0 = arr[0].y; }
    if (arr.length >= 2) {
      base.d0 = Math.hypot(arr[0].x - arr[1].x, arr[0].y - arr[1].y) || 1;
      var r = fsVp.getBoundingClientRect();
      base.sx = ((arr[0].x + arr[1].x) / 2 - r.left - tx) / s;
      base.sy = ((arr[0].y + arr[1].y) / 2 - r.top - ty) / s;
    }
  }
  function gestureMove() {
    var arr = Array.from(pts.values());
    if (arr.length === 1 && base.x0 != null) {
      tx = base.tx + (arr[0].x - base.x0); ty = base.ty + (arr[0].y - base.y0);
    } else if (arr.length >= 2) {
      var d = Math.hypot(arr[0].x - arr[1].x, arr[0].y - arr[1].y);
      var r = fsVp.getBoundingClientRect();
      var mx = (arr[0].x + arr[1].x) / 2 - r.left, my = (arr[0].y + arr[1].y) / 2 - r.top;
      s = clamp(base.s * d / base.d0, MIN, MAX); tx = mx - base.sx * s; ty = my - base.sy * s;
    }
    clampPan(); fsApply();
  }
  if (fsVp) fsVp.addEventListener('pointerdown', function (e) {
    pts.set(e.pointerId, { x: e.clientX, y: e.clientY });
    if (pts.size === 1) { downX = e.clientX; downY = e.clientY; tap = true; }
    snapshot();
  });
  window.addEventListener('pointermove', function (e) {
    if (!pts.has(e.pointerId)) return;
    pts.set(e.pointerId, { x: e.clientX, y: e.clientY });
    if (pts.size === 1 && Math.hypot(e.clientX - downX, e.clientY - downY) > 10) tap = false;
    gestureMove();
  });
  window.addEventListener('pointerup', function (e) {
    if (!pts.has(e.pointerId)) return;
    var single = pts.size === 1, ux = e.clientX, uy = e.clientY;
    pts.delete(e.pointerId); snapshot();
    if (single && tap) {
      var el = document.elementFromPoint(ux, uy);
      var mk = el && el.closest && el.closest('[data-mp-fs-marker]');
      // Open the home modal OVER the map (don't close the map) — so closing the modal
      // returns to the fullscreen map instead of dropping back to the page.
      if (mk) { openHome(mk.getAttribute('data-mp-fs-marker')); }
    }
  });
  window.addEventListener('pointercancel', function (e) { pts.delete(e.pointerId); });

  // delegated events
  document.addEventListener('click', function (e) {
    // fullscreen map controls
    if (e.target.closest('[data-mp-fs-close]')) { e.preventDefault(); closeFs(); return; }
    var zb = e.target.closest('[data-mp-fs-zoom]');
    if (zb) { e.preventDefault(); zoomBy(+zb.getAttribute('data-mp-fs-zoom') > 0 ? 1.4 : 1 / 1.4); return; }
    if (e.target.closest('[data-mp-fs]')) { e.preventDefault(); openFs(); return; }
    // mobile: tapping the (small) inline map opens the fullscreen zoomable view
    var wrap = e.target.closest('.mg-mp__imgwrap');
    if (wrap && section.contains(wrap) && isMobile() && !e.target.closest('[data-mp-view-prev],[data-mp-view-next]')) { e.preventDefault(); openFs(); return; }
    // desktop: a home box → open the shared modal
    var homeEl = e.target.closest('[data-mp-home]');
    if (homeEl && section.contains(homeEl)) { e.preventDefault(); openHome(homeEl.getAttribute('data-mp-home')); return; }
    // a row marker/zone exists only on the calibrated alt view → jump to the box view
    var rowish = e.target.closest('[data-mp-row], [data-mp-zone]');
    if (rowish && section.contains(rowish)) { e.preventDefault(); switchView(HOME_VIEW); return; }
    var pill = e.target.closest('[data-mp-view]');
    if (pill) { switchView(+pill.getAttribute('data-mp-view')); return; }
    if (e.target.closest('[data-mp-view-prev]')) { switchView(state.view - 1); return; }
    if (e.target.closest('[data-mp-view-next]')) { switchView(state.view + 1); return; }
  });

  document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && fs && !fs.hidden) closeFs(); });

  // Marker ↔ polygon hover sync
  section.addEventListener('mouseover', function (e) {
    var el = e.target.closest('.mg-mp__marker[data-mp-row], .mg-mp__zone[data-mp-zone], .mg-mp__marker--home[data-mp-home], .mg-mp__zone--home[data-mp-home]');
    if (el) setHover(el.getAttribute('data-mp-row') || el.getAttribute('data-mp-zone') || el.getAttribute('data-mp-home'), true);
  });
  section.addEventListener('mouseout', function (e) {
    var el = e.target.closest('.mg-mp__marker[data-mp-row], .mg-mp__zone[data-mp-zone], .mg-mp__marker--home[data-mp-home], .mg-mp__zone--home[data-mp-home]');
    if (el) setHover(el.getAttribute('data-mp-row') || el.getAttribute('data-mp-zone') || el.getAttribute('data-mp-home'), false);
  });

  // deep-link init: ?home= opens that home directly in the modal (if ready).
  (function init() {
    renderHotspots(0);
    var p = new URLSearchParams(window.location.search);
    var home = p.get('home');
    if (home && byHome[home]) { openHome(home); }
  })();
})();
</script>
@endpush
@endif
