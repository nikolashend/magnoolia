{{--
  Phase 34.2 SEO/Ads landing — /a-energiaklassi-ridaelamud
  Primary keyword: "A-energiaklassi ridaelamu". Self-contained, dev-managed, ET, indexable.
  Facts used are verified from the live site only (no invented numbers/claims).
--}}
@extends('layouts.app')

@section('title', 'A-energiaklassi ridaelamud | Energiatõhus kodu Harjumaal')
@section('meta_description', 'A-energiaklassi ridaelamud Magnoolias, Kiili vallas Harjumaal: maasoojuspump, soojustagastusega ventilatsioon ja põrandaküte. Energiatõhus kodu väiksemate kuludega — vaata plaane ja hindu.')

@section('content')
@php
  $base = rtrim(config('magnoolia.seo.canonical_base', 'https://magnoolia.ee'), '/');
  $url  = $base . '/a-energiaklassi-ridaelamud';

  // Single source of truth for the visible FAQ AND the FAQPage JSON-LD (cannot diverge).
  $faqs = [
    ['q' => 'Mida A-energiaklass ostja jaoks tähendab?',
     'a' => 'A-energiaklass on kõrgeim energiatõhususe tase, mis näitab, et hoone on projekteeritud ja ehitatud minimaalse energiakaoga. Praktikas tähendab see soojemat sisekliimat väiksema soojenemiskuluga ning läbimõeldud tehnosüsteeme.'],
    ['q' => 'Milline on Magnoolia kodude energialahendus?',
     'a' => 'Kodude soojus tuleb maasoojuspumbast, õhku vahetab soojustagastusega ventilatsioon ja tubasid soojendab põrandaküte. Need lahendused töötavad koos, et hoida ruumitemperatuur ühtlane ja energiakulu madal.'],
    ['q' => 'Kas A-energiaklass tähendab madalamaid kommunaalkulusid?',
     'a' => 'Energiatõhus hoone kulutab kütmiseks vähem energiat kui vanem, halvemini soojustatud maja. Täpseid summasid ei saa lubada, sest need sõltuvad pere tarbimisest ja energiahindadest, kuid loogika on selge: mida vähem soojust läheb raisku, seda väiksem on kuluosa.'],
    ['q' => 'Mille poolest erineb A-energiaklassi kodu vanemast majast?',
     'a' => 'Vanemad hooned kaotavad sageli soojust seinte, akende ja ventilatsiooni kaudu ning tuginevad tavaküttele. A-energiaklassi kodu on parema soojustusega, kasutab taastuvat maasoojust ja tagastab ventilatsioonist soojuse, mistõttu energiakadu on tunduvalt väiksem.'],
    ['q' => 'Kas maasoojuspump ja põrandaküte on hooldusvabad?',
     'a' => 'Süsteemid on igapäevaselt mugavad ja töötavad automaatselt, kuid nagu iga tehnosüsteem, vajavad need aeg-ajalt hooldust. Ehitusinfo lehel saab tutvuda lahenduste ja materjalidega lähemalt.'],
    ['q' => 'Kust näen A-energiaklassi kodude hindu?',
     'a' => 'I etapi kodude hinnad on avalikud hinnakirjas, II etapi hinnad täpsustatakse. Kõik plaanid, pinnad ja kehtivad hinnad leiad lehelt „Kodud ja hinnad“.'],
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
      "name": @json('A-energiaklassi ridaelamud — energiatõhus kodu Harjumaal'),
      "description": @json('A-energiaklassi ridaelamud Kiili vallas Harjumaal — maasoojuspump, soojustagastusega ventilatsioon ja põrandaküte energiatõhusa kodu jaoks.'),
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
      ['label' => 'A-energiaklassi ridaelamud'],
    ]])
    <div class="mg-page-hero__eyebrow">A-energiaklassi ridaelamud · Vaela küla, Kiili vald</div>
    <h1 class="mg-page-hero__title">A-energiaklassi ridaelamud — energiatõhus kodu väiksemate kuludega</h1>
    <p class="mg-page-hero__lead">Magnoolia kodud on A-energiaklassis: maasoojuspump, soojustagastusega ventilatsioon ja põrandaküte hoiavad sisekliima mugava ja energiakulu tagasihoidliku. 19 ridaelamukodu Harjumaal, Tallinna lähedal.</p>
    <p class="mg-page-hero__note">Vaela küla · Kiili vald · Harjumaa · ligikaudu 20 min Tallinnast (sõltuvalt marsruudist ja liiklusest)</p>
    <div class="mg-page-hero__ctas">
      <a href="{{ lroute('magnoolia.homes') }}" class="zoomvilla-btn" data-mg-analytics="magnoolia_cta_click">Vaata hindu ja plaane <i class="icon-angle-small-right"></i></a>
      <a href="{{ lroute('magnoolia.contact') }}" class="zoomvilla-btn zoomvilla-btn--border" data-mg-inquiry-open data-source-component="lp_a-energiaklassi-ridaelamud_hero" data-mg-analytics="magnoolia_cta_click">Küsi pakkumist</a>
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
          <p style="font-size:16px;color:#3a3530;line-height:1.75;margin:0;">A-energiaklass on hoone kõrgeim energiatõhususe tase — see näitab, kui vähe energiat kulub kodu kütmiseks ja toimimiseks. Magnoolia 19 ridaelamukodus on selleks maasoojuspump, soojustagastusega ventilatsioon ja põrandaküte. Praktikas tähendab see ühtlast soojust, värsket õhku ja väiksemat energiakulu kui vanemas majas. Täpseid kommunaalarveid ei saa lubada, kuid mida vähem soojust raisku läheb, seda väiksem on kuluosa.</p>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ── Energialahendus (feature grid) ────────────────────────── --}}
