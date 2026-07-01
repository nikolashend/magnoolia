{{--
  Phase 34.3 location hub — /asukoht/vaela-kula
  Hyper-local hub for "Vaela küla" (Kiili vald, Harjumaa). Self-contained, dev-managed, ET, indexable.
  Facts used are verified from the brief only (no invented numbers/claims/distances).
--}}
@extends('layouts.app')

@section('title', 'Vaela küla | Kodukoht Kiili vallas Tallinna lähedal — Magnoolia')
@section('meta_description', 'Vaela küla on rahulik kodukoht Kiili vallas, Tallinna lähedal. Magnoolia teel valmib 19 A-energiaklassi ridaelamukodu — uus Vaela lasteaed, terviserada ning IKEA, Selver ja Decathlon on lähedal.')

@section('content')
@php
  $base = rtrim(config('magnoolia.seo.canonical_base', 'https://magnoolia.ee'), '/');
  $url  = $base . '/asukoht/vaela-kula';

  // Single source of truth for the visible FAQ AND the FAQPage JSON-LD (cannot diverge).
  $faqs = [
    ['q' => 'Kus asub Vaela küla?',
     'a' => 'Vaela küla asub Kiili vallas Harjumaal, Tallinna lähedal. Magnoolia ridaelamukodud valmivad Vaela külas Magnoolia teel, rahulikus ja rohelises elukeskkonnas.'],
    ['q' => 'Kui kaugel on Tallinn Vaela külast?',
     'a' => 'Vaela küla asub Tallinna lähedal — autoga ligikaudu 15–20 minutit ja ühistranspordiga ligikaudu 25–35 minutit, sõltuvalt marsruudist ja liiklusest.'],
    ['q' => 'Kas Vaela külas on lasteaed?',
     'a' => 'Jah. Kiili Lasteaial on rühmad nii Kiili keskuses kui ka Vaela külas, mistõttu lasteaiakoht on Magnoolia kodudest jalutuskäigu kaugusel.'],
    ['q' => 'Millised poed ja teenused on Vaela küla lähedal?',
     'a' => 'Vaela küla lähedal on IKEA, Selver, Decathlon (esimene Eestis) ja Kurna Park, samuti Kiili keskuse kauplused. Igapäevased ostud ja vaba aja veetmine on mugavalt käeulatuses.'],
    ['q' => 'Kas Vaela külas saab looduses liikuda?',
     'a' => 'Jah. Vaela terviserada ja valgustatud kergliiklusteede võrgustik võimaldavad jalutamist, jooksmist ja jalgrattasõitu otse kodu lähedalt.'],
    ['q' => 'Millal Magnoolia kodud Vaela külas valmivad?',
     'a' => 'Ehitus toimub etappidena ja I etapp valmib 2027. Täpne ajakava ning saadaolevad kodud ja hinnad leiab lehelt „Kodud ja hinnad“.'],
  ];
@endphp

<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@graph": [
    {
      "@@type": "WebPage",
      "@@id": "{{ $url }}#webpage",
      "url": "{{ $url }}",
      "name": @json('Vaela küla — kodukoht Kiili vallas, Tallinna lähedal'),
      "description": @json('Vaela küla Kiili vallas on rahulik kodukoht Tallinna lähedal, kus Magnoolia teel valmib 19 A-energiaklassi ridaelamukodu.'),
      "inLanguage": "et-EE",
      "isPartOf": { "@@id": "{{ $base }}/#website" },
      "about": { "@@id": "{{ $base }}/#organization" }
    },
    {
      "@@type": "FAQPage",
      "@@id": "{{ $url }}#faq",
      "mainEntity": [
        @foreach($faqs as $i => $f)
        {
          "@@type": "Question",
          "name": @json($f['q']),
          "acceptedAnswer": { "@@type": "Answer", "text": @json($f['a']) }
        }@if(!$loop->last),@endif
        @endforeach
      ]
    }
  ]
}
</script>

