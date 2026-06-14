{{-- ══════════════════════════════════════════════════════════════
    HINNAD JA PLAANID — Magnoolia pricing table
    Phase 6: filter bar · stage groups · status-aware CTAs
    Source: config/magnoolia.php → units, stages arrays
    ══════════════════════════════════════════════════════════════ --}}
@php
    use App\Services\Magnoolia\RowhouseSelectionService;
    $units  = $mgPublic['units'] ?? [];
    $stages = $mgPublic['stages'] ?? [];
    $campaign = $mgPublic['campaign'] ?? [];
    $commercial = $mgPublic['commercial'] ?? [];

    // Phase 29 — rowhouse home view-models (for the row filter, "Vaata kodu"
    // modal triggers and the correct private-use land area), keyed by
    // "building-section" so the existing table columns stay untouched.
    $rhs = app(RowhouseSelectionService::class);
    $rhRows = $rhs->rows();
    $rhByBs = [];
    foreach ($rhs->allHomes() as $rhHome) {
        $rhByBs[$rhHome['building'] . '-' . $rhHome['section']] = $rhHome;
    }
    $rhLookup = function (array $unit) use ($rhByBs) {
        $b = 0; $s = 0;
        if (preg_match('/B(\d+)-S(\d+)/i', (string) ($unit['unit_key'] ?? $unit['id'] ?? ''), $m)) { $b = (int) $m[1]; $s = (int) $m[2]; }
        elseif (preg_match('/(\d+)/', (string) ($unit['building'] ?? ''), $mb)) { $b = (int) $mb[1]; if (preg_match('#/\s*(\d+)#', (string) ($unit['section'] ?? ''), $ms)) $s = (int) $ms[1]; }
        return $rhByBs[$b . '-' . $s] ?? null;
    };

    // Group units by stage for grouped table headers
    $byStage = [];
    foreach ($units as $unit) {
        $byStage[$unit['stage'] ?? 0][] = $unit;
    }

    // Status config: CSS class + translation key + context-aware CTA key
    $statusCfg = [
        'available' => ['label' => __('magnoolia.pricing.status_available'), 'class' => 'mg-status--available', 'cta_key' => 'magnoolia.pricing.cta_inquiry'],
        'reserved'  => ['label' => __('magnoolia.pricing.status_reserved'),  'class' => 'mg-status--reserved',  'cta_key' => 'magnoolia.pricing.cta_availability'],
        'sold'      => ['label' => __('magnoolia.pricing.status_sold'),       'class' => 'mg-status--sold',      'cta_key' => 'magnoolia.pricing.cta_sold'],
        'tbc'       => ['label' => __('magnoolia.pricing.status_tbc'),        'class' => 'mg-status--tbc',       'cta_key' => 'magnoolia.pricing.cta_inquiry'],
    ];
@endphp