<section class="mg-page-section mg-page-section--white">
  <div class="container">
    <div class="mg-section-heading" style="margin-bottom:36px;">
      <div class="mg-section-heading__eyebrow">Energialahendus</div>
      <h2 class="mg-section-heading__title">Kolm süsteemi, mis töötavad koos</h2>
    </div>
    <div class="row gutter-y-28">
      @php
        $feat = [
          ['icon' => 'fas fa-temperature-low', 'title' => 'Maasoojuspump',            'body' => 'Kütte- ja soojaveeallikas ammutab soojust maapinnast — taastuv energia, mis vähendab sõltuvust kallimatest kütteliikidest.'],
          ['icon' => 'fas fa-wind',            'title' => 'Soojustagastusega ventilatsioon', 'body' => 'Värske õhk tuleb sisse ja kasutatud õhk läheb välja, kuid soojus jääb suures osas majja — mugav sisekliima ilma soojuskaota.'],
          ['icon' => 'fas fa-th',              'title' => 'Põrandaküte',               'body' => 'Ühtlane, jalataldadelt algav soojus ilma nähtavate radiaatoriteta — madalatemperatuuriline lahendus, mis sobib energiatõhusa majaga.'],
          ['icon' => 'fas fa-bolt',            'title' => 'A-energiaklass',            'body' => 'Kõrgeim energiatõhususe tase — hoone on projekteeritud nii, et energiakadu jääks minimaalseks.'],
          ['icon' => 'fas fa-leaf',            'title' => 'Väiksem energiakulu',       'body' => 'Kui soojust ei kao seinte, akende ega ventilatsiooni kaudu, kulub kütmiseks vähem energiat kui halvemini soojustatud majas.'],
          ['icon' => 'fas fa-home',            'title' => 'Mugav igapäevaelu',         'body' => 'Süsteemid töötavad automaatselt taustal, hoides temperatuuri ja õhukvaliteedi ühtlasena kogu aasta.'],
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

{{-- ── Miks energiatõhusus loeb (page-specific angle) ────────── --}}
<section class="mg-page-section mg-page-section--cream">
  <div class="container">
    <div class="mg-section-heading" style="margin-bottom:32px;">
      <div class="mg-section-heading__eyebrow">Miks energiatõhusus loeb</div>
      <h2 class="mg-section-heading__title">A-energiaklassi kodu vs. vanem maja</h2>
    </div>
    <div class="row gutter-y-28">
      <div class="col-lg-6">
        <div style="background:#fff;border-radius:16px;padding:28px;height:100%;">
          <div style="font-size:15px;font-weight:700;color:#1d2430;margin-bottom:12px;">Kuidas vanem maja energiat kaotab</div>
          <p style="font-size:14px;color:#6f6a61;line-height:1.7;margin:0 0 12px;">Paljud vanemad hooned lasevad soojust läbi õhemate seinte, vanade akende ja ventileerimata ruumide. Kütteks kasutatakse sageli tavalahendusi, mis nõuavad rohkem energiat sama sisetemperatuuri hoidmiseks.</p>
          <p style="font-size:14px;color:#6f6a61;line-height:1.7;margin:0;">Tulemus on kõikuv soojus, tuuletõmbus ja suurem energiakulu — eriti külmadel kuudel.</p>
        </div>
      </div>
      <div class="col-lg-6">
        <div style="background:#fff;border-radius:16px;padding:28px;height:100%;border-left:4px solid #c89443;">
          <div style="font-size:15px;font-weight:700;color:#1d2430;margin-bottom:12px;">Kuidas Magnoolia kodu energiat hoiab</div>
          <p style="font-size:14px;color:#6f6a61;line-height:1.7;margin:0 0 12px;">A-energiaklassi kodu on projekteeritud nii, et soojus jääks majja: parem soojustus, taastuv maasoojus ja ventilatsioon, mis tagastab kasutatud õhu soojuse tagasi ruumidesse.</p>
          <p style="font-size:14px;color:#6f6a61;line-height:1.7;margin:0;">Nii püsib sisekliima ühtlasem ja kütteks kuluv energia väiksem. See on kuluosa loogika, mitte lubadus konkreetse arve suurusest — täpsed summad sõltuvad pere tarbimisest ja energiahindadest.</p>
        </div>
      </div>
    </div>
    <p style="font-size:13px;color:#8a8478;line-height:1.7;margin:20px 0 0;">Soovid näha, kuidas kodud on ehitatud ja millised lahendused kasutusel? Vaata täpsemat <a href="{{ lroute('magnoolia.construction') }}" style="color:#c89443;font-weight:600;">ehitusinfot</a> või tutvu <a href="{{ lroute('magnoolia.homes') }}" style="color:#c89443;font-weight:600;">kodude ja hindadega</a>.</p>
  </div>
</section>

{{-- ── Saadaolevad kodud (teaser → BOFU) ─────────────────────── --}}
<section class="mg-page-section mg-page-section--white">
  <div class="container">
    <div class="row align-items-center gutter-y-28">
      <div class="col-lg-7">
        <div class="mg-section-heading" style="margin-bottom:14px;">
          <div class="mg-section-heading__eyebrow">Saadaolevad kodud</div>
          <h2 class="mg-section-heading__title">Energiatõhusad kodud, 4–5 tuba</h2>
        </div>
        <p style="font-size:15px;color:#6f6a61;line-height:1.75;margin:0 0 18px;">Iga kodu on 4–5-toaline (netopinnaga ligikaudu 129 m², kuni ~143 m²), privaatse hooviala, terrassi ja rõduga ning oma parkimisega. A-energiaklassi lahendus on standard kõikides kodudes. I etapi hinnad on avalikud; II etapi hinnad täpsustatakse.</p>
        <a href="{{ lroute('magnoolia.homes') }}" class="zoomvilla-btn" data-mg-analytics="magnoolia_cta_click">Vaata kodusid ja hindu <i class="icon-angle-small-right"></i></a>
      </div>
      <div class="col-lg-5">
        <a href="{{ lroute('magnoolia.site-plan') }}" style="display:block;border-radius:16px;overflow:hidden;text-decoration:none;">
          <img {!! mg_img('Cam005.0000.jpg', '(max-width:991px) 100vw, 460px') !!} alt="Magnoolia A-energiaklassi ridaelamud Vaela külas" loading="lazy" decoding="async" style="width:100%;height:auto;display:block;border-radius:16px;">
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
          ['t' => 'Kellele sobib?', 'b' => 'Ostjatele, kelle jaoks on olulised madalamad energiakulud ja mugav sisekliima. Sobib peredele, kes soovivad kaasaegset ja energiatõhusat kodu ilma vana maja renoveerimise ja soojustuse muredeta.'],
          ['t' => 'Mida ostja teada saab?', 'b' => 'Kodude energialahenduse — maasoojuspump, soojustagastusega ventilatsioon ja põrandaküte — koos plaanide, pindade, etappide ja kehtiva hinnakirjaga. Tehnilise poole leiab ehitusinfo lehelt.'],
          ['t' => 'Mis on järgmine samm?', 'b' => 'Vaata saadaolevaid kodusid ja hindu ning küsi personaalset pakkumist. Müügikonsultant Diana selgitab hea meelega energialahenduse detaile ja aitab sobiva kodu valida.'],
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
      <a href="{{ lroute('magnoolia.construction') }}" class="mg-internal-link"><i class="fas fa-hard-hat"></i> Ehitusinfo</a>
      <a href="{{ lroute('magnoolia.site-plan') }}" class="mg-internal-link"><i class="fas fa-map"></i> Asendiplaan</a>
      <a href="{{ lroute('magnoolia.finantseerimine') }}" class="mg-internal-link"><i class="fas fa-percent"></i> Finantseerimine</a>
      <a href="{{ lroute('magnoolia.kkk') }}" class="mg-internal-link"><i class="fas fa-question-circle"></i> KKK</a>
      <a href="{{ lroute('magnoolia.contact') }}" class="mg-internal-link"><i class="fas fa-envelope"></i> Kontakt</a>
    </div>
  </div>
</section>

@include('sections.magnoolia.page-cta', [
  'title'   => 'A-energiaklassi kodu, mis hoiab kulusid tagasihoidlikuna',
  'sub'     => 'Vaata energiatõhusaid kodusid ja hindu ning küsi personaalset pakkumist.',
  'buttons' => [
    ['label' => 'Vaata kodusid ja hindu', 'url' => lroute('magnoolia.homes')],
    ['label' => 'Küsi pakkumist', 'url' => lroute('magnoolia.contact'), 'outline' => true, 'inquiry' => true, 'source' => 'lp_a-energiaklassi-ridaelamud_cta'],
  ]
])

@endsection
