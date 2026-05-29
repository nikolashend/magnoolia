@extends('layouts.app')

@section('title', $page['title'] ?? 'Magnoolia kodud ja hinnad')
@section('meta_description', $page['description'] ?? '')
@section('og_title', $page['title'] ?? 'Magnoolia kodud ja hinnad')
@section('og_description', $page['description'] ?? '')

@section('content')
@php
    $canonicalBase = rtrim(config('magnoolia.seo.canonical_base', config('app.url', url('/'))), '/');
@endphp

{{-- Page-level BreadcrumbList schema --}}
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@graph": [
    {
      "@@type": "BreadcrumbList",
      "itemListElement": [
        { "@@type": "ListItem", "position": 1, "name": "Avaleht", "item": "{{ $canonicalBase }}" },
        { "@@type": "ListItem", "position": 2, "name": "Kodud ja hinnad", "item": "{{ $canonicalBase }}/kodud-ja-hinnad" }
      ]
    },
    {
      "@@type": "FAQPage",
      "mainEntity": [
        {
          "@@type": "Question",
          "name": "Mis on Magnoolia kodude hind?",
          "acceptedAnswer": { "@@type": "Answer", "text": "Konkreetsed hinnad täpsustuvad vastavalt kinnitatud hinnatabelile. Saadavuse ja hinnainfo saamiseks saatke päring — Diana Tali täpsustab kõik detailid." }
        },
        {
          "@@type": "Question",
          "name": "Mis on Plaan A ja Plaan B erinevus?",
          "acceptedAnswer": { "@@type": "Answer", "text": "Plaan A on 3 koduga ridaelamu sektsiooni tüüp (~129,6 m², 4 tuba). Plaan B on 4 koduga ridaelamu sektsiooni tüüp (~143,2 m², 5 tuba). Mõlemal on privaatne hooviala, terrass, rõdu ja 2 parkimiskohta." }
        },
        {
          "@@type": "Question",
          "name": "Kuidas valida I ja II etapi vahel?",
          "acceptedAnswer": { "@@type": "Answer", "text": "I etapp (Magnoolia tee 1 ja 3) valmib kevadel 2027 ja sobib neile, kes soovivad varem sisse kolida. II etapp (Magnoolia tee 5–11) valmib kevadel 2028 ja pakub laiemat valikut. Küsige Diana käest konkreetsete kodude saadavust." }
        }
      ]
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
                ['label' => 'Kodud ja hinnad'],
            ]
        ])
        <h1 style="font-size:clamp(28px,4vw,44px);font-weight:700;color:#fff;margin:16px 0 20px;line-height:1.2;">
            {{ $page['h1'] ?? 'Magnoolia kodud ja hinnad' }}
        </h1>
        <p style="color:rgba(255,255,255,.7);font-size:17px;line-height:1.75;max-width:700px;margin-bottom:28px;">
            Magnoolia kodude valik aitab võrrelda aadresse, etappe, plaanitüüpe ja saadavust ühes kohas.
            I etapp hõlmab Magnoolia tee 1 ja 3 kodusid valmimisega kevadel 2027.
            II etapp hõlmab Magnoolia tee 5–11 kodusid valmimisega kevadel 2028.
        </p>
        <p style="color:rgba(255,255,255,.55);font-size:15px;line-height:1.65;max-width:700px;margin-bottom:0;">
            Lõplik hind ja isikliku kasutusõigusega hooviala täpsustuvad kinnitatud hinnatabeliga.
            Kui mõni kodu tundub sobiv, küsige
            <a href="{{ route('magnoolia.contact') }}" style="color:#c89443;font-weight:600;text-decoration:none;">Diana käest</a>
            konkreetse aadressi saadavust, plaani ja pakkumist.
        </p>
    </div>
</section>

{{-- ── SEO keyword intro ───────────────────────────────────────── --}}
<section style="background:#f7f4ef;padding:36px 0 0;">
    <div class="container">
        <div style="background:#fff;border-radius:16px;padding:28px 32px;border-left:3px solid #c89443;max-width:820px;">
            <p style="font-size:15px;color:#4a4540;line-height:1.75;margin:0;">
                Magnoolia on A-energiaklassi ridaelamu Vaela külas, Kiili vallas — ligikaudu 20 minutit Tallinnast.
                Uusarenduse 19 rida&shy;elamu&shy;kodu pakuvad privaatset hooviala, terrassi, rõdu ja läbimõeldud tehnosüsteeme.
                Vaadake allpool ridaelamukoduide tabelit, võrrelge plaane ja küsige saadavust.
            </p>
        </div>
    </div>
</section>

