{{-- ══════════════════════════════════════════════════════════════
    HINNAD JA PLAANID — Magnoolia pricing table
    Source: config/magnoolia.php → units array
    Phase 4: static preview with real unit data from config
    Phase 5: DB-driven with live status
    ══════════════════════════════════════════════════════════════ --}}
@php
    $units = config('magnoolia.units', []);
    $statuses = [
        'available' => ['label' => __('magnoolia.pricing.status_available'), 'class' => 'mg-status--available'],
        'reserved'  => ['label' => __('magnoolia.pricing.status_reserved'),  'class' => 'mg-status--reserved'],
        'sold'      => ['label' => __('magnoolia.pricing.status_sold'),       'class' => 'mg-status--sold'],
    ];
@endphp

<section class="section-space" id="hinnad" style="background:#f7f4ef;">
    <div class="container">

        {{-- Section header --}}
        <div class="sec-title text-center" style="margin-bottom:50px;">
            <div class="sec-title__top justify-content-center">
                <span class="line-left"></span>
                <h6 class="sec-title__tagline bw-split-in-right">{{ __('magnoolia.section.pricing_eyebrow') }}</h6>
                <span class="line-right"></span>
            </div>
            <h3 class="sec-title__title bw-split-in-left">{{ __('magnoolia.section.pricing_title') }}</h3>
            <p style="color:#6f6a61;margin-top:16px;font-size:16px;">{{ __('magnoolia.section.pricing_subtitle') }}</p>
        </div>

        {{-- ── DESKTOP TABLE ──────────────────────────────────────── --}}
        <div class="mg-pricing-table__wrap d-none d-lg-block wow fadeInUp" data-wow-duration="1200ms">
            <table class="mg-pricing-table" style="width:100%;border-collapse:collapse;">
                <thead>
                    <tr style="background:#151515;color:#fff;">
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
                    @forelse($units as $i => $unit)
                    @php
                        $st = $unit['status'] ?? 'unknown';
                        $stData = $statuses[$st] ?? ['label' => __('magnoolia.pricing.status_unknown'), 'class' => 'mg-status--unknown'];
                    @endphp
                    <tr style="background:{{ $i % 2 === 0 ? '#fff' : '#fbfaf7' }};border-bottom:1px solid rgba(29,36,48,.08);transition:background .2s;"
                        onmouseover="this.style.background='#f0ece3'" onmouseout="this.style.background='{{ $i % 2 === 0 ? '#fff' : '#fbfaf7' }}'">
                        <td style="padding:16px;font-weight:600;color:#1d2430;">{{ $unit['address'] }}</td>
                        <td style="padding:16px;text-align:center;color:#1d2430;">{{ number_format($unit['net_area'] ?? 0, 1) }} m²</td>
                        <td style="padding:16px;text-align:center;color:#1d2430;">{{ $unit['rooms'] ?? '—' }}</td>
                        <td style="padding:16px;text-align:center;color:#6f6a61;">{{ isset($unit['terrace_area']) && $unit['terrace_area'] > 0 ? number_format($unit['terrace_area'], 0) . ' m²' : '—' }}</td>
                        <td style="padding:16px;text-align:center;color:#6f6a61;">{{ isset($unit['balcony_area']) && $unit['balcony_area'] > 0 ? number_format($unit['balcony_area'], 0) . ' m²' : '—' }}</td>
                        <td style="padding:16px;text-align:center;color:#6f6a61;">{{ $unit['parking'] ?? 2 }}×</td>
                        <td style="padding:16px;text-align:right;font-weight:700;color:#1d2430;">
                            {{ $unit['price'] ? '€ ' . number_format($unit['price'], 0, ',', ' ') : __('magnoolia.pricing.price_request') }}
                        </td>
                        <td style="padding:16px;text-align:center;">
                            <span class="mg-status {{ $stData['class'] }}" style="display:inline-block;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:600;">
                                {{ $stData['label'] }}
                            </span>
                        </td>
                        <td style="padding:16px;text-align:center;">
                            <a href="{{ route('contact') }}" class="mg-table-cta">{{ __('magnoolia.pricing.cta_inquiry') }}</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" style="padding:32px;text-align:center;color:#6f6a61;">
                            {{ __('magnoolia.pricing.price_request') }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ── MOBILE CARDS ───────────────────────────────────────── --}}
        <div class="d-lg-none" style="display:flex;flex-direction:column;gap:16px;">
            @foreach($units as $unit)
            @php
                $st = $unit['status'] ?? 'unknown';
                $stData = $statuses[$st] ?? ['label' => __('magnoolia.pricing.status_unknown'), 'class' => 'mg-status--unknown'];
            @endphp
            <div style="background:#fff;border-radius:16px;padding:20px;box-shadow:0 4px 20px rgba(0,0,0,.08);">
                <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:12px;">
                    <div>
                        <div style="font-weight:700;color:#1d2430;font-size:15px;">{{ $unit['address'] }}</div>
                        <div style="color:#6f6a61;font-size:13px;margin-top:4px;">
                            {{ number_format($unit['net_area'] ?? 0, 1) }} m² · {{ $unit['rooms'] ?? '—' }} tuba · A-klass
                        </div>
                    </div>
                    <span class="mg-status {{ $stData['class'] }}" style="padding:4px 10px;border-radius:20px;font-size:11px;font-weight:600;white-space:nowrap;">
                        {{ $stData['label'] }}
                    </span>
                </div>
                <div style="display:flex;gap:16px;font-size:13px;color:#6f6a61;margin-bottom:14px;">
                    @if(!empty($unit['terrace_area']) && $unit['terrace_area'] > 0)
                        <span>Terrass {{ $unit['terrace_area'] }} m²</span>
                    @endif
                    @if(!empty($unit['balcony_area']) && $unit['balcony_area'] > 0)
                        <span>Rõdu {{ $unit['balcony_area'] }} m²</span>
                    @endif
                    <span>Parkimine {{ $unit['parking'] ?? 2 }}×</span>
                </div>
                <div style="display:flex;justify-content:space-between;align-items:center;">
                    <div style="font-weight:700;color:#1d2430;font-size:16px;">
                        {{ $unit['price'] ? '€ ' . number_format($unit['price'], 0, ',', ' ') : __('magnoolia.pricing.price_request') }}
                    </div>
                    <a href="{{ route('contact') }}" style="background:#c89443;color:#fff;padding:8px 16px;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;">
                        {{ __('magnoolia.pricing.cta_inquiry') }}
                    </a>
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
                    @foreach(__('magnoolia.pricing.includes_items') as $item)
                    <li style="display:flex;align-items:flex-start;gap:10px;font-size:14px;color:#6f6a61;">
                        <i class="icon-check-star" style="color:#c89443;margin-top:2px;flex-shrink:0;"></i>
                        {{ $item }}
                    </li>
                    @endforeach
                </ul>
            </div>
            <div style="flex:1;min-width:240px;display:flex;flex-direction:column;justify-content:space-between;gap:20px;">
                <p style="font-size:13px;color:#9a9490;font-style:italic;margin:0;">{{ __('magnoolia.pricing.disclaimer') }}</p>
                <div style="display:flex;gap:12px;flex-wrap:wrap;">
                    <a href="{{ route('contact') }}" class="zoomvilla-btn">
                        {{ __('magnoolia.pricing.cta_inquiry') }} <i class="icon-angle-small-right"></i>
                    </a>
                    <a href="#asendiplaan" class="zoomvilla-btn zoomvilla-btn--border">
                        Asendiplaan <i class="icon-angle-small-right"></i>
                    </a>
                </div>
            </div>
        </div>

    </div>
</section>
