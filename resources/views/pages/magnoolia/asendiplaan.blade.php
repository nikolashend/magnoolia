@extends('layouts.app')

@section('title', $page['title'] ?? 'Magnoolia asendiplaan')
@section('meta_description', $page['description'] ?? '')
@section('og_title', $page['title'] ?? 'Magnoolia asendiplaan')
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
        { "@@type": "ListItem", "position": 2, "name": "Asendiplaan", "item": "{{ $canonicalBase }}/asendiplaan" }
      ]
    },
    {
      "@@type": "ApartmentComplex",
      "@@id": "{{ $canonicalBase }}/#apartment-complex",
      "name": "Magnoolia ridaelamukodud",
      "description": "19 ridaelamukodu Vaela külas, Kiili vallas. Asendiplaanil on näha I ja II etapi kodude asukoht krundil.",
      "url": "{{ $canonicalBase }}/asendiplaan",
      "address": {
        "@@type": "PostalAddress",
        "streetAddress": "Magnoolia tee",
        "addressLocality": "Vaela küla, Kiili vald",
        "addressRegion": "Harjumaa",
        "addressCountry": "EE"
      }
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
                ['label' => 'Asendiplaan'],
            ]
        ])
        <h1 style="font-size:clamp(28px,4vw,44px);font-weight:700;color:#fff;margin:16px 0 20px;line-height:1.2;">
            {{ $page['h1'] ?? 'Magnoolia asendiplaan' }}
        </h1>
        <p style="color:rgba(255,255,255,.7);font-size:17px;line-height:1.75;max-width:700px;margin-bottom:24px;">
            Magnoolia uusarenduse asendiplaanil on näha 19 ridaelamukodu paiknemine krundil,
            I ja II etapi jaotus ning ühiste rohealade asetus.
        </p>
        <p style="color:rgba(255,255,255,.5);font-size:14px;max-width:700px;margin-bottom:0;">
            Interaktiivne asendiplaan täieneb koos projekti valmimisega.
            Kodu asukoha ja naabrite küsimustega pöördu julgelt
            <a href="{{ route('magnoolia.contact') }}" style="color:#c89443;font-weight:600;text-decoration:none;">Diana poole</a>.
        </p>
    </div>
</section>

{{-- ── Asendiplaan section ─────────────────────────────────────── --}}
@include('sections.magnoolia.asendiplaan')

{{-- ── Page FAQ ────────────────────────────────────────────────── --}}
<section style="background:#fbfaf7;padding:60px 0;">
    <div class="container">
        <div class="sec-title text-center" style="margin-bottom:36px;">
            <div class="sec-title__top justify-content-center">
                <span class="line-left"></span>
                <h6 class="sec-title__tagline bw-split-in-right">KKK</h6>
                <span class="line-right"></span>
            </div>
            <h2 class="sec-title__title bw-split-in-left">Küsimused asendiplaani kohta</h2>
        </div>
        @php
        $pageFaqs = [
            ['q' => 'Mitu kodu on Magnooliasse planeeritud?',
             'a' => '19 ridaelamukodu kokku — I etapis 8 kodu (Magnoolia tee 1 ja 3) ja II etapis 11 kodu (Magnoolia tee 5–11). Kõigil kodudel on privaatne hooviala.'],
            ['q' => 'Mis on I ja II etapi vahe asukoha mõttes?',
             'a' => 'Mõlemad etapid asuvad samal krundil Vaela külas. I etapil on Magnoolia tee ääres veidi erinev positsioon kui II etapil — täpsemaid detaile saab asendiplaanilt või Diana käest.'],
            ['q' => 'Kas asendiplaan on lõplik?',
             'a' => 'Asendiplaan põhineb kinnitatud projektil, kuid detailid (teed, haljastus) võivad ehituse käigus täpsustuda. Peamine paigutus on paika pandud.'],
            ['q' => 'Kuidas saada täpset infot naaberkodude kohta?',
             'a' => 'Küsige Diana Talilt (+372 58 16 40 78) — ta saab näidata asendiplaani detailsemalt ja vastata konkreetse aadressi naabruskonna kohta.'],
        ];
        @endphp
        <div class="row gutter-y-20" itemscope itemtype="https://schema.org/FAQPage">
            @foreach($pageFaqs as $i => $faq)
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
            Huvitav asukoht? Küsi vaba kodu.
        </h2>
        <p style="color:rgba(255,255,255,.65);font-size:16px;max-width:500px;margin:0 auto 32px;">
            Diana kinnitab saadavuse ja selgitab konkreetse aadressi asukohta asendiplaanile.
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
