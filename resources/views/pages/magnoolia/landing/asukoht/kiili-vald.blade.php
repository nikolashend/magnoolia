{{--
  Phase 34.3 location hub — /asukoht/kiili-vald
  Hub page for "Kiili vald": services, schools, sport & leisure, transport.
  Self-contained, dev-managed, ET, indexable. Facts verified only (no invented numbers/claims).
--}}
@extends('layouts.app')

@section('title', 'Kiili vald | Teenused, koolid ja vaba aeg — Magnoolia kodud')
@section('meta_description', 'Kiili vald Harjumaal pakub kooli, lasteaeda, kunstide kooli, spordihalli, padeli- ja discgolfirajad ning head ühendust Tallinnaga. Tutvu Kiili valla teenustega Magnoolia kodude lähedal.')

@section('content')
@php
  $base = rtrim(config('magnoolia.seo.canonical_base', 'https://magnoolia.ee'), '/');
  $url  = $base . '/asukoht/kiili-vald';

  $ldName = 'Kiili vald - teenused, koolid ja vaba aeg Magnoolia kodude lähedal';
  $ldDesc = 'Kiili valla teenused, koolid, sport ja transport - rahulik elukeskkond Tallinna lähedal, kus asub Magnoolia ridaelamuarendus Vaela külas.';

  // Single source of truth for the visible FAQ AND the FAQPage JSON-LD (cannot diverge).
  $faqs = [
    ['q' => 'Millised koolid ja lasteaiad on Kiili vallas?',
     'a' => 'Kiili vallas tegutsevad Kiili Gümnaasium, Kiili Lasteaed (Kiili keskuses ja Vaela külas) ning Kiili Kunstide Kool. Nii alusharidus kui ka üldharidus on kodu lähedal kättesaadavad.'],
    ['q' => 'Millised spordivõimalused Kiili vallas on?',
     'a' => 'Kiili vallas on Kiili Spordihall ja staadion, Vaela terviserada, Fitness 24/7 jõusaal, FV Padel ja Tennis, Kiili Skate park, discgolfi rajad ning valgustatud kergliiklusteede võrgustik.'],
    ['q' => 'Kui kaugel on Tallinn Kiili vallast?',
     'a' => 'Kiili vald asub Tallinna lähedal — autoga ligikaudu 15–20 minutit ja ühistranspordiga ligikaudu 25–35 minutit, sõltuvalt marsruudist ja liiklusest.'],
    ['q' => 'Millised vaba aja ja kultuurivõimalused Kiili vallas on?',
     'a' => 'Kiili Rahvamaja ja Raamatukogu ning Kiili Valla Noortekeskus pakuvad kultuuri- ja huvitegevust. Lähedal on ka Rae kultuurikeskus Jüris ning kaubandus- ja vaba aja kohad nagu IKEA, Selver ja Decathlon.'],
    ['q' => 'Kus Kiili vallas asub Magnoolia arendus?',
     'a' => 'Magnoolia asub Vaela külas, Kiili vallas, Magnoolia teel. Tegu on 19 A-energiaklassi ridaelamukoduga, mille I etapp valmib 2027.'],
    ['q' => 'Kas Kiili vallas on hea ühistranspordi- ja teedeühendus?',
     'a' => 'Jah. Kiili valda ühendavad Tallinnaga Viljandi maantee ja Tallinna ringtee uued liiklussõlmed ning ühistranspordiliinid Vaela, Kiili, Luige ja Kangru suunal.'],
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
      ['label' => 'Asukoht', 'url' => lroute('magnoolia.location')],
      ['label' => 'Kiili vald'],
    ]])
    <div class="mg-page-hero__eyebrow">Asukoht · Kiili vald, Harjumaa</div>
    <h1 class="mg-page-hero__title">Kiili vald — teenused, koolid ja rahulik elukeskkond Tallinna lähedal</h1>
    <p class="mg-page-hero__lead">Kiili vald ühendab rahuliku maakeskkonna ja hea igapäevataristu: kool ja lasteaed, kunstide kool, spordihall, padel ja discgolf ning mugav ühendus Tallinnaga. Just siia, Vaela külla, kerkib Magnoolia ridaelamuarendus.</p>
    <p class="mg-page-hero__note">Kiili keskus · Vaela küla · Harjumaa · Tallinn autoga ligikaudu 15–20 min (sõltuvalt liiklusest)</p>
    <div class="mg-page-hero__ctas">
      <a href="{{ lroute('magnoolia.homes') }}" class="zoomvilla-btn" data-mg-analytics="magnoolia_cta_click">Vaata kodusid ja hindu <i class="icon-angle-small-right"></i></a>
      <a href="{{ lroute('magnoolia.contact') }}" class="zoomvilla-btn zoomvilla-btn--border" data-mg-inquiry-open data-source-component="hub_kiili-vald_hero" data-mg-analytics="magnoolia_cta_click">Küsi pakkumist</a>
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
          <p style="font-size:16px;color:#3a3530;line-height:1.75;margin:0;">Kiili vald on rahulik elukeskkond Harjumaal, Tallinna lähedal. Kiili keskuses on Kiili Gümnaasium, Kiili Lasteaed, Kiili Kunstide Kool, Kiili Spordihall ja staadion ning Rahvamaja, Raamatukogu ja Noortekeskus. Vaba aja veetmiseks on Vaela terviserada, Fitness 24/7, FV Padel ja Tennis, Kiili Skate park ja discgolfi rajad. Tallinn on autoga ligikaudu 15–20 minuti kaugusel. Vaela külla ehitatakse Magnoolia — 19 A-energiaklassi ridaelamukodu.</p>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ── Kiili keskuse teenused ────────────────────────────────── --}}
