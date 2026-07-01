{{--
  Phase 34.2 SEO/Ads landing — /en/terraced-houses-harju-county
  Primary keyword: "terraced houses Harju County". Standalone, single-locale (EN), indexable.
  Facts used are verified from the live site only (no invented numbers/claims).
--}}
@extends('layouts.app')

@section('title', 'Terraced Houses in Harju County | New A-Class Homes Near Tallinn')
@section('meta_description', 'Magnoolia offers new A-energy-class terraced houses in Harju County — 19 family homes in Vaela village, Kiili municipality, near Tallinn. See plans, prices and availability, and request an offer.')

@section('content')
@php
  $base = rtrim(config('magnoolia.seo.canonical_base', 'https://magnoolia.ee'), '/');
  $url  = $base . '/en/terraced-houses-harju-county';

  // Single source of truth for the visible FAQ AND the FAQPage JSON-LD (cannot diverge).
  $faqs = [
    ['q' => 'Where in Harju County are the terraced houses located?',
     'a' => 'The Magnoolia terraced houses sit on Magnoolia tee in Vaela village, Kiili municipality — a calm residential pocket of Harju County that keeps you close to Tallinn.'],
    ['q' => 'How far is Tallinn from the development?',
     'a' => 'Tallinn is roughly a 20-minute drive, depending on the route and traffic. It is a practical commute for families who want more space without leaving the capital region behind.'],
    ['q' => 'How many terraced houses are there and how big are they?',
     'a' => 'There are 19 homes in total, each with 4–5 rooms and a net area of about 129 m² (up to roughly 143 m²) — enough room for family life and a home office.'],
    ['q' => 'Does every terraced house have its own outdoor space?',
     'a' => 'Yes. Each home comes with a private yard, plus a terrace and a balcony, so you get the feel of a detached house without the upkeep of a large plot.'],
    ['q' => 'What makes these homes A-energy-class?',
     'a' => 'Each home uses a ground-source heat pump, heat-recovery ventilation and underfloor heating. This A-energy-class setup is designed to keep running costs lower.'],
    ['q' => 'When will the homes be ready and are prices published?',
     'a' => 'Construction runs in stages: Stage I is due in 2027. Stage I prices are public in the price list, while Stage II prices will be specified later. See “Homes and prices” for current details.'],
  ];

  $ldName = 'Terraced Houses in Harju County — A-Class Homes Near Tallinn';
  $ldDesc = 'New A-energy-class terraced houses in Vaela village, Kiili municipality, Harju County — private yards and terraces, a short drive from Tallinn.';
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
      "inLanguage": "en-EE",
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
      ['label' => 'Home', 'url' => lroute('home')],
      ['label' => 'Terraced Houses in Harju County'],
    ]])
    <div class="mg-page-hero__eyebrow">Terraced houses in Harju County · Vaela village, Kiili municipality</div>
    <h1 class="mg-page-hero__title">Terraced Houses in Harju County — Modern A-Class Homes Near Tallinn</h1>
    <p class="mg-page-hero__lead">Magnoolia is a collection of 19 A-energy-class terraced houses in Vaela village, Kiili municipality. Four to five rooms, a private yard, a terrace and a balcony — family space in a quiet corner of Harju County.</p>
    <p class="mg-page-hero__note">Vaela village · Kiili municipality · Harju County · about 20 min from Tallinn (depending on traffic)</p>
    <div class="mg-page-hero__ctas">
      <a href="{{ lroute('magnoolia.homes') }}" class="zoomvilla-btn" data-mg-analytics="magnoolia_cta_click">See homes and prices <i class="icon-angle-small-right"></i></a>
      <a href="{{ lroute('magnoolia.contact') }}" class="zoomvilla-btn zoomvilla-btn--border" data-mg-inquiry-open data-source-component="lp_terraced-houses-harju-county_hero" data-mg-analytics="magnoolia_cta_click">Request an offer</a>
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
          <p style="font-size:16px;color:#3a3530;line-height:1.75;margin:0;">Magnoolia is a new terraced-house neighbourhood in Harju County — 19 A-energy-class homes in Vaela village, Kiili municipality. Every home has 4–5 rooms (about 129 m², up to roughly 143 m²), a private yard, a terrace, a balcony and its own parking. A ground-source heat pump, heat-recovery ventilation and underfloor heating help keep running costs down. Tallinn is roughly a 20-minute drive away.</p>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ── Why choose a terraced house here ──────────────────────── --}}
