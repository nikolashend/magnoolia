@extends('layouts.app')

@section('title', $page['title'] ?? 'Magnoolia ehitusinfo ja tehnilised lahendused')
@section('meta_description', $page['description'] ?? '')
@section('og_title', $page['title'] ?? 'Magnoolia ehitusinfo ja tehnilised lahendused')
@section('og_description', $page['description'] ?? '')

@section('content')
@php
    $canonicalBase = rtrim(config('magnoolia.seo.canonical_base', config('app.url', url('/'))), '/');
@endphp

<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@graph": [
    {
      "@@type": "BreadcrumbList",
      "itemListElement": [
        { "@@type": "ListItem", "position": 1, "name": "Avaleht", "item": "{{ $canonicalBase }}" },
        { "@@type": "ListItem", "position": 2, "name": "Ehitusinfo", "item": "{{ $canonicalBase }}/ehitusinfo" }
      ]
    },
    {
      "@@type": "ApartmentComplex",
      "@@id": "{{ $canonicalBase }}/#apartment-complex",
      "name": "Magnoolia ridaelamukodud",
      "description": "A-energiaklassi ridaelamukodud maasoojuspumba, kontrollitud ventilatsiooni, päikesepaneelide ja elektriautode laadimisega.",
      "url": "{{ $canonicalBase }}/ehitusinfo",
      "address": {
        "@@type": "PostalAddress",
        "streetAddress": "Magnoolia tee",
        "addressLocality": "Vaela küla, Kiili vald",
        "addressRegion": "Harjumaa",
        "addressCountry": "EE"
      }
    },
    {
      "@@type": "FAQPage",
      "mainEntity": [
        {
          "@@type": "Question",
          "name": "Mis energiaklass on Magnoolia kodudel?",
          "acceptedAnswer": { "@@type": "Answer", "text": "Magnoolia ridaelamukodud on A-energiaklass, mis tähendab väga madalaid kütte- ja tarbimiskulusid." }
        },
        {
          "@@type": "Question",
          "name": "Kas Magnoolia kodudes on maasoojuspump?",
          "acceptedAnswer": { "@@type": "Answer", "text": "Jah, iga kodu on varustatud oma maasoojuspumbaga. Maasoojus on Eesti kliimas üks efektiivsemaid küttesüsteeme." }
        },
        {
          "@@type": "Question",
          "name": "Kas on olemas EV laadimise võimalus?",
          "acceptedAnswer": { "@@type": "Answer", "text": "Jah, igale kodule on ette nähtud elektriautode laadimise võimalus oma parkimiskohtades." }
        }
      ]
    }
  ]
}
</script>

{{-- ── Page intro hero ────────────────────────────────────────── --}}
<section style="background:#1d2430;padding:60px 0 48px;">
    <div class="container">
        @include('partials.seo.breadcrumb', [
            'items' => [
                ['label' => 'Avaleht', 'url' => route('home')],
                ['label' => 'Ehitusinfo'],
            ]
        ])
        <h1 style="font-size:clamp(28px,4vw,44px);font-weight:700;color:#fff;margin:16px 0 20px;line-height:1.2;">
            {{ $page['h1'] ?? 'Magnoolia ehitusinfo ja tehnilised lahendused' }}
        </h1>
        <p style="color:rgba(255,255,255,.7);font-size:17px;line-height:1.75;max-width:700px;margin-bottom:24px;">
            Magnoolia ridaelamukodud on A-energiaklass — maasoojuspump, kontrollitud ventilatsioon,
            päikesepaneelid ja elektriautode laadimine on iga kodu standardvarustus.
        </p>
        <p style="color:rgba(255,255,255,.5);font-size:14px;max-width:700px;margin-bottom:0;">
            Allpool on ülevaade peamistest tehnilistest lahendustest. Täpsete spetsifikatsioonide küsimiseks
            <a href="{{ route('magnoolia.contact') }}" style="color:#c89443;font-weight:600;text-decoration:none;">võta ühendust</a>.
        </p>
    </div>
</section>

