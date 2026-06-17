@extends('layouts.app')

@section('title', __('magnoolia.page.sisedisain.page_title'))
@section('meta_description', $page['description'] ?? '')
@section('og_title', $page['title'] ?? '')
@section('og_description', $page['description'] ?? '')

@section('content')
@php
  $base = rtrim(config('magnoolia.seo.canonical_base', 'https://magnoolia.ee'), '/');
  $interiorImages = [
    ['file' => 'Interior 1.jpg',   'alt' => 'Magnoolia kodu elutuba — siseviimistluse näidislahendus',    'label' => 'Elutuba'],
    ['file' => 'Interior 2.jpg',   'alt' => 'Magnoolia kodu avatud plaan — elutuba ja köök',              'label' => 'Elutuba / Köök'],
    ['file' => 'Interior 3.jpg',   'alt' => 'Magnoolia ridaelamukodu magamistuba',                         'label' => 'Magamistuba'],
    ['file' => 'Interior 4.jpg',   'alt' => 'Magnoolia kodu vannituba — viimistlusnäidis',                'label' => 'Vannituba'],
    ['file' => 'Interior 5-2.jpg', 'alt' => 'Magnoolia ridaelamukodu sisevaade',                          'label' => 'Sisevaade'],
    ['file' => 'Interior 5_1.jpg', 'alt' => 'Magnoolia ridaelamukodu detailvaade',                        'label' => 'Detailvaade'],
  ];
@endphp

<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@graph": [
    {
      "@@type": "BreadcrumbList",
      "itemListElement": [
        { "@@type": "ListItem", "position": 1, "name": "Avaleht", "item": "{{ $base }}" },
        { "@@type": "ListItem", "position": 2, "name": "Sisedisain", "item": "{{ $base }}/sisedisain" }
      ]
    },
    {
      "@@type": "FAQPage",
      "mainEntity": [
        {
          "@@type": "Question",
          "name": "Mis viimistluspakett on Magnoolia kodudes?",
          "acceptedAnswer": { "@@type": "Answer", "text": "Siseviimistluse täpsed valikud on kinnitamisel. Müügilepingus fikseeritakse konkreetne viimistluspakett." }
        },
        {
          "@@type": "Question",
          "name": "Kas siseviimistlust saab kohandada?",
          "acceptedAnswer": { "@@type": "Answer", "text": "Kohandamisvõimalused sõltuvad ehitusetapist ja kokkuleppest arendajaga. Küsige Diana käest hetkel kehtiva info kohta." }
        }
      ]
    }
  ]
}
</script>

{{-- ── Hero ─────────────────────────────────────────────────── --}}
<div class="mg-page-hero" style="background-image:linear-gradient(to right, rgba(29,36,48,.80) 55%, rgba(29,36,48,.4)), url('{{ asset('assets/images/magnoolia/Interior%201.jpg') }}');background-size:cover;background-position:center;">
  <div class="container">
    @include('partials.seo.breadcrumb', ['items' => [
      ['label' => __('magnoolia.nav.home'), 'url' => route('home')],
      ['label' => __('magnoolia.nav.interior')],
    ]])
    <div class="mg-page-hero__eyebrow">{{ __('magnoolia.page.sisedisain.eyebrow') }}</div>
    <h1 class="mg-page-hero__title">{{ __('magnoolia.page.sisedisain.page_h1') }}</h1>
    <p class="mg-page-hero__lead">
      {{ __('magnoolia.page.sisedisain.lead') }}
    </p>
    <p class="mg-page-hero__note">
      {{ __('magnoolia.page.sisedisain.note') }}
    </p>
    <div class="mg-page-hero__ctas">
      <a href="#sisepildid" class="zoomvilla-btn">{{ __('magnoolia.page.sisedisain.cta_view') }} <i class="icon-angle-small-right"></i></a>
      <a href="{{ lroute('magnoolia.contact') }}" class="zoomvilla-btn zoomvilla-btn--border">{{ __('magnoolia.page.sisedisain.cta_inquiry') }}</a>
    </div>
  </div>
</div>

