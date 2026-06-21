@extends('layouts.app')

@section('title', __('magnoolia.page.ostuprotsess.page_title'))
@section('meta_description', $page['description'] ?? '')

@section('content')
@php
  $base = rtrim(config('magnoolia.seo.canonical_base', 'https://magnoolia.ee'), '/');
@endphp

@php
  $steps = __('magnoolia.page.ostuprotsess.steps');
@endphp
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@graph": [
    {
      "@@type": "BreadcrumbList",
      "itemListElement": [
        { "@@type": "ListItem", "position": 1, "name": "Avaleht", "item": "{{ $base }}" },
        { "@@type": "ListItem", "position": 2, "name": "Ostuprotsess", "item": "{{ $base }}/ostuprotsess" }
      ]
    },
    {
      "@@type": "HowTo",
      "@@id": "{{ $base }}/ostuprotsess#howto",
      "name": "Kuidas osta Magnoolia kodu",
      "description": "Samm-sammuline juhend Magnoolia ridaelamukodu ostmiseks — alates kodude vaatamisest kuni notariaalse lepinguni.",
      "totalTime": "P90D",
      "step": [
        @foreach($steps as $i => $step)
        {
          "@@type": "HowToStep",
          "position": {{ $i + 1 }},
          "name": "{{ e($step['title']) }}",
          "text": "{{ e($step['body']) }}"
        }{{ !$loop->last ? ',' : '' }}
        @endforeach
      ]
    },
    {
      "@@type": "FAQPage",
      "mainEntity": [
        {
          "@@type": "Question",
          "name": "Kuidas broneerida Magnoolia kodu?",
          "acceptedAnswer": { "@@type": "Answer", "text": "Broneerimine algab kontaktist Diana Raadiga. Pärast saadavuse kinnitamist sõlmitakse broneerimiskokkulepe ja tasutakse broneerimistasu." }
        },
        {
          "@@type": "Question",
          "name": "Millal sõlmitakse müügileping?",
          "acceptedAnswer": { "@@type": "Answer", "text": "Müügileping sõlmitakse notari juures vastavalt kokkulepitud ajakavale. Täpne tähtaeg fikseeritakse broneerimiskokkuleppes." }
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
      ['label' => __('magnoolia.nav.process')],
    ]])
    <div class="mg-page-hero__eyebrow">{{ __('magnoolia.page.ostuprotsess.eyebrow') }}</div>
    <h1 class="mg-page-hero__title">{{ mg_text('page.ostuprotsess.page_h1') }}</h1>
    <p class="mg-page-hero__lead">{{ mg_text('page.ostuprotsess.lead') }}</p>
    <p class="mg-page-hero__note">{{ mg_text('page.ostuprotsess.note') }}</p>
    <div class="mg-page-hero__ctas">
      <a href="#sammud" class="zoomvilla-btn">{{ __('magnoolia.page.ostuprotsess.cta_steps') }} <i class="icon-angle-small-right"></i></a>
      <a href="{{ lroute('magnoolia.contact') }}" class="zoomvilla-btn zoomvilla-btn--border">{{ __('magnoolia.page.ostuprotsess.cta_inquiry') }}</a>
    </div>
  </div>
</div>

{{-- ── Disclaimer ───────────────────────────────────────────── --}}
<section class="mg-page-section mg-page-section--warm">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div style="display:flex;gap:16px;align-items:flex-start;background:#fff;border-radius:12px;padding:24px;border-left:4px solid #c89443;">
          <i class="fas fa-balance-scale" style="color:#c89443;font-size:20px;flex-shrink:0;margin-top:2px;"></i>
          <div>
            <div style="font-weight:700;color:#1d2430;margin-bottom:6px;">{{ __('magnoolia.page.ostuprotsess.disc_title') }}</div>
            <p style="font-size:14px;color:#6f6a61;margin:0;line-height:1.6;">{{ __('magnoolia.page.ostuprotsess.disc_body') }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ── Timeline ─────────────────────────────────────────────── --}}
<section class="mg-page-section mg-page-section--white" id="sammud">
  <div class="container">
    <div class="mg-section-heading" style="margin-bottom:40px;">
      <div class="mg-section-heading__eyebrow">{{ __('magnoolia.page.ostuprotsess.steps_eyebrow') }}</div>
      <h2 class="mg-section-heading__title">{{ __('magnoolia.page.ostuprotsess.steps_title') }}</h2>
    </div>

    <div class="row justify-content-center">
      <div class="col-lg-9">
        <div class="mg-timeline">
          @foreach(__('magnoolia.page.ostuprotsess.steps') as $step)
          <div class="mg-timeline__item">
            <div class="mg-timeline__num">{{ $step['num'] }}</div>
            <div class="mg-timeline__body">
              <div class="mg-timeline__step-title">{{ $step['title'] }}</div>
              <div class="mg-timeline__step-body">{{ $step['body'] }}</div>
              @if($step['cta_label'])
              <a href="{{ route($step['cta_route']) }}" class="zoomvilla-btn" style="margin-top:12px;font-size:13px;padding:8px 20px;">
                {{ $step['cta_label'] }} <i class="icon-angle-small-right"></i>
              </a>
              @endif
            </div>
          </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ── FAQ ─────────────────────────────────────────────────── --}}
@include('sections.magnoolia.page-faq', [
  'eyebrow' => __('magnoolia.page.ostuprotsess.faq_eyebrow'),
  'title'   => __('magnoolia.page.ostuprotsess.faq_title'),
  'bg'      => 'cream',
  'faqs'    => __('magnoolia.page.ostuprotsess.faq_items'),
])

{{-- ── Internal links ──────────────────────────────────────── --}}
<section class="mg-page-section--cream mg-page-section--sm">
  <div class="container">
    <div class="mg-internal-links">
      <a href="{{ lroute('magnoolia.homes') }}" class="mg-internal-link"><i class="fas fa-table"></i> {{ __('magnoolia.page.ostuprotsess.link_homes') }}</a>
      <a href="{{ lroute('magnoolia.finantseerimine') }}" class="mg-internal-link"><i class="fas fa-calculator"></i> {{ __('magnoolia.page.ostuprotsess.link_fin') }}</a>
      <a href="{{ lroute('magnoolia.kkk') }}" class="mg-internal-link"><i class="fas fa-question-circle"></i> {{ __('magnoolia.page.ostuprotsess.link_faq') }}</a>
      <a href="{{ lroute('magnoolia.contact') }}" class="mg-internal-link"><i class="fas fa-envelope"></i> {{ __('magnoolia.page.ostuprotsess.link_cont') }}</a>
    </div>
  </div>
</section>

@include('sections.magnoolia.page-cta', [
  'title'   => __('magnoolia.page.ostuprotsess.cta_title'),
  'sub'     => __('magnoolia.page.ostuprotsess.cta_sub'),
  'buttons' => [
    ['label' => __('magnoolia.page.ostuprotsess.cta_btn1'), 'url' => lroute('magnoolia.contact')],
    ['label' => __('magnoolia.page.ostuprotsess.cta_btn2'), 'url' => lroute('magnoolia.homes'), 'outline' => true],
  ]
])

@endsection
