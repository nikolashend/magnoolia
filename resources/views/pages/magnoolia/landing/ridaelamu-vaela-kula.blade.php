{{--
  Phase 34.2 SEO/Ads landing — /ridaelamu-vaela-kula
  Primary keyword: "ridaelamu Vaela küla". Hyper-local ET landing, indexable, dev-managed.
  Facts used are verified from the live site only (no invented numbers/claims).
--}}
@extends('layouts.app')

@section('title', 'Ridaelamukodud Vaela külas | Magnoolia kodud Kiili vallas')
@section('meta_description', 'Ridaelamukodud Vaela külas Magnoolia tee ääres, Kiili vallas Harjumaal. 19 A-energiaklassi kodu privaatse hoovi, terrassi ja rõduga, Tallinn ligikaudu 20 min. Vaata plaane ja hindu.')

@section('content')
@php
  $base = rtrim(config('magnoolia.seo.canonical_base', 'https://magnoolia.ee'), '/');
  $url  = $base . '/ridaelamu-vaela-kula';

  $ldName = 'Ridaelamukodud Vaela külas — Magnoolia kodud Kiili vallas';
  $ldDesc = 'A-energiaklassi ridaelamukodud Magnoolia tee ääres Vaela külas, Kiili vallas Harjumaal — privaatse hoovi, terrassi ja rõduga, Tallinna lähedal.';

  // Single source of truth for the visible FAQ AND the FAQPage JSON-LD (cannot diverge).
  $faqs = [
    ['q' => 'Kus täpselt Vaela külas Magnoolia kodud asuvad?',
     'a' => 'Magnoolia ridaelamukodud kerkivad Magnoolia tee äärde Vaela külas, Kiili vallas Harjumaal. Täpsemat paiknemist ja kodude jaotust saad vaadata asendiplaanilt.'],
    ['q' => 'Milline on Vaela küla elukeskkonnana?',
     'a' => 'Vaela küla Kiili vallas on rahulik ja rohealadega ääristatud piirkond Tallinna lähialal. Piirkonna lähemat asukohakirjeldust ja ühendusi tutvustame asukoha lehel.'],
    ['q' => 'Kui kaugel on Vaela külast Tallinn?',
     'a' => 'Vaela küla asub Tallinna vahetus lähedal — sõiduaeg kesklinna on ligikaudu 20 minutit, sõltuvalt marsruudist ja liiklusest.'],
    ['q' => 'Mitu kodu Vaela külla ehitatakse ja millised need on?',
     'a' => 'Kokku valmib 19 A-energiaklassi ridaelamukodu. Kodud on 4–5-toalised, netopinnaga ligikaudu 129 m² (kuni ~143 m²), igal kodul privaatne hooviala, terrass ja rõdu.'],
    ['q' => 'Millal Vaela küla kodud valmivad?',
     'a' => 'Ehitus toimub etappidena — I etapp valmib 2027. II etapi täpsem ajakava ja hinnad täpsustatakse; kehtiva info leiad lehelt „Kodud ja hinnad“.'],
    ['q' => 'Kust näen Vaela küla kodude hindu?',
     'a' => 'I etapi kodude hinnad on avalikud hinnakirjas, II etapi hinnad täpsustatakse. Vaata kehtivaid hindu ja saadavust lehel „Kodud ja hinnad“ ning küsi soovi korral pakkumist.'],
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
      "name": @json($ldName),
      "description": @json($ldDesc),
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
      ['label' => 'Ridaelamukodud Vaela külas'],
    ]])
    <div class="mg-page-hero__eyebrow">Vaela küla · Kiili vald · Magnoolia tee</div>
    <h1 class="mg-page-hero__title">Ridaelamukodud Vaela külas — Magnoolia kodud Kiili vallas</h1>
    <p class="mg-page-hero__lead">Magnoolia toob Vaela külla, Magnoolia tee äärde, 19 A-energiaklassi ridaelamukodu. 4–5 tuba, privaatne hooviala, terrass ja rõdu ning rahulik elukeskkond Tallinna lähialal.</p>
    <p class="mg-page-hero__note">Vaela küla · Kiili vald · Harjumaa · Tallinn ligikaudu 20 min (sõltuvalt marsruudist ja liiklusest)</p>
    <div class="mg-page-hero__ctas">
      <a href="{{ lroute('magnoolia.homes') }}" class="zoomvilla-btn" data-mg-analytics="magnoolia_cta_click">Vaata kodusid ja hindu <i class="icon-angle-small-right"></i></a>
      <a href="{{ lroute('magnoolia.contact') }}" class="zoomvilla-btn zoomvilla-btn--border" data-mg-inquiry-open data-source-component="lp_ridaelamu-vaela-kula_hero" data-mg-analytics="magnoolia_cta_click">Küsi pakkumist</a>
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
          <p style="font-size:16px;color:#3a3530;line-height:1.75;margin:0;">Magnoolia asub Vaela külas, Kiili vallas — Magnoolia tee äärde valmib 19 A-energiaklassi ridaelamukodu. Iga kodu on 4–5-toaline (netopind ligikaudu 129 m², kuni ~143 m²), privaatse hooviala, terrassi ja rõduga ning oma parkimislahendusega. Maasoojuspump, soojustagastusega ventilatsioon ja põrandaküte hoiavad ülalpidamiskulud madalad. Tallinn on ligikaudu 20 minuti kaugusel, sõltuvalt marsruudist ja liiklusest.</p>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ── Miks Vaela küla ──────────────────────────────────────── --}}
