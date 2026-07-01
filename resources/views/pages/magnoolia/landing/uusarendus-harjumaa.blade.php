{{--
  SEO/Ads landing — /uusarendus-harjumaa
  Primary keyword: "uusarendus Harjumaa". Self-contained, dev-managed, ET, indexable.
  Facts used are verified from the live site only (no invented numbers/claims).
--}}
@extends('layouts.app')

@section('title', 'Uusarendus Harjumaal | A-energiaklassi ridaelamukodud Tallinna lähedal')
@section('meta_description', 'Magnoolia on uus ridaelamuarendus Harjumaal — 19 A-energiaklassi kodu Vaela külas, Kiili vallas, Tallinna lähedal. Kaasaegne ehitus, privaatne hoov ja terrass. Vaata hindu ja plaane ning küsi pakkumist.')

@section('content')
@php
  $base = rtrim(config('magnoolia.seo.canonical_base', 'https://magnoolia.ee'), '/');
  $url  = $base . '/uusarendus-harjumaa';

  $pageName = 'Uusarendus Harjumaal — A-energiaklassi ridaelamukodud Tallinna lähedal';
  $pageDesc = 'Uus A-energiaklassi ridaelamuarendus Vaela külas, Kiili vallas Harjumaal — kaasaegne ehitus, privaatne hoov ja terrass, Tallinna lähedal.';

  // Single source of truth for the visible FAQ AND the FAQPage JSON-LD (cannot diverge).
  $faqs = [
    ['q' => 'Kus Magnoolia uusarendus asub?',
     'a' => 'Magnoolia asub Vaela külas, Kiili vallas Harjumaal, Magnoolia teel. See ei ole Tallinna linnas, vaid rahulikus vallas Tallinna vahetus läheduses — sõiduaeg linna on ligikaudu 20 minutit, sõltuvalt marsruudist ja liiklusest.'],
    ['q' => 'Mis teeb Magnooliast kaasaegse uusarenduse?',
     'a' => 'Kodud ehitatakse A-energiaklassi: maasoojuspump, soojustagastusega ventilatsioon ja põrandaküte. Tegemist on uue, nullist projekteeritud ridaelamuarendusega, mitte vana hoone renoveeringuga.'],
    ['q' => 'Kui suured on kodud selles uusarenduses?',
     'a' => 'Kodud on 4–5-toalised, netopinnaga ligikaudu 129 m² (kuni ligikaudu 143 m²). Igal kodul on privaatne hooviala, terrass ja rõdu ning oma parkimislahendus.'],
    ['q' => 'Millal uusarendus valmib?',
     'a' => 'Arendus valmib etappidena. I etapp on plaanitud valmima 2027. II etapi täpne ajakava täpsustatakse müügikonsultandiga.'],
    ['q' => 'Kust näen kodude hindu ja saadavust?',
     'a' => 'I etapi kodude hinnad on avalikud hinnakirjas, II etapi hinnad täpsustatakse. Vaata kehtivat hinnakirja ja vaba valikut lehel „Kodud ja hinnad“.'],
    ['q' => 'Kes on arenduse taga?',
     'a' => 'Magnoolia arendaja on Estlanda OÜ, mis on tegutsenud alates 2009. aastast. Müügikonsultant Diana vastab hea meelega arendust ja kodusid puudutavatele küsimustele.'],
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
      "name": @json($pageName),
      "description": @json($pageDesc),
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
      ['label' => 'Uusarendus Harjumaal'],
    ]])
    <div class="mg-page-hero__eyebrow">Uusarendus Harjumaal · Vaela küla, Kiili vald</div>
    <h1 class="mg-page-hero__title">Uusarendus Harjumaal — A-energiaklassi ridaelamukodud Tallinna lähedal</h1>
    <p class="mg-page-hero__lead">Magnoolia on uus ridaelamuarendus Harjumaal: 19 A-energiaklassi kodu Vaela külas, Kiili vallas. Kaasaegne, nullist projekteeritud ehitus, privaatne hooviala ja mugav ühendus Tallinnaga.</p>
    <p class="mg-page-hero__note">Vaela küla · Kiili vald · Harjumaa · ligikaudu 20 min Tallinnast (sõltuvalt liiklusest)</p>
    <div class="mg-page-hero__ctas">
      <a href="{{ lroute('magnoolia.homes') }}" class="zoomvilla-btn" data-mg-analytics="magnoolia_cta_click">Vaata hindu ja plaane <i class="icon-angle-small-right"></i></a>
      <a href="{{ lroute('magnoolia.contact') }}" class="zoomvilla-btn zoomvilla-btn--border" data-mg-inquiry-open data-source-component="lp_uusarendus-harjumaa_hero" data-mg-analytics="magnoolia_cta_click">Küsi pakkumist</a>
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
          <p style="font-size:16px;color:#3a3530;line-height:1.75;margin:0;">Magnoolia on uus ridaelamuarendus Harjumaal — 19 A-energiaklassi kodu Vaela külas, Kiili vallas. Tegemist ei ole Tallinna linnaga, vaid rahuliku vallaga Tallinna vahetus läheduses: sõiduaeg linna on ligikaudu 20 minutit, sõltuvalt marsruudist ja liiklusest. Kodud on 4–5-toalised (ligikaudu 129 m²), privaatse hooviala, terrassi ja rõduga. Maasoojuspump, soojustagastusega ventilatsioon ja põrandaküte teevad neist energiatõhusad kodud.</p>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ── Miks see uusarendus ───────────────────────────────────── --}}