<section class="mg-page-section mg-page-section--white">
  <div class="container">
    <div class="mg-section-heading" style="margin-bottom:36px;">
      <div class="mg-section-heading__eyebrow">Kiili keskus</div>
      <h2 class="mg-section-heading__title">Teenused ja haridus kodu lähedal</h2>
    </div>
    <div class="row gutter-y-28">
      @php
        $feat = [
          ['icon' => 'fas fa-graduation-cap', 'title' => 'Kiili Gümnaasium', 'body' => 'Üldharidus kodu lähedal — koolitee jääb Kiili vallas lühikeseks ja mugavaks.'],
          ['icon' => 'fas fa-child',          'title' => 'Kiili Lasteaed',   'body' => 'Lasteaiakohad Kiili keskuses ja Vaela külas — alusharidus otse elukoha läheduses.'],
          ['icon' => 'fas fa-palette',        'title' => 'Kiili Kunstide Kool', 'body' => 'Muusika- ja kunstiharidus lastele ja noortele valla oma kunstide koolis.'],
          ['icon' => 'fas fa-dumbbell',       'title' => 'Spordihall ja staadion', 'body' => 'Kiili Spordihall ja staadion pakuvad ruumi treeninguks ja spordiharrastuseks aastaringselt.'],
          ['icon' => 'fas fa-book',           'title' => 'Rahvamaja ja Raamatukogu', 'body' => 'Kiili Rahvamaja ja Raamatukogu koondavad kultuuri- ja huvitegevuse ühte kohta.'],
          ['icon' => 'fas fa-users',          'title' => 'Noortekeskus',     'body' => 'Kiili Valla Noortekeskus pakub noortele tegevusi ja turvalist kogunemiskohta.'],
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

{{-- ── Sport, vaba aeg ja transport (page-specific local section) ── --}}
<section class="mg-page-section mg-page-section--cream">
  <div class="container">
    <div class="mg-section-heading" style="margin-bottom:32px;">
      <div class="mg-section-heading__eyebrow">Sport, vaba aeg ja transport</div>
      <h2 class="mg-section-heading__title">Aktiivne elu ja mugav liikumine Kiili vallas</h2>
    </div>
    <div class="row gutter-y-16">
      @foreach([
        'Fitness 24/7 jõusaal — treening igal kellaajal',
        'FV Padel ja Tennis — populaarsed pallimängud kodu lähedal',
        'Kiili Skate park ja discgolfi rajad noortele ja peredele',
        'Vaela terviserada ja valgustatud kergliiklusteede võrgustik',
        'Ühendus Tallinnaga Viljandi maantee ja ringtee uute liiklussõlmede kaudu',
        'Ühistranspordiga Tallinna ligikaudu 25–35 min (sõltuvalt marsruudist)',
      ] as $point)
      <div class="col-lg-6">
        <div style="display:flex;gap:12px;align-items:flex-start;background:#fff;border-radius:12px;padding:18px 20px;">
          <i class="fas fa-check" style="color:#c89443;margin-top:3px;flex-shrink:0;"></i>
          <span style="font-size:15px;color:#3a3530;line-height:1.55;">{{ $point }}</span>
        </div>
      </div>
      @endforeach
    </div>
    <p style="font-size:13px;color:#9a9490;line-height:1.65;margin:20px 0 0;">Kaubandus ja vaba aeg on käeulatuses ka valla piiril: IKEA, Selver, Decathlon (esimene Eestis), Kurna Park ja Jüri äripiirkond ning Kiili keskuse kauplused, kohvikud ja pubid.</p>
  </div>
</section>

{{-- ── Magnoolia Kiili vallas (teaser → BOFU) ────────────────── --}}
<section class="mg-page-section mg-page-section--white">
  <div class="container">
    <div class="row align-items-center gutter-y-28">
      <div class="col-lg-7">
        <div class="mg-section-heading" style="margin-bottom:14px;">
          <div class="mg-section-heading__eyebrow">Magnoolia Kiili vallas</div>
          <h2 class="mg-section-heading__title">Uus kodu Vaela külas</h2>
        </div>
        <p style="font-size:15px;color:#6f6a61;line-height:1.75;margin:0 0 18px;">Kiili valla teenuste ja rahuliku keskkonna keskele, Vaela külla Magnoolia teele, kerkib 19 A-energiaklassi ridaelamukodu. 4–5 tuba, ligikaudu 129 m², privaatne hooviala, terrass ja rõdu ning oma parkimine. Maasoojuspump, soojustagastusega ventilatsioon ja põrandaküte hoiavad ülalpidamiskulud madalad. I etapp valmib 2027.</p>
        <a href="{{ lroute('magnoolia.homes') }}" class="zoomvilla-btn" data-mg-analytics="magnoolia_cta_click">Vaata kodusid ja hindu <i class="icon-angle-small-right"></i></a>
      </div>
      <div class="col-lg-5">
        <a href="{{ lroute('magnoolia.homes') }}" style="display:block;border-radius:16px;overflow:hidden;text-decoration:none;">
          <img {!! mg_img('magnoolia_cam09.jpg', '(max-width:991px) 100vw, 460px') !!} alt="Magnoolia ridaelamud Vaela külas, Kiili vallas" loading="lazy" decoding="async" style="width:100%;height:auto;display:block;border-radius:16px;">
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
          ['t' => 'Kellele sobib?', 'b' => 'Peredele, kes soovivad rahulikku elukeskkonda hea igapäevataristuga — kool, lasteaed, sport ja huviharidus kodu lähedal, kuid Tallinn siiski lühikese sõidu kaugusel. Sobib ka neile, kes hindavad aktiivset vaba aega padeli-, discgolfi- ja tervisradade lähedal.'],
          ['t' => 'Mida ostja teada saab?', 'b' => 'Kiili valla teenused ja haridusvõimalused, sport ja vaba aja veetmise kohad ning transpordiühendused Tallinnaga. Lisaks Magnoolia arenduse asukoht Vaela külas, kodude plaanid, energialahendus, etapid ja kehtiv hinnakiri.'],
          ['t' => 'Mis on järgmine samm?', 'b' => 'Tutvu Vaela küla ja Magnoolia arendusega, vaata saadaolevaid kodusid ja hindu ning küsi personaalset pakkumist. Müügikonsultant Diana aitab leida sulle sobiva lahenduse.'],
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
      <a href="{{ route('magnoolia.hub.vaela-kula') }}" class="mg-internal-link"><i class="fas fa-tree"></i> Vaela küla</a>
      <a href="{{ route('magnoolia.hub.tallinna-lahedal') }}" class="mg-internal-link"><i class="fas fa-city"></i> Tallinna lähedal</a>
      <a href="{{ route('magnoolia.lp.uusarendus-kiili') }}" class="mg-internal-link"><i class="fas fa-hammer"></i> Uusarendus Kiili</a>
      <a href="{{ lroute('magnoolia.contact') }}" class="mg-internal-link"><i class="fas fa-envelope"></i> Kontakt</a>
    </div>
  </div>
</section>

@include('sections.magnoolia.page-cta', [
  'title'   => 'Ela Kiili vallas, Vaela külas',
  'sub'     => 'Vaata Magnoolia kodusid ja hindu ning küsi personaalset pakkumist.',
  'buttons' => [
    ['label' => 'Vaata kodusid ja hindu', 'url' => lroute('magnoolia.homes')],
    ['label' => 'Küsi pakkumist', 'url' => lroute('magnoolia.contact'), 'outline' => true, 'inquiry' => true, 'source' => 'hub_kiili-vald_cta'],
  ]
])

@endsection