<section class="mg-page-section mg-page-section--white">
  <div class="container">
    <div class="mg-section-heading" style="margin-bottom:36px;">
      <div class="mg-section-heading__eyebrow">Miks Vaela küla</div>
      <h2 class="mg-section-heading__title">Kodu Magnoolia tee ääres, Tallinna lähialal</h2>
    </div>
    <div class="row gutter-y-28">
      @php
        $feat = [
          ['icon' => 'fas fa-map-marker-alt','title' => 'Vaela küla, Kiili vald', 'body' => 'Kodud paiknevad Magnoolia tee ääres — hoomatava suurusega naabruskond rahulikus Harjumaa külas.'],
          ['icon' => 'fas fa-road',          'title' => 'Mugav ühendus Tallinnaga', 'body' => 'Tallinn on ligikaudu 20 minuti kaugusel, sõltuvalt marsruudist ja liiklusest — sobib igapäevaseks pendeldamiseks.'],
          ['icon' => 'fas fa-seedling',      'title' => 'Privaatne väliruum', 'body' => 'Igal kodul oma hooviala ning lisaks terrass ja rõdu — küla annab ruumi õue liikuda.'],
          ['icon' => 'fas fa-vector-square', 'title' => '4–5 tuba, ~129 m²', 'body' => 'Läbimõeldud plaanid kogu perele, netopind kuni ~143 m² — piisavalt ruumi eluks ja kodukontoriks.'],
          ['icon' => 'fas fa-bolt',          'title' => 'A-energiaklass', 'body' => 'Maasoojuspump, soojustagastusega ventilatsioon ja põrandaküte — energiatõhus kodu külakeskkonnas.'],
          ['icon' => 'fas fa-car',           'title' => 'Oma parkimine', 'body' => 'Autovarjualune ja parkimislahendus kodu juures — mugav igapäev ilma parkimismuredeta.'],
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

{{-- ── Page-specific angle: hyper-local Vaela küla context ───── --}}
<section class="mg-page-section mg-page-section--cream">
  <div class="container">
    <div class="mg-section-heading" style="margin-bottom:32px;">
      <div class="mg-section-heading__eyebrow">Elu Vaela külas</div>
      <h2 class="mg-section-heading__title">Küla rahu, linna lähedus</h2>
    </div>
    <div class="row gutter-y-28 align-items-center">
      <div class="col-lg-7">
        <p style="font-size:15px;color:#6f6a61;line-height:1.8;margin:0 0 16px;">Vaela küla asub Kiili vallas, Tallinna lähialal, kus rohealad ja väiksem hoonestustihedus loovad rahuliku argipäeva. Magnoolia tee äärde kerkiv 19 kodu jääb kompaktseks naabruskonnaks — piisavalt suureks, et olla ühtne, ja piisavalt väikeseks, et jääda inimlikuks.</p>
        <p style="font-size:15px;color:#6f6a61;line-height:1.8;margin:0;">Just külakeskkonna ja Tallinna läheduse kooslus teeb Vaela sobivaks peredele, kes soovivad korterist rohkema ruumi ja oma hoovi juurde, kuid ei taha loobuda mugavast ühendusest linnaga. Piirkonna asukohta ja ühendusteid tutvustame lähemalt asukoha lehel, kodude paiknemist aga asendiplaanil.</p>
      </div>
      <div class="col-lg-5">
        <div class="row gutter-y-16">
          @foreach([
            ['Magnoolia tee', 'Vaela küla aadress'],
            ['Kiili vald', 'Harjumaa'],
            ['~20 min', 'Tallinn (sõltub liiklusest)'],
            ['19 kodu', 'kompaktne naabruskond'],
          ] as $stat)
          <div class="col-6">
            <div style="background:#fff;border-radius:12px;padding:20px;height:100%;">
              <div style="font-size:20px;font-weight:700;color:#1d2430;margin-bottom:4px;">{{ $stat[0] }}</div>
              <div style="font-size:13px;color:#6f6a61;line-height:1.5;">{{ $stat[1] }}</div>
            </div>
          </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ── Teaser → asendiplaan / kodud (BOFU) ───────────────────── --}}
