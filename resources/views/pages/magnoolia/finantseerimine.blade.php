@extends('layouts.app')

@section('title', __('magnoolia.page.finantseerimine.page_title'))
@section('meta_description', $page['description'] ?? '')

@section('content')
@php
  $base = rtrim(config('magnoolia.seo.canonical_base', 'https://magnoolia.ee'), '/');
@endphp

<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@graph": [
    {
      "@@type": "BreadcrumbList",
      "itemListElement": [
        { "@@type": "ListItem", "position": 1, "name": "Avaleht", "item": "{{ $base }}" },
        { "@@type": "ListItem", "position": 2, "name": "Finantseerimine", "item": "{{ $base }}/finantseerimine" }
      ]
    },
    {
      "@@type": "FAQPage",
      "mainEntity": [
        {
          "@@type": "Question",
          "name": "Kas Magnoolia kodu saab osta pangalaenuga?",
          "acceptedAnswer": { "@@type": "Answer", "text": "Jah, Magnoolia kodusid saab finantseerida pangalaenuga. Laenuvõimalused sõltuvad ostja individuaalsest olukorrast." }
        },
        {
          "@@type": "Question",
          "name": "Kui suur omaosalus on vajalik?",
          "acceptedAnswer": { "@@type": "Answer", "text": "Omaosaluse nõue sõltub pangast ja ostja profiilist. Täpse info saate oma pangast." }
        }
      ]
    }
  ]
}
</script>

{{-- ── Hero ─────────────────────────────────────────────────── --}}
<div class="mg-page-hero" style="background-image:linear-gradient(to right, rgba(29,36,48,.88) 60%, rgba(29,36,48,.5)), url('{{ asset('assets/images/magnoolia/Cam001.0000.jpg') }}');background-size:cover;background-position:center;">
  <div class="container">
    @include('partials.seo.breadcrumb', ['items' => [
      ['label' => __('magnoolia.nav.home'), 'url' => route('home')],
      ['label' => __('magnoolia.nav.financing')],
    ]])
    <div class="mg-page-hero__eyebrow">{{ __('magnoolia.page.finantseerimine.eyebrow') }}</div>
    <h1 class="mg-page-hero__title">{{ __('magnoolia.page.finantseerimine.page_h1') }}</h1>
    <p class="mg-page-hero__lead">{{ __('magnoolia.page.finantseerimine.lead') }}</p>
    <p class="mg-page-hero__note">{{ __('magnoolia.page.finantseerimine.note') }}</p>
    <div class="mg-page-hero__ctas">
      <a href="#finants-info" class="zoomvilla-btn">{{ __('magnoolia.page.finantseerimine.cta_read') }} <i class="icon-angle-small-right"></i></a>
      <a href="{{ lroute('magnoolia.contact') }}" class="zoomvilla-btn zoomvilla-btn--border">{{ __('magnoolia.page.finantseerimine.cta_inquiry') }}</a>
    </div>
  </div>
</div>

{{-- ── Disclaimer ───────────────────────────────────────────── --}}
<section class="mg-page-section mg-page-section--warm">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div style="display:flex;gap:16px;align-items:flex-start;background:#fff;border-radius:12px;padding:24px;border-left:4px solid #c89443;">
          <i class="fas fa-info-circle" style="color:#c89443;font-size:20px;flex-shrink:0;margin-top:2px;"></i>
          <div>
            <div style="font-weight:700;color:#1d2430;margin-bottom:6px;">{{ __('magnoolia.page.finantseerimine.disc_title') }}</div>
            <p style="font-size:14px;color:#6f6a61;margin:0;line-height:1.6;">{{ __('magnoolia.page.finantseerimine.disc_body') }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ── 4 informational sections ────────────────────────────── --}}
<section class="mg-page-section mg-page-section--white" id="finants-info">
  <div class="container">
    <div class="mg-section-heading" style="margin-bottom:40px;">
      <div class="mg-section-heading__eyebrow">{{ __('magnoolia.page.finantseerimine.info_eyebrow') }}</div>
      <h2 class="mg-section-heading__title">{{ __('magnoolia.page.finantseerimine.info_title') }}</h2>
    </div>

    <div class="row gutter-y-28">
      @foreach(__('magnoolia.page.finantseerimine.sections') as $sec)
      <div class="col-lg-6">
        <div style="background:#f8f5f0;border-radius:16px;padding:32px;height:100%;">
          <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
            <div style="width:44px;height:44px;background:rgba(200,148,67,.15);border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
              <i class="{{ $sec['icon'] }}" style="color:#c89443;font-size:18px;"></i>
            </div>
            <div>
              <div style="font-size:11px;color:#c89443;font-weight:700;letter-spacing:.1em;text-transform:uppercase;">{{ $sec['num'] }}</div>
              <div style="font-size:17px;font-weight:700;color:#1d2430;">{{ $sec['title'] }}</div>
            </div>
          </div>
          <p style="font-size:14px;color:#6f6a61;line-height:1.6;margin-bottom:16px;">{{ $sec['body'] }}</p>
          <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:7px;">
            @foreach($sec['items'] as $item)
            <li style="font-size:13px;color:#444;display:flex;gap:8px;align-items:flex-start;">
              <i class="fas fa-check" style="color:#c89443;margin-top:3px;flex-shrink:0;font-size:11px;"></i>
              {{ $item }}
            </li>
            @endforeach
          </ul>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>

{{-- ── FAQ ─────────────────────────────────────────────────── --}}
@include('sections.magnoolia.page-faq', [
  'eyebrow' => __('magnoolia.page.finantseerimine.faq_eyebrow'),
  'title'   => __('magnoolia.page.finantseerimine.faq_title'),
  'bg'      => 'cream',
  'faqs'    => __('magnoolia.page.finantseerimine.faq_items'),
])

{{-- ── Internal links ──────────────────────────────────────── --}}
<section class="mg-page-section--cream mg-page-section--sm">
  <div class="container">
    <div class="mg-internal-links">
      <a href="{{ lroute('magnoolia.ostuprotsess') }}" class="mg-internal-link"><i class="fas fa-list-ol"></i> {{ __('magnoolia.page.finantseerimine.link_proc') }}</a>
      <a href="{{ lroute('magnoolia.homes') }}" class="mg-internal-link"><i class="fas fa-table"></i> {{ __('magnoolia.page.finantseerimine.link_homes') }}</a>
      <a href="{{ lroute('magnoolia.kkk') }}" class="mg-internal-link"><i class="fas fa-question-circle"></i> {{ __('magnoolia.page.finantseerimine.link_faq') }}</a>
      <a href="{{ lroute('magnoolia.contact') }}" class="mg-internal-link"><i class="fas fa-envelope"></i> {{ __('magnoolia.page.finantseerimine.link_cont') }}</a>
    </div>
  </div>
</section>

@include('sections.magnoolia.page-cta', [
  'title'   => __('magnoolia.page.finantseerimine.cta_title'),
  'sub'     => __('magnoolia.page.finantseerimine.cta_sub'),
  'buttons' => [
    ['label' => __('magnoolia.page.finantseerimine.cta_btn1'), 'url' => lroute('magnoolia.contact')],
    ['label' => __('magnoolia.page.finantseerimine.cta_btn2'), 'url' => lroute('magnoolia.ostuprotsess'), 'outline' => true],
  ]
])

@endsection
