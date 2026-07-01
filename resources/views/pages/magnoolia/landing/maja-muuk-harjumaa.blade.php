{{--
  SEO/Ads landing — /maja-muuk-harjumaa
  Primary keyword: "maja müük Harjumaa". Self-contained, dev-managed, ET, indexable.
  HONEST ANGLE: Magnoolia is a ridaelamu (townhouse), not an isolated detached house.
  It gives house-like comfort (private yard, terrace, lower upkeep) — educate transparently.
  Facts used are verified from the live site only (no invented numbers/claims).
--}}
@extends('layouts.app')

@section('title', 'Maja mugavus Harjumaal | Uus ridaelamukodu privaatse hooviga')
@section('meta_description', 'Otsid maja Harjumaalt? Magnoolia ridaelamukodu Vaela külas, Kiili vallas annab maja tunde — privaatne hoov, terrass, A-energiaklass ja vähem hoolduskoormust. Vaata hindu.')

@section('content')
@php
  $base = rtrim(config('magnoolia.seo.canonical_base', 'https://magnoolia.ee'), '/');
  $url  = $base . '/maja-muuk-harjumaa';

  $ldName = 'Maja mugavus Harjumaal — uus ridaelamukodu privaatse hooviga';
  $ldDesc = 'Maja tunne ridaelamu mugavusega Harjumaal — privaatne hoov, terrass ja A-energiaklass Vaela külas, Kiili vallas, Tallinna lähedal.';

  // Single source of truth for the visible FAQ AND the FAQPage JSON-LD (cannot diverge).
  $faqs = [
    ['q' => 'Kas Magnoolia on eramaja või ridaelamu?',
     'a' => 'Magnoolia on ridaelamu, mitte eraldiseisev eramaja. Iga kodu on siiski oma privaatse hooviala, terrassi ja rõduga ning oma sissepääsuga — see annab maja mugavuse ja privaatsuse ilma suure majahoolduse koormuseta.'],
    ['q' => 'Miks kaaluda ridaelamut, kui otsin maja Harjumaalt?',
     'a' => 'Ridaelamukodu pakub paljut, mida maja ostja otsib — oma hoov, mitu tuba kahel korrusel ja privaatsus —, kuid väiksema hoolduskoormuse ja sageli soodsama hinnaga kui üksik eramaja. Planeeritud elukeskkond ja valmis taristu on lisaväärtus.'],
    ['q' => 'Kui suured on Magnoolia kodud?',
     'a' => 'Kodud on 4–5-toalised, netopinnaga ligikaudu 129 m² (kuni ligikaudu 143 m²). Ruumid paiknevad kahel korrusel, mis annab elukorralduse, mis sarnaneb maja omale.'],
    ['q' => 'Kui kaugel on Tallinn?',
     'a' => 'Vaela küla Kiili vallas asub Tallinna lähedal — sõiduaeg on ligikaudu 20 minutit, sõltuvalt marsruudist ja liiklusest.'],
    ['q' => 'Millised on kodude hinnad ja millal need valmivad?',
     'a' => 'I etapi kodude hinnad on avalikud hinnakirjas, II etapi hinnad täpsustatakse. Ehitus toimub etappidena ja I etapp valmib 2027. Kehtiv hinnakiri ja saadavus on lehel „Kodud ja hinnad“.'],
    ['q' => 'Kas kodud on energiatõhusad?',
     'a' => 'Jah. Magnoolia kodud on A-energiaklassis: maasoojuspump, soojustagastusega ventilatsioon ja põrandaküte aitavad hoida ülalpidamiskulud madalamana kui vanemas majas.'],
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
      ['label' => 'Maja mugavus Harjumaal'],
    ]])
    <div class="mg-page-hero__eyebrow">Maja mugavus Harjumaal · Vaela küla, Kiili vald</div>
    <h1 class="mg-page-hero__title">Maja mugavus Harjumaal — uus ridaelamukodu privaatse hooviga</h1>
    <p class="mg-page-hero__lead">Otsid maja Harjumaalt? Magnoolia ridaelamukodu annab maja tunde — oma hoov, terrass, mitu tuba kahel korrusel ja privaatsus — kuid väiksema hoolduskoormusega ja valmis elukeskkonnas.</p>
    <p class="mg-page-hero__note">Vaela küla · Kiili vald · Harjumaa · ligikaudu 20 min Tallinnast (sõltuvalt liiklusest)</p>
    <div class="mg-page-hero__ctas">
      <a href="{{ lroute('magnoolia.homes') }}" class="zoomvilla-btn" data-mg-analytics="magnoolia_cta_click">Vaata hindu ja plaane <i class="icon-angle-small-right"></i></a>
      <a href="{{ lroute('magnoolia.contact') }}" class="zoomvilla-btn zoomvilla-btn--border" data-mg-inquiry-open data-source-component="lp_maja-muuk-harjumaa_hero" data-mg-analytics="magnoolia_cta_click">Küsi pakkumist</a>
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
          <p style="font-size:16px;color:#3a3530;line-height:1.75;margin:0;">Magnoolia ei ole eraldiseisev eramaja, vaid ridaelamu — kuid see pakub palju sellest, mida maja ostja Harjumaalt otsib. Iga kodu on 4–5-toaline (netopinnaga ligikaudu 129 m², kuni ligikaudu 143 m²), oma privaatse hooviala, terrassi ja rõduga ning oma parkimisega. Elu jaotub kahele korrusele nagu majas, kuid hooldus on väiksem ja elukeskkond on planeeritud. A-energiaklass hoiab ülalpidamiskulud madalad ning Tallinn on ligikaudu 20 minuti kaugusel.</p>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ── Maja tunne, ridaelamu mugavus ─────────────────────────── --}}
