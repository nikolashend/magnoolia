{{--
    partials/magnoolia/interior-finish-section.blade.php — Phase 31

    Premium native interior-finish / equipment standard section for /ehitusinfo.
    Data: config/magnoolia_interiors.php. Copy: magnoolia.interior.* (ET/RU/EN).
    Layout: editorial render block → 5 always-visible category blocks (no JS)
    → AI answer block → disclaimer + CTA. Proof sheets open larger via a link.
--}}
@php
  $iv   = config('magnoolia_interiors', []);
  $cats = $iv['categories'] ?? [];
  $ed   = $iv['editorial'] ?? [];
  $points = (array) __('magnoolia.interior.editorial_points');
  $disclaimer = __('magnoolia.interior.disclaimer');

  // minimal inline line icons (gold/navy), keyed by category icon
  $svg = 'viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"';
  $icons = [
    'electrical' => '<svg '.$svg.'><path d="M13 2 3 14h9l-1 8 10-12h-9z"/></svg>',
    'sanitary'   => '<svg '.$svg.'><path d="M12 2s6 7.6 6 12a6 6 0 0 1-12 0c0-4.4 6-12 6-12z"/></svg>',
    'tiles'      => '<svg '.$svg.'><rect x="3" y="3" width="18" height="18" rx="1.5"/><path d="M3 9h18M3 15h18M9 3v18M15 3v18"/></svg>',
    'finish'     => '<svg '.$svg.'><rect x="6" y="3" width="12" height="18" rx="1.5"/><path d="M6 3 3 6v15h3M18 3l3 3v15h-3"/><circle cx="14.5" cy="12" r="1"/></svg>',
    'paid'       => '<svg '.$svg.'><circle cx="12" cy="12" r="9"/><path d="M12 8v8M8 12h8"/></svg>',
  ];

  $imgUrl = function ($rel) {
    return $rel ? asset($rel) : null;
  };
  $catImgUrl = function ($rel) {
    // serve the 768 variant inline, link to base (full) for "open larger"
    return $rel ? asset(preg_replace('/\.webp$/', '-768.webp', $rel)) : null;
  };
@endphp