{{-- ── Hero (CTA above fold) ─────────────────────────────────── --}}
<div class="mg-page-hero" style="background-image:linear-gradient(to right, rgba(29,36,48,.9) 55%, rgba(29,36,48,.5)), url('{{ asset('assets/images/magnoolia/Cam001.0000-1600w.webp') }}');background-size:cover;background-position:center;">
  <div class="container">
    @include('partials.seo.breadcrumb', ['items' => [
      ['label' => 'Avaleht', 'url' => route('home')],
      ['label' => 'Asukoht', 'url' => lroute('magnoolia.location')],
      ['label' => 'Vaela küla'],
    ]])
    <div class="mg-page-hero__eyebrow">Asukoht · Vaela küla, Kiili vald, Harjumaa</div>
    <h1 class="mg-page-hero__title">Vaela küla — rahulik kodukoht Kiili vallas, Tallinna lähedal</h1>
    <p class="mg-page-hero__lead">Vaela küla on roheline ja privaatne elukeskkond Kiili vallas. Magnoolia teel valmib 19 A-energiaklassi ridaelamukodu — jalutuskäigu kaugusel on uus Vaela lasteaed ja terviserada ning IKEA, Selver ja Decathlon on käeulatuses.</p>
    <p class="mg-page-hero__note">Vaela küla · Kiili vald · Harjumaa · autoga ligikaudu 15–20 min Tallinnast (sõltuvalt liiklusest)</p>
    <div class="mg-page-hero__ctas">
      <a href="{{ lroute('magnoolia.homes') }}" class="zoomvilla-btn" data-mg-analytics="magnoolia_cta_click">Vaata kodusid ja hindu <i class="icon-angle-small-right"></i></a>
      <a href="{{ lroute('magnoolia.contact') }}" class="zoomvilla-btn zoomvilla-btn--border" data-mg-inquiry-open data-source-component="hub_vaela-kula_hero" data-mg-analytics="magnoolia_cta_click">Küsi pakkumist</a>
    </div>
  </div>
</div>

{{-- ── Lühidalt (AEO answer block) ───────────────────────────── --}}
<section class="mg-page-section mg-page-section--warm">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-9">
        <div style="background:#fff;border-radius:16px;padding:30px 34px;border-left:4px solid #c89443;">
          <div style="font-size:11px;color:#c89443;font-weight:700;letter-spacing:.12em;text-transform:uppercase;margin-bottom:10px;">Lühidalt</div>
          <p style="font-size:16px;color:#3a3530;line-height:1.75;margin:0;">Vaela küla on rahulik kodukoht Kiili vallas Harjumaal, Tallinna lähedal. Magnoolia teel valmib 19 A-energiaklassi ridaelamukodu — 4–5-toalist (ligikaudu 129 m²) privaatse hooviala, terrassi ja rõduga. Vahetus ümbruses on uus Vaela lasteaed, Vaela terviserada ja kergliiklusteed; veidi kaugemal IKEA, Selver, Decathlon ja Kurna Park. Autoga jõuab Tallinna ligikaudu 15–20 minutiga (sõltuvalt liiklusest).</p>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ── Elu Vaela külas (feature cards) ───────────────────────── --}}
