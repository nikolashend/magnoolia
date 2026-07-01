{{--
  SEO/Ads landing — /uus-kodu-tallinna-lahedal
  Primary keyword: "uus kodu Tallinna lähedal". Self-contained, dev-managed, ET, indexable.
  Facts used are verified from the live site only (no invented numbers/claims).
--}}
@extends('layouts.app')

@section('title', 'Uus kodu Tallinna lähedal | Oma hoovi ja terrassiga ridaelamukodu')
@section('meta_description', 'Uus kodu Tallinna lähedal — 19 A-energiaklassi ridaelamukodu Vaela külas, Kiili vallas. 4–5 tuba, privaatne hoov, terrass ja oma parkimine. Vaata kodusid, hindu ja küsi pakkumist.')

@section('content')
@php
  $base = rtrim(config('magnoolia.seo.canonical_base', 'https://magnoolia.ee'), '/');
  $url  = $base . '/uus-kodu-tallinna-lahedal';

  $pageName = 'Uus kodu Tallinna lähedal - ridaelamukodu privaatse hoovi ja terrassiga';
  $pageDesc = 'Uus A-energiaklassi ridaelamukodu Vaela külas, Kiili vallas, Tallinna lähedal, privaatse hoovi, terrassi ja oma parkimisega.';

  // Single source of truth for the visible FAQ AND the FAQPage JSON-LD (cannot diverge).
  $faqs = [
    ['q' => 'Kui kaugel Tallinnast Magnoolia asub?',
     'a' => 'Magnoolia paikneb Vaela külas, Kiili vallas — Tallinna vahetus läheduses. Autoga jõuab linna ligikaudu 20 minutiga, sõltuvalt marsruudist ja liiklusest.'],
    ['q' => 'Miks valida ridaelamukodu korteri asemel?',
     'a' => 'Ridaelamukodu annab rohkem ruumi ja oma väliala — 4–5 tuba, privaatne hoov, terrass ja rõdu. Saad eramaja tunde ja lastele oma õue, ilma korterelamu naabrusmüra ja ühiskoridorideta.'],
    ['q' => 'Kui suured Magnoolia kodud on?',
     'a' => 'Kodud on 4–5-toalised, netopinnaga ligikaudu 129 m² (kuni ~143 m²). Plaanid on jaotatud kahele korrusele, et magamis- ja elutsoonid oleksid selgelt eristatud.'],
    ['q' => 'Kas uues kodus on oma parkimine?',
     'a' => 'Jah. Igal kodul on oma parkimislahendus koos autovarjualusega, nii et parkimiskoha pärast ei pea muretsema.'],
    ['q' => 'Millal saab uude koju sisse kolida?',
     'a' => 'Ehitus toimub etappidena. I etapp valmib 2027. II etapi ajakava täpsustatakse — kõige ajakohasema info saad müügikonsultandilt.'],
    ['q' => 'Kui suured on uue kodu ülalpidamiskulud?',
     'a' => 'Kodud on A-energiaklassis: maasoojuspump, soojustagastusega ventilatsioon ja põrandaküte hoiavad energiakulu tagasihoidlikuna. Täpsed kulud sõltuvad pere tarbimisest ja hindadest.'],
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
      ['label' => 'Uus kodu Tallinna lähedal'],
    ]])
    <div class="mg-page-hero__eyebrow">Uus kodu Tallinna lähedal · Vaela küla, Kiili vald</div>
    <h1 class="mg-page-hero__title">Uus kodu Tallinna lähedal — ridaelamu mugavus, eramaja tunne</h1>
    <p class="mg-page-hero__lead">Koli korterist rohkema ruumi ja oma hoovi juurde. Magnoolia on 19 uut A-energiaklassi ridaelamukodu Vaela külas, Kiili vallas — 4–5 tuba, privaatne hooviala, terrass, rõdu ja oma parkimine, kõik mugavas kauguses Tallinnast.</p>
    <p class="mg-page-hero__note">Vaela küla · Kiili vald · Harjumaa · ligikaudu 20 min Tallinnast (sõltuvalt liiklusest)</p>
    <div class="mg-page-hero__ctas">
      <a href="{{ lroute('magnoolia.homes') }}" class="zoomvilla-btn" data-mg-analytics="magnoolia_cta_click">Vaata kodusid ja hindu <i class="icon-angle-small-right"></i></a>
      <a href="{{ lroute('magnoolia.contact') }}" class="zoomvilla-btn zoomvilla-btn--border" data-mg-inquiry-open data-source-component="lp_uus-kodu-tallinna-lahedal_hero" data-mg-analytics="magnoolia_cta_click">Küsi pakkumist</a>
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
          <p style="font-size:16px;color:#3a3530;line-height:1.75;margin:0;">Otsid uut kodu Tallinna lähedal? Magnoolia pakub 19 A-energiaklassi ridaelamukodu Vaela külas, Kiili vallas. Iga kodu on 4–5-toaline (ligikaudu 129 m², kuni ~143 m²) privaatse hooviala, terrassi ja rõduga ning oma parkimisega. Maasoojuspump, soojustagastusega ventilatsioon ja põrandaküte hoiavad ülalpidamiskulud tagasihoidlikuna. Tallinn on autoga ligikaudu 20 minuti kaugusel.</p>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ── Miks uus kodu Magnoolias ──────────────────────────────── --}}
