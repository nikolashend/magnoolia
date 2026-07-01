{{--
  Phase 34.2 SEO/Ads landing — /ridaelamu-oma-hooviga
  Primary keyword: "ridaelamu oma hooviga". Self-contained, dev-managed, ET, indexable.
  Angle: private outdoor life — yard, terrace, balcony. Facts verified from the live site only.
--}}
@extends('layouts.app')

@section('title', 'Ridaelamu oma hooviga | Privaatne väliruum igale kodule')
@section('meta_description', 'Ridaelamu oma hooviga Magnoolias: iga A-energiaklassi kodu Vaela külas Kiili vallas saab privaatse hooviala, terrassi ja rõdu. Vaata plaane ja hindu ning küsi pakkumist.')

@section('content')
@php
  $base = rtrim(config('magnoolia.seo.canonical_base', 'https://magnoolia.ee'), '/');
  $url  = $base . '/ridaelamu-oma-hooviga';

  // Single source of truth for the visible FAQ AND the FAQPage JSON-LD (cannot diverge).
  $faqs = [
    ['q' => 'Kas igal kodul on tõesti oma hoov?',
     'a' => 'Jah. Iga Magnoolia ridaelamukodu juurde kuulub privaatne hooviala, mida kasutab ainult selle kodu pere. Lisaks on igal kodul terrass ja rõdu, nii et väliruumi jagub nii einestamiseks kui puhkamiseks.'],
    ['q' => 'Kui suur on hoov ja terrass?',
     'a' => 'Hoovi ja terrassi täpne suurus sõltub konkreetsest kodust ja selle asukohast krundil. Täpsed mõõdud, plaanid ja piirid leiad iga kodu juurest lehel „Kodud ja hinnad“.'],
    ['q' => 'Kas hoov sobib lastele ja lemmikloomadele?',
     'a' => 'Privaatne hooviala annab peredele oma väliruumi laste mänguks ja lemmikloomadele. Kuna tegemist on eraldi hoovialaga, ei pea igapäevaselt jagama ühist õue naabritega nagu korterelamus.'],
    ['q' => 'Kui palju hooldust hoov nõuab?',
     'a' => 'Ridaelamu hoov on kompaktsem kui üksiku eramaja krunt, mistõttu igapäevane hooldus on jõukohasem. Saad eramaja väliruumi tunde ilma suure krundi koormuseta.'],
    ['q' => 'Kas kodudel on A-energiaklass?',
     'a' => 'Jah. Magnoolia kodud on A-energiaklassis: maasoojuspump, soojustagastusega ventilatsioon ja põrandaküte aitavad hoida ülalpidamiskulud madalamana.'],
    ['q' => 'Millal kodud valmivad ja mis on hinnad?',
     'a' => 'Ehitus toimub etappidena, I etapp valmib 2027. I etapi hinnad on avalikud hinnakirjas, II etapi hinnad täpsustatakse. Vaata kehtivat hinnakirja lehel „Kodud ja hinnad“.'],
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
      "name": @json('Ridaelamu oma hooviga — privaatne väliruum igale kodule'),
      "description": @json('A-energiaklassi ridaelamukodud oma privaatse hoovi, terrassi ja rõduga Vaela külas, Kiili vallas Harjumaal.'),
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
      ['label' => 'Ridaelamu oma hooviga'],
    ]])
    <div class="mg-page-hero__eyebrow">Oma hoov, terrass ja rõdu · Vaela küla, Kiili vald</div>
    <h1 class="mg-page-hero__title">Ridaelamu oma hooviga — privaatne väliruum igale kodule</h1>
    <p class="mg-page-hero__lead">Magnoolia kodud on loodud oma väliruumi ümber: iga A-energiaklassi ridaelamukodu saab privaatse hooviala ning lisaks terrassi ja rõdu. Rohkem õhku, valgust ja oma nurka pere jaoks — Tallinna lähedal.</p>
    <p class="mg-page-hero__note">Privaatne hooviala + terrass + rõdu · Vaela küla · Kiili vald · Harjumaa</p>
    <div class="mg-page-hero__ctas">
      <a href="{{ lroute('magnoolia.homes') }}" class="zoomvilla-btn" data-mg-analytics="magnoolia_cta_click">Vaata kodusid ja hoove <i class="icon-angle-small-right"></i></a>
      <a href="{{ lroute('magnoolia.contact') }}" class="zoomvilla-btn zoomvilla-btn--border" data-mg-inquiry-open data-source-component="lp_ridaelamu-oma-hooviga_hero" data-mg-analytics="magnoolia_cta_click">Küsi pakkumist</a>
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
          <p style="font-size:16px;color:#3a3530;line-height:1.75;margin:0;">Magnoolia on 19 A-energiaklassi ridaelamukodu, kus igal kodul on oma väliruum: privaatne hooviala, terrass ja rõdu. See annab eramaja õueelu tunde ilma suure krundi hoolduskoormuseta. Kodud on 4–5-toalised (netopinnaga ligikaudu 129 m²) oma parkimisega, Vaela külas Kiili vallas. Hoovi ja terrassi täpsed mõõdud sõltuvad konkreetsest kodust ning on toodud lehel „Kodud ja hinnad“.</p>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ── Oma väliruumi eelised ─────────────────────────────────── --}}
