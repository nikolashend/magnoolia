{{--
  SEO/Ads landing — /perekodu-tallinna-lahedal
  Primary keyword: "perekodu Tallinna lähedal". Self-contained, dev-managed, ET, indexable.
  Facts used are verified from the live site only (no invented numbers/claims).
--}}
@extends('layouts.app')

@section('title', 'Perekodu Tallinna lähedal | Oma hoovi ja terrassiga uus kodu')
@section('meta_description', 'Perekodu Tallinna lähedal — 19 uut A-energiaklassi ridaelamukodu Vaela külas, Kiili vallas. Ruumi lastele, privaatne hoov, terrass, oma parkimine ja rahulik keskkond.')

@section('content')
@php
  $base = rtrim(config('magnoolia.seo.canonical_base', 'https://magnoolia.ee'), '/');
  $url  = $base . '/perekodu-tallinna-lahedal';

  $ldName = 'Perekodu Tallinna lähedal — oma hoovi ja terrassiga uus kodu';
  $ldDesc = 'Uued A-energiaklassi ridaelamukodud perele Vaela külas, Kiili vallas — privaatse hoovi, terrassi ja oma parkimisega, Tallinna lähedal.';

  // Single source of truth for the visible FAQ AND the FAQPage JSON-LD (cannot diverge).
  $faqs = [
    ['q' => 'Miks on Magnoolia hea valik perekoduks?',
     'a' => 'Kodud on 4–5-toalised (netopinnaga ligikaudu 129 m², kuni ligikaudu 143 m²), privaatse hooviala, terrassi ja rõduga. Ruumi jätkub lastele, esikusse ja panipaikadele ning kodukontorile, ilma üksiku eramaja hoolduskoormuseta.'],
    ['q' => 'Kas lastel on hoovis turvaline mängida?',
     'a' => 'Igal kodul on oma privaatne hooviala ja terrass. Vaela küla Kiili vallas on rahulik ja madala hoonestustihedusega elukeskkond, mis sobib lastega perele paremini kui tiheda liiklusega linnatänav.'],
    ['q' => 'Kui kaugel on Tallinn ja kas sinna saab mugavalt tööle?',
     'a' => 'Vaela küla asub Tallinna lähedal — sõiduaeg on ligikaudu 20 minutit, sõltuvalt marsruudist ja liiklusest. Nii jääb linn käeulatusse, kuid kodu jääb rahulikku keskkonda.'],
    ['q' => 'Kas kodus on ruumi kodukontorile?',
     'a' => 'Jah. 4–5-toalised läbimõeldud plaanid annavad paindlikkust — üks tuba sobib hästi kodukontoriks või lastetoaks, sõltuvalt pere vajadustest.'],
    ['q' => 'Kuidas on lahendatud parkimine ja hoiuruum?',
     'a' => 'Igal kodul on oma parkimislahendus (autovarjualune). Ridaelamukodu kahel korrusel annab loomulikult rohkem panipaiku ja hoiuruumi kui korter.'],
    ['q' => 'Millal kodud valmivad ja kust näen hindu?',
     'a' => 'Ehitus toimub etappidena, I etapp valmib 2027. I etapi hinnad on avalikud hinnakirjas, II etapi hinnad täpsustatakse. Vaata kehtivat hinnakirja ja saadavust lehel „Kodud ja hinnad“.'],
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
      ['label' => 'Perekodu Tallinna lähedal'],
    ]])
    <div class="mg-page-hero__eyebrow">Perekodu · Vaela küla, Kiili vald · Tallinna lähedal</div>
    <h1 class="mg-page-hero__title">Perekodu Tallinna lähedal — ruumi lastele, hoov ja rahulik elukeskkond</h1>
    <p class="mg-page-hero__lead">Magnoolia on 19 uut A-energiaklassi ridaelamukodu, mis on mõeldud pere igapäevaeluks: 4–5 tuba, privaatne hooviala, terrass ja rõdu ning oma parkimine — kõik rahulikus keskkonnas Tallinna lähedal.</p>
    <p class="mg-page-hero__note">Vaela küla · Kiili vald · Harjumaa · ligikaudu 20 min Tallinnast (sõltuvalt liiklusest)</p>
    <div class="mg-page-hero__ctas">
      <a href="{{ lroute('magnoolia.homes') }}" class="zoomvilla-btn" data-mg-analytics="magnoolia_cta_click">Vaata kodusid ja hindu <i class="icon-angle-small-right"></i></a>
      <a href="{{ lroute('magnoolia.contact') }}" class="zoomvilla-btn zoomvilla-btn--border" data-mg-inquiry-open data-source-component="lp_perekodu-tallinna-lahedal_hero" data-mg-analytics="magnoolia_cta_click">Küsi pakkumist</a>
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
          <p style="font-size:16px;color:#3a3530;line-height:1.75;margin:0;">Magnoolia sobib perele, kes soovib korterist rohkem ruumi ja oma õue, kuid ei taha üksiku maja hoolduskoormust. 19 A-energiaklassi ridaelamukodu Vaela külas, Kiili vallas — 4–5 tuba (ligikaudu 129 m², kuni ligikaudu 143 m²), privaatne hooviala, terrass ja rõdu ning oma parkimine. Maasoojuspump, soojustagastusega ventilatsioon ja põrandaküte hoiavad ülalpidamiskulud madalad. Tallinn on ligikaudu 20 minuti kaugusel, sõltuvalt marsruudist ja liiklusest.</p>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ── Miks pere valib Magnoolia ─────────────────────────────── --}}