{{-- ── Hinnad table ────────────────────────────────────────────── --}}
@include('sections.magnoolia.hinnad')

{{-- ── Floor plan teaser ──────────────────────────────────────── --}}
<section style="background:#fff;padding:60px 0;">
    <div class="container">
        <div class="sec-title text-center" style="margin-bottom:36px;">
            <div class="sec-title__top justify-content-center">
                <span class="line-left"></span>
                <h6 class="sec-title__tagline bw-split-in-right">Korrusplaanid</h6>
                <span class="line-right"></span>
            </div>
            <h2 class="sec-title__title bw-split-in-left">Plaan A ja Plaan B</h2>
            <p style="color:#6f6a61;margin-top:16px;font-size:16px;max-width:580px;margin-left:auto;margin-right:auto;">
                Kaks plaanitüüpi — 4-toaline Plaan A (~129,6 m²) ja 5-toaline Plaan B (~143,2 m²).
                Mõlemal on privaatne hooviala, terrass ja rõdu.
            </p>
        </div>
        <div style="text-align:center;display:flex;gap:16px;justify-content:center;flex-wrap:wrap;">
            <a href="{{ route('home') }}#plaanid" class="zoomvilla-btn">
                Vaata korrusplaane <i class="icon-angle-small-right"></i>
            </a>
            <a href="{{ route('magnoolia.contact') }}" class="zoomvilla-btn zoomvilla-btn--border">
                Küsi täpset plaani <i class="icon-angle-small-right"></i>
            </a>
        </div>
    </div>
</section>

{{-- ── Page FAQ ────────────────────────────────────────────────── --}}
<section style="background:#fbfaf7;padding:60px 0;">
    <div class="container">
        <div class="sec-title text-center" style="margin-bottom:40px;">
            <div class="sec-title__top justify-content-center">
                <span class="line-left"></span>
                <h6 class="sec-title__tagline bw-split-in-right">KKK</h6>
                <span class="line-right"></span>
            </div>
            <h2 class="sec-title__title bw-split-in-left">Korduma kippuvad küsimused kodude ja hindade kohta</h2>
        </div>
        @php
        $pageFaqs = [
            ['q' => 'Mis on Magnoolia kodude hind?',
             'a' => 'Konkreetsed hinnad täpsustuvad vastavalt kinnitatud hinnatabelile. Saatke päring — Diana Tali täpsustab hinna, plaani ja saadavuse konkreetse aadressi põhjal.'],
            ['q' => 'Mis vahe on Plaan A ja Plaan B vahel?',
             'a' => 'Plaan A on 4-toaline kodu (~129,6 m²), Plaan B on 5-toaline kodu (~143,2 m²). Mõlemad plaanid asuvad ridaelamu sektsioonis privaatse hoovialaga, terrassi ja rõduga.'],
            ['q' => 'Kuidas valida I ja II etapi vahel?',
             'a' => 'I etapp (Magnoolia tee 1 ja 3) valmib kevadel 2027. II etapp (Magnoolia tee 5–11) valmib kevadel 2028. Varasem etapp sobib neile, kes soovivad kiiremini sisse kolida. Küsi Diana käest konkreetsete kodude saadavust.'],
            ['q' => 'Kuidas broneerida Magnoolia kodu?',
             'a' => 'Saatke päring kontaktivormi kaudu või helistage Diana Talile (+372 58 16 40 78). Diana kinnitab saadavuse, selgitab broneerimisprotsessi ja lepib kokku järgmised sammud.'],
            ['q' => 'Kas privaatse hooviala suurus on teada?',
             'a' => 'Hooviala suurus täpsustub koos kinnitatud hinnatabeliga. Igal kodul on oma piiratud kasutusõigusega hooviala.'],
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
            Leidsid sobiva kodu?
        </h2>
        <p style="color:rgba(255,255,255,.65);font-size:16px;max-width:500px;margin:0 auto 32px;">
            Küsi Diana käest konkreetse aadressi saadavust, täpset plaani ja pakkumist.
        </p>
        <div style="display:flex;gap:14px;justify-content:center;flex-wrap:wrap;">
            <a href="{{ route('magnoolia.contact') }}" class="zoomvilla-btn">
                Küsi pakkumist <i class="icon-angle-small-right"></i>
            </a>
            <a href="tel:+37258164078" class="zoomvilla-btn zoomvilla-btn--border">
                <i class="fas fa-phone" style="margin-right:8px;"></i>Helista Dianale
            </a>
            <a href="{{ route('magnoolia.site-plan') }}" class="zoomvilla-btn zoomvilla-btn--border">
                Vaata asendiplaani <i class="icon-angle-small-right"></i>
            </a>
        </div>
    </div>
</section>

@endsection
