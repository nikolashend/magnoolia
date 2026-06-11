{{--
    home-availability-board.blade.php
    Phase 28 — Compact homepage availability board
    Shows all 19 homes grouped by stage with status badges + CTAs.
    Full detailed table remains only on /kodud-ja-hinnad.
--}}
@php
    $units    = $mgPublic['units'] ?? [];
    $stages   = $mgPublic['stages'] ?? [];
    $settings = $mgPublic['settings'] ?? [];
    $locale   = app()->getLocale();

    $stage1Units = collect($units)->where('stage', 1)->values();
    $stage2Units = collect($units)->where('stage', 2)->values();

    $stage1Complete = $settings['stage_1_completion'] ?? 'kevad 2027';
    $stage2Complete = $settings['stage_2_completion'] ?? 'kevad 2028';

    $localizeCompletion = function($val) use ($locale) {
        if ($locale === 'ru') return str_ireplace(['kevad','spring'],['весна','весна'], $val);
        if ($locale === 'en') return str_ireplace(['kevad 2027','kevad 2028','kevad'],['spring 2027','spring 2028','spring'], $val);
        return $val;
    };

    $statusLabel = function($status) use ($locale) {
        $labels = [
            'available' => ['et'=>'Vaba','ru'=>'Свободен','en'=>'Available'],
            'reserved'  => ['et'=>'Broneeritud','ru'=>'Забronирован','en'=>'Reserved'],
            'sold'      => ['et'=>'Müüdud','ru'=>'Продан','en'=>'Sold'],
            'tbc'       => ['et'=>'Täpsustamisel','ru'=>'Уточняется','en'=>'TBC'],
        ];
        return $labels[$status][$locale] ?? $labels[$status]['et'] ?? ucfirst($status);
    };

    $statusColor = [
        'available' => '#4ade80',
        'reserved'  => '#fbbf24',
        'sold'      => '#f87171',
        'tbc'       => '#94a3b8',
    ];

    $total     = count($units);
    $available = collect($units)->where('status','available')->count();
    $reserved  = collect($units)->where('status','reserved')->count();
    $sold      = collect($units)->where('status','sold')->count();
@endphp