<section class="mg-page-section mg-page-section--white">
  <div class="container">
    <div class="mg-section-heading" style="margin-bottom:36px;">
      <div class="mg-section-heading__eyebrow">Why Magnoolia</div>
      <h2 class="mg-section-heading__title">The space of a house, the ease of a terraced home</h2>
    </div>
    <div class="row gutter-y-28">
      @php
        $feat = [
          ['icon' => 'fas fa-vector-square', 'title' => '4–5 rooms, ~129 m²', 'body' => 'Generous, well-planned layouts of up to about 143 m² — comfortable for family life, guests and a dedicated home office.'],
          ['icon' => 'fas fa-seedling',      'title' => 'Private yard',       'body' => 'Every home has its own yard, plus a terrace and balcony — outdoor space that belongs to you alone.'],
          ['icon' => 'fas fa-bolt',          'title' => 'A-energy class',     'body' => 'Ground-source heat pump, heat-recovery ventilation and underfloor heating make for an efficient home with lower running costs.'],
          ['icon' => 'fas fa-car',           'title' => 'Your own parking',   'body' => 'A carport and parking solution right by your door — no daily hunt for a spot.'],
          ['icon' => 'fas fa-map-marked-alt','title' => 'Harju County setting','body' => 'Vaela village in Kiili municipality — a peaceful part of Harju County that stays connected to the capital.'],
          ['icon' => 'fas fa-home',          'title' => '19 individual homes', 'body' => 'A low-density, thoughtfully planned neighbourhood — the feel of a house, not an apartment block.'],
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

{{-- ── Page-specific angle: family living in Harju County ────── --}}
<section class="mg-page-section mg-page-section--cream">
  <div class="container">
    <div class="mg-section-heading" style="margin-bottom:32px;">
      <div class="mg-section-heading__eyebrow">Family living in Harju County</div>
      <h2 class="mg-section-heading__title">Room to grow, close to the capital</h2>
    </div>
    <div class="row gutter-y-16">
      @foreach([
        'A quiet Harju County location instead of a busy city street',
        'More room than an apartment — 4–5 rooms across two floors',
        'A private yard and terrace where children and pets can play',
        'Own parking at the door, no shared garage queues',
        'A commute to Tallinn of roughly 20 minutes, traffic permitting',
        'A-energy-class comfort that helps keep monthly costs in check',
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
          <h2 class="mg-section-heading__title">19 homes, built in two stages</h2>
        </div>
        <p style="font-size:15px;color:#6f6a61;line-height:1.75;margin:0 0 18px;">Browse every home's floor plan, area, status and price in one place. Stage I prices are public; Stage II prices will be specified later. Availability changes over time, so it is worth checking the price list and requesting a personal offer.</p>
        <a href="{{ lroute('magnoolia.homes') }}" class="zoomvilla-btn" data-mg-analytics="magnoolia_cta_click">See homes and prices <i class="icon-angle-small-right"></i></a>
      </div>
      <div class="col-lg-5">
        <a href="{{ lroute('magnoolia.site-plan') }}" style="display:block;border-radius:16px;overflow:hidden;text-decoration:none;">
          <img {!! mg_img('Cam005.0000.jpg', '(max-width:991px) 100vw, 460px') !!} alt="Magnoolia terraced houses in Vaela village, Harju County" loading="lazy" decoding="async" style="width:100%;height:auto;display:block;border-radius:16px;">
        </a>
      </div>
    </div>
  </div>
</section>

{{-- ── Who it suits / What you'll learn / Next step ──────────── --}}
<section class="mg-page-section mg-page-section--warm">
  <div class="container">
    <div class="row gutter-y-28">
      @php
        $aeo = [
          ['t' => 'Who is it for?', 'b' => 'Families ready to move on from an apartment who want more space and a private yard, but not the maintenance of a large detached plot. It also suits remote workers and anyone who values a calm Harju County setting close to Tallinn.'],
          ['t' => 'What will a buyer learn?', 'b' => 'Floor plans and areas, yard, terrace and balcony sizes, parking, the energy solution, the construction stages and completion timing, plus the current price list — all gathered on the “Homes and prices” page.'],
          ['t' => 'What is the next step?', 'b' => 'Explore the available homes and prices, choose the layout that fits you and request a personal offer. Sales consultant Diana will help you find the right home.'],
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
      <a href="{{ lroute('magnoolia.homes') }}" class="mg-internal-link"><i class="fas fa-table"></i> Homes and prices</a>
      <a href="{{ lroute('magnoolia.site-plan') }}" class="mg-internal-link"><i class="fas fa-map"></i> Site plan</a>
      <a href="{{ lroute('magnoolia.location') }}" class="mg-internal-link"><i class="fas fa-map-marker-alt"></i> Location</a>
      <a href="{{ lroute('magnoolia.construction') }}" class="mg-internal-link"><i class="fas fa-hard-hat"></i> Construction</a>
      <a href="{{ lroute('magnoolia.contact') }}" class="mg-internal-link"><i class="fas fa-envelope"></i> Contact</a>
    </div>
  </div>
</section>

@include('sections.magnoolia.page-cta', [
  'title'   => 'Find your terraced house in Harju County',
  'sub'     => 'Explore the available homes and request a personal offer.',
  'buttons' => [
    ['label' => 'See homes and prices', 'url' => lroute('magnoolia.homes')],
    ['label' => 'Request an offer', 'url' => lroute('magnoolia.contact'), 'outline' => true, 'inquiry' => true, 'source' => 'lp_terraced-houses-harju-county_cta'],
  ]
])

@endsection
