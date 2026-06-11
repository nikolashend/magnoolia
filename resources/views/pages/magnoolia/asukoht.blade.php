@extends('layouts.app')

@section('title', __('magnoolia.page.asukoht.page_title'))
@section('meta_description', $page['description'] ?? '')
@section('og_title', $page['title'] ?? '')
@section('og_description', $page['description'] ?? '')

@section('content')
@php
  $base = rtrim(config('magnoolia.seo.canonical_base', 'https://magnoolia.ee'), '/');
@endphp

<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@graph": [
    {
      "@@type": "BreadcrumbList",
      "itemListElement": [
        { "@@type": "ListItem", "position": 1, "name": "Avaleht", "item": "{{ $base }}" },
        { "@@type": "ListItem", "position": 2, "name": "Asukoht", "item": "{{ $base }}/asukoht" }
      ]
    },
    {
      "@@type": "Place",
      "@@id": "{{ $base }}/#place",
      "name": "Magnoolia tee, Vaela küla, Kiili vald",
      "address": {
        "@@type": "PostalAddress",
        "streetAddress": "Magnoolia tee",
        "addressLocality": "Vaela küla, Kiili vald",
        "addressRegion": "Harjumaa",
        "addressCountry": "EE"
      },
      "geo": { "@@type": "GeoCoordinates", "latitude": 59.3488, "longitude": 24.8027 }
    },
    {
      "@@type": "FAQPage",
      "mainEntity": [
        {
          "@@type": "Question",
          "name": "Kui kaugel on Magnoolia Tallinnast?",
          "acceptedAnswer": { "@@type": "Answer", "text": "Magnoolia tee asub Vaela külas, Kiili vallas. Tallinna kesklinnani on sõltuvalt marsruudist ja liiklusolukorrast ligikaudu 20–30 minutit." }
        },
        {
          "@@type": "Question",
          "name": "Kas Vaela külas on lasteaed ja kool?",
          "acceptedAnswer": { "@@type": "Answer", "text": "Kiili vald on kasvav piirkond, kus haridusasutuste täpne paiknemine on täpsustamisel. Küsige Diana käest hetkel kehtiva info kohta." }
        },
        {
          "@@type": "Question",
          "name": "Millised teed viivad Magnoolia juurde?",
          "acceptedAnswer": { "@@type": "Answer", "text": "Asukoht on hea ühendusteedega: Tallinna–Tartu maantee ja Tallinna–Pärnu maantee on mõlemad kiiresti käeulatuses. Täpne juurdepääsutee kinnitatakse ehitusprojektis." }
        }
      ]
    }
  ]
}
</script>

{{-- ── Hero ─────────────────────────────────────────────────── --}}
<div class="mg-page-hero" style="background-image:linear-gradient(to right, rgba(29,36,48,.88) 60%, rgba(29,36,48,.5)), url('{{ asset('assets/images/magnoolia/magnoolia_cam07.jpg') }}');background-size:cover;background-position:center;">
  <div class="container">
    @include('partials.seo.breadcrumb', ['items' => [
      ['label' => __('magnoolia.nav.home'), 'url' => route('home')],
      ['label' => __('magnoolia.nav.location')],
    ]])
    <div class="mg-page-hero__eyebrow">{{ __('magnoolia.page.asukoht.eyebrow') }}</div>
    <h1 class="mg-page-hero__title">{{ __('magnoolia.page.asukoht.page_h1') }}</h1>
    <p class="mg-page-hero__lead">
      {{ __('magnoolia.page.asukoht.lead') }}
    </p>
    <div class="mg-page-hero__ctas">
      <a href="https://maps.google.com/?q=Magnoolia+tee,Vaela,Kiili+vald" target="_blank" rel="noopener"
         class="zoomvilla-btn">
        {{ __('magnoolia.page.asukoht.cta_map') }} <i class="fas fa-external-link-alt" style="font-size:12px;"></i>
      </a>
      <a href="{{ lroute('magnoolia.contact') }}" class="zoomvilla-btn zoomvilla-btn--border">{{ __('magnoolia.page.asukoht.cta_inquiry') }}</a>
    </div>
  </div>
</div>

