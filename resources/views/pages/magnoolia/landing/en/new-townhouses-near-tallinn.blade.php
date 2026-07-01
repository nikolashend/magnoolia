{{--
  Phase 34.2 SEO/Ads landing — /en/new-townhouses-near-tallinn
  Primary keyword: "townhouse for sale Estonia / near Tallinn". EN buyer-intent, indexable.
  Facts used are verified from the live site only (no invented numbers/claims).
--}}
@extends('layouts.app')

@section('title', 'New Townhouses Near Tallinn | A-Energy Class Homes in Harju County')
@section('meta_description', 'Discover 19 new A-energy-class townhouses for sale near Tallinn in Vaela village, Kiili municipality. Private yard, terrace and balcony. See homes, prices and availability.')

@section('content')
@php
  $base = rtrim(config('magnoolia.seo.canonical_base', 'https://magnoolia.ee'), '/');
  $url  = $base . '/en/new-townhouses-near-tallinn';

  // Single source of truth for the visible FAQ AND the FAQPage JSON-LD (cannot diverge).
  $faqs = [
    ['q' => 'Where exactly are the townhouses located?',
     'a' => 'Magnoolia is in Vaela village, Kiili municipality, Harju County, on Magnoolia tee. It is a quiet residential setting near Tallinn — the drive is about 20 minutes depending on route and traffic.'],
    ['q' => 'How much do the townhouses cost?',
     'a' => 'Prices for the Stage I homes are published in the current price list. Stage II prices will be specified later. Please review the up-to-date price list and availability on the "Homes & prices" page.'],
    ['q' => 'How large are the homes and how many rooms do they have?',
     'a' => 'Each townhouse has 4–5 rooms with a net area of roughly 129 m² (up to about 143 m²). The layouts are designed for everyday family living across two floors.'],
    ['q' => 'Does every home have its own outdoor space?',
     'a' => 'Yes. Every Magnoolia home has a private yard plus a terrace and balcony, giving a house-like feel with far less upkeep than a detached property.'],
    ['q' => 'What makes these homes energy-efficient?',
     'a' => 'The homes are A-energy class, combining a ground-source heat pump, heat-recovery ventilation and underfloor heating — a modern solution that helps keep running costs lower.'],
    ['q' => 'When will the homes be completed?',
     'a' => 'Construction runs in stages, with Stage I scheduled for 2027. The developer, Estlanda OÜ, has been building since 2009; a precise schedule can be confirmed with the sales consultant.'],
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
      "name": @json('New Townhouses Near Tallinn — A-Energy-Class Homes in Harju County'),
      "description": @json('New A-energy-class townhouses for sale near Tallinn in Vaela village, Kiili municipality — each with a private yard, terrace and balcony.'),
      "inLanguage": "en",
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
      ['label' => 'Home', 'url' => route('en.home')],
      ['label' => 'New townhouses near Tallinn'],
    ]])
    <div class="mg-page-hero__eyebrow">Townhouses near Tallinn · Vaela village, Kiili municipality</div>
    <h1 class="mg-page-hero__title">New Townhouses Near Tallinn — A-Energy-Class Homes with Private Yard</h1>
    <p class="mg-page-hero__lead">Magnoolia is a collection of 19 A-energy-class townhouses in Vaela village, Kiili municipality. Each home offers 4–5 rooms, a private yard, a terrace and a balcony, with an easy connection to Tallinn.</p>
    <p class="mg-page-hero__note">Vaela village · Kiili municipality · Harju County · about 20 min from Tallinn (depending on traffic)</p>
    <div class="mg-page-hero__ctas">
      <a href="{{ lroute('magnoolia.homes') }}" class="zoomvilla-btn" data-mg-analytics="magnoolia_cta_click">See homes & prices <i class="icon-angle-small-right"></i></a>
      <a href="{{ lroute('magnoolia.contact') }}" class="zoomvilla-btn zoomvilla-btn--border" data-mg-inquiry-open data-source-component="lp_new-townhouses-near-tallinn_hero" data-mg-analytics="magnoolia_cta_click">Request an offer</a>
    </div>
  </div>