@if(count($cats))
<section class="mg-page-section mg-page-section--white" id="siseviimistlus">
  <div class="container">

    {{-- Intro --}}
    <div class="mg-section-heading" style="margin-bottom:14px;">
      <div class="mg-section-heading__eyebrow">{{ __('magnoolia.interior.section_eyebrow') }}</div>
      <h2 class="mg-section-heading__title">{{ __('magnoolia.interior.section_title') }}</h2>
      <p class="mg-section-heading__subtitle">{{ __('magnoolia.interior.section_subtitle') }}</p>
    </div>
    <p class="mg-if__disclaimer mg-if__disclaimer--intro">{{ $disclaimer }}</p>

    {{-- Layer 1 — editorial interior preview --}}
    @if(!empty($ed['image_day']))
    <div class="mg-if__editorial">
      <div class="mg-if__editorial-media">
        <img class="mg-if__editorial-img mg-if__editorial-img--lg"
             src="{{ asset(preg_replace('/\.webp$/', '-768.webp', $ed['image_day'])) }}"
             srcset="{{ asset(preg_replace('/\.webp$/', '-768.webp', $ed['image_day'])) }} 768w, {{ asset($ed['image_day']) }} 1600w"
             sizes="(min-width:992px) 48vw, 100vw"
             width="1600" height="900" loading="lazy" decoding="async"
             alt="{{ __('magnoolia.interior.alt_day') }}">
        @if(!empty($ed['image_evening']))
        <img class="mg-if__editorial-img mg-if__editorial-img--sm"
             src="{{ asset(preg_replace('/\.webp$/', '-768.webp', $ed['image_evening'])) }}"
             width="1600" height="900" loading="lazy" decoding="async"
             alt="{{ __('magnoolia.interior.alt_evening') }}">
        @endif
      </div>
      <div class="mg-if__editorial-text">
        <h3 class="mg-if__editorial-title">{{ __('magnoolia.interior.editorial_title') }}</h3>
        <ul class="mg-if__points">
          @foreach($points as $pt)
          <li>{{ $pt }}</li>
          @endforeach
        </ul>
        <a href="{{ lroute('magnoolia.contact') }}#kontaktivorm" class="mg-btn mg-btn--gold mg-if__editorial-cta"
           data-mg-inquiry-open data-source-component="sisedisain_viimistluspakett"
           data-mg-analytics="ehitusinfo-siseviimistlus-cta">{{ __('magnoolia.interior.editorial_cta') }}</a>
      </div>
    </div>
    @endif

    {{-- Layer 2+3 — category blocks, permanently open (Phase 35.1 item 18) --}}
    <div class="mg-if__cats">
      @foreach($cats as $key => $cat)
      @php
        $title = __('magnoolia.interior.cat.'.$key.'.title');
        $desc  = __('magnoolia.interior.cat.'.$key.'.description');
        $proofFull = $imgUrl($cat['overview'] ?? null);
        $proofSm   = $catImgUrl($cat['overview'] ?? null);
        $proofLg   = ($cat['overview'] ?? null)
            ? asset(preg_replace('/\.webp$/', '-1400.webp', $cat['overview']))
            : $proofSm;
        // Phase 36 Module B — "kuidas neid musta taustaga fotosid asendada?".
        // Each proof sheet is now a slot; a bound media item replaces the whole set.
        $proofSlot = mg_slot('sisedisain.proof.' . $key);
        if ($proofSlot['bound']) {
            $proofFull = $proofSlot['src'];
            $proofSm   = $proofSlot['src'];
            $proofLg   = $proofSlot['src'];
        }
      @endphp
      {{-- Phase 35.1 item 18 — "pidevalt avatud": this used to be a <details>
           accordion. Opening it by default was not enough: buyers could still
           collapse it, and Indrek asked for the specification to be permanently
           visible because people were not finding it behind the "+". The toggle
           is gone entirely — plain headings, content always on screen. --}}
      <section class="mg-if-card mg-if-card--static">
        <div class="mg-if-card__summary">
          <span class="mg-if-card__icon" aria-hidden="true">{!! $icons[$cat['icon'] ?? 'finish'] ?? '' !!}</span>
          <span class="mg-if-card__head">
            <h3 class="mg-if-card__title">{{ $title }}</h3>
            <span class="mg-if-card__desc">{{ $desc }}</span>
          </span>
        </div>

        <div class="mg-if-card__body">
          <div class="mg-if-card__grid">
            @php
              // Phase 36 Module C — the equipment rows are an editable list per
              // category. Unpublished, this falls back to the config array, so the
              // specification stays exactly as it ships.
              $mgItems = array_map(
                  fn ($row) => ['name' => $row['name'] ?? '', 'type' => $row['type'] ?? 'standard'],
                  mg_list('sisedisain.spec.' . $key)
              );
              $mgItems = array_values(array_filter($mgItems, fn ($row) => filled($row['name'])));
              if ($mgItems === []) {
                  $mgItems = $cat['items'];
              }
            @endphp
            <ul class="mg-if-card__items">
              @foreach($mgItems as $item)
              <li class="mg-if-item">
                <span class="mg-if-item__name">{{ $item['name'] }}</span>
                <span class="mg-if-item__badge mg-if-item__badge--{{ $item['type'] }}">
                  {{ $item['type'] === 'paid' ? __('magnoolia.interior.paid_label') : __('magnoolia.interior.standard_label') }}
                </span>
              </li>
              @endforeach
            </ul>

            @if($proofSm)
            <figure class="mg-if-card__proof">
              <a href="{{ $proofFull }}" target="_blank" rel="noopener noreferrer"
                 data-mg-analytics="ehitusinfo-materials-detail-open"
                 aria-label="{{ __('magnoolia.interior.open_larger') }}">
                {{-- Phase 35.1 item 10: serve the large variant, not the 768 thumbnail. --}}
                <img src="{{ $proofLg }}" srcset="{{ $proofSm }} 768w, {{ $proofLg }} 1400w"
                     sizes="(min-width:992px) 900px, 100vw"
                     loading="lazy" decoding="async" width="1400" height="789"
                     alt="{{ __('magnoolia.interior.proof_alt', ['category' => $title]) }}">
                <span class="mg-if-card__proof-zoom">{{ __('magnoolia.interior.open_larger') }} ↗</span>
              </a>
            </figure>
            @endif
          </div>
          @if($key === 'paid' || $key === 'tiles')
          <p class="mg-if__disclaimer mg-if__disclaimer--sm">{{ $disclaimer }}</p>
          @endif
        </div>
      </section>
      @endforeach
    </div>

    {{-- AI answer block (crawlable, text-based) --}}
    <div class="mg-if__ai">
      <h3 class="mg-if__ai-q">{{ __('magnoolia.interior.ai_q') }}</h3>
      <p class="mg-if__ai-a">{{ __('magnoolia.interior.ai_a') }}</p>
    </div>

    {{-- Closing disclaimer + CTA --}}
    <p class="mg-if__disclaimer">{{ $disclaimer }}</p>
    <div class="mg-if__ctas">
      <a href="{{ lroute('magnoolia.contact') }}#kontaktivorm" class="mg-btn mg-btn--gold"
         data-mg-inquiry-open data-source-component="sisedisain_viimistluspakett"
         data-mg-analytics="ehitusinfo-siseviimistlus-cta">{{ __('magnoolia.interior.cta_package') }}</a>
      <a href="{{ lroute('magnoolia.contact') }}#kontaktivorm" class="mg-btn mg-btn--ghost"
         data-mg-inquiry-open data-source-component="sisedisain_viimistluspakett_offer"
         data-mg-analytics="ehitusinfo-paid-options-open">{{ __('magnoolia.interior.cta_offer') }}</a>
    </div>
  </div>
</section>
@endif