{{-- ── Technical spec cards ───────────────────────────────────── --}}
<section style="background:#f7f4ef;padding:60px 0;">
    <div class="container">
        <div class="sec-title text-center" style="margin-bottom:48px;">
            <div class="sec-title__top justify-content-center">
                <span class="line-left"></span>
                <h6 class="sec-title__tagline bw-split-in-right">Tehnika</h6>
                <span class="line-right"></span>
            </div>
            <h2 class="sec-title__title bw-split-in-left">Millest Magnoolia kodud on tehtud?</h2>
        </div>
        @php
        $techCards = [
            ['icon' => 'fas fa-leaf',       'title' => 'A-energiaklass',
             'text' => 'Kõik 19 kodu vastavad A-energiaklassi nõuetele. See tähendab väga madalat energiatarvet ja madalaid küte- ning kommunaalkulusid.'],
            ['icon' => 'fas fa-thermometer-half', 'title' => 'Maasoojuspump',
             'text' => 'Igal kodul on oma maasoojuspump. Maasoojus on Eesti kliimas üks efektiivsemaid ja majanduslikult soodsaimaid küttesüsteeme.'],
            ['icon' => 'fas fa-wind',        'title' => 'Kontrollitud ventilatsioon',
             'text' => 'Soojustagastusega ventilatsioonisüsteem hoiab sisekliima värskena ja energiakulu madalana — värsket õhku ilma suuremate soojakaotusteta.'],
            ['icon' => 'fas fa-solar-panel', 'title' => 'Päikesepaneelid',
             'text' => 'Päikesepaneelid on osa iga kodu standardvarustusest. Nad toetavad elektritarvet ja vähendavad sõltuvust võrgu kallistumisest.'],
            ['icon' => 'fas fa-charging-station', 'title' => 'EV laadimisvõimalus',
             'text' => 'Igale kodule on ette nähtud elektriautode laadimise võimalus. Kahte parkimiskohta saab vajaduse korral laadimiseks varustada.'],
            ['icon' => 'fas fa-building',   'title' => 'Ehitusstandard',
             'text' => 'Ridaelamu on ehitatud kvaliteetsete materjalide ja kaasaegsete standarditega. Täpsed materjalivalikud ja viimistlused täpsustuvad projekti dokumentatsiooniga.'],
        ];
        @endphp
        <div class="row gutter-y-30">
            @foreach($techCards as $i => $card)
            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-duration="800ms" data-wow-delay="{{ $i * 80 }}ms">
                <div style="background:#fff;border-radius:16px;padding:28px 24px;height:100%;border:1px solid rgba(29,36,48,.07);">
                    <div style="width:52px;height:52px;background:rgba(200,148,67,.12);border-radius:14px;display:flex;align-items:center;justify-content:center;margin-bottom:18px;">
                        <i class="{{ $card['icon'] }}" style="color:#c89443;font-size:20px;"></i>
                    </div>
                    <h3 style="font-size:16px;font-weight:700;color:#1d2430;margin:0 0 10px;">{{ $card['title'] }}</h3>
                    <p style="font-size:14px;color:#6f6a61;line-height:1.7;margin:0;">{{ $card['text'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ── Completion stages ───────────────────────────────────────── --}}
<section style="background:#fff;padding:60px 0;">
    <div class="container">
        <div class="sec-title text-center" style="margin-bottom:40px;">
            <div class="sec-title__top justify-content-center">
                <span class="line-left"></span>
                <h6 class="sec-title__tagline bw-split-in-right">Etapid</h6>
                <span class="line-right"></span>
            </div>
            <h2 class="sec-title__title bw-split-in-left">Ehitusetapid ja valmimisajad</h2>
        </div>
        <div class="row gutter-y-20">
            @php
            $stages = config('magnoolia.stages', []);
            @endphp
            @if(!empty($stages))
                @foreach($stages as $stage)
                <div class="col-lg-6 col-md-6">
                    <div style="background:#fbfaf7;border-radius:14px;padding:24px 28px;border-left:3px solid #c89443;">
                        <h3 style="font-size:16px;font-weight:700;color:#1d2430;margin:0 0 8px;">
                            {{ $stage['name'] ?? '' }}
                        </h3>
                        <p style="font-size:14px;color:#6f6a61;margin:0 0 8px;">
                            <strong>Aadress:</strong> {{ is_array($stage['addresses'] ?? null) ? implode(', ', $stage['addresses']) : ($stage['address'] ?? '') }}
                        </p>
                        <p style="font-size:14px;color:#6f6a61;margin:0;">
                            <strong>Valmimine:</strong> {{ $stage['completion'] ?? '' }}
                        </p>
                    </div>
                </div>
                @endforeach
            @else
                <div class="col-lg-6">
                    <div style="background:#fbfaf7;border-radius:14px;padding:24px 28px;border-left:3px solid #c89443;">
                        <h3 style="font-size:16px;font-weight:700;color:#1d2430;margin:0 0 8px;">I etapp</h3>
                        <p style="font-size:14px;color:#6f6a61;margin:0 0 8px;"><strong>Aadress:</strong> Magnoolia tee 1 ja Magnoolia tee 3</p>
                        <p style="font-size:14px;color:#6f6a61;margin:0;"><strong>Valmimine:</strong> Kevad 2027</p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div style="background:#fbfaf7;border-radius:14px;padding:24px 28px;border-left:3px solid #c89443;">
                        <h3 style="font-size:16px;font-weight:700;color:#1d2430;margin:0 0 8px;">II etapp</h3>
                        <p style="font-size:14px;color:#6f6a61;margin:0 0 8px;"><strong>Aadress:</strong> Magnoolia tee 5, 7, 9 ja 11</p>
                        <p style="font-size:14px;color:#6f6a61;margin:0;"><strong>Valmimine:</strong> Kevad 2028</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>

{{-- ── FAQ accordion section ───────────────────────────────────── --}}
@include('sections.approved.accordion-source')

{{-- ── Tech FAQ ────────────────────────────────────────────────── --}}
<section style="background:#fbfaf7;padding:60px 0;">
    <div class="container">
        <div class="sec-title text-center" style="margin-bottom:36px;">
            <div class="sec-title__top justify-content-center">
                <span class="line-left"></span>
                <h6 class="sec-title__tagline bw-split-in-right">KKK</h6>
                <span class="line-right"></span>
            </div>
            <h2 class="sec-title__title bw-split-in-left">Küsimused ehituse ja tehnika kohta</h2>
        </div>
        @php
        $techFaqs = [
            ['q' => 'Mis energiaklass on Magnoolia kodudel?',
             'a' => 'Magnoolia ridaelamukodud on A-energiaklass, mis tähendab väga madalaid kütte- ja tarbimiskulusid.'],
            ['q' => 'Kas Magnoolia kodudes on maasoojuspump?',
             'a' => 'Jah, iga kodu on varustatud oma maasoojuspumbaga. Maasoojus on Eesti kliimas üks efektiivsemaid küttesüsteeme.'],
            ['q' => 'Kas on elektriautode laadimise võimalus?',
             'a' => 'Jah, igale kodule on ette nähtud EV laadimise võimalus oma parkimiskohtades.'],
            ['q' => 'Mis on kontrollitud ventilatsioon?',
             'a' => 'Soojustagastusega ventilatsioonisüsteem tagab värske õhu ilma suuremate soojakadudeta — hea sisekliima ja madal energiakulu.'],
            ['q' => 'Millal I etapp valmib?',
             'a' => 'I etapp (Magnoolia tee 1 ja 3) valmib planeeritult kevadel 2027. II etapp (Magnoolia tee 5–11) valmib kevadel 2028.'],
        ];
        @endphp
        <div class="row gutter-y-20" itemscope itemtype="https://schema.org/FAQPage">
            @foreach($techFaqs as $i => $faq)
            <div class="col-lg-6 col-md-6 wow fadeInUp" data-wow-duration="800ms" data-wow-delay="{{ $i * 80 }}ms"
                 itemprop="mainEntity" itemscope itemtype="https://schema.org/Question">
                <div style="background:#fff;border-radius:14px;padding:24px;height:100%;border:1px solid rgba(29,36,48,.07);">
                    <h3 itemprop="name" style="font-size:15px;font-weight:700;color:#1d2430;margin:0 0 10px;">{{ $faq['q'] }}</h3>
                    <div itemprop="acceptedAnswer" itemscope itemtype="https://schema.org/Answer">
                        <p itemprop="text" style="font-size:14px;color:#6f6a61;line-height:1.7;margin:0;">{{ $faq['a'] }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ── CTA block ───────────────────────────────────────────────── --}}
<section style="background:#1d2430;padding:60px 0;">
    <div class="container" style="text-align:center;">
        <h2 style="font-size:28px;font-weight:700;color:#fff;margin-bottom:16px;">
            Täpsemad spetsifikatsioonid? Küsi Dianalt.
        </h2>
        <p style="color:rgba(255,255,255,.65);font-size:16px;max-width:500px;margin:0 auto 32px;">
            Diana saab saata täpse tehnikakirjelduse ja vastata kõigile ehitusalastele küsimustele.
        </p>
        <div style="display:flex;gap:14px;justify-content:center;flex-wrap:wrap;">
            <a href="{{ route('magnoolia.contact') }}" class="zoomvilla-btn">
                Küsi pakkumist <i class="icon-angle-small-right"></i>
            </a>
            <a href="{{ route('magnoolia.homes') }}" class="zoomvilla-btn zoomvilla-btn--border">
                Vaata hinnatabelit <i class="icon-angle-small-right"></i>
            </a>
        </div>
    </div>
</section>

@endsection