{{-- ── Map fallback ─────────────────────────────────────────── --}}
<section class="mg-page-section mg-page-section--white">
  <div class="container">
    <div class="mg-location-fallback">
      <div class="mg-location-fallback__icon">
        <i class="fas fa-map-marker-alt"></i>
      </div>
      <div class="mg-location-fallback__body">
        <div class="mg-location-fallback__label">{{ __('magnoolia.page.asukoht.address_label') }}</div>
        <div class="mg-location-fallback__address">Magnoolia tee, Vaela küla, Kiili vald, Harjumaa</div>
        <a href="https://maps.google.com/?q=Magnoolia+tee,Vaela,Kiili+vald"
           target="_blank" rel="noopener"
           class="zoomvilla-btn" style="margin-top:20px;margin-bottom:20px;display:inline-flex;">
          <i class="fas fa-directions" style="margin-right:8px;"></i> {{ __('magnoolia.page.asukoht.address_map_btn') }}
        </a>
      </div>
      <div class="mg-location-fallback__img" aria-hidden="true">
        <img src="{{ asset('assets/images/magnoolia/Magnoolia tee_ES_7.jpg') }}"
             alt="Magnoolia tee keskkond Vaela külas"
             width="560" height="380" loading="lazy">
      </div>
    </div>
  </div>
</section>

{{-- ── Distance table ───────────────────────────────────────── --}}
<section class="mg-page-section mg-page-section--cream">
  <div class="container">
    <div class="row gutter-y-40 align-items-start">
      <div class="col-lg-6">
        <div class="mg-section-heading" style="margin-bottom:28px;">
          <div class="mg-section-heading__eyebrow">{{ __('magnoolia.page.asukoht.dist_eyebrow') }}</div>
          <h2 class="mg-section-heading__title">{{ __('magnoolia.page.asukoht.dist_title') }}</h2>
          <p class="mg-section-heading__subtitle">
            {{ __('magnoolia.page.asukoht.dist_note') }}
          </p>
        </div>
        <table class="mg-distance-table">
          <thead>
            <tr><th>{{ __('magnoolia.page.asukoht.dist_col_dest') }}</th><th>{{ __('magnoolia.page.asukoht.dist_col_km') }}</th><th>{{ __('magnoolia.page.asukoht.dist_col_time') }}</th></tr>
          </thead>
          <tbody>
            @foreach(__('magnoolia.page.asukoht.distances') as $row)
            <tr>
              <td>{{ $row['dest'] }}</td>
              <td>{{ $row['dist'] }}</td>
              <td>{{ $row['time'] }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
        <p class="mg-seo-note" style="margin-top:14px;">
          {{ __('magnoolia.page.asukoht.seo_note') }}
        </p>
      </div>

      <div class="col-lg-6">
        <div class="row gutter-y-20">
          @foreach([
            ['icon' => 'fas fa-tree',       'title' => __('magnoolia.page.asukoht.card1_title'), 'body' => __('magnoolia.page.asukoht.card1_body')],
            ['icon' => 'fas fa-car',        'title' => __('magnoolia.page.asukoht.card2_title'), 'body' => __('magnoolia.page.asukoht.card2_body')],
            ['icon' => 'fas fa-baby',       'title' => __('magnoolia.page.asukoht.card3_title'), 'body' => __('magnoolia.page.asukoht.card3_body')],
            ['icon' => 'fas fa-chart-line', 'title' => __('magnoolia.page.asukoht.card4_title'), 'body' => __('magnoolia.page.asukoht.card4_body')],
          ] as $card)
          <div class="col-12">
            <div class="mg-proof-card" style="padding:20px 24px;">
              <div class="mg-proof-card__icon"><i class="{{ $card['icon'] }}"></i></div>
              <div>
                <div class="mg-proof-card__title">{{ $card['title'] }}</div>
                <div class="mg-proof-card__body">{{ $card['body'] }}</div>
              </div>
            </div>
          </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ── Phase 26: Education & Family photo section ───────────── --}}
