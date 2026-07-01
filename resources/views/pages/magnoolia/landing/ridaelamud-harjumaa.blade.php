{{--
  Phase 34.2 SEO/Ads landing — /ridaelamud-harjumaa
  Primary keyword: "ridaelamu Harjumaa". Self-contained, dev-managed, ET, indexable.
  Facts used are verified from the live site only (no invented numbers/claims).
--}}
@extends('layouts.app')

@section('title', 'Ridaelamud Harjumaal | Uued A-energiaklassi kodud Magnoolias')
@section('meta_description', 'Magnoolia pakub A-energiaklassi ridaelamukodusid Vaela külas, Kiili vallas Harjumaal. 19 kodu privaatse hoovi ja terrassiga. Vaata hindu, plaane ja saadavust ning küsi pakkumist.')

@section('content')
@php
  $base = rtrim(config('magnoolia.seo.canonical_base', 'https://magnoolia.ee'), '/');
  $url  = $base . '/ridaelamud-harjumaa';

  // Single source of truth for the visible FAQ AND the FAQPage JSON-LD (cannot diverge).
  $faqs = [
    ['q' => 'Mis on Magnoolia ridaelamute hinnad?',
     'a' => 'I etapi kodude hinnad on avalikud hinnakirjas, II etapi hinnad täpsustatakse. Vaata kehtivat hinnakirja ja saadavust lehel „Kodud ja hinnad“.'],
    ['q' => 'Kas igal kodul on oma hoov?',
     'a' => 'Jah. Igal Magnoolia ridaelamukodul on privaatne hooviala ning lisaks terrass ja rõdu, mis annavad eramaja tunde ilma suure majahoolduseta.'],
    ['q' => 'Kui kaugel on Tallinn?',
     'a' => 'Vaela küla Kiili vallas asub Tallinna lähedal — sõiduaeg on ligikaudu 20 minutit, sõltuvalt marsruudist ja liiklusest.'],
    ['q' => 'Mitu tuba kodudel on?',
     'a' => 'Magnoolia kodud on 4–5-toalised, netopinnaga ligikaudu 129 m². Plaanid on läbi mõeldud pere igapäevaeluks.'],
    ['q' => 'Millal kodud valmivad?',
     'a' => 'Ehitus toimub etappidena, I etapp valmib 2027. Täpne ajakava täpsustatakse müügikonsultandiga.'],
    ['q' => 'Kas kodudel on A-energiaklass?',
     'a' => 'Jah. Magnoolia kodud on A-energiaklassis: maasoojuspump, soojustagastusega ventilatsioon ja põrandaküte aitavad hoida ülalpidamiskulud madalamana.'],
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
      "name": @json('Ridaelamud Harjumaal — A-energiaklassi kodud Magnoolias'),
      "description": @json('A-energiaklassi ridaelamukodud Vaela külas, Kiili vallas Harjumaal — privaatse hoovi ja terrassiga, Tallinna lähedal.'),
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
      ['label' => 'Ridaelamud Harjumaal'],
    ]])
    <div class="mg-page-hero__eyebrow">Ridaelamud Harjumaal · Vaela küla, Kiili vald</div>
    <h1 class="mg-page-hero__title">A-energiaklassi ridaelamud Harjumaal — privaatse hoovi ja terrassiga kodud</h1>
    <p class="mg-page-hero__lead">Magnoolia on 19 A-energiaklassi ridaelamukodu Vaela külas, Kiili vallas. 4–5 tuba, privaatne hooviala, terrass ja rõdu ning mugav ühendus Tallinnaga.</p>
    <p class="mg-page-hero__note">Vaela küla · Kiili vald · Harjumaa · ligikaudu 20 min Tallinnast (sõltuvalt liiklusest)</p>
    <div class="mg-page-hero__ctas">
      <a href="{{ lroute('magnoolia.homes') }}" class="zoomvilla-btn" data-mg-analytics="magnoolia_cta_click">Vaata hindu ja plaane <i class="icon-angle-small-right"></i></a>
      <a href="{{ lroute('magnoolia.contact') }}" class="zoomvilla-btn zoomvilla-btn--border" data-mg-inquiry-open data-source-component="lp_ridaelamud_harjumaa_hero" data-mg-analytics="magnoolia_cta_click">Küsi pakkumist</a>
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
          <p style="font-size:16px;color:#3a3530;line-height:1.75;margin:0;">Magnoolia on uus ridaelamuarendus Harjumaal — 19 A-energiaklassi kodu Vaela külas, Kiili vallas. Iga kodu on 4–5-toaline (ligikaudu 129 m²), privaatse hooviala, terrassi ja rõduga ning oma parkimisega. Maasoojuspump, soojustagastusega ventilatsioon ja põrandaküte hoiavad ülalpidamiskulud madalad. Tallinn on ligikaudu 20 minuti kaugusel.</p>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ── Miks Magnoolia ridaelamu ──────────────────────────────── --}}