{{-- ── Disclaimer ───────────────────────────────────────────── --}}
<section class="mg-page-section mg-page-section--warm">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-9">
        <div style="display:flex;gap:16px;align-items:flex-start;background:#fff;border-radius:12px;padding:24px;border-left:4px solid #c89443;">
          <i class="fas fa-info-circle" style="color:#c89443;font-size:20px;flex-shrink:0;margin-top:2px;"></i>
          <div>
            <div style="font-weight:700;color:#1d2430;margin-bottom:6px;">{{ __('magnoolia.page.sisedisain.disclaimer_title') }}</div>
            <p style="font-size:14px;color:#6f6a61;margin:0;line-height:1.6;">
              {{ __('magnoolia.page.sisedisain.disclaimer_body') }}
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ── Phase 26: Prestige pakett — sanitaarruumid ─────────── --}}
<section class="mg-page-section mg-page-section--white" id="prestige">
  <div class="container">
    <div class="mg-section-heading" style="margin-bottom:40px;">
      <div class="mg-section-heading__eyebrow">{{ __('magnoolia.page.sisedisain.prestige_eyebrow') }}</div>
      <h2 class="mg-section-heading__title">{{ __('magnoolia.page.sisedisain.prestige_title') }}</h2>
      <p class="mg-section-heading__subtitle">{{ __('magnoolia.page.sisedisain.prestige_sub') }}</p>
    </div>

    <div class="row gutter-y-32">

      {{-- Sanitaarruumid --}}
      <div class="col-lg-6">
        <div style="background:#f8f5f0;border-radius:16px;padding:28px;height:100%;border-left:4px solid #c89443;">
          <h3 style="font-size:16px;font-weight:700;color:#1d2430;margin:0 0 16px;display:flex;align-items:center;gap:10px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#c89443" stroke-width="2" aria-hidden="true"><path d="M4 12a8 8 0 0 1 16 0Z"/><path d="M2 12h20"/><path d="M4 12v8"/><path d="M20 12v8"/></svg>
            {{ __('magnoolia.page.sisedisain.sanitary_title') }}
          </h3>
          <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:8px;">
            @foreach([
              'RAK Resort rimless WC-pott',
              'Loputuskasti nupp SLIM — kroom või must',
              'Valamu Balteco Onyx 40',
              'Damixa Core valamusegisti',
              'ACO plaaditud dušširenn',
              'Dušiklaas alumiiniumraamis, ulatub laeni',
              'Damixa Core duššilift',
            ] as $item)
            <li style="display:flex;align-items:baseline;gap:10px;font-size:13.5px;color:#444;line-height:1.5;">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#c89443" stroke-width="2.5" style="flex-shrink:0;margin-top:2px;" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
              {{ $item }}
            </li>
            @endforeach
            <li style="display:flex;align-items:baseline;gap:10px;font-size:13px;color:#888;font-style:italic;line-height:1.5;">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#bbb" stroke-width="2.5" style="flex-shrink:0;margin-top:2px;" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
              {{ __('magnoolia.page.sisedisain.sanitary_extra') }}: Balteco Nova valamukapp
            </li>
          </ul>
        </div>
      </div>

      {{-- Materjalid ja pinnad --}}
      <div class="col-lg-6">
        <div style="background:#f8f5f0;border-radius:16px;padding:28px;height:100%;border-left:4px solid #c89443;">
          <h3 style="font-size:16px;font-weight:700;color:#1d2430;margin:0 0 16px;display:flex;align-items:center;gap:10px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#c89443" stroke-width="2" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 3v6"/></svg>
            {{ __('magnoolia.page.sisedisain.materials_title') }}
          </h3>
          <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:8px;">
            @foreach([
              'Milk Oak Rustic Light — põrandaparkett',
              'Ivory Oak Rustic Light — alternatiiv',
              'Tikkurila Symphony Opus II G497',
              'Tikkurila Symphony Opus II L497',
              'Ukselink BETA SLIM — must / harjatud kroom / grafiit',
              'Puitliist 65×15, värvitud',
              'Jung LS 990 mattvalge',
              'Põrandakütte display',
              'Korteri fonotelefon (video intercom)',
              'IP20 / IP65 kohtvalgustid',
            ] as $item)
            <li style="display:flex;align-items:baseline;gap:10px;font-size:13.5px;color:#444;line-height:1.5;">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#c89443" stroke-width="2.5" style="flex-shrink:0;margin-top:2px;" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
              {{ $item }}
            </li>
            @endforeach
          </ul>
        </div>
      </div>

    </div>

    {{-- Developer replacement disclaimer --}}
    <div style="margin-top:24px;padding:14px 20px;background:#f0ede8;border-radius:8px;font-size:12px;color:#888;font-style:italic;">
      {{ __('magnoolia.page.sisedisain.replacement_disclaimer') }}
    </div>
  </div>