<section class="mg-page-section mg-page-section--white">
  <div class="container">
    <div class="mg-section-heading" style="margin-bottom:36px;">
      <div class="mg-section-heading__eyebrow">Miks Magnoolia</div>
      <h2 class="mg-section-heading__title">Kaasaegne uusarendus, mis on ehitatud tänaseks päevaks</h2>
    </div>
    <div class="row gutter-y-28">
      @php
        $feat = [
          ['icon' => 'fas fa-drafting-compass', 'title' => 'Nullist projekteeritud', 'body' => 'Uus arendus, mitte vana hoone kohandus — kaasaegne planeering ja tänapäevased tehnilised lahendused algusest peale.'],
          ['icon' => 'fas fa-bolt',             'title' => 'A-energiaklass',        'body' => 'Maasoojuspump, soojustagastusega ventilatsioon ja põrandaküte — energiatõhus kodu väiksema ülalpidamiskoormusega.'],
          ['icon' => 'fas fa-vector-square',    'title' => '4–5 tuba, ~129 m²',     'body' => 'Ruumikad plaanid kuni ligikaudu 143 m²-ni — piisavalt ruumi pere igapäevaeluks ja kodukontoriks.'],
          ['icon' => 'fas fa-seedling',         'title' => 'Privaatne hooviala',    'body' => 'Igal kodul oma hoov ning terrass ja rõdu — väliruum, mis kuulub ainult sinu perele.'],
          ['icon' => 'fas fa-map-marker-alt',   'title' => 'Harjumaa, Tallinna serv','body' => 'Vaela küla, Kiili vald — roheline elukeskkond ligikaudu 20 min kaugusel Tallinnast (sõltuvalt liiklusest).'],
          ['icon' => 'fas fa-layer-group',      'title' => 'Etapiviisiline areng',  'body' => 'Arendus valmib etappidena, I etapp 2027 — läbimõeldud ja jälgitav ehituse kulg.'],
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

{{-- ── Uusarendus Harjumaal, mitte Tallinna linnas (unique angle) ── --}}
<section class="mg-page-section mg-page-section--cream">
  <div class="container">
    <div class="mg-section-heading" style="margin-bottom:32px;">
      <div class="mg-section-heading__eyebrow">Asukoht ausalt</div>
      <h2 class="mg-section-heading__title">Harjumaa uusarendus Tallinna külje all — parim mõlemast maailmast</h2>
    </div>
    <div class="row gutter-y-28 align-items-start">
      <div class="col-lg-7">
        <p style="font-size:15px;color:#6f6a61;line-height:1.8;margin:0 0 16px;">Magnoolia ei asu Tallinna linnas, vaid Vaela külas Kiili vallas — ja seda me ütleme ausalt. Just see teebki arendusest atraktiivse uue kodu valiku Harjumaal: saad kaasaegse ridaelamukodu roheluses ja rahus, ilma kesklinna müra, kitsaste tänavate ja parkimismureta.</p>
        <p style="font-size:15px;color:#6f6a61;line-height:1.8;margin:0;">Tallinn jääb siiski mugavalt käeulatusse — sõiduaeg linna on ligikaudu 20 minutit, sõltuvalt marsruudist ja liiklusest. Nii saad Harjumaa uusarenduses ühendada linnalähedase mugavuse ja eeslinna rahu, mida korteriturg linnas harva pakub.</p>
      </div>
      <div class="col-lg-5">
        <div class="row gutter-y-16">
          @foreach([
            ['t' => 'Vald, mitte linn', 'b' => 'Kiili vald — vaiksem keskkond, kuid Tallinn vaid lühikese sõidu kaugusel.'],
            ['t' => 'Uus, mitte vana', 'b' => 'Kaasaegne A-energiaklassi ehitus, mitte järelturu vana hoone.'],
            ['t' => 'Ridaelamu, mitte korter', 'b' => 'Oma hoov, terrass ja parkimine — rohkem privaatsust kui korteris.'],
          ] as $row)
          <div class="col-lg-12">
            <div style="background:#fff;border-radius:12px;padding:18px 20px;">
              <div style="font-size:15px;font-weight:700;color:#1d2430;margin-bottom:5px;">{{ $row['t'] }}</div>
              <p style="font-size:13.5px;color:#6f6a61;line-height:1.6;margin:0;">{{ $row['b'] }}</p>
            </div>
          </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ── Saadaolevad kodud (teaser → BOFU) ─────────────────────── --}}