<section class="mg-page-section mg-page-section--white">
  <div class="container">
    <div class="mg-section-heading" style="margin-bottom:36px;">
      <div class="mg-section-heading__eyebrow">Miks Magnoolia</div>
      <h2 class="mg-section-heading__title">Ridaelamu mugavus, eramaja tunne</h2>
    </div>
    <div class="row gutter-y-28">
      @php
        $feat = [
          ['icon' => 'fas fa-vector-square', 'title' => '4–5 tuba, ~129 m²', 'body' => 'Ruumikad, läbimõeldud plaanid kogu perele — piisavalt ruumi nii igapäevaeluks kui ka kodukontoriks.'],
          ['icon' => 'fas fa-seedling',      'title' => 'Privaatne hooviala', 'body' => 'Igal kodul oma hoov ning terrass ja rõdu — väliruum, mis kuulub ainult sulle.'],
          ['icon' => 'fas fa-bolt',          'title' => 'A-energiaklass',      'body' => 'Maasoojuspump, soojustagastusega ventilatsioon ja põrandaküte — energiatõhus kodu väiksemate kuludega.'],
          ['icon' => 'fas fa-car',           'title' => 'Oma parkimine',       'body' => 'Mugav parkimislahendus kodu juures — ei mingit igahommikust parkimiskoha otsimist.'],
          ['icon' => 'fas fa-map-marker-alt','title' => 'Tallinna lähedal',    'body' => 'Vaela küla, Kiili vald — rahulik elukeskkond ligikaudu 20 min kaugusel Tallinnast.'],
          ['icon' => 'fas fa-home',          'title' => '19 sõltumatut kodu',  'body' => 'Madal hoonestustihedus ja läbimõeldud arendus, mitte korterelamu tunne.'],
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

{{-- ── Ridaelamu või korter? ─────────────────────────────────── --}}
<section class="mg-page-section mg-page-section--cream">
  <div class="container">
    <div class="mg-section-heading" style="margin-bottom:32px;">
      <div class="mg-section-heading__eyebrow">Ridaelamu või korter?</div>
      <h2 class="mg-section-heading__title">Rohkem ruumi, vähem müra, oma hoov</h2>
    </div>
    <div class="row gutter-y-16">
      @foreach([
        'Rohkem ruumi kui korteris — 4–5 tuba kahel korrusel',
        'Privaatne hooviala ja terrass otse kodu juures',
        'Vähem naabrite müra kui korterelamus',
        'Oma parkimine kodu juures',
        'Sobib perele, lastele ja lemmikloomadele',
        'A-energiaklass — madalamad ülalpidamiskulud',
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

{{-- ── Saadaolevad kodud (teaser → BOFU) ─────────────────────── --}}
<section class="mg-page-section mg-page-section--white">
  <div class="container">
    <div class="row align-items-center gutter-y-28">
      <div class="col-lg-7">
        <div class="mg-section-heading" style="margin-bottom:14px;">
          <div class="mg-section-heading__eyebrow">Saadaolevad kodud</div>
          <h2 class="mg-section-heading__title">19 kodu, kahes etapis</h2>
        </div>
        <p style="font-size:15px;color:#6f6a61;line-height:1.75;margin:0 0 18px;">Vaata kõikide kodude plaane, pindasid, staatust ja hindu ühelt lehelt. I etapi hinnad on avalikud; II etapi hinnad täpsustatakse. Saadavus muutub — soovitame uudistada hinnakirja ja küsida pakkumist.</p>
        <a href="{{ lroute('magnoolia.homes') }}" class="zoomvilla-btn" data-mg-analytics="magnoolia_cta_click">Vaata kodusid ja hindu <i class="icon-angle-small-right"></i></a>
      </div>
      <div class="col-lg-5">
        <a href="{{ lroute('magnoolia.site-plan') }}" style="display:block;border-radius:16px;overflow:hidden;text-decoration:none;">
          <img {!! mg_img('Cam014.0000.jpg', '(max-width:991px) 100vw, 460px') !!} alt="Magnoolia ridaelamud Vaela külas — tänavavaade" loading="lazy" decoding="async" style="width:100%;height:auto;display:block;border-radius:16px;">
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
          ['t' => 'Kellele sobib?', 'b' => 'Peredele, kes soovivad korterist välja kasvada, vajavad rohkem ruumi ja oma hoovi, kuid ei taha üksiku eramaja hoolduskoormust. Sobib ka kaugtöötajatele ja neile, kes hindavad rahulikku keskkonda Tallinna lähedal.'],
          ['t' => 'Mida ostja teada saab?', 'b' => 'Kodude plaanid ja pinnad, hooviala, terrassi ja rõdu suurused, parkimine, energialahendus, etapid ja valmimisaeg ning kehtiv hinnakiri — kõik koondatud lehele „Kodud ja hinnad“.'],
          ['t' => 'Mis on järgmine samm?', 'b' => 'Vaata saadaolevaid kodusid ja hindu, vali sobiv kodu ning küsi personaalset pakkumist. Müügikonsultant Diana aitab leida sulle parima lahenduse.'],
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
      <a href="{{ lroute('magnoolia.finantseerimine') }}" class="mg-internal-link"><i class="fas fa-percent"></i> Finantseerimine</a>
      <a href="{{ lroute('magnoolia.ostuprotsess') }}" class="mg-internal-link"><i class="fas fa-list-ol"></i> Ostuprotsess</a>
      <a href="{{ lroute('magnoolia.contact') }}" class="mg-internal-link"><i class="fas fa-envelope"></i> Kontakt</a>
    </div>
  </div>
</section>

@include('sections.magnoolia.page-cta', [
  'title'   => 'Leia oma ridaelamukodu Harjumaal',
  'sub'     => 'Vaata saadaolevaid kodusid ja küsi personaalset pakkumist.',
  'buttons' => [
    ['label' => 'Vaata kodusid ja hindu', 'url' => lroute('magnoolia.homes')],
    ['label' => 'Küsi pakkumist', 'url' => lroute('magnoolia.contact'), 'outline' => true, 'inquiry' => true, 'source' => 'lp_ridaelamud_harjumaa_cta'],
  ]
])

@endsection
