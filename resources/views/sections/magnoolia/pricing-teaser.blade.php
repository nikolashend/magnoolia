{{--
    sections/magnoolia/pricing-teaser.blade.php
    Phase 26 — Compact homepage pricing/availability teaser.
    Does NOT render full table. Shows key facts + availability summary + CTAs.
--}}
@php
  $locale   = app()->getLocale();
  $units    = $mgPublic['units'] ?? [];
  $settings = $mgPublic['settings'] ?? [];
  $campaign = config('magnoolia.campaign', []);

  $total     = count($units);
  $available = collect($units)->where('status', 'available')->count();
  $reserved  = collect($units)->where('status', 'reserved')->count();
  $sold      = collect($units)->where('status', 'sold')->count();

  $stage1Complete = $settings['stage_1_completion'] ?? 'Kevad 2027';
  $stage2Complete = $settings['stage_2_completion'] ?? 'Kevad 2028';
  $showCampaign   = !empty($campaign['enabled']) && !empty($campaign['amount_eur']);
@endphp

<section class="section-space" style="background:#1d2430;">
  <div class="container">

    {{-- Campaign ribbon --}}
    @if($showCampaign)
    <div style="background:#c89443;border-radius:10px;padding:14px 24px;margin-bottom:32px;display:flex;align-items:center;gap:12px;flex-wrap:wrap;"
         data-mg-analytics="magnoolia_campaign_click">
      <span style="font-size:11px;font-weight:800;color:#fff;letter-spacing:.12em;text-transform:uppercase;flex-shrink:0;">
        @if($locale==='ru') АКЦИЯ @elseif($locale==='en') OFFER @else KAMPAANIA @endif
      </span>
      <span style="color:#fff;font-size:14px;flex:1;">
        @if($locale==='ru') {{ $campaign['body_short_ru'] ?? '' }}
        @elseif($locale==='en') {{ $campaign['body_short_en'] ?? '' }}
        @else {{ $campaign['body_short_et'] ?? '' }}
        @endif
      </span>
      @if(!($campaign['legal_final'] ?? true))
      <span style="font-size:11px;color:rgba(255,255,255,.65);">
        @if($locale==='ru') Точные условия уточняет Diana.
        @elseif($locale==='en') Exact terms confirmed by Diana.
        @else Täpsed tingimused kinnitab Diana.
        @endif
      </span>
      @endif
    </div>
    @endif

    <div style="display:flex;align-items:flex-start;gap:40px;flex-wrap:wrap;">

      {{-- Left: key facts --}}
      <div style="flex:1;min-width:260px;">
        <div style="font-size:11px;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:#c89443;margin-bottom:12px;">
          @if($locale==='ru') Дома и цены @elseif($locale==='en') Homes & Prices @else Kodud ja hinnad @endif
        </div>
        <h2 style="font-size:clamp(20px,3vw,30px);font-weight:800;color:#fff;margin:0 0 24px;line-height:1.2;">
          @if($locale==='ru') Выберите подходящий дом Magnoolia
          @elseif($locale==='en') Find your Magnoolia home
          @else Vali endale sobiv Magnoolia kodu
          @endif
        </h2>
        <div style="display:flex;flex-direction:column;gap:10px;margin-bottom:24px;">
          @foreach([
            ['icon'=>'🏠','val'=>$total . ($locale==='ru' ? ' домов' : ($locale==='en' ? ' homes' : ' kodu'))],
            ['icon'=>'📅','val'=>'I etapp ' . $stage1Complete],
            ['icon'=>'📅','val'=>'II etapp ' . $stage2Complete],
            ['icon'=>'📐','val'=>$locale==='ru' ? 'Планировка A и B' : ($locale==='en' ? 'Plan A & B' : 'Plaan A ja B')],
          ] as $fact)
          <div style="display:flex;align-items:center;gap:10px;font-size:14px;color:rgba(255,255,255,.85);">
            <span aria-hidden="true">{{ $fact['icon'] }}</span>
            <span>{{ $fact['val'] }}</span>
          </div>
          @endforeach
        </div>
      </div>

      {{-- Right: availability summary --}}
      <div style="flex-shrink:0;min-width:220px;">
        <div style="font-size:11px;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:rgba(255,255,255,.5);margin-bottom:16px;">
          @if($locale==='ru') Статус @elseif($locale==='en') Status @else Saadavus @endif
        </div>
        @foreach([
          ['label_et'=>'Saadaval','label_ru'=>'Свободно','label_en'=>'Available','count'=>$available,'color'=>'#4ade80'],
          ['label_et'=>'Broneeritud','label_ru'=>'Забронировано','label_en'=>'Reserved','count'=>$reserved,'color'=>'#fbbf24'],
          ['label_et'=>'Müüdud','label_ru'=>'Продано','label_en'=>'Sold','count'=>$sold,'color'=>'#f87171'],
        ] as $s)
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;">
          <div style="display:flex;align-items:center;gap:8px;font-size:14px;color:rgba(255,255,255,.75);">
            <span style="width:8px;height:8px;border-radius:50%;background:{{ $s['color'] }};flex-shrink:0;" aria-hidden="true"></span>
            {{ $s['label_' . $locale] ?? $s['label_et'] }}
          </div>
          <span style="font-size:16px;font-weight:800;color:#fff;">{{ $s['count'] }}</span>
        </div>
        @endforeach

        <div style="border-top:1px solid rgba(255,255,255,.12);padding-top:14px;margin-top:6px;display:flex;flex-direction:column;gap:8px;">
          <a href="{{ lroute('magnoolia.homes') }}"
             style="padding:12px 20px;background:#c89443;color:#fff;text-decoration:none;border-radius:8px;font-size:13px;font-weight:700;letter-spacing:.04em;text-align:center;display:block;">
            @if($locale==='ru') Смотреть дома и цены @elseif($locale==='en') View homes & prices @else Vaata kodusid ja hindu @endif
          </a>
          <a href="{{ lroute('magnoolia.site-plan') }}"
             style="padding:12px 20px;background:transparent;color:rgba(255,255,255,.8);text-decoration:none;border-radius:8px;font-size:13px;font-weight:600;text-align:center;display:block;border:1px solid rgba(255,255,255,.25);">
            @if($locale==='ru') Смотреть на карте @elseif($locale==='en') View on map @else Vaata kodusid kaardil @endif
          </a>
          <button type="button"
                  class="zoomvilla-btn"
                  data-mg-inquiry-open
                  data-source-component="homepage_pricing_teaser"
                  data-mg-analytics="magnoolia_cta_click"
                  style="width:100%;padding:12px 20px;border:1px solid rgba(255,255,255,.3);background:transparent;color:#fff;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;text-align:center;">
            @if($locale==='ru') Запросить предложение @elseif($locale==='en') Request a quote @else Küsi pakkumist @endif
          </button>
        </div>
      </div>

    </div>
  </div>
</section>
