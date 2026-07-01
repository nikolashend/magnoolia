{{--
  Phase 34.2 SEO/Ads landing — /ridamajad-harjumaa
  Primary keyword: "ridamaja Harjumaa" (synonym of ridaelamu). Self-contained, dev-managed, ET, indexable.
  Facts used are verified from the live site only (no invented numbers/claims).
--}}
@extends('layouts.app')

@section('title', 'Ridamajad Harjumaal | Uued A-energiaklassi kodud Magnoolias')
@section('meta_description', 'Ridamajad Harjumaal — Magnoolia 19 A-energiaklassi kodu Vaela külas, Kiili vallas. 4–5 tuba, privaatne hoov, terrass ja rõdu, Tallinna lähedal. Vaata plaane ja hindu.')

@section('content')
@php
  $base = rtrim(config('magnoolia.seo.canonical_base', 'https://magnoolia.ee'), '/');
  $url  = $base . '/ridamajad-harjumaa';

  // Single source of truth for the visible FAQ AND the FAQPage JSON-LD (cannot diverge).
  $faqs = [
    ['q' => 'Mis vahe on ridamajal ja ridaelamul?',
     'a' => 'Sisuliselt vahet ei ole — „ridamaja“ ja „ridaelamu“ tähistavad sama tüüpi kodu, kus mitu iseseisvat elamut on ühendatud ühte ritta. Magnoolia kodusid võib nimetada mõlemat pidi: need on 4–5-toalised ridaelamukodud oma hoovi ja sissepääsuga.'],
    ['q' => 'Kui suur on ühe ridamaja pind?',
     'a' => 'Magnoolia kodude netopind on ligikaudu 129 m² (kuni ~143 m²), tubade arv 4–5. Ruumid jagunevad kahele korrusele, mis annab pere igapäevaeluks selge ja mugava jaotuse.'],
    ['q' => 'Kas iga ridamaja juurde kuulub oma maa?',
     'a' => 'Jah. Igal kodul on privaatne hooviala ning lisaks terrass ja rõdu. Väliruum kuulub ainult sinu perele — ilma ühise trepikoja ja korterelamu tundeta.'],
    ['q' => 'Kus Magnoolia ridamajad asuvad?',
     'a' => 'Aadressil Magnoolia tee, Vaela küla, Kiili vald, Harjumaa. Tallinna kesklinna jõuab autoga ligikaudu 20 minutiga, sõltuvalt marsruudist ja liiklusest.'],
    ['q' => 'Kui palju ridamajad maksavad?',
     'a' => 'I etapi kodude hinnad on avaldatud hinnakirjas, II etapi hinnad täpsustatakse. Ajakohase hinnakirja ja saadavuse leiad lehelt „Kodud ja hinnad“.'],
    ['q' => 'Kui energiatõhusad kodud on?',
     'a' => 'Kodud on A-energiaklassis: maasoojuspump, soojustagastusega ventilatsioon ja põrandaküte. See tähendab ühtlast sisekliimat ja mõõdukamaid ülalpidamiskulusid.'],
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
      "name": @json('Ridamajad Harjumaal — A-energiaklassi kodud Magnoolias'),
      "description": @json('A-energiaklassi ridamajad ehk ridaelamukodud Vaela külas, Kiili vallas Harjumaal — oma hoovi ja terrassiga, Tallinna lähedal.'),
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
      ['label' => 'Ridamajad Harjumaal'],
    ]])
    <div class="mg-page-hero__eyebrow">Ridamajad Harjumaal · Vaela küla, Kiili vald</div>
    <h1 class="mg-page-hero__title">Uued ridamajad Harjumaal — rohkem ruumi, privaatne hoov ja mugav ühendus Tallinnaga</h1>
    <p class="mg-page-hero__lead">Magnoolia kodud on ridaelamukodud, mida paljud otsivad ka sõnaga „ridamaja“. 19 A-energiaklassi kodu Vaela külas, Kiili vallas: 4–5 tuba, oma hoov, terrass ja rõdu.</p>
    <p class="mg-page-hero__note">Magnoolia tee · Vaela küla · Kiili vald · Harjumaa · ligikaudu 20 min Tallinnast (sõltuvalt liiklusest)</p>
    <div class="mg-page-hero__ctas">
      <a href="{{ lroute('magnoolia.homes') }}" class="zoomvilla-btn" data-mg-analytics="magnoolia_cta_click">Vaata kodusid ja hindu <i class="icon-angle-small-right"></i></a>
      <a href="{{ lroute('magnoolia.contact') }}" class="zoomvilla-btn zoomvilla-btn--border" data-mg-inquiry-open data-source-component="lp_ridamajad_harjumaa_hero" data-mg-analytics="magnoolia_cta_click">Küsi pakkumist</a>
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
          <p style="font-size:16px;color:#3a3530;line-height:1.75;margin:0;">Kui otsid ridamaja Harjumaal, siis Magnoolia kodud vastavad täpselt sellele soovile — need on ridaelamukodud, mida nimetatakse ka ridamajadeks. Kokku 19 A-energiaklassi kodu Vaela külas, Kiili vallas. Iga kodu on 4–5-toaline (netopind ligikaudu 129 m², kuni ~143 m²), oma hoovi, terrassi ja rõduga ning parkimislahendusega. Maasoojuspump, soojustagastusega ventilatsioon ja põrandaküte hoiavad ülalpidamise mõõdukana. Tallinn on ligikaudu 20 minuti kaugusel.</p>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ── Miks Magnoolia ridamaja ───────────────────────────────── --}}
