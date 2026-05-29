@extends('layouts.app')

@section('title', $page['title'] ?? 'Magnoolia asukoht — Vaela küla, Kiili vald')
@section('meta_description', $page['description'] ?? '')
@section('og_title', $page['title'] ?? 'Magnoolia asukoht — Vaela küla, Kiili vald')
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
        { "@@type": "ListItem", "position": 2, "name": "Asukoht", "item": "{{ $canonicalBase }}/asukoht" }
      ]
    },
    {
      "@@type": "Place",
      "@@id": "{{ $canonicalBase }}/#place",
      "name": "Magnoolia ridaelamukodud — Vaela küla",
      "description": "Magnoolia uusarendus asub Vaela külas, Kiili vallas, Harjumaal. Asukoht pakub Tallinna ligidust kombineerituna vaikse looduskeskkonnaga.",
      "address": {
        "@@type": "PostalAddress",
        "streetAddress": "Magnoolia tee",
        "addressLocality": "Vaela küla, Kiili vald",
        "addressRegion": "Harjumaa",
        "postalCode": "75401",
        "addressCountry": "EE"
      },
      "geo": {
        "@@type": "GeoCoordinates",
        "latitude": 59.3488,
        "longitude": 24.8027
      }
    }
  ]
}
</script>

{{-- ── Page intro hero ────────────────────────────────────────── --}}
<section style="background:#1d2430;padding:220px 0 48px 0;margin-top:-160px;">
    <div class="container">
        @include('partials.seo.breadcrumb', [
            'items' => [
                ['label' => 'Avaleht', 'url' => route('home')],
                ['label' => 'Asukoht'],
            ]
        ])
        <h1 style="font-size:clamp(28px,4vw,44px);font-weight:700;color:#fff;margin:16px 0 20px;line-height:1.2;">
            {{ $page['h1'] ?? 'Magnoolia asukoht — Vaela küla, Kiili vald, Tallinna lähedal' }}
        </h1>
        <p style="color:rgba(255,255,255,.7);font-size:17px;line-height:1.75;max-width:700px;margin-bottom:24px;">
            Magnoolia uusarendus asub Vaela külas, Kiili vallas, Harjumaal.
            Asukoht ühendab vaikse looduskeskkonna ja Tallinna läheduse —
            Tallinna suund jääb ligikaudu 20 minuti autosõidu kaugusele sõltuvalt marsruudist ja liiklusest.
        </p>
    </div>
</section>

{{-- ── Location detail cards ──────────────────────────────────── --}}
<section style="background:#f7f4ef;padding:60px 0;">
    <div class="container">
        <div class="sec-title text-center" style="margin-bottom:48px;">
            <div class="sec-title__top justify-content-center">
                <span class="line-left"></span>
                <h6 class="sec-title__tagline bw-split-in-right">Asukoha eelised</h6>
                <span class="line-right"></span>
            </div>
            <h2 class="sec-title__title bw-split-in-left">Miks Vaela küla ja Kiili vald?</h2>
        </div>
        <div class="row gutter-y-30">
            @php
            $locationCards = [
                ['icon' => 'fas fa-route', 'title' => 'Tallinna lähedus',
                 'text' => 'Tallinna suund jääb ligikaudu 20 minuti autosõidu kaugusele sõltuvalt marsruudist ja liiklusest. Kiili vald on üks kiiremini kasvavatest valdadest Harjumaal.'],
                ['icon' => 'fas fa-leaf', 'title' => 'Loodus ja vaikus',
                 'text' => 'Vaela küla pakub rahulikku elukeskkonda, rohealasid ja vähem liiklusest tingitud müra kui Tallinna lähilinnaosad. Privaatne hooviala igal kodul toetab õueskäimist.'],
                ['icon' => 'fas fa-graduation-cap', 'title' => 'Teenused lähedal',
                 'text' => 'Kiili linn asub vahetult lähedal — seal on lasteaiad, koolid, kauplused ja muud igapäevateenused. Täpsemad vahemaad täpsustub projekti infomaterjalidest.'],
                ['icon' => 'fas fa-bus', 'title' => 'Ühistransport',
                 'text' => 'Kiili piirkonnas toimib ühistranspordiühendus Tallinnaga. Lähima peatuse kaugus täpsustub — küsige Dianalt ajakohast transportinfot.'],
                ['icon' => 'fas fa-tree', 'title' => 'Harjumaa loodusrikkus',
                 'text' => 'Harjumaa pakub loodusmatku, jalg- ja rattaradasid. Asukoht Kiili vallas võimaldab aktiivsema välitegevuse sidumist igapäevaeluga.'],
                ['icon' => 'fas fa-home', 'title' => 'Kasvav piirkond',
                 'text' => 'Kiili vald on aktiivselt arenenud — uusarendusi, infrastruktuuri investeeringuid ja elanike arvu kasvu on piirkonnas täheldatud viimaste aastate jooksul.'],
            ];
            @endphp
            @foreach($locationCards as $i => $card)
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

