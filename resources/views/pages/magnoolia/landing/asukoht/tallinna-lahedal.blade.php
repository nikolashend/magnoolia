{{--
  Phase 34.3 location hub — /asukoht/tallinna-lahedal
  Focus: proximity/commute to Tallinn (CAUTIOUS wording), green & safe environment.
  Self-contained, dev-managed, ET, indexable. Only verified local facts used.
--}}
@extends('layouts.app')

@section('title', 'Tallinna lähedal | Kodu rohelises keskkonnas, linn käeulatuses — Magnoolia')
@section('meta_description', 'Magnoolia Vaela külas, Kiili vallas — Tallinna lähedal. Autoga ~15–20 min, ühistranspordiga ~25–35 min, sõltuvalt liiklusest. Roheline keskkond, linn käeulatuses.')

@section('content')
@php
  $base = rtrim(config('magnoolia.seo.canonical_base', 'https://magnoolia.ee'), '/');
  $url  = $base . '/asukoht/tallinna-lahedal';

  // Single source of truth for the visible FAQ AND the FAQPage JSON-LD (cannot diverge).
  $faqs = [
    ['q' => 'Kui kaugel on Magnoolia Tallinnast?',
     'a' => 'Vaela küla Kiili vallas asub Tallinna vahetus läheduses. Autoga on sõiduaeg ligikaudu 15–20 minutit ja ühistranspordiga ligikaudu 25–35 minutit, sõltuvalt marsruudist ja liiklusest.'],
    ['q' => 'Kuidas Tallinnasse liigelda?',
     'a' => 'Ühendust toetavad Viljandi maantee ja Tallinna ringtee uued liiklussõlmed. Autoga pääseb mugavalt nii Tallinna kesklinna kui ka ringteel asuvatesse sihtkohtadesse; lisaks on olemas ühistranspordiühendus Tallinna suunal.'],
    ['q' => 'Kas Tallinna lähedus tähendab müra ja tihedat liiklust?',
     'a' => 'Ei. Magnoolia asub rahulikus Vaela külas rohelises keskkonnas, kuid heade ühendusteede tõttu jääb linn siiski käeulatusse. Nii saab ühendada vaikse elukeskkonna ja mugava juurdepääsu linnale.'],
    ['q' => 'Kas piirkonnas on igapäevaks vajalik olemas?',
     'a' => 'Jah. Läheduses on Kiili keskuse kauplused ning ringtee suunal IKEA, Selver ja Decathlon (esimene Eestis), samuti Kurna Park ja Jüri äripiirkond. Igapäevased teenused on kättesaadavad ka ilma Tallinna sõitmata.'],
    ['q' => 'Kas Tallinna lähedal saab elada eramaja tunnetusega?',
     'a' => 'Jah. Magnoolia ridaelamukodudel on privaatne hooviala, terrass ja rõdu ning oma parkimine. Nii saab eramajale omase privaatsuse Tallinna läheduses, ilma suure majahoolduseta.'],
    ['q' => 'Millal Magnoolia kodud valmivad?',
     'a' => 'Ehitus toimub etappidena, I etapp valmib 2027. Täpne ajakava täpsustatakse müügikonsultandiga.'],
  ];

  $pageName = 'Tallinna lähedal — kodu rohelises keskkonnas, linn käeulatuses';
  $pageDesc = 'Magnoolia Vaela külas, Kiili vallas — Tallinna lähedal. Autoga ligikaudu 15–20 min, ühistranspordiga 25–35 min, sõltuvalt liiklusest. Roheline ja turvaline keskkond.';
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
      ['label' => 'Asukoht', 'url' => lroute('magnoolia.location')],
      ['label' => 'Tallinna lähedal'],
    ]])
    <div class="mg-page-hero__eyebrow">Asukoht · Vaela küla, Kiili vald · Tallinna lähedal</div>
    <h1 class="mg-page-hero__title">Tallinna lähedal — kodu rohelises keskkonnas, linn käeulatuses</h1>
    <p class="mg-page-hero__lead">Magnoolia asub Vaela külas, Kiili vallas — rahulikus rohelises keskkonnas, kust linn jääb siiski käeulatusse. Autoga on Tallinn ligikaudu 15–20 minuti kaugusel, sõltuvalt marsruudist ja liiklusest.</p>
    <p class="mg-page-hero__note">Vaela küla · Kiili vald · Harjumaa · autoga ~15–20 min, ühistranspordiga ~25–35 min Tallinnast (sõltuvalt liiklusest)</p>
    <div class="mg-page-hero__ctas">
      <a href="{{ lroute('magnoolia.homes') }}" class="zoomvilla-btn" data-mg-analytics="magnoolia_cta_click">Vaata hindu ja plaane <i class="icon-angle-small-right"></i></a>
      <a href="{{ lroute('magnoolia.contact') }}" class="zoomvilla-btn zoomvilla-btn--border" data-mg-inquiry-open data-source-component="hub_tallinna-lahedal_hero" data-mg-analytics="magnoolia_cta_click">Küsi pakkumist</a>
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
          <p style="font-size:16px;color:#3a3530;line-height:1.75;margin:0;">Magnoolia asub Tallinna lähedal — Vaela külas, Kiili vallas Harjumaal. Autoga on sõiduaeg linna ligikaudu 15–20 minutit ja ühistranspordiga ligikaudu 25–35 minutit, sõltuvalt marsruudist ja liiklusest. Viljandi maantee ja Tallinna ringtee uued liiklussõlmed teevad juurdepääsu mugavaks. Nii saab elada rahulikus ja rohelises keskkonnas, kaotamata ühendust linnaga.</p>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ── Miks Tallinna lähedal ─────────────────────────────────── --}}