<section class="mg-page-section mg-page-section--white">
  <div class="container">
    <div class="mg-section-heading" style="margin-bottom:36px;">
      <div class="mg-section-heading__eyebrow">Miks just siia</div>
      <h2 class="mg-section-heading__title">Ridamaja, mis ühendab ruumikuse ja hoolduse lihtsuse</h2>
    </div>
    <div class="row gutter-y-28">
      @php
        $feat = [
          ['icon' => 'fas fa-vector-square', 'title' => '4–5 tuba kahel korrusel', 'body' => 'Netopind ligikaudu 129 m² (kuni ~143 m²) — magamistoad, elumine ja töönurk mahuvad ühte koju ära.'],
          ['icon' => 'fas fa-tree',          'title' => 'Oma maa ja hoov',        'body' => 'Privaatne hooviala koos terrassi ja rõduga — laste mänguks, grillimiseks või hommikukohviks värskes õhus.'],
          ['icon' => 'fas fa-bolt',          'title' => 'A-energiaklass',         'body' => 'Maasoojuspump, soojustagastusega ventilatsioon ja põrandaküte tagavad ühtlase sooja ja mõistlikud kulud.'],
          ['icon' => 'fas fa-car',           'title' => 'Autovarjualune',         'body' => 'Oma parkimislahendus kodu juures — auto on lähedal ja kaitstud, ilma parkimiskoha pärast muretsemata.'],
          ['icon' => 'fas fa-route',         'title' => 'Ühendus Tallinnaga',     'body' => 'Vaela küla Kiili vallas jääb Tallinna lähedale — sõiduaeg on ligikaudu 20 minutit, sõltuvalt marsruudist ja liiklusest.'],
          ['icon' => 'fas fa-drafting-compass','title' => 'Läbimõeldud arendus',  'body' => 'Estlanda OÜ (tegutseb aastast 2009) rajab 19 kodu etappidena — I etapp valmib 2027.'],
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

{{-- ── Page-specific angle: ridamaja = ridaelamu (terminoloogia) ── --}}
<section class="mg-page-section mg-page-section--cream">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-9">
        <div class="mg-section-heading" style="margin-bottom:24px;">
          <div class="mg-section-heading__eyebrow">Terminoloogia selgeks</div>
          <h2 class="mg-section-heading__title">„Ridamaja“ ja „ridaelamu“ — sama kodu, kaks nime</h2>
        </div>
        <p style="font-size:15px;color:#5a544c;line-height:1.8;margin:0 0 16px;">Paljud ostjad otsivad kuulutustest „ridamaja“, teised „ridaelamut“. Praktikas tähendavad mõlemad sama: iseseisvate kodude rida, kus igal elamul on oma sissepääs ja oma maa, kuid seinad on naabritega ühised. Magnoolia kodud on just niisugused — ametlikult ridaelamukodud, argikeeles ridamajad.</p>
        <p style="font-size:15px;color:#5a544c;line-height:1.8;margin:0;">See tähendab, et ei pea valima terminite vahel — Magnoolias saad ruumika kahekorruselise kodu oma hoovi, terrassi ja rõduga, ilma korterelamu ühiste alade ja mürata ning ilma üksiku eramaja täieliku hoolduskoormuseta. Ridamaja formaat on sisuliselt kompromiss korteri mugavuse ja eramaja privaatsuse vahel.</p>
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
          <div class="mg-section-heading__eyebrow">Kodud ja plaanid</div>
          <h2 class="mg-section-heading__title">Vaata iga ridamaja plaani ja hinda</h2>
        </div>
        <p style="font-size:15px;color:#6f6a61;line-height:1.75;margin:0 0 18px;">Kõik 19 kodu ühel lehel — planeeringud, pinnad, staatus ja hinnad. I etapi hinnad on avalikud, II etapi hinnad täpsustatakse. Saadavus muutub jooksvalt, seega tasub hinnakirja üle vaadata ja soovi korral pakkumine küsida.</p>
        <a href="{{ lroute('magnoolia.homes') }}" class="zoomvilla-btn" data-mg-analytics="magnoolia_cta_click">Vaata kodusid ja hindu <i class="icon-angle-small-right"></i></a>
      </div>
      <div class="col-lg-5">
        <a href="{{ lroute('magnoolia.site-plan') }}" style="display:block;border-radius:16px;overflow:hidden;text-decoration:none;">
          <img {!! mg_img('Cam005.0000.jpg', '(max-width:991px) 100vw, 460px') !!} alt="Magnoolia ridamajad Vaela külas — vaade arendusele" loading="lazy" decoding="async" style="width:100%;height:auto;display:block;border-radius:16px;">
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
          ['t' => 'Kellele sobib?', 'b' => 'Peredele, kes tunnevad korterist kitsust ja soovivad oma maad, kuid ei taha eramaja täit hoolduskoormust. Sobib ka neile, kes teevad kaugtööd ja hindavad vaikset keskkonda Tallinna lähedal.'],
          ['t' => 'Mida ostja teada saab?', 'b' => 'Iga kodu planeeringu ja pinna, hooviala, terrassi ja rõdu, parkimise ja energialahenduse, ehituse etapid ning ajakohase hinnakirja — kõik on koondatud lehele „Kodud ja hinnad“.'],
          ['t' => 'Mis on järgmine samm?', 'b' => 'Vali sobiva suuruse ja planeeringuga ridamaja, tutvu hinnaga ja küsi personaalset pakkumist. Müügikonsultant Diana aitab su küsimustele vastuse leida.'],
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
      <a href="{{ lroute('magnoolia.ostuprotsess') }}" class="mg-internal-link"><i class="fas fa-list-ol"></i> Ostuprotsess</a>
      <a href="{{ lroute('magnoolia.kkk') }}" class="mg-internal-link"><i class="fas fa-question-circle"></i> KKK</a>
      <a href="{{ lroute('magnoolia.contact') }}" class="mg-internal-link"><i class="fas fa-envelope"></i> Kontakt</a>
    </div>
  </div>
</section>

@include('sections.magnoolia.page-cta', [
  'title'   => 'Leia oma ridamaja Harjumaal',
  'sub'     => 'Vaata saadaolevaid kodusid ja küsi personaalset pakkumist.',
  'buttons' => [
    ['label' => 'Vaata kodusid ja hindu', 'url' => lroute('magnoolia.homes')],
    ['label' => 'Küsi pakkumist', 'url' => lroute('magnoolia.contact'), 'outline' => true, 'inquiry' => true, 'source' => 'lp_ridamajad_harjumaa_cta'],
  ]
])

@endsection