</div>

{{-- ── In short (AEO answer block) ───────────────────────────── --}}
<section class="mg-page-section mg-page-section--warm">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-9">
        <div style="background:#fff;border-radius:16px;padding:30px 34px;border-left:4px solid #c89443;">
          <div style="font-size:11px;color:#c89443;font-weight:700;letter-spacing:.12em;text-transform:uppercase;margin-bottom:10px;">In short</div>
          <p style="font-size:16px;color:#3a3530;line-height:1.75;margin:0;">Magnoolia is a new townhouse development near Tallinn — 19 A-energy-class homes in Vaela village, Kiili municipality. Each home has 4–5 rooms (net area around 129 m²), a private yard, a terrace and a balcony, plus its own parking. A ground-source heat pump, heat-recovery ventilation and underfloor heating support lower running costs. Tallinn is about 20 minutes away, depending on route and traffic.</p>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ── Why Magnoolia ─────────────────────────────────────────── --}}
<section class="mg-page-section mg-page-section--white">
  <div class="container">
    <div class="mg-section-heading" style="margin-bottom:36px;">
      <div class="mg-section-heading__eyebrow">Why Magnoolia</div>
      <h2 class="mg-section-heading__title">The space of a house, the ease of a townhouse</h2>
    </div>
    <div class="row gutter-y-28">
      @php
        $feat = [
          ['icon' => 'fas fa-vector-square', 'title' => '4–5 rooms, ~129 m²', 'body' => 'Generous, well-considered layouts across two floors — room enough for family life and a home office alike.'],
          ['icon' => 'fas fa-seedling',      'title' => 'Private outdoor space', 'body' => 'Every home comes with its own yard, plus a terrace and balcony — outdoor space that belongs to you alone.'],
          ['icon' => 'fas fa-bolt',          'title' => 'A-energy class',        'body' => 'A ground-source heat pump, heat-recovery ventilation and underfloor heating make for an efficient home with lower running costs.'],
          ['icon' => 'fas fa-car',           'title' => 'Your own parking',      'body' => 'A convenient parking solution at the home, including a carport — no daily hunt for a space.'],
          ['icon' => 'fas fa-map-marker-alt','title' => 'Close to Tallinn',      'body' => 'Vaela village in Kiili municipality — a calm setting about 20 minutes from Tallinn, depending on traffic.'],
          ['icon' => 'fas fa-home',          'title' => '19 independent homes',  'body' => 'A low-density, thoughtfully planned development — the feel of a house, not an apartment block.'],
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

{{-- ── Buying from abroad / moving near Tallinn (page-specific angle) ── --}}
<section class="mg-page-section mg-page-section--cream">
  <div class="container">
    <div class="mg-section-heading" style="margin-bottom:32px;">
      <div class="mg-section-heading__eyebrow">Settling near Tallinn</div>
      <h2 class="mg-section-heading__title">A calm base within reach of the capital</h2>
    </div>
    <div class="row gutter-y-16">
      @foreach([
        'Near Tallinn without the density of the city — a residential village setting',
        'A modern, low-maintenance home rather than an older detached property',
        'Private yard, terrace and balcony for outdoor living all year round',
        'Own parking and a carport at the home',
        'Suited to families relocating, remote workers and pet owners',
        'A-energy-class solution built to keep running costs in check',
      ] as $point)
      <div class="col-lg-6">
        <div style="display:flex;gap:12px;align-items:flex-start;background:#fff;border-radius:12px;padding:18px 20px;">
          <i class="fas fa-check" style="color:#c89443;margin-top:3px;flex-shrink:0;"></i>
          <span style="font-size:15px;color:#3a3530;line-height:1.55;">{{ $point }}</span>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>

{{-- ── Available homes (teaser → BOFU) ───────────────────────── --}}
<section class="mg-page-section mg-page-section--white">
  <div class="container">
    <div class="row align-items-center gutter-y-28">
      <div class="col-lg-7">
        <div class="mg-section-heading" style="margin-bottom:14px;">
          <div class="mg-section-heading__eyebrow">Available homes</div>
          <h2 class="mg-section-heading__title">19 homes, delivered in two stages</h2>
        </div>
        <p style="font-size:15px;color:#6f6a61;line-height:1.75;margin:0 0 18px;">Browse every home's floor plan, area, status and price in one place. Stage I prices are public; Stage II prices will be specified. Availability changes over time, so we recommend checking the price list and requesting a personal offer.</p>
        <a href="{{ lroute('magnoolia.homes') }}" class="zoomvilla-btn" data-mg-analytics="magnoolia_cta_click">See homes & prices <i class="icon-angle-small-right"></i></a>
      </div>
      <div class="col-lg-5">
        <a href="{{ lroute('magnoolia.site-plan') }}" style="display:block;border-radius:16px;overflow:hidden;text-decoration:none;">
          <img {!! mg_img('Cam005.0000.jpg', '(max-width:991px) 100vw, 460px') !!} alt="Magnoolia townhouses in Vaela village near Tallinn" loading="lazy" decoding="async" style="width:100%;height:auto;display:block;border-radius:16px;">
        </a>
      </div>
    </div>
  </div>
</section>

{{-- ── Who is it for / What buyers learn / Next step ─────────── --}}
<section class="mg-page-section mg-page-section--warm">
  <div class="container">
    <div class="row gutter-y-28">
      @php
        $aeo = [
          ['t' => 'Who is it for?', 'b' => 'Families ready to move on from apartment living who want more space and a private yard, but not the upkeep of a detached house. It also suits remote workers and anyone who values a calm setting within reach of Tallinn.'],
          ['t' => 'What will you learn?', 'b' => 'Floor plans and areas, the size of the yard, terrace and balcony, parking, the energy solution, the construction stages and completion timing, and the current price list — all gathered on the "Homes & prices" page.'],
          ['t' => 'What is the next step?', 'b' => 'Browse the available homes and prices, choose the one that fits and request a personal offer. Sales consultant Diana will help you find the right solution.'],
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
  'eyebrow' => 'FAQ',
  'title'   => 'Frequently asked questions',
  'bg'      => 'white',
  'faqs'    => $faqs,
])