<section class="mg-page-section mg-page-section--white">
  <div class="container">
    <div class="mg-section-heading" style="margin-bottom:36px;">
      <div class="mg-section-heading__eyebrow">Elu Vaela külas</div>
      <h2 class="mg-section-heading__title">Rahulik küla, mugav vahetu ümbrus</h2>
    </div>
    <div class="row gutter-y-28">
      @php
        $feat = [
          ['icon' => 'fas fa-child',         'title' => 'Lasteaed lähedal',    'body' => 'Kiili Lasteaia rühmad asuvad ka Vaela külas — lasteaiakoht on Magnoolia kodudest jalutuskäigu kaugusel.'],
          ['icon' => 'fas fa-running',       'title' => 'Vaela terviserada',   'body' => 'Jalutamiseks, jooksmiseks ja jalgrattasõiduks sobiv terviserada algab otse küla ümbrusest.'],
          ['icon' => 'fas fa-shopping-cart', 'title' => 'Poed käeulatuses',    'body' => 'IKEA, Selver ja Decathlon (esimene Eestis) ning Kurna Park on Vaela küla lähedal — igapäevased ostud on mugavad.'],
          ['icon' => 'fas fa-seedling',      'title' => 'Roheline keskkond',   'body' => 'Madal hoonestustihedus ja privaatne hooviala iga kodu juures annavad küla rahu ja looduse tunde.'],
          ['icon' => 'fas fa-road',          'title' => 'Magnoolia tee',       'body' => 'Kodud valmivad Vaela külas Magnoolia teel — rahulikus tänavaruumis, kuid heade ühendustega.'],
          ['icon' => 'fas fa-map-marker-alt','title' => 'Tallinna lähedal',    'body' => 'Autoga ligikaudu 15–20 min ja ühistranspordiga ligikaudu 25–35 min Tallinna (sõltuvalt marsruudist).'],
        ];
      @endphp
      @foreach($feat as $f)
      <div class="col-lg-4 col-md-6">
        <div style="background:#f8f5f0;border-radius:16px;padding:28px;height:100%;">
          <div style="width:46px;height:46px;background:rgba(200,148,67,.15);border-radius:11px;display:flex;align-items:center;justify-content:center;margin-bottom:16px;">
            <i class="{{ $f['icon'] }}" style="color:#c89443;font-size:18px;"></i>
          </div>
          <div style="font-size:17px;font-weight:700;color:#1d2430;margin-bottom:8px;">{{ $f['title'] }}</div>
          <p style="font-size:14px;color:#6f6a61;line-height:1.65;margin:0;">{{ $f['body'] }}</p>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>

{{-- ── Vahetu ümbrus (page-specific local section) ───────────── --}}
<section class="mg-page-section mg-page-section--cream">
  <div class="container">
    <div class="mg-section-heading" style="margin-bottom:32px;">
      <div class="mg-section-heading__eyebrow">Vahetu ümbrus</div>
      <h2 class="mg-section-heading__title">Mis on Vaela küla lähedal</h2>
    </div>
    <div class="row gutter-y-16">
      @foreach([
        'Uus Vaela lasteaed — Kiili Lasteaia rühmad jalutuskäigu kaugusel',
        'Vaela terviserada ja valgustatud kergliiklusteed',
        'IKEA, Selver ja Decathlon (esimene Eestis) lähedal',
        'Kurna Park ja Kiili keskuse kauplused',
        'Kiili Gümnaasium ning Kiili Kunstide Kool',
        'Head ühendused Viljandi maantee ja Tallinna ringtee sõlmede kaudu',
      ] as $point)
      <div class="col-lg-6">
        <div style="display:flex;gap:12px;align-items:flex-start;background:#fff;border-radius:12px;padding:18px 20px;">
          <i class="fas fa-check" style="color:#c89443;margin-top:3px;flex-shrink:0;"></i>
          <span style="font-size:15px;color:#3a3530;line-height:1.55;">{{ $point }}</span>
        </div>
      </div>
      @endforeach
    </div>
    <p style="font-size:13px;color:#9a9490;line-height:1.6;margin:22px 0 0;">Sõiduajad on ligikaudsed ja sõltuvad marsruudist ning liiklusest.</p>
  </div>
</section>

{{-- ── Kodud Vaela külas (teaser → BOFU) ─────────────────────── --}}
<section class="mg-page-section mg-page-section--white">
  <div class="container">
    <div class="row align-items-center gutter-y-28">
      <div class="col-lg-7">
        <div class="mg-section-heading" style="margin-bottom:14px;">
          <div class="mg-section-heading__eyebrow">Kodud Vaela külas</div>
          <h2 class="mg-section-heading__title">Magnoolia ridaelamud Magnoolia teel</h2>
        </div>
        <p style="font-size:15px;color:#6f6a61;line-height:1.75;margin:0 0 18px;">Vaela külas valmib 19 A-energiaklassi ridaelamukodu — 4–5-toalist privaatse hooviala, terrassi ja rõduga ning oma parkimisega. Maasoojuspump, soojustagastusega ventilatsioon ja põrandaküte hoiavad ülalpidamiskulud madalad. Vaata plaane, pindasid ja hindu ühelt lehelt.</p>
        <a href="{{ lroute('magnoolia.homes') }}" class="zoomvilla-btn" data-mg-analytics="magnoolia_cta_click">Vaata kodusid ja hindu <i class="icon-angle-small-right"></i></a>
      </div>
      <div class="col-lg-5">
        <a href="{{ lroute('magnoolia.homes') }}" style="display:block;border-radius:16px;overflow:hidden;text-decoration:none;">
          <img {!! mg_img('magnoolia_cam09.jpg', '(max-width:991px) 100vw, 460px') !!} alt="Magnoolia ridaelamud Vaela külas Magnoolia teel" loading="lazy" decoding="async" style="width:100%;height:auto;display:block;border-radius:16px;">
        </a>
      </div>
    </div>
  </div>
