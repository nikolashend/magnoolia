{{-- ══════════════════════════════════════════════════════════════
    ASENDIPLAAN — Masterplan section
    Phase 6: hotspot data-attributes added (coords are placeholder until SVG/EXR)
    Phase 7 TODO: interaktiivsed SVG hotspotid koordinaatidega
    ══════════════════════════════════════════════════════════════ --}}
@php
    $allUnits = config('magnoolia.units', []);
    $stages   = config('magnoolia.stages', []);
    $mapCfg   = config('magnoolia_map', []);
    $hotspots = $mapCfg['hotspots'] ?? [];
    $showDevHotspots = (bool) ($mapCfg['show_dev_hotspots'] ?? false);
    $statusColors = [
        'available' => '#4caf50',
        'reserved'  => '#ff9800',
        'sold'      => '#f44336',
        'tbc'       => '#9c27b0',
    ];
@endphp
<section class="section-space" id="asendiplaan" style="background:#151515;">
    <div class="container">

        {{-- Section header --}}
        <div class="sec-title text-center" style="margin-bottom:40px;">
            <div class="sec-title__top justify-content-center">
                <span class="line-left" style="background:rgba(255,255,255,.2);"></span>
                <h6 class="sec-title__tagline bw-split-in-right" style="color:#c89443;">{{ __('magnoolia.section.masterplan_eyebrow') }}</h6>
                <span class="line-right" style="background:rgba(255,255,255,.2);"></span>
            </div>
            <h3 class="sec-title__title bw-split-in-left" style="color:#fff;">{{ __('magnoolia.section.masterplan_title') }}</h3>
            <p style="color:rgba(255,255,255,.6);margin-top:16px;font-size:16px;max-width:620px;margin-left:auto;margin-right:auto;">
                {{ __('magnoolia.section.asendiplaan_desc') }}
                <a href="{{ lroute('magnoolia.contact') }}#kontaktivorm" style="color:#c89443;text-decoration:none;font-weight:600;">{{ __('magnoolia.section.asendiplaan_diana_link') }}</a>.
            </p>
            <p style="color:rgba(255,255,255,.45);margin-top:10px;font-size:13px;max-width:700px;margin-left:auto;margin-right:auto;">
                {{ __('magnoolia.section.asendiplaan_compare_pre') }} <a href="{{ lroute('home') }}#hinnad" style="color:rgba(200,148,67,.7);text-decoration:none;">{{ __('magnoolia.section.asendiplaan_compare_link') }}</a>.
                {{ __('magnoolia.section.asendiplaan_compare_map') }}
            </p>
        </div>

        {{-- Masterplan image --}}
        <div class="row align-items-center gutter-y-40">
            <div class="col-lg-8 wow fadeInLeft" data-wow-duration="1400ms">
                {{-- Phase 5 TODO: replace with interactive SVG mastplan --}}
                {{-- data-masterplan="magnoolia" attribute reserved for future hotspots --}}
                <div class="mg-masterplan" data-masterplan="magnoolia" style="position:relative;border-radius:20px;overflow:hidden;">
                    <a href="{{ asset('assets/images/magnoolia/magnoolia_cam09.jpg') }}" class="img-popup">
                        <img src="{{ asset('assets/images/magnoolia/magnoolia_cam09.jpg') }}"
                             alt="Magnoolia 19 kodu asendiplaan Kiili vallas"
                             width="820" height="560"
                             loading="lazy"
                             style="width:100%;height:auto;display:block;object-fit:cover;">
                        <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);
                                    background:rgba(200,148,67,.92);color:#fff;border-radius:50%;
                                    width:60px;height:60px;display:flex;align-items:center;justify-content:center;
                                    font-size:22px;transition:transform .3s;"
                             onmouseover="this.style.transform='translate(-50%,-50%) scale(1.1)'"
                             onmouseout="this.style.transform='translate(-50%,-50%) scale(1)'">
                            <i class="icon-zoom-1"></i>
                        </div>
                    </a>
                    {{-- TODO (Yellow Studio): replace fallback map layer after final SVG/EXR + mask mapping arrives. --}}
                    <div class="mg-masterplan__hotspots" aria-hidden="true"
                         style="position:absolute;inset:0;pointer-events:none;">
                        @foreach($allUnits as $unit)
                        @php
                            $unitMap = $hotspots[$unit['id']] ?? null;
                            $x = $unitMap['x_percent'] ?? null;
                            $y = $unitMap['y_percent'] ?? null;
                            $canRender = $showDevHotspots && $x !== null && $y !== null;
                        @endphp
                        {{-- Each hotspot carries full data-attributes for future JS interaction --}}
                        <div class="mg-masterplan__hotspot"
                             data-unit-id="{{ $unit['id'] }}"
                             data-address="{{ $unit['address'] }}"
                             data-status="{{ $unit['status'] ?? 'tbc' }}"
                             data-stage="{{ $unit['stage'] ?? 0 }}"
                             data-completion="{{ $stages[$unit['stage'] ?? 0]['completion'] ?? '' }}"
                             data-rooms="{{ $unit['rooms'] ?? '' }}"
                             data-area="{{ $unit['net_area'] ?? '' }}"
                             data-masterplan-key="{{ $unit['masterplan_key'] ?? $unit['id'] }}"
                             style="{{ $canRender ? 'display:block;top:' . $y . '%;left:' . $x . '%;' : 'display:none;' }}">{{-- coords set from config/magnoolia_map.php when approved --}}</div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-lg-4 wow fadeInRight" data-wow-duration="1400ms" data-wow-delay="200ms">
                <div style="color:#fff;padding:0 0 0 20px;">

                    <h4 style="font-size:22px;font-weight:700;margin-bottom:20px;color:#fff;">
                        {{ __('magnoolia.section.asendiplaan_map_title') }}
                    </h4>
                    <p style="color:rgba(255,255,255,.65);font-size:15px;line-height:1.7;margin-bottom:28px;">
                        {{ __('magnoolia.section.asendiplaan_map_body') }}
                    </p>

                    {{-- Legend --}}
                    <div style="display:flex;flex-direction:column;gap:12px;margin-bottom:32px;">
                        <div style="display:flex;align-items:center;gap:12px;font-size:14px;color:rgba(255,255,255,.7);">
                            <span style="width:14px;height:14px;border-radius:50%;background:#c89443;flex-shrink:0;"></span>
                            {{ __('magnoolia.section.asendiplaan_legend1') }}
                        </div>
                        <div style="display:flex;align-items:center;gap:12px;font-size:14px;color:rgba(255,255,255,.7);">
                            <span style="width:14px;height:14px;border-radius:50%;background:rgba(255,255,255,.3);flex-shrink:0;"></span>
                            {{ __('magnoolia.section.asendiplaan_legend2') }}
                        </div>
                        <div style="display:flex;align-items:center;gap:12px;font-size:14px;color:rgba(255,255,255,.7);">
                            <span style="width:14px;height:14px;border-radius:50%;background:rgba(255,255,255,.15);flex-shrink:0;border:1px solid rgba(255,255,255,.3);"></span>
                            {{ __('magnoolia.section.asendiplaan_legend3') }}
                        </div>
                    </div>

                    {{-- Trust facts --}}
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:32px;">
                        <div style="background:rgba(255,255,255,.07);border-radius:12px;padding:16px;">
                            <div style="font-size:22px;font-weight:700;color:#c89443;">19</div>
                            <div style="font-size:12px;color:rgba(255,255,255,.5);margin-top:2px;">{{ __('magnoolia.section.asendiplaan_trust_homes') }}</div>
                        </div>
                        <div style="background:rgba(255,255,255,.07);border-radius:12px;padding:16px;">
                            <div style="font-size:22px;font-weight:700;color:#c89443;">A</div>
                            <div style="font-size:12px;color:rgba(255,255,255,.5);margin-top:2px;">{{ __('magnoolia.section.asendiplaan_trust_energy') }}</div>
                        </div>
                        <div style="background:rgba(255,255,255,.07);border-radius:12px;padding:16px;">
                            <div style="font-size:16px;font-weight:700;color:#c89443;">kevad 2027</div>
                            <div style="font-size:12px;color:rgba(255,255,255,.5);margin-top:2px;">{{ __('magnoolia.section.asendiplaan_trust_stage1') }}</div>
                        </div>
                        <div style="background:rgba(255,255,255,.07);border-radius:12px;padding:16px;">
                            <div style="font-size:22px;font-weight:700;color:#c89443;">{{ __('magnoolia.hero.distance_value') }}</div>
                            <div style="font-size:12px;color:rgba(255,255,255,.5);margin-top:2px;">{{ __('magnoolia.section.asendiplaan_trust_tallinn') }}</div>
                        </div>
                    </div>

                    <a href="{{ lroute('home') }}#hinnad" class="zoomvilla-btn" data-event="cta_click" data-dest="hinnad">
                        {{ __('magnoolia.section.asendiplaan_cta_homes') }} <i class="icon-angle-small-right"></i>
                    </a>
                    <a href="{{ lroute('magnoolia.contact') }}#kontaktivorm" class="zoomvilla-btn zoomvilla-btn--border" data-event="cta_click" data-dest="kontakt" style="margin-top:12px;">
                        {{ __('magnoolia.section.asendiplaan_cta_ask') }} <i class="icon-angle-small-right"></i>
                    </a>
                </div>
            </div>
        </div>

    </div>

    {{-- Mobile unit list (visible on small screens) --}}
    <div class="d-lg-none" style="margin-top:32px;">
        @foreach($stages as $stageNum => $stageCfg)
        <div style="margin-bottom:24px;">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px;">
                <span style="background:#c89443;color:#fff;font-size:11px;font-weight:700;padding:3px 10px;border-radius:20px;">{{ $stageCfg['label'] }}</span>
                <span style="color:rgba(255,255,255,.6);font-size:13px;">{{ __('magnoolia.section.asendiplaan_mobile_completing') }} {{ $stageCfg['completion'] }}</span>
            </div>
            <div style="display:flex;flex-direction:column;gap:8px;">
                @foreach($allUnits as $unit)
                @if(($unit['stage'] ?? 0) === $stageNum)
                @php $color = $statusColors[$unit['status'] ?? 'tbc'] ?? '#9c27b0'; @endphp
                <div style="display:flex;align-items:center;justify-content:space-between;background:rgba(255,255,255,.07);border-radius:10px;padding:12px 16px;cursor:pointer;"
                     onclick="mgOpenUnit('{{ $unit['id'] }}')">
                    <div>
                        <div style="font-weight:600;color:#fff;font-size:14px;">{{ $unit['address'] }}</div>
                        <div style="color:rgba(255,255,255,.5);font-size:12px;margin-top:2px;">{{ $unit['rooms'] ?? '—' }} {{ __('magnoolia.pricing.rooms_unit') }} · {{ $unit['net_area'] ?? '—' }} m²</div>
                    </div>
                    <div style="display:flex;align-items:center;gap:10px;">
                        <span style="width:10px;height:10px;border-radius:50%;background:{{ $color }};flex-shrink:0;"></span>
                        @if(($unit['status'] ?? '') !== 'sold')
                        <a href="{{ lroute('magnoolia.contact') }}#kontaktivorm" style="background:#c89443;color:#fff;padding:6px 14px;border-radius:6px;font-size:12px;font-weight:600;text-decoration:none;">{{ __('magnoolia.section.asendiplaan_mobile_ask') }}</a>
                        @endif
                    </div>
                </div>
                @endif
                @endforeach
            </div>
        </div>
        @endforeach

        {{-- Mobile legend --}}
        <div style="display:flex;flex-wrap:wrap;gap:12px;margin-top:16px;padding-top:16px;border-top:1px solid rgba(255,255,255,.1);">
            <div style="display:flex;align-items:center;gap:6px;font-size:12px;color:rgba(255,255,255,.6);">
                <span style="width:10px;height:10px;border-radius:50%;background:#4caf50;"></span> {{ __('magnoolia.section.asendiplaan_legend_avail') }}
            </div>
            <div style="display:flex;align-items:center;gap:6px;font-size:12px;color:rgba(255,255,255,.6);">
                <span style="width:10px;height:10px;border-radius:50%;background:#ff9800;"></span> {{ __('magnoolia.section.asendiplaan_legend_reserved') }}
            </div>
            <div style="display:flex;align-items:center;gap:6px;font-size:12px;color:rgba(255,255,255,.6);">
                <span style="width:10px;height:10px;border-radius:50%;background:#f44336;"></span> {{ __('magnoolia.section.asendiplaan_legend_sold') }}
            </div>
        </div>
    </div>

</section>