</section>

{{-- ── Phase 26: Aet Piel — personaalne sisekujundus ──────── --}}
<section class="mg-page-section mg-page-section--cream">
  <div class="container">
    <div style="max-width:760px;margin:0 auto;background:#fff;border-radius:16px;padding:32px 36px;border:1px solid rgba(29,36,48,.08);display:flex;align-items:flex-start;gap:24px;flex-wrap:wrap;">
      <div style="flex:1;min-width:240px;">
        <div style="font-size:11px;color:#c89443;font-weight:700;letter-spacing:.1em;text-transform:uppercase;margin-bottom:12px;">{{ __('magnoolia.page.sisedisain.aet_eyebrow') }}</div>
        <div style="font-size:24px;font-weight:700;color:#1d2430;line-height:1.2;margin-bottom:4px;">Aet Piel</div>
        <div style="font-size:13px;color:#888;margin-bottom:14px;">{{ __('magnoolia.page.sisedisain.aet_role') }}</div>
        <p style="font-size:14px;color:#6f6a61;line-height:1.6;margin:0 0 16px;">{{ __('magnoolia.page.sisedisain.aet_body') }}</p>
        <div style="display:flex;flex-direction:column;gap:6px;">
          <a href="tel:+3725553858"
             data-mg-analytics="magnoolia_phone_click"
             style="font-size:15px;font-weight:700;color:#1d2430;text-decoration:none;display:flex;align-items:center;gap:8px;">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#c89443" stroke-width="2" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 13a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 9.57a16 16 0 0 0 6.29 6.29l.94-.94a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
            +372 555 38 586
          </a>
          <a href="mailto:aet@piel.ee"
             data-mg-analytics="magnoolia_email_click"
             style="font-size:14px;color:#c89443;text-decoration:none;display:flex;align-items:center;gap:8px;">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
            aet@piel.ee
          </a>
          <a href="https://www.aetpiel.com" target="_blank" rel="noopener noreferrer"
             style="font-size:12px;color:#aaa;text-decoration:none;">www.aetpiel.com</a>
        </div>
      </div>
      <div style="flex-shrink:0;">
        <a href="https://www.aetpiel.com" target="_blank" rel="noopener noreferrer"
           aria-label="Aet Piel — www.aetpiel.com" data-mg-analytics="magnoolia_aetpiel_click"
           style="text-decoration:none;display:inline-block;">
          @if(file_exists(public_path('assets/magnoolia/logos/aet-piel.png')))
          <img src="{{ asset('assets/magnoolia/logos/aet-piel.png') }}"
               alt="Aet Piel — sisekujundus"
               width="120" height="60"
               loading="lazy" decoding="async"
               style="max-width:120px;height:auto;">
          @else
          <span style="display:inline-block;padding:12px 20px;border:2px solid #c89443;border-radius:8px;font-size:16px;font-weight:700;color:#c89443;letter-spacing:.06em;white-space:nowrap;">
            AET PIEL →
          </span>
          @endif
        </a>
      </div>
    </div>
  </div>
</section>

{{-- ── Interior gallery grid ───────────────────────────────── --}}
<section class="mg-page-section mg-page-section--white" id="sisepildid">
  <div class="container">
    <div class="mg-section-heading" style="margin-bottom:40px;">
      <div class="mg-section-heading__eyebrow">{{ __('magnoolia.page.sisedisain.gallery_eyebrow') }}</div>
      <h2 class="mg-section-heading__title">{{ __('magnoolia.page.sisedisain.gallery_title') }}</h2>
    </div>

    <div class="mg-gallery-grid">
      @foreach($interiorImages as $i => $img)
        @php $exists = file_exists(public_path('assets/images/magnoolia/' . $img['file'])); @endphp
        @if($exists)
        <div class="mg-gallery-item {{ $i === 0 ? 'mg-gallery-item--wide' : '' }}"
             onclick="mgLightboxOpen('{{ asset('assets/images/magnoolia/' . $img['file']) }}', '{{ $img['alt'] }}')"
             style="cursor:pointer;">
          <img src="{{ asset('assets/images/magnoolia/' . $img['file']) }}"
               alt="{{ $img['alt'] }}"
               loading="lazy" width="600" height="420" style="width:100%;height:100%;object-fit:cover;">
          <div class="mg-gallery-item__caption">{{ $img['label'] }}</div>
        </div>
        @endif
      @endforeach
    </div>
  </div>