<section class="mg-page-section mg-page-section--white">
  <div class="container">
    <div class="mg-section-heading" style="margin-bottom:36px;">
      <div class="mg-section-heading__eyebrow">Miks Magnoolia</div>
      <h2 class="mg-section-heading__title">Kõik, mida korterist puudu jäi — ühes kodus</h2>
    </div>
    <div class="row gutter-y-28">
      @php
        $feat = [
          ['icon' => 'fas fa-vector-square', 'title' => 'Ruumi kogu perele', 'body' => '4–5 tuba kahel korrusel, ligikaudu 129 m² (kuni ~143 m²) — magamistoad eraldi elutsoonist ning ruumi ka kodukontorile.'],
          ['icon' => 'fas fa-seedling',      'title' => 'Oma hoov ja terrass', 'body' => 'Privaatne hooviala koos terrassi ja rõduga — lastele oma õu ja sulle koht hommikukohvile, ilma naabritega jagamata.'],
          ['icon' => 'fas fa-bolt',          'title' => 'A-energiaklassi kodu', 'body' => 'Maasoojuspump, soojustagastusega ventilatsioon ja põrandaküte — soe ja energiatõhus uus kodu väiksema kütte- ja jahutuskuluga.'],
          ['icon' => 'fas fa-car',           'title' => 'Oma parkimine', 'body' => 'Parkimislahendus koos autovarjualusega otse kodu juures — auto on kaitstud ja alati käepärast.'],
          ['icon' => 'fas fa-map-marker-alt','title' => 'Tallinna lähedal', 'body' => 'Vaela küla, Kiili vald — vaikne looduslähedane keskkond ligikaudu 20 min kaugusel Tallinnast (sõltuvalt liiklusest).'],
          ['icon' => 'fas fa-key',           'title' => 'Uus ja valmis kolimiseks', 'body' => 'Uus ridaelamukodu ilma renoveerimismureta — I etapp valmib 2027, sisse saad kolida uude, läbimõeldud koju.'],
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

{{-- ── Korterist ridaelamusse (page-specific angle) ──────────── --}}
<section class="mg-page-section mg-page-section--cream">
  <div class="container">
    <div class="mg-section-heading" style="margin-bottom:32px;">
      <div class="mg-section-heading__eyebrow">Korterist ridaelamusse</div>
      <h2 class="mg-section-heading__title">Kui korter jääb pisut kitsaks</h2>
    </div>
    <div class="row gutter-y-28 align-items-start">
      <div class="col-lg-7">
        <p style="font-size:15px;color:#6f6a61;line-height:1.8;margin:0 0 16px;">Paljud pered jõuavad hetkeni, kus korterisse ei mahu enam ära — laps kasvab, kodukontor vajab oma nurka ja õue tahaks lihtsalt astuda, mitte lifti oodata. Samas ei pruugi üksik eramaja oma aiatööde ja hoolduskoormusega olla see, mida päriselt soovid.</p>
        <p style="font-size:15px;color:#6f6a61;line-height:1.8;margin:0;">Ridaelamukodu on siin kesktee: eramaja avarus ja oma väliruum, kuid mõõdukama halduskoormusega. Magnoolia kodus on eluruumid ja magamistoad jaotatud kahele korrusele, oma hoov ja terrass on kohe ukse taga ning auto ootab enda parkimiskohal. Uus, A-energiaklassi kodu tähendab ka seda, et esimestel aastatel ei pea renoveerimisele mõtlema.</p>
      </div>
      <div class="col-lg-5">
        <div class="row gutter-y-16">
          @foreach([
            'Rohkem ruumi kui korteris — 4–5 tuba kahel korrusel',
            'Oma hoov ja terrass laste ning puhkehetkede jaoks',
            'Vähem jagatud pindu ja naabrite müra',
            'Oma parkimine koos autovarjualusega',
            'Mõõdukam halduskoormus kui üksikul eramajal',
            'Uus A-energiaklassi kodu ilma renoveerimismureta',
          ] as $point)
          <div class="col-lg-12">
            <div style="display:flex;gap:12px;align-items:flex-start;background:#fff;border-radius:12px;padding:16px 18px;">
              <i class="fas fa-check" style="color:#c89443;margin-top:3px;flex-shrink:0;"></i>
              <span style="font-size:14px;color:#3a3530;line-height:1.55;">{{ $point }}</span>
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
          <div class="mg-section-heading__eyebrow">Saadaolevad kodud</div>
          <h2 class="mg-section-heading__title">Vaata plaane ja leia oma uus kodu</h2>
        </div>
        <p style="font-size:15px;color:#6f6a61;line-height:1.75;margin:0 0 18px;">Kõikide kodude plaanid, pinnad, staatus ja hinnad on koondatud ühele lehele. I etapi hinnad on avalikud hinnakirjas; II etapi hinnad täpsustatakse. Saadavus muutub, seega tasub hinnakirja üle vaadata ja sobiva kodu kohta pakkumist küsida.</p>
        <a href="{{ lroute('magnoolia.homes') }}" class="zoomvilla-btn" data-mg-analytics="magnoolia_cta_click">Vaata kodusid ja hindu <i class="icon-angle-small-right"></i></a>
      </div>
      <div class="col-lg-5">
        <a href="{{ lroute('magnoolia.site-plan') }}" style="display:block;border-radius:16px;overflow:hidden;text-decoration:none;">
          <img {!! mg_img('Cam005.0000.jpg', '(max-width:991px) 100vw, 460px') !!} alt="Magnoolia ridaelamukodud Vaela külas Tallinna lähedal" loading="lazy" decoding="async" style="width:100%;height:auto;display:block;border-radius:16px;">
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
          ['t' => 'Kellele sobib?', 'b' => 'Peredele, kes kolivad korterist suuremasse koju, vajavad lastele oma õue ja soovivad eramaja tunnet ilma suure hoolduskoormuseta. Sobib ka kaugtöötajatele ja neile, kes hindavad rahulikku keskkonda Tallinna lähedal.'],
          ['t' => 'Mida ostja teada saab?', 'b' => 'Kodude plaanid ja pinnad, hooviala, terrassi ja rõdu suurused, parkimislahendus, A-energiaklassi tehnika, ehitusetapid ja valmimisaeg ning kehtiv hinnakiri — kõik lehel „Kodud ja hinnad“.'],
          ['t' => 'Mis on järgmine samm?', 'b' => 'Vaata saadaolevaid kodusid ja hindu, vali sobiv plaan ning küsi personaalset pakkumist. Müügikonsultant Diana aitab leida sinu perele sobivaima kodu.'],
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
      <a href="{{ lroute('magnoolia.site-plan') }}" class="mg-internal-link"><i class="fas fa-map"></i> Asendiplaan</a>
      <a href="{{ lroute('magnoolia.finantseerimine') }}" class="mg-internal-link"><i class="fas fa-percent"></i> Finantseerimine</a>
      <a href="{{ lroute('magnoolia.ostuprotsess') }}" class="mg-internal-link"><i class="fas fa-list-ol"></i> Ostuprotsess</a>
      <a href="{{ lroute('magnoolia.kkk') }}" class="mg-internal-link"><i class="fas fa-question-circle"></i> KKK</a>
      <a href="{{ lroute('magnoolia.contact') }}" class="mg-internal-link"><i class="fas fa-envelope"></i> Kontakt</a>
    </div>
  </div>
</section>

@include('sections.magnoolia.page-cta', [
  'title'   => 'Leia oma uus kodu Tallinna lähedal',
  'sub'     => 'Vaata saadaolevaid kodusid ja küsi personaalset pakkumist.',
  'buttons' => [
    ['label' => 'Vaata kodusid ja hindu', 'url' => lroute('magnoolia.homes')],
    ['label' => 'Küsi pakkumist', 'url' => lroute('magnoolia.contact'), 'outline' => true, 'inquiry' => true, 'source' => 'lp_uus-kodu-tallinna-lahedal_cta'],
  ]
])

@endsection