<section class="mg-page-section mg-page-section--white">
  <div class="container">
    <div class="row align-items-center gutter-y-28">
      <div class="col-lg-7">
        <div class="mg-section-heading" style="margin-bottom:14px;">
          <div class="mg-section-heading__eyebrow">Kodud Vaela külas</div>
          <h2 class="mg-section-heading__title">Vaata paiknemist ja plaane</h2>
        </div>
        <p style="font-size:15px;color:#6f6a61;line-height:1.75;margin:0 0 18px;">Asendiplaanilt näed, kuidas 19 kodu Magnoolia tee ääres paiknevad, ning lehelt „Kodud ja hinnad“ iga kodu plaani, pinna, staatuse ja hinna. I etapi hinnad on avalikud, II etapi hinnad täpsustatakse — saadavus muutub, seega tasub hinnakirja uudistada.</p>
        <a href="{{ lroute('magnoolia.site-plan') }}" class="zoomvilla-btn" data-mg-analytics="magnoolia_cta_click">Vaata asendiplaani <i class="icon-angle-small-right"></i></a>
      </div>
      <div class="col-lg-5">
        <a href="{{ lroute('magnoolia.homes') }}" style="display:block;border-radius:16px;overflow:hidden;text-decoration:none;">
          <img {!! mg_img('Cam005.0000.jpg', '(max-width:991px) 100vw, 460px') !!} alt="Magnoolia ridaelamukodud Vaela külas, Magnoolia tee ääres" loading="lazy" decoding="async" style="width:100%;height:auto;display:block;border-radius:16px;">
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
          ['t' => 'Kellele sobib?', 'b' => 'Peredele, kes otsivad kodu just Vaela külast või laiemalt Kiili vallast — rahulikku külakeskkonda oma hoovi, terrassi ja rõduga, kuid mugava ühendusega Tallinnaga. Sobib nii kasvavale perele kui ka kaugtöötajale.'],
          ['t' => 'Mida ostja teada saab?', 'b' => 'Kodude täpne paiknemine Magnoolia tee ääres (asendiplaan), kodude plaanid ja pinnad, hooviala, terrassi ja rõdu, energialahendus, parkimine, etapid ja valmimisaeg ning kehtiv hinnakiri lehel „Kodud ja hinnad“.'],
          ['t' => 'Mis on järgmine samm?', 'b' => 'Tutvu asukoha ja asendiplaaniga, vaata saadaolevaid kodusid ja hindu ning küsi personaalset pakkumist. Müügikonsultant Diana aitab leida sulle sobiva kodu Vaela külas.'],
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
  'title'   => 'Korduma kippuvad küsimused Vaela küla kohta',
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
      <a href="{{ lroute('magnoolia.ostuprotsess') }}" class="mg-internal-link"><i class="fas fa-list-ol"></i> Ostuprotsess</a>
      <a href="{{ lroute('magnoolia.kkk') }}" class="mg-internal-link"><i class="fas fa-question-circle"></i> KKK</a>
      <a href="{{ lroute('magnoolia.contact') }}" class="mg-internal-link"><i class="fas fa-envelope"></i> Kontakt</a>
    </div>
  </div>
</section>

@include('sections.magnoolia.page-cta', [
  'title'   => 'Leia oma kodu Vaela külas',
  'sub'     => 'Tutvu asukoha ja asendiplaaniga ning küsi personaalset pakkumist.',
  'buttons' => [
    ['label' => 'Vaata kodusid ja hindu', 'url' => lroute('magnoolia.homes')],
    ['label' => 'Küsi pakkumist', 'url' => lroute('magnoolia.contact'), 'outline' => true, 'inquiry' => true, 'source' => 'lp_ridaelamu-vaela-kula_cta'],
  ]
])

@endsection