</section>

{{-- ── Room by room ─────────────────────────────────────────── --}}
<section class="mg-page-section mg-page-section--cream">
  <div class="container">
    <div class="mg-section-heading" style="margin-bottom:40px;">
      <div class="mg-section-heading__eyebrow">{{ __('magnoolia.page.sisedisain.rooms_eyebrow') }}</div>
      <h2 class="mg-section-heading__title">{{ __('magnoolia.page.sisedisain.rooms_title') }}</h2>
    </div>

    <div class="row gutter-y-32">
      @foreach(__('magnoolia.page.sisedisain.rooms') as $room)
      <div class="col-lg-3 col-md-6">
        <div style="background:#fff;border-radius:16px;padding:28px;height:100%;border:1px solid rgba(29,36,48,.06);">
          <div style="width:44px;height:44px;background:rgba(200,148,67,.1);border-radius:10px;display:flex;align-items:center;justify-content:center;margin-bottom:16px;">
            <i class="{{ $room['icon'] }}" style="color:#c89443;font-size:18px;"></i>
          </div>
          <div style="font-size:16px;font-weight:700;color:#1d2430;margin-bottom:10px;">{{ $room['room'] }}</div>
          <p style="font-size:13px;color:#6f6a61;line-height:1.6;margin-bottom:12px;">{{ $room['content'] }}</p>
          <p style="font-size:12px;color:#aaa;font-style:italic;margin:0;">{{ $room['note'] }}</p>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>

{{-- ── Phase 28: PPTX-sourced materials gallery ────────────── --}}
@php
  $pptxDir = 'assets/magnoolia/sisedisain/pptx/Magnoolia__kodud_Prestige_Sisedisain/webp';
  $pptxBase = public_path($pptxDir);
  $pptxImages = [];
  if (is_dir($pptxBase)) {
    $alts = [
      'image1.webp'  => 'Magnoolia Prestige siseviimistlus — vannitoa näidislahendus',
      'image2.webp'  => 'Magnoolia Prestige — sanitaarruum ja viimistlusdetailid',
      'image3.webp'  => 'Magnoolia Prestige — plaadilahendus vannitoas',
      'image4.webp'  => 'Magnoolia Prestige — duširuumi viimistlus',
      'image5.webp'  => 'Magnoolia Prestige — köögimöbel ja pinnad',
      'image6.webp'  => 'Magnoolia Prestige — elutoa viimistlus',
      'image9.webp'  => 'Magnoolia Prestige — valgustus ja elektritarvikud',
      'image14.webp' => 'Magnoolia Prestige — põrandakatted',
      'image15.webp' => 'Magnoolia Prestige — materjalidetailid',
      'image16.webp' => 'Magnoolia Prestige — sisearhitektuurne lahendus',
    ];
    foreach ($alts as $fname => $alt) {
      if (file_exists($pptxBase . '/' . $fname)) {
        $pptxImages[] = ['file' => $fname, 'alt' => $alt, 'url' => asset($pptxDir . '/' . $fname)];
      }
    }
  }
@endphp

