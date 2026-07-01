{{--
  SEO/Ads landing — /uusarendus-kiili
  Primary keyword: "uusarendus Kiili". Self-contained, dev-managed, ET, indexable.
  Facts used are verified from the live site only (no invented numbers/claims).
--}}
@extends('layouts.app')

@section('title', 'Uusarendus Kiili vallas | Magnoolia ridaelamukodud Vaela külas')
@section('meta_description', 'Magnoolia on uusarendus Kiili vallas — 19 A-energiaklassi ridaelamukodu Vaela külas Harjumaal, privaatse hoovi ja terrassiga, Tallinna lähedal. Vaata plaane ja hindu.')

@section('content')
@php
  $base = rtrim(config('magnoolia.seo.canonical_base', 'https://magnoolia.ee'), '/');
  $url  = $base . '/uusarendus-kiili';

  // Single source of truth for the visible FAQ AND the FAQPage JSON-LD (cannot diverge).
  $faqs = [
    ['q' => 'Kus Magnoolia uusarendus Kiili vallas asub?',
     'a' => 'Magnoolia asub Vaela külas, Kiili vallas Harjumaal, aadressiga Magnoolia tee. Piirkond ühendab rahuliku külakeskkonna mugava ligipääsuga Tallinnale ja Kiili aleviku teenustele.'],
    ['q' => 'Miks valida kodu just Kiili vallas?',
     'a' => 'Kiili vald pakub vaikset ja looduslähedast elukeskkonda pere jaoks, samas jäävad Tallinn ning igapäevased teenused käeulatusse. Vaela küla sobib neile, kes soovivad ruumi ja privaatsust linna lähedal.'],
    ['q' => 'Kui kaugel on Tallinn Vaela külast?',
     'a' => 'Vaela küla asub Tallinna lähedal — sõiduaeg on ligikaudu 20 minutit, sõltuvalt marsruudist ja liiklusest. Täpne aeg oleneb sihtkohast ja kellaajast.'],
    ['q' => 'Millised kodud uusarenduses on?',
     'a' => 'Magnoolia koosneb 19-st A-energiaklassi ridaelamukodust. Kodud on 4–5-toalised, netopinnaga ligikaudu 129 m² (kuni ~143 m²), privaatse hooviala, terrassi ja rõduga.'],
    ['q' => 'Millal uusarendus valmib?',
     'a' => 'Ehitus toimub etappidena. I etapp valmib 2027. Järgmiste etappide täpne ajakava täpsustatakse müügikonsultandiga.'],
    ['q' => 'Kust näen Kiili uusarenduse hindu?',
     'a' => 'I etapi kodude hinnad on avalikud hinnakirjas, II etapi hinnad täpsustatakse. Kehtivad hinnad ja saadavuse leiad lehelt „Kodud ja hinnad“.'],
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
      "name": @json('Uusarendus Kiili vallas — Magnoolia ridaelamukodud Vaela külas'),
      "description": @json('A-energiaklassi ridaelamukodude uusarendus Vaela külas, Kiili vallas Harjumaal — privaatse hoovi ja terrassiga, Tallinna lähedal.'),
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
      ['label' => 'Uusarendus Kiili vallas'],
    ]])
    <div class="mg-page-hero__eyebrow">Uusarendus · Vaela küla, Kiili vald</div>
    <h1 class="mg-page-hero__title">Uusarendus Kiili vallas — Magnoolia ridaelamukodud Vaela külas</h1>
    <p class="mg-page-hero__lead">19 A-energiaklassi ridaelamukodu rahulikus Vaela külas, Kiili vallas Harjumaal. 4–5 tuba, privaatne hooviala, terrass ja rõdu ning mugav ligipääs Tallinnale.</p>
    <p class="mg-page-hero__note">Vaela küla · Kiili vald · Harjumaa · ligikaudu 20 min Tallinnast (sõltuvalt liiklusest)</p>
    <div class="mg-page-hero__ctas">
      <a href="{{ lroute('magnoolia.homes') }}" class="zoomvilla-btn" data-mg-analytics="magnoolia_cta_click">Vaata kodusid ja hindu <i class="icon-angle-small-right"></i></a>
      <a href="{{ lroute('magnoolia.contact') }}" class="zoomvilla-btn zoomvilla-btn--border" data-mg-inquiry-open data-source-component="lp_uusarendus-kiili_hero" data-mg-analytics="magnoolia_cta_click">Küsi pakkumist</a>
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
          <p style="font-size:16px;color:#3a3530;line-height:1.75;margin:0;">Magnoolia on uusarendus Kiili vallas — 19 A-energiaklassi ridaelamukodu Vaela külas, Magnoolia teel. Kodud on 4–5-toalised (ligikaudu 129 m², kuni ~143 m²), privaatse hooviala, terrassi ja rõduga ning oma parkimisega. Maasoojuspump, soojustagastusega ventilatsioon ja põrandaküte hoiavad kulud kontrolli all. Piirkond ühendab looduslähedase külaelu Tallinna lähedusega — sõiduaeg on ligikaudu 20 minutit, sõltuvalt marsruudist ja liiklusest.</p>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ── Miks Kiili vald ja Vaela küla ─────────────────────────── --}}