{{-- ── Map / address block ─────────────────────────────────────── --}}
<section style="background:#fff;padding:60px 0;">
    <div class="container">
        <div class="row align-items-center gutter-y-30">
            <div class="col-lg-5">
                <h2 style="font-size:clamp(22px,3vw,34px);font-weight:700;color:#1d2430;margin-bottom:20px;">
                    Aadress ja kaart
                </h2>
                <p style="color:#6f6a61;font-size:16px;line-height:1.75;margin-bottom:24px;">
                    Magnoolia kodud asuvad aadressil <strong>Magnoolia tee, Vaela küla, Kiili vald, Harjumaa</strong>.
                    Kiili valla lähimad teenused ja ühendused on hästi kättesaadavad.
                </p>
                <ul style="list-style:none;padding:0;margin:0 0 28px;">
                    <li style="display:flex;align-items:flex-start;gap:10px;margin-bottom:12px;font-size:15px;color:#4a4540;">
                        <i class="fas fa-map-marker-alt" style="color:#c89443;margin-top:3px;flex-shrink:0;"></i>
                        Magnoolia tee, Vaela küla, Kiili vald, Harjumaa
                    </li>
                    <li style="display:flex;align-items:flex-start;gap:10px;margin-bottom:12px;font-size:15px;color:#4a4540;">
                        <i class="fas fa-car" style="color:#c89443;margin-top:3px;flex-shrink:0;"></i>
                        Tallinnast ligikaudu 20 min autosõitu (sõltub marsruudist ja liiklusest)
                    </li>
                    <li style="display:flex;align-items:flex-start;gap:10px;font-size:15px;color:#4a4540;">
                        <i class="fas fa-city" style="color:#c89443;margin-top:3px;flex-shrink:0;"></i>
                        Kiili teenused (koolid, kauplused) vahetus läheduses
                    </li>
                </ul>
                <a href="{{ route('magnoolia.contact') }}" class="zoomvilla-btn">
                    Küsi täpsemat infot <i class="icon-angle-small-right"></i>
                </a>
            </div>
            <div class="col-lg-7">
                <div style="border-radius:16px;overflow:hidden;border:1px solid rgba(29,36,48,.1);">
                    <iframe
                        src="https://www.google.com/maps/embed/v1/place?key=AIzaSyD-9tSrke72PouQMnMX-a7eZSW0jkFmBWY&q=Vaela+küla,Kiili+vald,Harjumaa,Estonia"
                        width="100%"
                        height="380"
                        style="border:0;display:block;"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        title="Magnoolia asukoht — Vaela küla, Kiili vald">
                    </iframe>
                </div>
                <p style="font-size:12px;color:#aaa;margin-top:8px;text-align:center;">
                    Täpne aadress: Magnoolia tee, Vaela küla, Kiili vald, Harjumaa
                </p>
            </div>
        </div>
    </div>
</section>

{{-- ── Location FAQ ────────────────────────────────────────────── --}}
<section style="background:#fbfaf7;padding:60px 0;">
    <div class="container">
        <div class="sec-title text-center" style="margin-bottom:36px;">
            <div class="sec-title__top justify-content-center">
                <span class="line-left"></span>
                <h6 class="sec-title__tagline bw-split-in-right">KKK</h6>
                <span class="line-right"></span>
            </div>
            <h2 class="sec-title__title bw-split-in-left">Küsimused asukoha kohta</h2>
        </div>
        @php
        $locationFaqs = [
            ['q' => 'Kui kaugel on Magnoolia Tallinnast?',
             'a' => 'Tallinna suund jääb ligikaudu 20 minuti autosõidu kaugusele sõltuvalt marsruudist ja liiklusest. Kiili vald asub Harjumaal, üks Eesti kiiremini kasvavatest omavalitsustest.'],
            ['q' => 'Kas Vaela külas on ühistransport?',
             'a' => 'Kiili piirkonnas toimib ühistranspordiühendus Tallinnaga. Lähima peatuse täpne kaugus täpsustub — küsige Dianalt (+372 58 16 40 78) ajakohast infot.'],
            ['q' => 'Millised teenused on Kiili vallas?',
             'a' => 'Kiili linnas on lasteaiad, koolid, toidukauplused ja muud igapäevateenused. Piirkond on aktiivselt arenenud ning infrastruktuur on Harjumaa standardite järgi heal tasemel.'],
            ['q' => 'Milline on piirkonna areng tulevikus?',
             'a' => 'Kiili vald on üks kiiremini kasvavatest valdadest Eestis — elanike arv kasvab ja infrastruktuuri investeeringuid tehakse. See toetab koduostu pikaajalise investeeringuna.'],
        ];
        @endphp
        <div class="row gutter-y-20" itemscope itemtype="https://schema.org/FAQPage">
            @foreach($locationFaqs as $i => $faq)
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
            Sobib asukoht? Küsi saadavust.
        </h2>
        <p style="color:rgba(255,255,255,.65);font-size:16px;max-width:500px;margin:0 auto 32px;">
            Diana kinnitab vabade kodude saadavuse ja vastab kõigile asukoha küsimustele.
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