<section class="mg-page-section mg-page-section--white">
  <div class="container">
    <div class="mg-section-heading mg-section-heading--center" style="margin-bottom:40px;">
      <div class="mg-section-heading__eyebrow">{{ __('magnoolia.page.asukoht.edu_eyebrow') }}</div>
      <h2 class="mg-section-heading__title">{{ __('magnoolia.page.asukoht.edu_title') }}</h2>
      <p class="mg-section-heading__subtitle">{{ __('magnoolia.page.asukoht.edu_sub') }}</p>
    </div>
    <div class="row gutter-y-24">
      @foreach([
        ['img' => 'vaela-lasteaed.webp',  'title' => __('magnoolia.page.asukoht.edu_card1_title'), 'body' => __('magnoolia.page.asukoht.edu_card1_body'), 'alt' => __('magnoolia.page.asukoht.edu_card1_alt')],
        ['img' => 'vaela-lasteaed-2.webp','title' => __('magnoolia.page.asukoht.edu_card2_title'), 'body' => __('magnoolia.page.asukoht.edu_card2_body'), 'alt' => __('magnoolia.page.asukoht.edu_card2_alt')],
        ['img' => 'kiili-kool.jpg',        'title' => __('magnoolia.page.asukoht.edu_card3_title'), 'body' => __('magnoolia.page.asukoht.edu_card3_body'), 'alt' => __('magnoolia.page.asukoht.edu_card3_alt')],
        ['img' => 'kiili-spordimaja.jpg',  'title' => __('magnoolia.page.asukoht.edu_card4_title'), 'body' => __('magnoolia.page.asukoht.edu_card4_body'), 'alt' => __('magnoolia.page.asukoht.edu_card4_alt')],
      ] as $card)
      <div class="col-lg-3 col-md-6">
        <div style="border-radius:12px;overflow:hidden;box-shadow:0 2px 16px rgba(0,0,0,.07);background:#fff;height:100%;">
          @if(file_exists(public_path('assets/magnoolia/location/' . $card['img'])))
          <button type="button"
                  onclick="mgLightboxOpen('{{ asset('assets/magnoolia/location/' . $card['img']) }}','{{ addslashes($card['alt']) }}')"
                  style="display:block;width:100%;padding:0;border:0;background:none;cursor:zoom-in;"
                  aria-label="Suurenda: {{ $card['alt'] }}">
            <img src="{{ asset('assets/magnoolia/location/' . $card['img']) }}"
                 alt="{{ $card['alt'] }}"
                 width="360" height="240"
                 decoding="async" loading="lazy"
                 style="width:100%;height:200px;object-fit:cover;display:block;">
          </button>
          @endif
          <div style="padding:20px;">
            <div style="font-size:13px;font-weight:700;color:#1d2430;margin-bottom:8px;">{{ $card['title'] }}</div>
            <div style="font-size:13px;color:#666;line-height:1.6;">{{ $card['body'] }}</div>
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>

{{-- ── Phase 26: Shopping & Services photo section ────────── --}}
<section class="mg-page-section mg-page-section--cream">
  <div class="container">
    <div class="mg-editorial-row">
      <div class="mg-editorial-row__content">
        <div class="mg-editorial-row__kicker">{{ __('magnoolia.page.asukoht.shop_eyebrow') }}</div>
        <h2 class="mg-editorial-row__title">{{ __('magnoolia.page.asukoht.shop_title') }}</h2>
        <p class="mg-editorial-row__body">{{ __('magnoolia.page.asukoht.shop_body') }}</p>
        <div style="display:flex;flex-wrap:wrap;gap:8px;margin-top:16px;">
          @foreach(['IKEA', 'Selver', 'Decathlon', 'Kurna Park'] as $store)
          <span style="padding:6px 14px;background:#fff;border-radius:20px;font-size:12px;font-weight:600;color:#1d2430;border:1px solid #e5e0d8;">{{ $store }}</span>
          @endforeach
        </div>
      </div>
      <div class="mg-editorial-row__img">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;border-radius:12px;overflow:hidden;">
          @foreach([
            ['img'=>'kiili-selver.jpg',     'alt'=>'Kiili Selver — igapäevased ostud lähedal'],
            ['img'=>'kurna-park.jpg',       'alt'=>'Kurna Park kaubanduskeskus'],
            ['img'=>'ikea.jpg',             'alt'=>'IKEA — ligipääsetav autoga'],
            ['img'=>'kiili-decathlon.jpg',  'alt'=>'Decathlon Kiili piirkonnas'],
          ] as $f)
          @if(file_exists(public_path('assets/magnoolia/location/' . $f['img'])))
          <button type="button"
                  onclick="mgLightboxOpen('{{ asset('assets/magnoolia/location/' . $f['img']) }}','{{ addslashes($f['alt']) }}')"
                  style="display:block;padding:0;border:0;background:none;cursor:zoom-in;">
            <img src="{{ asset('assets/magnoolia/location/' . $f['img']) }}"
                 alt="{{ $f['alt'] }}"
                 width="220" height="160"
                 decoding="async" loading="lazy"
                 style="width:100%;height:140px;object-fit:cover;display:block;border-radius:6px;">
          </button>
          @endif
          @endforeach
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ── Phase 26: Nature, Sport & Active Life ──────────────── --}}
<section class="mg-page-section mg-page-section--white">
  <div class="container">
    <div class="mg-section-heading mg-section-heading--center" style="margin-bottom:40px;">
      <div class="mg-section-heading__eyebrow">{{ __('magnoolia.page.asukoht.sport_eyebrow') }}</div>
      <h2 class="mg-section-heading__title">{{ __('magnoolia.page.asukoht.sport_title') }}</h2>
    </div>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:12px;">
      @foreach([
        ['img'=>'kergliiklusteed.jpg',    'alt'=>__('magnoolia.page.asukoht.sport_alt_cycling')],
        ['img'=>'kiili-cycling.jpg',      'alt'=>__('magnoolia.page.asukoht.sport_alt_bike')],
        ['img'=>'kiili-spordihall.jpg',   'alt'=>__('magnoolia.page.asukoht.sport_alt_hall')],
        ['img'=>'kiili-loodus.jpg',       'alt'=>__('magnoolia.page.asukoht.sport_alt_nature')],
        ['img'=>'kiili-sunset.jpg',       'alt'=>__('magnoolia.page.asukoht.sport_alt_sunset')],
        ['img'=>'turvaline-keskkond.avif','alt'=>__('magnoolia.page.asukoht.sport_alt_community')],
      ] as $img)
      @if(file_exists(public_path('assets/magnoolia/location/' . $img['img'])))
      <button type="button"
              onclick="mgLightboxOpen('{{ asset('assets/magnoolia/location/' . $img['img']) }}','{{ addslashes($img['alt']) }}')"
              style="display:block;border-radius:10px;overflow:hidden;aspect-ratio:4/3;padding:0;border:0;background:none;cursor:zoom-in;width:100%;">
        <img src="{{ asset('assets/magnoolia/location/' . $img['img']) }}"
             alt="{{ $img['alt'] }}"
             width="280" height="210"
             decoding="async" loading="lazy"
             style="width:100%;height:100%;object-fit:cover;display:block;">
      </button>
      @endif
      @endforeach
    </div>
    <p style="font-size:11px;color:#bbb;margin-top:16px;text-align:center;">*{{ __('magnoolia.disclaimer.images') }}</p>
  </div>