<section class="mg-page-section mg-page-section--white">
  <div class="container">
    <div class="mg-section-heading" style="margin-bottom:36px;">
      <div class="mg-section-heading__eyebrow">Oma väliruum</div>
      <h2 class="mg-section-heading__title">Hoov, terrass ja rõdu — kolm väliruumi ühes kodus</h2>
    </div>
    <div class="row gutter-y-28">
      @php
        $feat = [
          ['icon' => 'fas fa-seedling',      'title' => 'Privaatne hooviala', 'body' => 'Igal kodul oma hoov, mida kasutab ainult sinu pere — oma nurk õues, ilma ühist õue naabritega jagamata.'],
          ['icon' => 'fas fa-couch',         'title' => 'Terrass välielamiseks', 'body' => 'Terrass loob loomuliku ülemineku toa ja hoovi vahel — koht hommikukohvile, õhtusöögile ja külaliste võõrustamiseks.'],
          ['icon' => 'fas fa-cloud-sun',     'title' => 'Rõdu ülakorrusel',   'body' => 'Lisaks hoovile ja terrassile on kodul rõdu — veel üks väliruum, kust nautida õhtupäikest ja avarat vaadet.'],
          ['icon' => 'fas fa-broom',         'title' => 'Jõukohane hooldus',  'body' => 'Ridaelamu hoov on kompaktsem kui üksiku maja krunt, seega väliruumi hooldus jääb igapäevaselt jõukohaseks.'],
          ['icon' => 'fas fa-user-shield',   'title' => 'Rohkem privaatsust', 'body' => 'Oma hooviala ja läbimõeldud paigutus annavad korterist enam privaatsust — oma väliruum ilma pealtvaatajateta.'],
          ['icon' => 'fas fa-bolt',          'title' => 'A-energiaklass',     'body' => 'Maasoojuspump, soojustagastusega ventilatsioon ja põrandaküte — soe kodu ka siis, kui hoovis on jahe.'],
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

{{-- ── Elu oma hoovis (page-specific angle) ──────────────────── --}}
<section class="mg-page-section mg-page-section--cream">
  <div class="container">
    <div class="mg-section-heading" style="margin-bottom:32px;">
      <div class="mg-section-heading__eyebrow">Elu oma hoovis</div>
      <h2 class="mg-section-heading__title">Väliruum, mis muudab argipäeva</h2>
    </div>
    <div class="row gutter-y-16">
      @foreach([
        'Lapsed saavad mängida oma hoovis, silmapiiril kodu aknast',
        'Lemmikloomale oma õueala, ilma iga kord jalutama minemata',
        'Terrass grillimiseks ja sõprade võõrustamiseks suveõhtutel',
        'Rõdu hommikukohvile ja õhtupäikese nautimiseks',
        'Ruumi aiataimedele, potililledele või väiksele peenrale',
        'Oma väliruum korteri asemel — vähem müra, rohkem privaatsust',
      ] as $point)
      <div class="col-lg-6">
        <div style="display:flex;gap:12px;align-items:flex-start;background:#fff;border-radius:12px;padding:18px 20px;">
          <i class="fas fa-check" style="color:#c89443;margin-top:3px;flex-shrink:0;"></i>
          <span style="font-size:15px;color:#3a3530;line-height:1.55;">{{ $point }}</span>
        </div>
      </div>
      @endforeach
    </div>
    <p style="font-size:14px;color:#8a857c;line-height:1.7;margin:22px 0 0;">Hoovi ja terrassi mõõdud erinevad kodude lõikes — vaata iga kodu täpseid plaane ja piire lehel „Kodud ja hinnad“.</p>
  </div>
</section>

{{-- ── Vaata kodusid ja hoove (teaser → BOFU) ────────────────── --}}
<section class="mg-page-section mg-page-section--white">
  <div class="container">
    <div class="row align-items-center gutter-y-28">
      <div class="col-lg-7">
        <div class="mg-section-heading" style="margin-bottom:14px;">
          <div class="mg-section-heading__eyebrow">Vaata kodusid</div>
          <h2 class="mg-section-heading__title">Iga kodu koos oma hoovialaga</h2>
        </div>
        <p style="font-size:15px;color:#6f6a61;line-height:1.75;margin:0 0 18px;">Asendiplaanilt näed, kuidas hoovialad kodude vahel paiknevad, ning iga kodu juurest leiad plaani, pinna, staatuse ja hinna. I etapi hinnad on avalikud; II etapi hinnad täpsustatakse. Saadavus muutub — soovitame hinnakirja uudistada ja küsida pakkumist.</p>
        <a href="{{ lroute('magnoolia.homes') }}" class="zoomvilla-btn" data-mg-analytics="magnoolia_cta_click">Vaata kodusid ja hindu <i class="icon-angle-small-right"></i></a>
      </div>
      <div class="col-lg-5">
        <a href="{{ lroute('magnoolia.site-plan') }}" style="display:block;border-radius:16px;overflow:hidden;text-decoration:none;">
          <img {!! mg_img('Cam005.0000.jpg', '(max-width:991px) 100vw, 460px') !!} alt="Magnoolia ridaelamud oma hoovialadega Vaela külas" loading="lazy" decoding="async" style="width:100%;height:auto;display:block;border-radius:16px;">
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
          ['t' => 'Kellele sobib?', 'b' => 'Peredele, kes soovivad korterist välja kasvada ja hindavad oma väliruumi — hoovi lastele ja lemmikloomadele, terrassi ja rõdu puhkuseks. Sobib neile, kes tahavad eramaja õueelu tunnet ilma suure krundi hoolduskoormuseta.'],
          ['t' => 'Mida ostja teada saab?', 'b' => 'Iga kodu plaani ja pinna, hooviala paigutuse asendiplaanil, terrassi ja rõdu, parkimise, energialahenduse ning etapid ja valmimisaja. Hoovi täpsed mõõdud ja kehtiv hinnakiri on koondatud lehele „Kodud ja hinnad“.'],
          ['t' => 'Mis on järgmine samm?', 'b' => 'Vaata saadaolevaid kodusid ja nende hoovialasid asendiplaanil, vali sobiv kodu ning küsi personaalset pakkumist. Müügikonsultant Diana aitab leida sulle parima lahenduse.'],
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
      <a href="{{ lroute('magnoolia.construction') }}" class="mg-internal-link"><i class="fas fa-hard-hat"></i> Ehitusinfo</a>
      <a href="{{ lroute('magnoolia.ostuprotsess') }}" class="mg-internal-link"><i class="fas fa-list-ol"></i> Ostuprotsess</a>
      <a href="{{ lroute('magnoolia.kkk') }}" class="mg-internal-link"><i class="fas fa-question-circle"></i> KKK</a>
      <a href="{{ lroute('magnoolia.contact') }}" class="mg-internal-link"><i class="fas fa-envelope"></i> Kontakt</a>
    </div>
  </div>
</section>

@include('sections.magnoolia.page-cta', [
  'title'   => 'Leia oma kodu koos privaatse hooviga',
  'sub'     => 'Vaata saadaolevaid kodusid ja nende hoovialasid ning küsi personaalset pakkumist.',
  'buttons' => [
    ['label' => 'Vaata kodusid ja hindu', 'url' => lroute('magnoolia.homes')],
    ['label' => 'Küsi pakkumist', 'url' => lroute('magnoolia.contact'), 'outline' => true, 'inquiry' => true, 'source' => 'lp_ridaelamu-oma-hooviga_cta'],
  ]
])

@endsection