<section class="mg-page-section mg-page-section--white">
  <div class="container">
    <div class="row align-items-center gutter-y-28">
      <div class="col-lg-7">
        <div class="mg-section-heading" style="margin-bottom:14px;">
          <div class="mg-section-heading__eyebrow">Kodud ja hinnad</div>
          <h2 class="mg-section-heading__title">Tutvu uue arenduse kodudega</h2>
        </div>
        <p style="font-size:15px;color:#6f6a61;line-height:1.75;margin:0 0 18px;">Kõik 19 kodu, nende plaanid, pinnad, staatus ja hinnad on koondatud ühele lehele. I etapi hinnad on avalikud, II etapi hinnad täpsustatakse. Saadavus muutub, seega tasub hinnakirja jälgida ja huvipakkuva kodu kohta pakkumist küsida.</p>
        <a href="{{ lroute('magnoolia.homes') }}" class="zoomvilla-btn" data-mg-analytics="magnoolia_cta_click">Vaata kodusid ja hindu <i class="icon-angle-small-right"></i></a>
      </div>
      <div class="col-lg-5">
        <a href="{{ lroute('magnoolia.site-plan') }}" style="display:block;border-radius:16px;overflow:hidden;text-decoration:none;">
          <img {!! mg_img('Cam005.0000.jpg', '(max-width:991px) 100vw, 460px') !!} alt="Magnoolia uusarendus Vaela külas Harjumaal — arhitektuurivaade" loading="lazy" decoding="async" style="width:100%;height:auto;display:block;border-radius:16px;">
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
          ['t' => 'Kellele sobib?', 'b' => 'Ostjatele, kes otsivad Harjumaalt uut kodu ega taha järelturu vana korterit või maja. Sobib peredele, kes soovivad oma hoovi ja rohkem ruumi, ning kaugtöötajatele, kes hindavad rahu Tallinna vahetus läheduses.'],
          ['t' => 'Mida ostja teada saab?', 'b' => 'Arenduse asukoha ja etapid, kodude plaanid ja pinnad, hooviala, terrassi ja rõdu, parkimise, A-energiaklassi lahenduse ning kehtiva hinnakirja — kõik lehel „Kodud ja hinnad“.'],
          ['t' => 'Mis on järgmine samm?', 'b' => 'Vaata saadaolevaid kodusid ja hindu, vali sobiv kodu ning küsi personaalset pakkumist. Müügikonsultant Diana aitab leida sinu perele sobiva lahenduse.'],
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
      <a href="{{ lroute('magnoolia.site-plan') }}" class="mg-internal-link"><i class="fas fa-map"></i> Asendiplaan</a>
      <a href="{{ lroute('magnoolia.location') }}" class="mg-internal-link"><i class="fas fa-map-marker-alt"></i> Asukoht</a>
      <a href="{{ lroute('magnoolia.construction') }}" class="mg-internal-link"><i class="fas fa-hard-hat"></i> Ehituse käik</a>
      <a href="{{ lroute('magnoolia.ostuprotsess') }}" class="mg-internal-link"><i class="fas fa-list-ol"></i> Ostuprotsess</a>
      <a href="{{ lroute('magnoolia.contact') }}" class="mg-internal-link"><i class="fas fa-envelope"></i> Kontakt</a>
    </div>
  </div>
</section>

@include('sections.magnoolia.page-cta', [
  'title'   => 'Uus kodu Harjumaal ootab',
  'sub'     => 'Tutvu Magnoolia uusarenduse kodudega ja küsi personaalset pakkumist.',
  'buttons' => [
    ['label' => 'Vaata kodusid ja hindu', 'url' => lroute('magnoolia.homes')],
    ['label' => 'Küsi pakkumist', 'url' => lroute('magnoolia.contact'), 'outline' => true, 'inquiry' => true, 'source' => 'lp_uusarendus-harjumaa_cta'],
  ]
])

@endsection