</section>

{{-- ── Phase 26: Connection to Tallinn ────────────────────── --}}
<section class="mg-page-section mg-page-section--cream">
  <div class="container">
    <div class="mg-editorial-row">
      <div class="mg-editorial-row__img">
        @if(file_exists(public_path('assets/magnoolia/location/hea-uhendus-tallinnaga.avif')))
        <img src="{{ asset('assets/magnoolia/location/hea-uhendus-tallinnaga.avif') }}"
             alt="{{ __('magnoolia.page.asukoht.transport_img_alt') }}"
             width="580" height="380" loading="lazy" decoding="async"
             style="border-radius:12px;width:100%;height:100%;object-fit:cover;">
        @endif
      </div>
      <div class="mg-editorial-row__content">
        <div class="mg-editorial-row__kicker">{{ __('magnoolia.page.asukoht.transport_eyebrow') }}</div>
        <h2 class="mg-editorial-row__title">{{ __('magnoolia.page.asukoht.transport_title') }}</h2>
        <p class="mg-editorial-row__body">{{ __('magnoolia.page.asukoht.transport_body') }}</p>
        <p class="mg-editorial-row__body">{{ __('magnoolia.page.asukoht.transport_note') }}</p>
        <a href="https://maps.google.com/?q=Magnoolia+tee,Vaela,Kiili+vald"
           target="_blank" rel="noopener noreferrer"
           class="zoomvilla-btn"
           data-mg-analytics="magnoolia_location_map_open"
           style="margin-top:20px;">
          {{ __('magnoolia.page.asukoht.transport_cta') }}
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-left:6px;" aria-hidden="true"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
        </a>
      </div>
    </div>
  </div>
</section>

{{-- ── Neighbourhood editorial ──────────────────────────────── --}}
<section class="mg-page-section mg-page-section--white">
  <div class="container">
    <div class="mg-editorial-row">
      <div class="mg-editorial-row__img">
        <img src="{{ asset('assets/images/magnoolia/Magnoolia tee_ES_8.jpg') }}"
             alt="Vaela küla keskkond — Magnoolia ridaelamukodud"
             width="580" height="420" loading="lazy">
      </div>
      <div class="mg-editorial-row__content">
        <div class="mg-editorial-row__kicker">{{ __('magnoolia.page.asukoht.edit_kicker') }}</div>
        <h2 class="mg-editorial-row__title">{{ __('magnoolia.page.asukoht.edit_title') }}</h2>
        <p class="mg-editorial-row__body">
          {{ __('magnoolia.page.asukoht.edit_body1') }}
        </p>
        <p class="mg-editorial-row__body">
          {{ __('magnoolia.page.asukoht.edit_body2') }}
        </p>
        <a href="{{ lroute('magnoolia.contact') }}" class="zoomvilla-btn" style="margin-top:20px;">
          {{ __('magnoolia.page.asukoht.edit_cta') }} <i class="icon-angle-small-right"></i>
        </a>
      </div>
    </div>
  </div>