@if(count($pptxImages) > 0)
<section class="mg-page-section mg-page-section--white" id="pptx-gallery">
  <div class="container">
    <div class="mg-section-heading" style="margin-bottom:40px;">
      <div class="mg-section-heading__eyebrow">{{ __('magnoolia.page.sisedisain.gallery_eyebrow') }}</div>
      <h2 class="mg-section-heading__title">
        {{ app()->getLocale()==='ru' ? 'Примеры материалов и отделки — пакет Prestige' : (app()->getLocale()==='en' ? 'Material & finish samples — Prestige package' : 'Materjalid ja viimistlusnäidised — Prestige pakett') }}
      </h2>
      <p class="mg-section-heading__subtitle" style="font-style:italic;font-size:13px;color:#9a9490;">
        {{ app()->getLocale()==='ru' ? 'Изображения носят иллюстративный характер. Точные характеристики уточняются в договоре.' : (app()->getLocale()==='en' ? 'Images are illustrative. Exact specifications are confirmed in the sales contract.' : 'Pildid on illustratiivsed. Täpsed karakteristikud kinnitatakse müügilepingus.') }}
      </p>
    </div>

    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:16px;">
      @foreach($pptxImages as $i => $img)
      <div style="border-radius:12px;overflow:hidden;aspect-ratio:4/3;cursor:pointer;background:#f0ede8;"
           onclick="mgLightboxOpen('{{ $img['url'] }}', '{{ addslashes($img['alt']) }}')">
        <img src="{{ $img['url'] }}"
             alt="{{ $img['alt'] }}"
             width="560" height="420"
             loading="{{ $i < 4 ? 'eager' : 'lazy' }}"
             decoding="async"
             style="width:100%;height:100%;object-fit:cover;transition:transform .3s;"
             onmouseover="this.style.transform='scale(1.03)'"
             onmouseout="this.style.transform='scale(1)'">
      </div>
      @endforeach
    </div>

    <div style="margin-top:24px;padding:14px 20px;background:#f0ede8;border-radius:8px;font-size:12px;color:#888;font-style:italic;text-align:center;">
      {{ app()->getLocale()==='ru' ? 'Siseviimistluse ja lisavalikute täpsed tingimused kinnitatakse müügipakkumises.' : (app()->getLocale()==='en' ? 'Exact interior finish conditions are confirmed in the sales offer.' : 'Siseviimistluse ja lisavalikute täpsed tingimused kinnitatakse müügipakkumises.') }}
    </div>
  </div>
</section>
@endif

{{-- ── FAQ ─────────────────────────────────────────────────── --}}
@include('sections.magnoolia.page-faq', [
  'eyebrow' => __('magnoolia.page.sisedisain.faq_eyebrow'),
  'title'   => __('magnoolia.page.sisedisain.faq_title'),
  'bg'      => 'warm',
  'faqs'    => __('magnoolia.page.sisedisain.faq_items'),
])

{{-- ── Internal links ──────────────────────────────────────── --}}
<section class="mg-page-section--cream mg-page-section--sm">
  <div class="container">
    <div class="mg-internal-links">
      <a href="{{ lroute('magnoolia.arhitektuur') }}" class="mg-internal-link"><i class="fas fa-building"></i> {{ __('magnoolia.page.sisedisain.link_arch') }}</a>
      <a href="{{ lroute('magnoolia.galerii') }}" class="mg-internal-link"><i class="fas fa-images"></i> {{ __('magnoolia.page.sisedisain.link_gallery') }}</a>
      <a href="{{ lroute('magnoolia.homes') }}" class="mg-internal-link"><i class="fas fa-table"></i> {{ __('magnoolia.page.sisedisain.link_homes') }}</a>
      <a href="{{ lroute('magnoolia.contact') }}" class="mg-internal-link"><i class="fas fa-envelope"></i> {{ __('magnoolia.page.sisedisain.link_cont') }}</a>
    </div>
  </div>
</section>

@include('sections.magnoolia.page-cta', [
  'title'   => __('magnoolia.page.sisedisain.cta_title'),
  'sub'     => __('magnoolia.page.sisedisain.cta_sub'),
  'buttons' => [
    ['label' => __('magnoolia.page.sisedisain.cta_btn1'), 'url' => lroute('magnoolia.contact')],
    ['label' => __('magnoolia.page.sisedisain.cta_btn2'), 'url' => lroute('magnoolia.galerii'), 'outline' => true],
  ]
])

{{-- Lightbox --}}
<div id="mg-lightbox" onclick="this.style.display='none'" style="display:none;">
  <div class="mg-lightbox__inner">
    <img id="mg-lightbox-img" src="data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" alt="" aria-hidden="true">
    <div id="mg-lightbox-cap"></div>
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
}
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') { var lb = document.getElementById('mg-lightbox'); if(lb) lb.style.display='none'; }
});
</script>
@endpush