</section>

{{-- ── Kellele sobib / Mida ostja teada saab / Järgmine samm ─── --}}
<section class="mg-page-section mg-page-section--warm">
  <div class="container">
    <div class="row gutter-y-28">
      @php
        $aeo = [
          ['t' => 'Kellele sobib?', 'b' => 'Peredele, kes soovivad rahulikku ja rohelist kodukohta Tallinna lähedal, kuid ei taha loobuda mugavast igapäevaümbrusest. Vaela küla sobib eriti neile, kelle jaoks on olulised lasteaed ja terviserada jalutuskäigu kaugusel.'],
          ['t' => 'Mida ostja teada saab?', 'b' => 'Vaela küla asukoha ja vahetu ümbruse — lasteaed, terviserada, poed ja ühendused Tallinnaga — ning Magnoolia kodude plaanid, pinnad, energialahenduse ja kehtiva hinnakirja lehelt „Kodud ja hinnad“.'],
          ['t' => 'Mis on järgmine samm?', 'b' => 'Vaata saadaolevaid kodusid ja hindu, tutvu asukohaga ning küsi personaalset pakkumist. Müügikonsultant Diana aitab leida sobiva kodu Vaela külas.'],
        ];
      @endphp
      @foreach($aeo as $a)
      <div class="col-lg-4">
        <div style="background:#fff;border-radius:16px;padding:28px;height:100%;">
          <div style="font-size:17px;font-weight:700;color:#1d2430;margin-bottom:10px;">{{ $a['t'] }}</div>
          <p style="font-size:14px;color:#6f6a61;line-height:1.7;margin:0;">{{ $a['b'] }}</p>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>

{{-- ── FAQ (visible + microdata; JSON-LD above is built from same $faqs) ── --}}
@include('sections.magnoolia.page-faq', [
  'eyebrow' => 'KKK',
  'title'   => 'Korduma kippuvad küsimused',
  'bg'      => 'white',
  'faqs'    => $faqs,
])

{{-- ── Internal links ────────────────────────────────────────── --}}
<section class="mg-page-section--cream mg-page-section--sm">
  <div class="container">
    <div class="mg-internal-links">
      <a href="{{ lroute('magnoolia.homes') }}" class="mg-internal-link"><i class="fas fa-table"></i> Kodud ja hinnad</a>
      <a href="{{ lroute('magnoolia.location') }}" class="mg-internal-link"><i class="fas fa-map-marker-alt"></i> Asukoht</a>
      <a href="{{ route('magnoolia.lp.ridaelamu-vaela-kula') }}" class="mg-internal-link"><i class="fas fa-home"></i> Ridaelamu Vaela külas</a>
      <a href="{{ route('magnoolia.hub.kiili-vald') }}" class="mg-internal-link"><i class="fas fa-map"></i> Kiili vald</a>
      <a href="{{ route('magnoolia.hub.tallinna-lahedal') }}" class="mg-internal-link"><i class="fas fa-location-arrow"></i> Tallinna lähedal</a>
      <a href="{{ lroute('magnoolia.contact') }}" class="mg-internal-link"><i class="fas fa-envelope"></i> Kontakt</a>
    </div>
  </div>
</section>

@include('sections.magnoolia.page-cta', [
  'title'   => 'Tutvu koduga Vaela külas',
  'sub'     => 'Vaata saadaolevaid kodusid ja küsi personaalset pakkumist.',
  'buttons' => [
    ['label' => 'Vaata kodusid ja hindu', 'url' => lroute('magnoolia.homes')],
    ['label' => 'Küsi pakkumist', 'url' => lroute('magnoolia.contact'), 'outline' => true, 'inquiry' => true, 'source' => 'hub_vaela-kula_cta'],
  ]
])

@endsection