<section class="mg-page-section mg-page-section--white">
  <div class="container">
    <div class="mg-section-heading" style="margin-bottom:36px;">
      <div class="mg-section-heading__eyebrow">Maja ostja jaoks</div>
      <h2 class="mg-section-heading__title">Kõik oluline majast — väiksema koormusega</h2>
    </div>
    <div class="row gutter-y-28">
      @php
        $feat = [
          ['icon' => 'fas fa-layer-group',   'title' => 'Elu kahel korrusel', 'body' => 'Magamistoad üleval, ühisruumid all — elukorraldus, mis meenutab maja, ilma korteri kompromissideta.'],
          ['icon' => 'fas fa-seedling',      'title' => 'Oma privaatne hoov',  'body' => 'Igal kodul on oma hooviala, terrass ja rõdu — väliruum lastele, grillimiseks või aiapidamiseks kuulub ainult sinule.'],
          ['icon' => 'fas fa-tools',         'title' => 'Vähem hoolduskoormust','body' => 'Ridaelamu tähendab väiksemat katust, fassaadi ja krunti hooldada kui üksikul eramajal — rohkem aega elamiseks.'],
          ['icon' => 'fas fa-bolt',          'title' => 'A-energiaklass',      'body' => 'Maasoojuspump, soojustagastusega ventilatsioon ja põrandaküte — soe uus kodu väiksemate kuludega kui vanemas majas.'],
          ['icon' => 'fas fa-car',           'title' => 'Oma parkimine',       'body' => 'Autovarjualune ja parkimislahendus kodu juures — mugavus, mida linnakorterist tihti napib.'],
          ['icon' => 'fas fa-map-marker-alt','title' => 'Planeeritud keskkond','body' => 'Valmis taristu ja läbimõeldud arendus Vaela külas — rahulik naabruskond ligikaudu 20 min Tallinnast.'],
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

{{-- ── Page-specific angle: maja vs ridaelamu (honest education) ── --}}
<section class="mg-page-section mg-page-section--cream">
  <div class="container">
    <div class="mg-section-heading" style="margin-bottom:20px;">
      <div class="mg-section-heading__eyebrow">Aus võrdlus</div>
      <h2 class="mg-section-heading__title">Maja või ridaelamu — mis on tegelik erinevus?</h2>
    </div>
    <div class="row gutter-y-28 align-items-start">
      <div class="col-lg-7">
        <p style="font-size:15px;color:#6f6a61;line-height:1.8;margin:0 0 16px;">Oleme ausad: Magnoolia kodu ei ole eraldiseisev eramaja, vaid ridaelamukodu. See tähendab, et jagad naabriga ühte või kahte seina. Kuid sellega ka enamik erinevusi lõpeb — sinu hoov, sissepääs, terrass ja parkimine on sinu omad.</p>
        <p style="font-size:15px;color:#6f6a61;line-height:1.8;margin:0;">Paljude ostjate jaoks on ridaelamu tegelikult parem valik kui maja: samasugune ruumitunne ja oma väliala, kuid madalam hoolduskoormus, energiatõhusam lahendus ja valmis naabruskond. Kui maja otsimise põhjus on rohkem ruumi ja oma hoov — mitte tingimata neli vaba seina —, tasub ridaelamut tõsiselt kaaluda.</p>
      </div>
      <div class="col-lg-5">
        <div style="background:#fff;border-radius:16px;padding:26px 28px;">
          <div style="font-size:12px;color:#c89443;font-weight:700;letter-spacing:.1em;text-transform:uppercase;margin-bottom:14px;">Mida sa ei kaota</div>
          @foreach([
            'Oma privaatne hoov ja terrass',
            '4–5 tuba kahel korrusel',
            'Oma sissepääs ja parkimine',
            'Uus A-energiaklassi kodu',
          ] as $keep)
          <div style="display:flex;gap:10px;align-items:flex-start;margin-bottom:12px;">
            <i class="fas fa-check" style="color:#c89443;margin-top:3px;flex-shrink:0;"></i>
            <span style="font-size:14px;color:#3a3530;line-height:1.55;">{{ $keep }}</span>
          </div>
          @endforeach
          <div style="font-size:12px;color:#c89443;font-weight:700;letter-spacing:.1em;text-transform:uppercase;margin:18px 0 12px;">Mida sa võidad</div>
          @foreach([
            'Väiksem katus, fassaad ja krunt hooldada',
            'Sageli soodsam hind kui eramajal',
            'Valmis taristu ja planeeritud keskkond',
          ] as $win)
          <div style="display:flex;gap:10px;align-items:flex-start;margin-bottom:12px;">
            <i class="fas fa-plus" style="color:#c89443;margin-top:3px;flex-shrink:0;"></i>
            <span style="font-size:14px;color:#3a3530;line-height:1.55;">{{ $win }}</span>
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
          <h2 class="mg-section-heading__title">19 kodu Vaela külas — vali endale sobiv</h2>
        </div>
        <p style="font-size:15px;color:#6f6a61;line-height:1.75;margin:0 0 18px;">Kokku on 19 A-energiaklassi ridaelamukodu, mis valmivad etappidena. Vaata plaane, pindasid, hooviala paiknemist ja hindu ühelt lehelt. I etapi hinnad on avalikud; II etapi hinnad täpsustatakse. Saadavus muutub — soovitame hinnakirja üle vaadata ja pakkumist küsida.</p>
        <a href="{{ lroute('magnoolia.homes') }}" class="zoomvilla-btn" data-mg-analytics="magnoolia_cta_click">Vaata kodusid ja hindu <i class="icon-angle-small-right"></i></a>
      </div>
      <div class="col-lg-5">
        <a href="{{ lroute('magnoolia.site-plan') }}" style="display:block;border-radius:16px;overflow:hidden;text-decoration:none;">
          <img {!! mg_img('Cam005.0000.jpg', '(max-width:991px) 100vw, 460px') !!} alt="Magnoolia ridaelamukodu privaatse hooviala ja terrassiga Vaela külas" loading="lazy" decoding="async" style="width:100%;height:auto;display:block;border-radius:16px;">
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
          ['t' => 'Kellele sobib?', 'b' => 'Ostjatele, kes otsivad maja tunnet — rohkem ruumi ja oma hoovi —, kuid ei soovi üksiku eramaja hoolduskoormust ega hinda. Sobib peredele, lastega peredele ja lemmikloomaomanikele, kes hindavad rahulikku keskkonda Tallinna lähedal.'],
          ['t' => 'Mida ostja teada saab?', 'b' => 'Kodude plaanid ja pinnad, hooviala, terrassi ja rõdu paiknemine, parkimine, energialahendus, etapid ja valmimisaeg ning kehtiv hinnakiri — kõik koondatud lehele „Kodud ja hinnad“. Küsimuste korral aitab müügikonsultant.'],
          ['t' => 'Mis on järgmine samm?', 'b' => 'Vaata saadaolevaid kodusid ja hindu, vali sobiv plaan ning küsi personaalset pakkumist. Müügikonsultant Diana aitab võrrelda valikuid ja leida sinu perele sobiva kodu.'],
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
  'title'   => 'Maja mugavus Harjumaal ootab sind',
  'sub'     => 'Vaata saadaolevaid kodusid ja küsi personaalset pakkumist.',
  'buttons' => [
    ['label' => 'Vaata kodusid ja hindu', 'url' => lroute('magnoolia.homes')],
    ['label' => 'Küsi pakkumist', 'url' => lroute('magnoolia.contact'), 'outline' => true, 'inquiry' => true, 'source' => 'lp_maja-muuk-harjumaa_cta'],
  ]
])

@endsection