<section class="mg-page-section mg-page-section--white">
  <div class="container">
    <div class="mg-section-heading" style="margin-bottom:36px;">
      <div class="mg-section-heading__eyebrow">Miks Tallinna lähedal</div>
      <h2 class="mg-section-heading__title">Rahu rohelises keskkonnas, linn käeulatuses</h2>
    </div>
    <div class="row gutter-y-28">
      @php
        $feat = [
          ['icon' => 'fas fa-car', 'title' => 'Autoga ~15–20 min', 'body' => 'Sõiduaeg Tallinna on autoga ligikaudu 15–20 minutit, sõltuvalt marsruudist ja liiklusest — mugav igapäevaseks pendelrändeks.'],
          ['icon' => 'fas fa-bus', 'title' => 'Ühistransport linna', 'body' => 'Ühistranspordiga ligikaudu 25–35 minutit Tallinna suunal, sõltuvalt marsruudist ja liiklusest — alternatiiv autole.'],
          ['icon' => 'fas fa-road', 'title' => 'Uued liiklussõlmed', 'body' => 'Viljandi maantee ja Tallinna ringtee uued liiklussõlmed muudavad juurdepääsu linnale ja ringteele sujuvamaks.'],
          ['icon' => 'fas fa-tree', 'title' => 'Roheline keskkond', 'body' => 'Rahulik Vaela küla rohelises keskkonnas — vaikus ja loodus otse kodu ümber, eemal linnamürast.'],
          ['icon' => 'fas fa-shield-alt', 'title' => 'Turvaline elukeskkond', 'body' => 'Väljakujunenud elupiirkond peredele, kus elukeskkond on rahulik ja lastele sobiv.'],
          ['icon' => 'fas fa-city', 'title' => 'Linn käeulatuses', 'body' => 'Tallinna töökohad, teenused ja vaba aja võimalused on lähedal, ilma et peaks linnas elama.'],
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

{{-- ── Ühendus Tallinnaga (page-specific local section) ──────── --}}
<section class="mg-page-section mg-page-section--cream">
  <div class="container">
    <div class="mg-section-heading" style="margin-bottom:32px;">
      <div class="mg-section-heading__eyebrow">Ühendus linnaga</div>
      <h2 class="mg-section-heading__title">Kuidas Tallinn jääb käeulatusse</h2>
    </div>
    <div class="row gutter-y-16">
      @foreach([
        'Autoga ligikaudu 15–20 minutit, sõltuvalt marsruudist ja liiklusest',
        'Ühistranspordiga ligikaudu 25–35 minutit Tallinna suunal',
        'Viljandi maantee ja Tallinna ringtee uued liiklussõlmed',
        'Mugav juurdepääs ringteele ja selle sihtkohtadesse',
        'Ringtee suunal IKEA, Selver ja Decathlon (esimene Eestis)',
        'Rahulik ja roheline elukeskkond ilma linnaga ühendust kaotamata',
      ] as $point)
      <div class="col-lg-6">
        <div style="display:flex;gap:12px;align-items:flex-start;background:#fff;border-radius:12px;padding:18px 20px;">
          <i class="fas fa-check" style="color:#c89443;margin-top:3px;flex-shrink:0;"></i>
          <span style="font-size:15px;color:#3a3530;line-height:1.55;">{{ $point }}</span>
        </div>
      </div>
      @endforeach
    </div>
    <p style="font-size:13px;color:#8a857c;line-height:1.6;margin:20px 0 0;">Sõiduajad on ligikaudsed ja sõltuvad marsruudist ning liiklusest.</p>
  </div>
</section>

{{-- ── Teaser → BOFU ─────────────────────────────────────────── --}}
<section class="mg-page-section mg-page-section--white">
  <div class="container">
    <div class="row align-items-center gutter-y-28">
      <div class="col-lg-7">
        <div class="mg-section-heading" style="margin-bottom:14px;">
          <div class="mg-section-heading__eyebrow">Saadaolevad kodud</div>
          <h2 class="mg-section-heading__title">Kodu Tallinna lähedal — 19 A-energiaklassi kodu</h2>
        </div>
        <p style="font-size:15px;color:#6f6a61;line-height:1.75;margin:0 0 18px;">Magnoolia on 19 A-energiaklassi ridaelamukodu Vaela külas. Iga kodu on 4–5-toaline (ligikaudu 129 m²), privaatse hooviala, terrassi ja rõduga ning oma parkimisega. Vaata plaane, staatust ja hindu ühelt lehelt ning küsi pakkumist.</p>
        <a href="{{ lroute('magnoolia.homes') }}" class="zoomvilla-btn" data-mg-analytics="magnoolia_cta_click">Vaata kodusid ja hindu <i class="icon-angle-small-right"></i></a>
      </div>
      <div class="col-lg-5">
        <a href="{{ lroute('magnoolia.homes') }}" style="display:block;border-radius:16px;overflow:hidden;text-decoration:none;">
          <img {!! mg_img('magnoolia_cam09.jpg', '(max-width:991px) 100vw, 460px') !!} alt="Magnoolia ridaelamud Vaela külas, Tallinna lähedal" loading="lazy" decoding="async" style="width:100%;height:auto;display:block;border-radius:16px;">
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
          ['t' => 'Kellele sobib?', 'b' => 'Peredele ja kaugtöötajatele, kes soovivad rahulikku ja rohelist elukeskkonda, kuid ei taha linnast liiga kaugele kolida. Sobib neile, kes hindavad head ühendust Tallinnaga ning eelistavad oma hoovi ja privaatsust korteri asemel.'],
          ['t' => 'Mida ostja teada saab?', 'b' => 'Ligikaudsed sõiduajad Tallinna autoga ja ühistranspordiga, ühendusteed ja liiklussõlmed, piirkonna teenused ning kodude plaanid, pinnad, etapid ja kehtiv hinnakiri — kõik ühest kohast.'],
          ['t' => 'Mis on järgmine samm?', 'b' => 'Vaata saadaolevaid kodusid ja hindu, tutvu asukohaga ning küsi personaalset pakkumist. Müügikonsultant Diana aitab leida sulle parima lahenduse.'],
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
      <a href="{{ route('magnoolia.hub.kiili-vald') }}" class="mg-internal-link"><i class="fas fa-map"></i> Kiili vald</a>
      <a href="{{ route('magnoolia.lp.uus-kodu-tallinna-lahedal') }}" class="mg-internal-link"><i class="fas fa-home"></i> Uus kodu Tallinna lähedal</a>
      <a href="{{ route('magnoolia.lp.perekodu-tallinna-lahedal') }}" class="mg-internal-link"><i class="fas fa-users"></i> Perekodu Tallinna lähedal</a>
      <a href="{{ lroute('magnoolia.contact') }}" class="mg-internal-link"><i class="fas fa-envelope"></i> Kontakt</a>
    </div>
  </div>
</section>

@include('sections.magnoolia.page-cta', [
  'title'   => 'Kodu Tallinna lähedal — rohelises keskkonnas',
  'sub'     => 'Vaata saadaolevaid kodusid ja küsi personaalset pakkumist.',
  'buttons' => [
    ['label' => 'Vaata kodusid ja hindu', 'url' => lroute('magnoolia.homes')],
    ['label' => 'Küsi pakkumist', 'url' => lroute('magnoolia.contact'), 'outline' => true, 'inquiry' => true, 'source' => 'hub_tallinna-lahedal_cta'],
  ]
])

@endsection