</section>

{{-- ── Answer Unit (AI-citable) ──────────────────────── --}}
@php
  $au = __('magnoolia.answer_unit.asukoht');
  $au['cta_route'] = lroute('magnoolia.contact');
@endphp
@include('sections.magnoolia.answer-unit', ['unit' => $au])

{{-- ── FAQ ─────────────────────────────────────────────────── --}}
@include('sections.magnoolia.page-faq', [
  'eyebrow' => __('magnoolia.page.asukoht.faq_eyebrow'),
  'title'   => __('magnoolia.page.asukoht.faq_title'),
  'bg'      => 'cream',
  'faqs'    => __('magnoolia.page.asukoht.faq_items'),
])

{{-- ── Internal links ──────────────────────────────────────── --}}
<section class="mg-page-section--cream mg-page-section--sm">
  <div class="container">
    <div class="mg-internal-links">
      <a href="{{ lroute('magnoolia.site-plan') }}" class="mg-internal-link"><i class="fas fa-map"></i> {{ __('magnoolia.page.asukoht.link_plan') }}</a>
      <a href="{{ lroute('magnoolia.homes') }}" class="mg-internal-link"><i class="fas fa-table"></i> {{ __('magnoolia.page.asukoht.link_homes') }}</a>
      <a href="{{ lroute('magnoolia.construction') }}" class="mg-internal-link"><i class="fas fa-hard-hat"></i> {{ __('magnoolia.page.asukoht.link_constr') }}</a>
      <a href="{{ lroute('magnoolia.contact') }}" class="mg-internal-link"><i class="fas fa-envelope"></i> {{ __('magnoolia.page.asukoht.link_cont') }}</a>
    </div>
  </div>
</section>

@include('sections.magnoolia.page-cta', [
  'title'   => __('magnoolia.page.asukoht.cta_title'),
  'sub'     => __('magnoolia.page.asukoht.cta_sub'),
  'buttons' => [
    ['label' => __('magnoolia.page.asukoht.cta_btn1'), 'url' => lroute('magnoolia.contact')],
    ['label' => __('magnoolia.page.asukoht.cta_btn2'), 'url' => lroute('magnoolia.homes'), 'outline' => true],
  ]
])

{{-- Lightbox --}}
<div id="mg-lightbox"
     onclick="this.style.display='none'"
     role="dialog" aria-modal="true" aria-label="Pilt suurendatult"
     style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,.88);align-items:center;justify-content:center;cursor:zoom-out;">
  <button type="button"
          onclick="event.stopPropagation();document.getElementById('mg-lightbox').style.display='none'"
          aria-label="Sulge"
          style="position:absolute;top:16px;right:20px;background:none;border:none;color:#fff;font-size:32px;line-height:1;cursor:pointer;padding:4px 10px;">&#x2715;</button>
  <div style="max-width:90vw;max-height:90vh;text-align:center;" onclick="event.stopPropagation()">
    <img id="mg-lightbox-img" src="data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" alt="" aria-hidden="true"
         style="max-width:90vw;max-height:82vh;border-radius:8px;object-fit:contain;display:block;margin:0 auto;">
    <div id="mg-lightbox-cap" style="color:#ddd;font-size:13px;margin-top:12px;"></div>
  </div>
</div>

@endsection

@push('scripts')
<script>
function mgLightboxOpen(src, alt) {
  var lb = document.getElementById('mg-lightbox');
  document.getElementById('mg-lightbox-img').src = src;
  document.getElementById('mg-lightbox-img').alt = alt;
  document.getElementById('mg-lightbox-cap').textContent = alt;
  lb.style.display = 'flex';
  document.body.style.overflow = 'hidden';
}
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') {
    var lb = document.getElementById('mg-lightbox');
    if (lb) { lb.style.display = 'none'; document.body.style.overflow = ''; }
  }
});
</script>
@endpush