<section class="mg-page-section mg-page-section--white">
  <div class="container">
    <div class="mg-section-heading" style="margin-bottom:36px;">
      <div class="mg-section-heading__eyebrow">Miks pere valib Magnoolia</div>
      <h2 class="mg-section-heading__title">Kodu, mis kasvab koos perega</h2>
    </div>
    <div class="row gutter-y-28">
      @php
        $feat = [
          ['icon' => 'fas fa-child',          'title' => 'Ruumi lastele',       'body' => '4–5 tuba kahel korrusel — igapäevaruum, laste toad ja panipaigad ilma, et pere teineteisele jalgu jääks.'],
          ['icon' => 'fas fa-seedling',       'title' => 'Oma hoov ja terrass', 'body' => 'Privaatne hooviala, terrass ja rõdu annavad lastele mängukoha ja perele oma väliruumi otse kodu juures.'],
          ['icon' => 'fas fa-laptop-house',   'title' => 'Kodukontori võimalus','body' => 'Läbimõeldud plaanid lubavad ühe toa sisustada tööks või õpperuumiks — paindlik lahendus kaugtöötajale.'],
          ['icon' => 'fas fa-car',            'title' => 'Oma parkimine',       'body' => 'Autovarjualune kodu juures — mugav pere autole ja külalistele, ilma igapäevase koha otsimiseta.'],
          ['icon' => 'fas fa-leaf',           'title' => 'Rahulik keskkond',    'body' => 'Vaela küla madala hoonestustihedusega ümbrus sobib lastega perele paremini kui tiheda liiklusega tänav.'],
          ['icon' => 'fas fa-bolt',           'title' => 'Madalamad kulud',     'body' => 'A-energiaklass: maasoojuspump, soojustagastusega ventilatsioon ja põrandaküte hoiavad ülalpidamise mõistlikuna.'],
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

{{-- ── Page angle: pere igapäev Vaelas ───────────────────────── --}}
<section class="mg-page-section mg-page-section--cream">
  <div class="container">
    <div class="mg-section-heading" style="margin-bottom:32px;">
      <div class="mg-section-heading__eyebrow">Pere igapäev Vaelas</div>
      <h2 class="mg-section-heading__title">Linn käeulatuses, kodu rahus</h2>
    </div>
    <div class="row gutter-y-16">
      @foreach([
        'Lapsed saavad õues mängida oma privaatsel hoovialal',
        'Terrass ja rõdu pikendavad elutuba soojal ajal välja',
        'Üks tuba jääb vabaks kodukontoriks või õppimiseks',
        'Kahekorruseline plaan annab loomulikult rohkem panipaiku',
        'Oma parkimine tähendab, et auto on alati kodu juures',
        'Tallinna töö- ja koolikohad on ligikaudu 20 min kaugusel',
      ] as $point)
      <div class="col-lg-6">
        <div style="display:flex;gap:12px;align-items:flex-start;background:#fff;border-radius:12px;padding:18px 20px;">
          <i class="fas fa-check" style="color:#c89443;margin-top:3px;flex-shrink:0;"></i>
          <span style="font-size:15px;color:#3a3530;line-height:1.55;">{{ $point }}</span>
        </div>
      </div>
      @endforeach
    </div>
    <p style="font-size:14px;color:#8a8378;line-height:1.7;margin:22px 0 0;">Kaugused ja sõiduajad sõltuvad marsruudist ja liiklusest. Täpsemat infot asukoha ja ühenduste kohta leiad asukoha lehelt.</p>
  </div>
</section>

{{-- ── Saadaolevad kodud (teaser → BOFU) ─────────────────────── --}}
<section class="mg-page-section mg-page-section--white">
  <div class="container">
    <div class="row align-items-center gutter-y-28">
      <div class="col-lg-7">
        <div class="mg-section-heading" style="margin-bottom:14px;">
          <div class="mg-section-heading__eyebrow">Saadaolevad kodud</div>
          <h2 class="mg-section-heading__title">Vali perele sobiv plaan</h2>
        </div>
        <p style="font-size:15px;color:#6f6a61;line-height:1.75;margin:0 0 18px;">Vaata kõikide kodude plaane, pindasid, toa­de arvu, staatust ja hindu ühelt lehelt ning võrdle, milline sobib sinu perele. I etapi hinnad on avalikud; II etapi hinnad täpsustatakse. Saadavus muutub — soovitame uudistada hinnakirja ja küsida pakkumist.</p>
        <a href="{{ lroute('magnoolia.homes') }}" class="zoomvilla-btn" data-mg-analytics="magnoolia_cta_click">Vaata kodusid ja hindu <i class="icon-angle-small-right"></i></a>
      </div>
      <div class="col-lg-5">
        <a href="{{ lroute('magnoolia.site-plan') }}" style="display:block;border-radius:16px;overflow:hidden;text-decoration:none;">
          <img {!! mg_img('Cam005.0000.jpg', '(max-width:991px) 100vw, 460px') !!} alt="Magnoolia ridaelamukodud perele Vaela külas" loading="lazy" decoding="async" style="width:100%;height:auto;display:block;border-radius:16px;">
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
          ['t' => 'Kellele sobib?', 'b' => 'Peredele, kes soovivad korterist rohkem ruumi, oma hoovi lastele ja rahulikku elukeskkonda, kuid ei taha üksiku eramaja hoolduskoormust. Sobib hästi ka kaugtöötajatele, kes hindavad kodukontori võimalust Tallinna lähedal.'],
          ['t' => 'Mida ostja teada saab?', 'b' => 'Kodude plaanid ja pinnad, tubade arv, hooviala, terrassi ja rõdu suurused, parkimine, energialahendus, etapid ja valmimisaeg ning kehtiv hinnakiri — kõik koondatud lehele „Kodud ja hinnad“.'],
          ['t' => 'Mis on järgmine samm?', 'b' => 'Vaata saadaolevaid kodusid ja hindu, vali perele sobiv plaan ning küsi personaalset pakkumist. Müügikonsultant Diana aitab leida sulle parima lahenduse.'],
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
      <a href="{{ lroute('magnoolia.kkk') }}" class="mg-internal-link"><i class="fas fa-question-circle"></i> KKK</a>
      <a href="{{ lroute('magnoolia.contact') }}" class="mg-internal-link"><i class="fas fa-envelope"></i> Kontakt</a>
    </div>
  </div>
</section>

@include('sections.magnoolia.page-cta', [
  'title'   => 'Leia oma perele kodu Tallinna lähedal',
  'sub'     => 'Vaata saadaolevaid kodusid ja küsi personaalset pakkumist.',
  'buttons' => [
    ['label' => 'Vaata kodusid ja hindu', 'url' => lroute('magnoolia.homes')],
    ['label' => 'Küsi pakkumist', 'url' => lroute('magnoolia.contact'), 'outline' => true, 'inquiry' => true, 'source' => 'lp_perekodu-tallinna-lahedal_cta'],
  ]
])

@endsection