<section class="mg-page-section mg-page-section--white">
  <div class="container">
    <div class="mg-section-heading" style="margin-bottom:36px;">
      <div class="mg-section-heading__eyebrow">Miks Kiili vald</div>
      <h2 class="mg-section-heading__title">Looduslähedane külaelu Tallinna lähedal</h2>
    </div>
    <div class="row gutter-y-28">
      @php
        $feat = [
          ['icon' => 'fas fa-tree',          'title' => 'Rahulik keskkond',    'body' => 'Vaela küla pakub vaikset ja looduslähedast miljööd — vähem müra ja kiirustamist, rohkem ruumi hingata.'],
          ['icon' => 'fas fa-map-marker-alt','title' => 'Tallinna lähedus',     'body' => 'Linn ja töökohad jäävad käeulatusse — sõiduaeg ligikaudu 20 min, sõltuvalt marsruudist ja liiklusest.'],
          ['icon' => 'fas fa-store',         'title' => 'Kohalikud teenused',   'body' => 'Kiili alevik teenustega on lähedal, nii et igapäevased toimetused ei nõua alati linna sõitu.'],
          ['icon' => 'fas fa-bolt',          'title' => 'A-energiaklass',       'body' => 'Maasoojuspump, soojustagastusega ventilatsioon ja põrandaküte — energiatõhus uusehitis madalamate kuludega.'],
          ['icon' => 'fas fa-seedling',      'title' => 'Privaatne hooviala',   'body' => 'Igal kodul oma hoov, terrass ja rõdu — väliruum lastele, aiapidamiseks ja puhkuseks.'],
          ['icon' => 'fas fa-home',          'title' => '19 kodu uusarendus',   'body' => 'Läbimõeldud, madala hoonestustihedusega arendus, mitte suur korterelamu — naabruskonna tunne.'],
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

{{-- ── Perele: igapäevaelu Vaelas (page-specific angle) ──────── --}}
<section class="mg-page-section mg-page-section--cream">
  <div class="container">
    <div class="mg-section-heading" style="margin-bottom:32px;">
      <div class="mg-section-heading__eyebrow">Perele</div>
      <h2 class="mg-section-heading__title">Kodu, mis kasvab koos perega</h2>
    </div>
    <div class="row gutter-y-16">
      @foreach([
        'Oma hoov ja terrass lastele ning lemmikloomadele mängimiseks',
        'Ruumi 4–5 tuba — eraldi magamistoad ja koht kodukontorile',
        'Looduslähedane ümbrus igapäevasteks jalutuskäikudeks',
        'Kiili aleviku teenused lähedal, ilma pidevalt linna sõitmata',
        'Vaikne naabruskond madala hoonestustihedusega',
        'Mugav ligipääs Tallinnale tööl ja koolides käimiseks',
      ] as $point)
      <div class="col-lg-6">
        <div style="display:flex;gap:12px;align-items:flex-start;background:#fff;border-radius:12px;padding:18px 20px;">
          <i class="fas fa-check" style="color:#c89443;margin-top:3px;flex-shrink:0;"></i>
          <span style="font-size:15px;color:#3a3530;line-height:1.55;">{{ $point }}</span>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>

{{-- ── Ühendus ja igapäevaelu (unique angle) ─────────────────── --}}
<section class="mg-page-section mg-page-section--white">
  <div class="container">
    <div class="mg-section-heading" style="margin-bottom:24px;">
      <div class="mg-section-heading__eyebrow">Ühendus ja igapäev</div>
      <h2 class="mg-section-heading__title">Vaikne kodu, linn käeulatuses</h2>
    </div>
    <div class="row justify-content-center">
      <div class="col-lg-10">
        <p style="font-size:15px;color:#6f6a61;line-height:1.8;margin:0 0 14px;">Vaela küla eelis on tasakaal: hommikul ärkad rahulikus külakeskkonnas, kuid Tallinn ja selle töökohad, koolid ning teenused jäävad lähedale — sõiduaeg on ligikaudu 20 minutit, sõltuvalt marsruudist ja liiklusest. Kiili alevik pakub lähedal igapäevaseid teenuseid, nii et paljud toimetused saab ära ajada kodu lähedal.</p>
        <p style="font-size:15px;color:#6f6a61;line-height:1.8;margin:0;">Selline asukoht sobib eriti peredele ja kaugtöötajatele, kes soovivad rohkem ruumi ja privaatsust, kuid ei taha loobuda linna mugavustest. Täpse ülevaate asukohast ja ümbruskonnast leiad asukoha lehelt.</p>
      </div>
    </div>
  </div>
</section>

{{-- ── Kodud ja hinnad (teaser → BOFU) ───────────────────────── --}}
<section class="mg-page-section mg-page-section--warm">
  <div class="container">
    <div class="row align-items-center gutter-y-28">
      <div class="col-lg-7">
        <div class="mg-section-heading" style="margin-bottom:14px;">
          <div class="mg-section-heading__eyebrow">Kodud ja hinnad</div>
          <h2 class="mg-section-heading__title">19 kodu, etappidena valmiv arendus</h2>
        </div>
        <p style="font-size:15px;color:#6f6a61;line-height:1.75;margin:0 0 18px;">Vaata kõikide kodude plaane, pindasid, staatust ja hindu ühelt lehelt. I etapi hinnad on avalikud, II etapi hinnad täpsustatakse. Saadavus muutub — soovitame uudistada kehtivat hinnakirja ja küsida pakkumist.</p>
        <a href="{{ lroute('magnoolia.homes') }}" class="zoomvilla-btn" data-mg-analytics="magnoolia_cta_click">Vaata kodusid ja hindu <i class="icon-angle-small-right"></i></a>
      </div>
      <div class="col-lg-5">
        <a href="{{ lroute('magnoolia.site-plan') }}" style="display:block;border-radius:16px;overflow:hidden;text-decoration:none;">
          <img {!! mg_img('Cam005.0000.jpg', '(max-width:991px) 100vw, 460px') !!} alt="Magnoolia uusarendus Vaela külas, Kiili vald" loading="lazy" decoding="async" style="width:100%;height:auto;display:block;border-radius:16px;">
        </a>
      </div>
    </div>
  </div>
</section>

{{-- ── Kellele sobib / Mida ostja teada saab / Järgmine samm ─── --}}
<section class="mg-page-section mg-page-section--white">
  <div class="container">
    <div class="row gutter-y-28">
      @php
        $aeo = [
          ['t' => 'Kellele sobib?', 'b' => 'Peredele ja kaugtöötajatele, kes soovivad ruumi ning oma hoovi rahulikus külakeskkonnas, kuid tahavad hoida Tallinna lähedal. Sobib neile, kes eelistavad uut A-energiaklassi kodu suure eramaja hoolduskoormuseta.'],
          ['t' => 'Mida ostja teada saab?', 'b' => 'Kodude plaanid ja pinnad, hooviala, terrassi ja rõdu suurused, parkimine, energialahendus, etappide ajakava ning kehtiv hinnakiri — kõik koondatud lehele „Kodud ja hinnad“. Asukoha ja ümbruskonna kohta leiad infot asukoha lehelt.'],
          ['t' => 'Mis on järgmine samm?', 'b' => 'Vaata saadaolevaid kodusid ja hindu, tutvu asukohaga ning küsi personaalset pakkumist. Müügikonsultant Diana aitab leida sinu perele sobivaima lahenduse.'],
        ];
      @endphp
      @foreach($aeo as $a)
      <div class="col-lg-4">
        <div style="background:#f8f5f0;border-radius:16px;padding:28px;height:100%;">
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
      <a href="{{ lroute('magnoolia.site-plan') }}" class="mg-internal-link"><i class="fas fa-map"></i> Asendiplaan</a>
      <a href="{{ lroute('magnoolia.finantseerimine') }}" class="mg-internal-link"><i class="fas fa-percent"></i> Finantseerimine</a>
      <a href="{{ lroute('magnoolia.ostuprotsess') }}" class="mg-internal-link"><i class="fas fa-list-ol"></i> Ostuprotsess</a>
      <a href="{{ lroute('magnoolia.contact') }}" class="mg-internal-link"><i class="fas fa-envelope"></i> Kontakt</a>
    </div>
  </div>
</section>

@include('sections.magnoolia.page-cta', [
  'title'   => 'Leia oma kodu Kiili valla uusarenduses',
  'sub'     => 'Vaata saadaolevaid kodusid Vaela külas ja küsi personaalset pakkumist.',
  'buttons' => [
    ['label' => 'Vaata kodusid ja hindu', 'url' => lroute('magnoolia.homes')],
    ['label' => 'Küsi pakkumist', 'url' => lroute('magnoolia.contact'), 'outline' => true, 'inquiry' => true, 'source' => 'lp_uusarendus-kiili_cta'],
  ]
])

@endsection