<section class="section-space" id="hinnad" style="background:#f7f4ef;">
    <div class="container">

        {{-- Section header --}}
        <div class="sec-title text-center" style="margin-bottom:36px;">
            <div class="sec-title__top justify-content-center">
                <span class="line-left"></span>
                <h6 class="sec-title__tagline bw-split-in-right">{{ __('magnoolia.section.pricing_eyebrow') }}</h6>
                <span class="line-right"></span>
            </div>
            <h3 class="sec-title__title bw-split-in-left">{{ __('magnoolia.section.pricing_title') }}</h3>
            <p style="color:#6f6a61;margin-top:16px;font-size:16px;max-width:600px;margin-left:auto;margin-right:auto;">
                {{ __('magnoolia.section.pricing_subtitle') }}
            </p>
        </div>

        {{-- ── Buyer orientation note ──────────────────────────────────── --}}
        <div class="mg-buyer-note wow fadeInUp" data-wow-duration="800ms"
             style="border-left:3px solid #c89443;background:#fff;border-radius:0 12px 12px 0;
                    padding:16px 20px;margin-bottom:32px;display:flex;align-items:flex-start;gap:14px;">
            <i class="fas fa-info-circle" style="color:#c89443;font-size:16px;margin-top:2px;flex-shrink:0;"></i>
            <p style="margin:0;font-size:14px;color:#4a4540;line-height:1.6;">
                {{ __('magnoolia.pricing.buyer_note') }}
                <a href="{{ lroute('magnoolia.contact') }}#kontaktivorm" style="color:#c89443;font-weight:600;text-decoration:none;">{{ __('magnoolia.pricing.buyer_note_link') }}</a>
                {{ __('magnoolia.pricing.buyer_note_end') }}
            </p>
        </div>

        {{-- ── Filter bar ──────────────────────────────────────────────── --}}
        <div class="wow fadeInUp" data-wow-duration="800ms"
             style="display:flex;flex-wrap:wrap;gap:8px;justify-content:center;margin-bottom:36px;">
            @foreach([
                ['key' => 'all',       'label' => __('magnoolia.pricing.filter_all')],
                ['key' => 'available', 'label' => __('magnoolia.pricing.filter_available')],
                ['key' => 'reserved',  'label' => __('magnoolia.pricing.filter_reserved')],
                ['key' => 'sold',      'label' => __('magnoolia.pricing.filter_sold')],
                ['key' => 'stage-1',   'label' => __('magnoolia.pricing.filter_stage1')],
                ['key' => 'stage-2',   'label' => __('magnoolia.pricing.filter_stage2')],
            ] as $f)
            <button type="button" data-filter="{{ $f['key'] }}" onclick="mgFilter('{{ $f['key'] }}')"
                    style="padding:8px 20px;border-radius:100px;font-size:13px;font-weight:600;cursor:pointer;border:1px solid;transition:all .2s;
                           {{ $loop->first ? 'background:#1d2430;color:#fff;border-color:#1d2430;' : 'background:transparent;color:#6f6a61;border-color:rgba(29,36,48,.25);' }}">
                {{ $f['label'] }}
            </button>
            @endforeach
        </div>

        {{-- ── Row (address group) filter — Phase 29 ───────────────────── --}}
        <div class="wow fadeInUp" data-wow-duration="800ms"
             style="display:flex;flex-wrap:wrap;gap:8px;justify-content:center;margin-bottom:28px;margin-top:-20px;">
            <button type="button" data-filter="all" onclick="mgFilter('all')"
                    style="padding:7px 16px;border-radius:100px;font-size:12.5px;font-weight:600;cursor:pointer;border:1px solid rgba(29,36,48,.25);background:transparent;color:#6f6a61;">
                {{ __('magnoolia.rowhouse.filter_all') }}
            </button>
            @foreach($rhRows as $row)
            <button type="button" data-filter="tee-{{ $row['building'] }}" onclick="mgFilter('tee-{{ $row['building'] }}')"
                    style="padding:7px 16px;border-radius:100px;font-size:12.5px;font-weight:600;cursor:pointer;border:1px solid rgba(29,36,48,.25);background:transparent;color:#6f6a61;">
                {{ $row['title'] }}
            </button>
            @endforeach
        </div>

        {{-- Count indicator --}}
        @php $unitTotal = count($units); @endphp
        <div style="text-align:center;margin-bottom:16px;margin-top:-12px;">
            <span id="mg-filter-count"
                  data-count-all-pre="{{ __('magnoolia.pricing.count_all_pre') }}"
                  data-count-filter-pre="{{ __('magnoolia.pricing.count_filter_pre') }}"
                  data-count-suffix="{{ __('magnoolia.pricing.count_suffix') }}"
                  data-total="{{ $unitTotal }}"
                  style="font-size:13px;color:#9a9490;font-style:italic;">@if($unitTotal > 0){{ __('magnoolia.pricing.count_all_pre') }} {{ $unitTotal }} {{ __('magnoolia.pricing.count_suffix') }}@else&nbsp;@endif</span>
        </div>

        {{-- Buyer helper tip --}}
        <p style="text-align:center;font-size:13px;color:#9a9490;margin-bottom:28px;margin-top:-4px;">
            <i class="icon-info" style="color:#c89443;margin-right:5px;"></i>
            <em>{{ __('magnoolia.pricing.buyer_tip_pre') }}
            <a href="{{ lroute('magnoolia.contact') }}#kontaktivorm" style="color:#c89443;text-decoration:none;font-weight:600;">{{ __('magnoolia.pricing.buyer_tip_link') }}</a>
            {{ __('magnoolia.pricing.buyer_tip_end') }}</em>
        </p>

        {{-- ── Table microcopy ─────────────────────────────────────── --}}
        <p class="wow fadeInUp" data-wow-duration="800ms"
           style="text-align:center;font-size:13px;color:#9a9490;margin-bottom:20px;line-height:1.6;max-width:680px;margin-left:auto;margin-right:auto;">
            {{ __('magnoolia.pricing.table_note') }}
        </p>

        {{-- ── DESKTOP TABLE ──────────────────────────────────────── --}}
        @if(($campaign['enabled'] ?? false) && !empty($campaign['body']))
        <div class="wow fadeInUp" data-wow-duration="900ms"
             style="margin-bottom:18px;background:#1d2430;border-radius:12px;padding:14px 18px;display:flex;align-items:center;gap:10px;">
            <span style="background:#c89443;color:#fff;font-size:11px;font-weight:700;padding:4px 10px;border-radius:20px;letter-spacing:.06em;">{{ $campaign['title'] ?? 'KAMPAANIA' }}</span>
            <span style="color:rgba(255,255,255,.82);font-size:14px;">{{ $campaign['body'] }}</span>
        </div>
        @endif

        <div class="d-none d-lg-block wow fadeInUp" data-wow-duration="1200ms">
          @foreach($byStage as $stageNum => $stageUnits)
          @php $sCfg = $stages[$stageNum] ?? null; @endphp
          @if($sCfg)
          <div data-stage-group="{{ $stageNum }}"
               style="display:flex;align-items:center;flex-wrap:wrap;gap:10px;padding:13px 20px;background:#1d2430;border-radius:12px 12px 0 0;{{ !$loop->first ? 'margin-top:40px;' : '' }}">
              <span style="background:#c89443;color:#fff;font-size:11px;font-weight:700;padding:3px 10px;border-radius:20px;letter-spacing:.06em;">{{ $sCfg['label'] }}</span>
              <span style="color:rgba(255,255,255,.75);font-size:14px;">{{ implode(' · ', $sCfg['buildings']) }}</span>
              <span style="margin-left:auto;color:rgba(200,148,67,.9);font-size:13px;font-weight:600;">{{ __('magnoolia.pricing.completing') }} {{ $sCfg['completion'] }}</span>
              <span style="color:rgba(255,255,255,.4);font-size:13px;">{{ $sCfg['homes'] }} {{ __('magnoolia.pricing.homes_label') }}</span>
          </div>
          @endif
          <table data-stage-table="{{ $stageNum }}" style="width:100%;border-collapse:collapse;margin-bottom:0;">
                <thead>
                    <tr style="background:#2c3441;color:rgba(255,255,255,.72);">
                        <th style="padding:14px 16px;text-align:left;font-size:13px;font-weight:600;letter-spacing:.04em;">{{ __('magnoolia.pricing.address') }}</th>
                        <th style="padding:14px 16px;text-align:center;font-size:13px;font-weight:600;">{{ __('magnoolia.pricing.area') }}</th>
                        <th style="padding:14px 16px;text-align:center;font-size:13px;font-weight:600;">{{ __('magnoolia.pricing.rooms') }}</th>
                        <th style="padding:14px 16px;text-align:center;font-size:13px;font-weight:600;">{{ __('magnoolia.pricing.terrace') }}</th>
                        <th style="padding:14px 16px;text-align:center;font-size:13px;font-weight:600;">{{ __('magnoolia.pricing.balcony') }}</th>
                        <th style="padding:14px 16px;text-align:center;font-size:13px;font-weight:600;">{{ __('magnoolia.pricing.parking') }}</th>
                        <th style="padding:14px 16px;text-align:right;font-size:13px;font-weight:600;">{{ __('magnoolia.pricing.price') }}</th>
                        <th style="padding:14px 16px;text-align:center;font-size:13px;font-weight:600;">{{ __('magnoolia.pricing.status') }}</th>
                        <th style="padding:14px 16px;text-align:center;font-size:13px;font-weight:600;"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($stageUnits as $i => $unit)
                    @php
                        $st  = $unit['status'] ?? 'tbc';
                        $cfg = $statusCfg[$st] ?? $statusCfg['tbc'];
                        $publicPrice = ($unit['price_public'] ?? false) ? ($unit['price'] ?? null) : null;
                        $rh    = $rhLookup($unit);
                        $rhKey = $rh['asset_key'] ?? ($unit['unit_key'] ?? $unit['id'] ?? '');
                        $rhBld = $rh ? ('tee-' . $rh['building']) : '';
                    @endphp
                    <tr class="mg-unit-row"
                        data-event="unit_row_click"
                        data-unit-id="{{ $unit['address'] }}"
                        data-status="{{ $st }}"
                        data-stage="stage-{{ $stageNum }}"
                        data-building="{{ $rhBld }}"
                        aria-label="Ava kodu detailid: {{ $unit['address'] }}"
                        tabindex="{{ $st === 'sold' ? '-1' : '0' }}"
                        style="background:{{ $i % 2 === 0 ? '#fff' : '#fbfaf7' }};border-bottom:1px solid rgba(29,36,48,.07);transition:all .18s;cursor:{{ $st === 'sold' ? 'default' : 'pointer' }};{{ $st === 'sold' ? 'opacity:.55;' : '' }}"
                        onclick="{{ $st !== 'sold' ? "mgOpenHome('".$rhKey."')" : '' }}"
                        onkeydown="{{ $st !== 'sold' ? "if(event.key==='Enter'||event.key===' '){event.preventDefault();mgOpenHome('".$rhKey."');}" : '' }}"
                        onmouseover="this.style.background='#f5f0e5'" onmouseout="this.style.background='{{ $i % 2 === 0 ? '#fff' : '#fbfaf7' }}'">
                        <td style="padding:15px 16px;">
                            <div style="font-weight:600;color:#1d2430;font-size:14px;">{{ $unit['address'] }}</div>
                            @php
                                $ptChip = match($unit['plan_type'] ?? null) {
                                    'type-a' => ['label' => 'Plaan A', 'class' => 'mg-plan-chip--a'],
                                    'type-b' => ['label' => 'Plaan B', 'class' => 'mg-plan-chip--b'],
                                    default  => null,
                                };
                            @endphp
                            @if($ptChip)
                            <span class="mg-plan-chip {{ $ptChip['class'] }}">{{ $ptChip['label'] }}</span>
                            @endif
                        </td>
                        <td style="padding:15px 16px;text-align:center;color:#1d2430;font-weight:500;">{{ number_format($unit['net_area'] ?? 0, 1) }} m²</td>
                        <td style="padding:15px 16px;text-align:center;color:#1d2430;">{{ $unit['rooms'] ?? '—' }}</td>
                        <td style="padding:15px 16px;text-align:center;color:#6f6a61;font-size:13px;">{{ !empty($unit['terrace_area']) ? number_format($unit['terrace_area'],1).' m²' : '—' }}</td>
                        <td style="padding:15px 16px;text-align:center;color:#6f6a61;font-size:13px;">{{ !empty($unit['balcony_area']) ? number_format($unit['balcony_area'],1).' m²' : '—' }}</td>
                        <td style="padding:15px 16px;text-align:center;color:#6f6a61;">{{ $unit['parking'] ?? 2 }}×</td>
                        <td style="padding:15px 16px;text-align:right;font-weight:700;
                                   color:{{ $publicPrice ? '#1d2430' : '#c89443' }};font-size:{{ $publicPrice ? '15' : '12' }}px;">
                            {{ $publicPrice ? '€ '.number_format($publicPrice, 0, ',', ' ') : __('magnoolia.pricing.price_tbc_inline') }}
                        </td>
                        <td style="padding:15px 16px;text-align:center;">
                            <span class="mg-status {{ $cfg['class'] }}"
                                  style="display:inline-block;padding:3px 11px;border-radius:20px;font-size:11px;font-weight:700;">
                                {{ $cfg['label'] }}
                            </span>
                        </td>
                        <td style="padding:15px 16px;text-align:center;white-space:nowrap;">
                            <button type="button" class="mg-table-cta mg-table-cta--ghost"
                                    data-mg-home-open="{{ $rhKey }}"
                                    data-mg-analytics="magnoolia_home_detail_open"
                                    onclick="event.stopPropagation();"
                                    style="border:1px solid rgba(29,36,48,.2);background:#fff;color:#1d2430;cursor:pointer;margin-bottom:6px;">{{ __('magnoolia.rowhouse.view_home') }}</button>
                            <br>
                            @if($st === 'sold')
                                <a href="{{ lroute('home') }}#hinnad" class="mg-table-cta mg-table-cta--muted"
                                   data-event="unit_view" data-unit-id="{{ $unit['address'] }}">{{ __($cfg['cta_key']) }}</a>
                            @else
                                <a href="{{ lroute('magnoolia.contact') }}?unit={{ urlencode($unit['address']) }}#kontaktivorm" class="mg-table-cta"
                                   data-event="unit_modal_open" data-unit-id="{{ $unit['address'] }}">{{ __($cfg['cta_key']) }}</a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
          @endforeach
        </div>

        {{-- ── MOBILE CARDS ───────────────────────────────────────── --}}
        <div class="d-lg-none" style="display:flex;flex-direction:column;gap:14px;">
            @foreach($units as $unit)
            @php
                $st  = $unit['status'] ?? 'tbc';
                $cfg = $statusCfg[$st] ?? $statusCfg['tbc'];
                $sCfg = $stages[$unit['stage'] ?? 0] ?? null;
                $publicPrice = ($unit['price_public'] ?? false) ? ($unit['price'] ?? null) : null;
                $rh    = $rhLookup($unit);
                $rhKey = $rh['asset_key'] ?? ($unit['unit_key'] ?? $unit['id'] ?? '');
                $rhBld = $rh ? ('tee-' . $rh['building']) : '';
                $rhYard = $rh ? RowhouseSelectionService::formatArea($rh['private_yard_area']) : null;
            @endphp
            <div class="mg-unit-card"
                 data-status="{{ $st }}"
                 data-stage="stage-{{ $unit['stage'] ?? 0 }}"
                 data-building="{{ $rhBld }}"
                 onclick="mgOpenHome('{{ $rhKey }}')"
                 style="background:#fff;border-radius:16px;padding:20px;box-shadow:0 4px 20px rgba(0,0,0,.08);border-top:3px solid #c89443;cursor:pointer;">
                <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:8px;">
                    <div>
                        <div style="font-weight:700;color:#1d2430;font-size:15px;">{{ $unit['address'] }}</div>
                        <div style="color:#6f6a61;font-size:13px;margin-top:3px;">
                            {{ $unit['rooms'] ?? '—' }} {{ __('magnoolia.pricing.rooms_unit') }} · {{ number_format($unit['net_area'] ?? 0, 1) }} m² · {{ __('magnoolia.pricing.area_class') }}
                        </div>
                        @php
                            $mobilePtChip = match($unit['plan_type'] ?? null) {
                                'type-a' => ['label' => 'Plaan A', 'class' => 'mg-plan-chip--a'],
                                'type-b' => ['label' => 'Plaan B', 'class' => 'mg-plan-chip--b'],
                                default  => null,
                            };
                        @endphp
                        @if($mobilePtChip)
                        <span class="mg-plan-chip {{ $mobilePtChip['class'] }}" style="margin-top:6px;display:inline-block;">{{ $mobilePtChip['label'] }}</span>
                        @endif
                    </div>
                    <span class="mg-status {{ $cfg['class'] }}"
                          style="padding:4px 10px;border-radius:20px;font-size:11px;font-weight:700;flex-shrink:0;">
                        {{ $cfg['label'] }}
                    </span>
                </div>
                @if($sCfg)
                <div style="display:inline-flex;align-items:center;gap:6px;background:#f7f4ef;border-radius:8px;padding:4px 10px;margin-bottom:10px;font-size:12px;color:#6f6a61;">
                    <span style="width:6px;height:6px;border-radius:50%;background:#c89443;flex-shrink:0;"></span>
                    {{ $sCfg['label'] }} · {{ $sCfg['completion'] }}
                </div>
                @endif
                <div style="display:flex;flex-wrap:wrap;gap:8px;font-size:12px;color:#6f6a61;margin-bottom:14px;padding-bottom:14px;border-bottom:1px solid rgba(29,36,48,.07);">
                    @if(!empty($unit['terrace_area']) && $unit['terrace_area'] > 0)
                        <span>{{ __('magnoolia.pricing.terrace_label') }} {{ number_format($unit['terrace_area'],1) }} m²</span>
                    @endif
                    @if(!empty($unit['balcony_area']) && $unit['balcony_area'] > 0)
                        <span>{{ __('magnoolia.pricing.balcony_label') }} {{ number_format($unit['balcony_area'],1) }} m²</span>
                    @endif
                    @if($rhYard)
                        <span>{{ __('magnoolia.rowhouse.spec_yard') }} {{ $rhYard }} m²</span>
                    @endif
                    <span>{{ $unit['parking'] ?? 2 }}× {{ __('magnoolia.pricing.parking_label') }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;align-items:center;gap:10px;flex-wrap:wrap;">
                    <div style="font-weight:700;color:#1d2430;font-size:15px;">
                        {{ $publicPrice ? '€ '.number_format($publicPrice, 0, ',', ' ') : __('magnoolia.pricing.price_tbc') }}
                    </div>
                    <div style="display:flex;gap:8px;align-items:center;">
                        <button type="button" data-mg-home-open="{{ $rhKey }}" onclick="event.stopPropagation();"
                                data-mg-analytics="magnoolia_home_detail_open"
                                style="background:#fff;border:1px solid rgba(29,36,48,.2);color:#1d2430;padding:9px 14px;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;min-height:40px;">
                            {{ __('magnoolia.rowhouse.view_home') }}
                        </button>
                        @if($st !== 'sold')
                        <a href="{{ lroute('magnoolia.contact') }}#kontaktivorm"
                           onclick="event.stopPropagation();"
                           style="background:#c89443;color:#fff;padding:9px 18px;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;min-height:40px;display:inline-flex;align-items:center;">
                            {{ __($cfg['cta_key']) }}
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- ── What's included ──────────────────────────────────── --}}
        <div class="wow fadeInUp" data-wow-duration="1200ms" data-wow-delay="300ms"
             style="margin-top:48px;background:#fff;border-radius:16px;padding:32px 36px;display:flex;flex-wrap:wrap;gap:32px;align-items:flex-start;">
            <div style="flex:1;min-width:240px;">
                <h4 style="font-size:15px;font-weight:700;color:#1d2430;margin-bottom:14px;">{{ __('magnoolia.pricing.includes_title') }}</h4>
                <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:8px;">
                    @foreach(($commercial['included'] ?? __('magnoolia.pricing.includes_items')) as $item)
                    <li style="display:flex;align-items:flex-start;gap:10px;font-size:14px;color:#6f6a61;">
                        <i class="icon-check-star" style="color:#c89443;margin-top:2px;flex-shrink:0;"></i>
                        {{ $item }}
                    </li>
                    @endforeach
                </ul>
            </div>
            <div style="flex:1;min-width:240px;">
                <h4 style="font-size:15px;font-weight:700;color:#1d2430;margin-bottom:14px;">{{ __('magnoolia.pricing.extras_title') }}</h4>
                <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:8px;">
                    @foreach(($commercial['extras'] ?? []) as $extra)
                    <li style="display:flex;align-items:flex-start;justify-content:space-between;gap:10px;font-size:13px;color:#6f6a61;">
                        <span>{{ $extra['name'] }}</span>
                        <strong style="color:#1d2430;white-space:nowrap;">€ {{ number_format((int) ($extra['price'] ?? 0), 0, ',', ' ') }}</strong>
                    </li>
                    @endforeach
                </ul>
                @if(!empty($commercial['excluded']))
                <div style="margin-top:12px;font-size:12px;color:#9a9490;">
                    {{ __('magnoolia.pricing.excluded_prefix') }} {{ implode(' · ', $commercial['excluded']) }}
                </div>
                @endif
            </div>
            <div style="flex:1;min-width:240px;display:flex;flex-direction:column;justify-content:space-between;gap:20px;">
                <p style="font-size:13px;color:#9a9490;font-style:italic;margin:0;">{{ __('magnoolia.pricing.disclaimer') }}</p>
                <div style="display:flex;gap:12px;flex-wrap:wrap;">
                    <a href="{{ lroute('magnoolia.contact') }}#kontaktivorm" class="zoomvilla-btn">
                        {{ __('magnoolia.pricing.cta_inquiry') }} <i class="icon-angle-small-right"></i>
                    </a>
                    <a href="{{ lroute('home') }}#asendiplaan" class="zoomvilla-btn zoomvilla-btn--border">
                        {{ __('magnoolia.nav.masterplan') }} <i class="icon-angle-small-right"></i>
                    </a>
                </div>
            </div>
        </div>

    </div>
</section>
<script>
(function () {
    function mgFilter(key) {
        document.querySelectorAll('[data-filter]').forEach(function (btn) {
            var active = btn.dataset.filter === key;
            btn.style.background  = active ? '#1d2430' : 'transparent';
            btn.style.color       = active ? '#fff'    : '#6f6a61';
            btn.style.borderColor = active ? '#1d2430' : 'rgba(29,36,48,.25)';
        });
        document.querySelectorAll('.mg-unit-row').forEach(function (row) {
            var show = key === 'all' || row.dataset.status === key || row.dataset.stage === key || row.dataset.building === key;
            row.style.display = show ? '' : 'none';
        });
        document.querySelectorAll('[data-stage-group]').forEach(function (header) {
            var sg  = 'stage-' + header.dataset.stageGroup;
            var vis = document.querySelectorAll('.mg-unit-row[data-stage="' + sg + '"]:not([style*="display: none"])').length;
            var hide = key !== 'all' && key !== sg && vis === 0;
            header.style.display = hide ? 'none' : '';
            var tbl = header.nextElementSibling;
            if (tbl) tbl.style.display = hide ? 'none' : '';
        });
        document.querySelectorAll('.mg-unit-card').forEach(function (card) {
            var show = key === 'all' || card.dataset.status === key || card.dataset.stage === key || card.dataset.building === key;
            card.style.display = show ? '' : 'none';
        });
        /* Update count — never show 0 when total>0 and all-filter */
        setTimeout(function () {
            var rows  = document.querySelectorAll('.mg-unit-row');
            var el = document.getElementById('mg-filter-count');
            var visible = 0;
            rows.forEach(function (r) { if (r.style.display !== 'none') visible++; });
            if (el) {
                var allPre    = el.dataset.countAllPre    || '';
                var filterPre = el.dataset.countFilterPre || '';
                var suffix    = el.dataset.countSuffix    || '';
                var total     = parseInt(el.dataset.total || rows.length, 10);
                /* Safety: if rows.length is 0 but data-total > 0, keep server-rendered text */
                if (rows.length === 0 && total > 0) return;
                el.textContent = visible === rows.length
                    ? allPre + ' ' + rows.length + ' ' + suffix
                    : filterPre + ' ' + visible + ' ' + suffix;
            }
        }, 10);
    }
    window.mgFilter = mgFilter;
})();
</script>

{{-- Phase 29 — home-detail modal (provides window.mgOpenHome for table/cards) --}}
@include('components.magnoolia.home-detail-modal')