<section class="section-space" id="saadavus" aria-label="{{ $locale==='ru' ? 'Доступность домов' : ($locale==='en' ? 'Homes availability' : 'Kodude saadavus') }}"
         style="background:#f7f4ef;padding-top:72px;padding-bottom:72px;">
    <div class="container">

        {{-- Section header --}}
        <div style="text-align:center;margin-bottom:48px;">
            <div style="font-size:11px;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:#c89443;margin-bottom:12px;">
                {{ $locale==='ru' ? 'Статус доступности' : ($locale==='en' ? 'Availability status' : 'Saadavuse ülevaade') }}
            </div>
            <h2 style="font-size:clamp(22px,3.5vw,36px);font-weight:800;color:#1d2430;line-height:1.2;margin:0 0 16px;">
                {{ $locale==='ru' ? '19 домов Magnoolia — статус и доступность' : ($locale==='en' ? '19 Magnoolia homes — status overview' : '19 Magnoolia kodu — saadavuse ülevaade') }}
            </h2>
            {{-- Summary chips --}}
            <div style="display:flex;align-items:center;justify-content:center;gap:16px;flex-wrap:wrap;margin-bottom:8px;">
                @if($available)
                <div style="display:inline-flex;align-items:center;gap:6px;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:20px;padding:6px 14px;font-size:13px;font-weight:600;color:#166534;">
                    <span style="width:8px;height:8px;border-radius:50%;background:#4ade80;flex-shrink:0;"></span>
                    {{ $available }} {{ $locale==='ru' ? 'свободно' : ($locale==='en' ? 'available' : 'vaba') }}
                </div>
                @endif
                @if($reserved)
                <div style="display:inline-flex;align-items:center;gap:6px;background:#fffbeb;border:1px solid #fde68a;border-radius:20px;padding:6px 14px;font-size:13px;font-weight:600;color:#92400e;">
                    <span style="width:8px;height:8px;border-radius:50%;background:#fbbf24;flex-shrink:0;"></span>
                    {{ $reserved }} {{ $locale==='ru' ? 'забронировано' : ($locale==='en' ? 'reserved' : 'broneeritud') }}
                </div>
                @endif
                @if($sold)
                <div style="display:inline-flex;align-items:center;gap:6px;background:#fef2f2;border:1px solid #fecaca;border-radius:20px;padding:6px 14px;font-size:13px;font-weight:600;color:#991b1b;">
                    <span style="width:8px;height:8px;border-radius:50%;background:#f87171;flex-shrink:0;"></span>
                    {{ $sold }} {{ $locale==='ru' ? 'продано' : ($locale==='en' ? 'sold' : 'müüdud') }}
                </div>
                @endif
            </div>
        </div>

        {{-- Two stage columns --}}
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:32px;margin-bottom:48px;">

            {{-- Stage I --}}
            <div style="background:#fff;border-radius:16px;padding:28px;box-shadow:0 2px 20px rgba(0,0,0,.06);">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;flex-wrap:wrap;gap:8px;">
                    <div>
                        <div style="font-size:11px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#c89443;margin-bottom:4px;">
                            {{ $locale==='ru' ? 'I этап' : ($locale==='en' ? 'Phase I' : 'I etapp') }}
                        </div>
                        <div style="font-size:13px;color:#6b7280;">
                            {{ $locale==='ru' ? 'Magnoolia tee 1 ja 3' : 'Magnoolia tee 1 ja 3' }} &middot; {{ $localizeCompletion($stage1Complete) }}
                        </div>
                    </div>
                    <span style="font-size:13px;font-weight:600;color:#1d2430;background:#f3f4f6;border-radius:8px;padding:4px 10px;">
                        {{ $stage1Units->count() }} {{ $locale==='ru' ? 'домов' : ($locale==='en' ? 'homes' : 'kodu') }}
                    </span>
                </div>

                @foreach($stage1Units as $unit)
                @php
                    $st = $unit['status'] ?? 'tbc';
                    $color = $statusColor[$st] ?? '#94a3b8';
                    $planType = strtoupper($unit['plan_type'] ?? 'a');
                    $planType = str_replace('TYPE-','',$planType);
                @endphp
                <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 0;border-bottom:1px solid #f3f4f6;">
                    <div style="display:flex;align-items:center;gap:10px;flex:1;min-width:0;">
                        <span style="width:8px;height:8px;border-radius:50%;background:{{ $color }};flex-shrink:0;" aria-hidden="true"></span>
                        <div style="min-width:0;">
                            <div style="font-size:13px;font-weight:600;color:#1d2430;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $unit['address'] ?? '' }}</div>
                            <div style="font-size:11px;color:#9ca3af;">
                                {{ $locale==='ru' ? 'Планировка' : ($locale==='en' ? 'Plan' : 'Plaan') }} {{ $planType }}
                                &middot; {{ $unit['rooms'] ?? '—' }}{{ $locale==='ru' ? ' ком.' : ($locale==='en' ? ' rooms' : ' tuba') }}
                                &middot; {{ number_format($unit['net_area'] ?? 0, 1) }} m²
                            </div>
                        </div>
                    </div>
                    <div style="flex-shrink:0;margin-left:10px;">
                        @if($st === 'available')
                            <button type="button"
                                    data-mg-inquiry-open
                                    data-source-component="availability_board_stage1"
                                    data-unit-id="{{ $unit['id'] ?? '' }}"
                                    data-unit-address="{{ $unit['address'] ?? '' }}"
                                    data-mg-analytics="magnoolia_home_availability_click"
                                    style="background:#c89443;color:#fff;border:none;border-radius:8px;padding:5px 12px;font-size:11px;font-weight:700;cursor:pointer;white-space:nowrap;letter-spacing:.02em;">
                                {{ $locale==='ru' ? 'Запрос' : ($locale==='en' ? 'Enquire' : 'Küsi') }}
                            </button>
                        @elseif($st === 'reserved')
                            <button type="button"
                                    data-mg-inquiry-open
                                    data-source-component="availability_board_stage1_reserved"
                                    data-unit-id="{{ $unit['id'] ?? '' }}"
                                    data-unit-address="{{ $unit['address'] ?? '' }}"
                                    data-mg-analytics="magnoolia_home_availability_click"
                                    style="background:transparent;color:#c89443;border:1px solid #c89443;border-radius:8px;padding:5px 12px;font-size:11px;font-weight:700;cursor:pointer;white-space:nowrap;">
                                {{ $locale==='ru' ? 'Уточнить' : ($locale==='en' ? 'Check' : 'Uuri') }}
                            </button>
                        @elseif($st === 'sold')
                            <span style="font-size:11px;font-weight:600;color:#9ca3af;">
                                {{ $locale==='ru' ? 'Продан' : ($locale==='en' ? 'Sold' : 'Müüdud') }}
                            </span>
                        @else
                            <span style="font-size:11px;color:#9ca3af;">
                                {{ $locale==='ru' ? 'Уточняется' : ($locale==='en' ? 'TBC' : 'Täpsustamisel') }}
                            </span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Stage II --}}
            <div style="background:#fff;border-radius:16px;padding:28px;box-shadow:0 2px 20px rgba(0,0,0,.06);">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;flex-wrap:wrap;gap:8px;">
                    <div>
                        <div style="font-size:11px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#c89443;margin-bottom:4px;">
                            {{ $locale==='ru' ? 'II этап' : ($locale==='en' ? 'Phase II' : 'II etapp') }}
                        </div>
                        <div style="font-size:13px;color:#6b7280;">
                            Magnoolia tee 5–11 &middot; {{ $localizeCompletion($stage2Complete) }}
                        </div>
                    </div>
                    <span style="font-size:13px;font-weight:600;color:#1d2430;background:#f3f4f6;border-radius:8px;padding:4px 10px;">
                        {{ $stage2Units->count() }} {{ $locale==='ru' ? 'домов' : ($locale==='en' ? 'homes' : 'kodu') }}
                    </span>
                </div>

                @foreach($stage2Units as $unit)
                @php
                    $st = $unit['status'] ?? 'tbc';
                    $color = $statusColor[$st] ?? '#94a3b8';
                    $planType = strtoupper($unit['plan_type'] ?? 'a');
                    $planType = str_replace('TYPE-','',$planType);
                @endphp
                <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 0;border-bottom:1px solid #f3f4f6;">
                    <div style="display:flex;align-items:center;gap:10px;flex:1;min-width:0;">
                        <span style="width:8px;height:8px;border-radius:50%;background:{{ $color }};flex-shrink:0;" aria-hidden="true"></span>
                        <div style="min-width:0;">
                            <div style="font-size:13px;font-weight:600;color:#1d2430;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $unit['address'] ?? '' }}</div>
                            <div style="font-size:11px;color:#9ca3af;">
                                {{ $locale==='ru' ? 'Планировка' : ($locale==='en' ? 'Plan' : 'Plaan') }} {{ $planType }}
                                &middot; {{ $unit['rooms'] ?? '—' }}{{ $locale==='ru' ? ' ком.' : ($locale==='en' ? ' rooms' : ' tuba') }}
                                &middot; {{ number_format($unit['net_area'] ?? 0, 1) }} m²
                            </div>
                        </div>
                    </div>
                    <div style="flex-shrink:0;margin-left:10px;">
                        @if($st === 'available')
                            <button type="button"
                                    data-mg-inquiry-open
                                    data-source-component="availability_board_stage2"
                                    data-unit-id="{{ $unit['id'] ?? '' }}"
                                    data-unit-address="{{ $unit['address'] ?? '' }}"
                                    data-mg-analytics="magnoolia_home_availability_click"
                                    style="background:#c89443;color:#fff;border:none;border-radius:8px;padding:5px 12px;font-size:11px;font-weight:700;cursor:pointer;white-space:nowrap;letter-spacing:.02em;">
                                {{ $locale==='ru' ? 'Запрос' : ($locale==='en' ? 'Enquire' : 'Küsi') }}
                            </button>
                        @elseif($st === 'reserved')
                            <button type="button"
                                    data-mg-inquiry-open
                                    data-source-component="availability_board_stage2_reserved"
                                    data-unit-id="{{ $unit['id'] ?? '' }}"
                                    data-unit-address="{{ $unit['address'] ?? '' }}"
                                    data-mg-analytics="magnoolia_home_availability_click"
                                    style="background:transparent;color:#c89443;border:1px solid #c89443;border-radius:8px;padding:5px 12px;font-size:11px;font-weight:700;cursor:pointer;white-space:nowrap;">
                                {{ $locale==='ru' ? 'Уточнить' : ($locale==='en' ? 'Check' : 'Uuri') }}
                            </button>
                        @elseif($st === 'sold')
                            <span style="font-size:11px;font-weight:600;color:#9ca3af;">
                                {{ $locale==='ru' ? 'Продан' : ($locale==='en' ? 'Sold' : 'Müüdud') }}
                            </span>
                        @else
                            <span style="font-size:11px;color:#9ca3af;">
                                {{ $locale==='ru' ? 'Уточняется' : ($locale==='en' ? 'TBC' : 'Täpsustamisel') }}
                            </span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

        </div>

        {{-- Full table CTA --}}
        <div style="text-align:center;">
            <a href="{{ lroute('magnoolia.homes') }}"
               style="display:inline-flex;align-items:center;gap:8px;background:#1d2430;color:#fff;text-decoration:none;padding:14px 32px;border-radius:10px;font-size:14px;font-weight:700;letter-spacing:.04em;"
               data-mg-analytics="magnoolia_home_availability_click">
                {{ $locale==='ru' ? 'Смотреть все 19 домов и цены' : ($locale==='en' ? 'View all 19 homes & prices' : 'Vaata kõiki 19 kodu ja hindu') }}
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="6 3 11 8 6 13"/></svg>
            </a>
            <div style="margin-top:12px;font-size:12px;color:#9ca3af;">
                {{ $locale==='ru' ? 'Полная таблица с площадями, планами и деталями' : ($locale==='en' ? 'Full table with areas, plans and details' : 'Täielik tabel pindade, plaanide ja üksikasyadega') }}
            </div>
        </div>

    </div>
</section>