{{-- ── Internal links ────────────────────────────────────────── --}}
<section class="mg-page-section--cream mg-page-section--sm">
  <div class="container">
    <div class="mg-internal-links">
      <a href="{{ lroute('magnoolia.homes') }}" class="mg-internal-link"><i class="fas fa-table"></i> Homes & prices</a>
      <a href="{{ lroute('magnoolia.site-plan') }}" class="mg-internal-link"><i class="fas fa-map"></i> Site plan</a>
      <a href="{{ lroute('magnoolia.location') }}" class="mg-internal-link"><i class="fas fa-map-marker-alt"></i> Location</a>
      <a href="{{ lroute('magnoolia.finantseerimine') }}" class="mg-internal-link"><i class="fas fa-percent"></i> Financing</a>
      <a href="{{ lroute('magnoolia.ostuprotsess') }}" class="mg-internal-link"><i class="fas fa-list-ol"></i> Buying process</a>
      <a href="{{ lroute('magnoolia.construction') }}" class="mg-internal-link"><i class="fas fa-hard-hat"></i> Construction</a>
      <a href="{{ lroute('magnoolia.contact') }}" class="mg-internal-link"><i class="fas fa-envelope"></i> Contact</a>
    </div>
  </div>
</section>

@include('sections.magnoolia.page-cta', [
  'title'   => 'Find your townhouse near Tallinn',
  'sub'     => 'Browse the available homes and request a personal offer.',
  'buttons' => [
    ['label' => 'See homes & prices', 'url' => lroute('magnoolia.homes')],
    ['label' => 'Request an offer', 'url' => lroute('magnoolia.contact'), 'outline' => true, 'inquiry' => true, 'source' => 'lp_new-townhouses-near-tallinn_cta'],
  ]
])

@endsection
