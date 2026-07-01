@extends('layouts.app')

@section('title', __('magnoolia.page.ehitusinfo.page_title'))
@section('meta_description', $page['description'] ?? '')
@section('og_title', $page['title'] ?? '')
@section('og_description', $page['description'] ?? '')

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
        { "@@type": "ListItem", "position": 2, "name": "Ehitusinfo", "item": "{{ $base }}/ehitusinfo" }
      ]
    },
    {
      "@@type": "FAQPage",
      "mainEntity": [
        {
          "@@type": "Question",
          "name": "Mis energiaklass on Magnoolia kodudel?",
          "acceptedAnswer": { "@@type": "Answer", "text": "Magnoolia ridaelamukodud projekteeritakse A-energiaklassi nõuetele vastavalt." }
        },
        {
          "@@type": "Question",
          "name": "Milline küttesüsteem paigaldatakse?",
          "acceptedAnswer": { "@@type": "Answer", "text": "Igasse kodu paigaldatakse maasoojuspump. Täpne tehniline lahendus kinnitatakse ehitusprojektis." }
        },
        {
          "@@type": "Question",
          "name": "Kas elektriautole on laadimisvõimalus?",
          "acceptedAnswer": { "@@type": "Answer", "text": "Projekteerimisel on arvestatud EV laadimise ettevalmistusega. Täpne lahendus kinnitatakse tehnilistest spetsifikaatidest." }
        }
      ]
    }
  ]
}
</script>

{{-- ── Hero ─────────────────────────────────────────────────── --}}
<div class="mg-page-hero" style="background-image:linear-gradient(to right, rgba(29,36,48,.88) 60%, rgba(29,36,48,.5)), url('{{ asset('assets/images/magnoolia/Cam014.0000.jpg') }}');background-size:cover;background-position:center;">
  <div class="container">
    @include('partials.seo.breadcrumb', ['items' => [
      ['label' => __('magnoolia.nav.home'), 'url' => route('home')],
      ['label' => __('magnoolia.nav.building')],
    ]])
    <div class="mg-page-hero__eyebrow">{{ __('magnoolia.page.ehitusinfo.eyebrow') }}</div>
    <h1 class="mg-page-hero__title">{{ mg_text('page.ehitusinfo.page_h1') }}</h1>
    <p class="mg-page-hero__lead">
      {{ mg_text('page.ehitusinfo.lead') }}
    </p>
    <p class="mg-page-hero__note">
      {{ mg_text('page.ehitusinfo.note') }}
    </p>
    <div class="mg-page-hero__ctas">
      <a href="#tehnika" class="zoomvilla-btn">{{ __('magnoolia.page.ehitusinfo.cta_view') }} <i class="icon-angle-small-right"></i></a>
      <a href="{{ lroute('magnoolia.contact') }}" class="zoomvilla-btn zoomvilla-btn--border">{{ __('magnoolia.page.ehitusinfo.cta_inquiry') }}</a>
    </div>
  </div>
</div>

{{-- ── Trust intro ──────────────────────────────────────────── --}}
<section class="mg-page-section mg-page-section--white">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8 text-center">
        <div class="mg-section-heading mg-section-heading--center" style="margin-bottom:0;">
          <div class="mg-section-heading__eyebrow">{{ __('magnoolia.page.ehitusinfo.section_eyebrow') }}</div>
          <h2 class="mg-section-heading__title">{{ __('magnoolia.page.ehitusinfo.section_title') }}</h2>
          <p class="mg-section-heading__subtitle">
            {{ __('magnoolia.page.ehitusinfo.section_sub') }}
          </p>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ── Proof cards ──────────────────────────────────────────── --}}
<section class="mg-page-section mg-page-section--cream" id="tehnika">
  <div class="container">
    <div class="mg-section-heading" style="margin-bottom:40px;">
      <div class="mg-section-heading__eyebrow">{{ __('magnoolia.page.ehitusinfo.cards_eyebrow') }}</div>
      <h2 class="mg-section-heading__title">{{ __('magnoolia.page.ehitusinfo.cards_title') }}</h2>
    </div>

    <div class="row gutter-y-28">
      @foreach([
        ['icon' => 'fas fa-leaf',          'title' => __('magnoolia.page.ehitusinfo.card1_title'), 'body' => __('magnoolia.page.ehitusinfo.card1_body')],
        ['icon' => 'fas fa-temperature-low','title' => __('magnoolia.page.ehitusinfo.card2_title'), 'body' => __('magnoolia.page.ehitusinfo.card2_body')],
        ['icon' => 'fas fa-wind',           'title' => __('magnoolia.page.ehitusinfo.card3_title'), 'body' => __('magnoolia.page.ehitusinfo.card3_body')],
        ['icon' => 'fas fa-solar-panel',    'title' => __('magnoolia.page.ehitusinfo.card4_title'), 'body' => __('magnoolia.page.ehitusinfo.card4_body')],
        ['icon' => 'fas fa-car-battery',    'title' => __('magnoolia.page.ehitusinfo.card5_title'), 'body' => __('magnoolia.page.ehitusinfo.card5_body')],
        ['icon' => 'fas fa-hard-hat',       'title' => __('magnoolia.page.ehitusinfo.card6_title'), 'body' => __('magnoolia.page.ehitusinfo.card6_body')],
      ] as $card)
      <div class="col-lg-4 col-md-6">
        <div class="mg-proof-card" style="height:100%;">
          <div class="mg-proof-card__icon"><i class="{{ $card['icon'] }}"></i></div>
          <div class="mg-proof-card__title">{{ $card['title'] }}</div>
          <div class="mg-proof-card__body">{{ $card['body'] }}</div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>

{{-- ── Energiasäästlik + tehnosüsteemid (prepared client copy) ── --}}
@include('sections.magnoolia.energiasaastlik')
@include('sections.magnoolia.tehnosysteemid')

{{-- ── Technical accordion ─────────────────────────────────── --}}
<section class="mg-page-section mg-page-section--white">
  <div class="container">
    <div class="mg-section-heading" style="margin-bottom:40px;">
      <div class="mg-section-heading__eyebrow">{{ __('magnoolia.page.ehitusinfo.accordion_eyebrow') }}</div>
      <h2 class="mg-section-heading__title">{{ __('magnoolia.page.ehitusinfo.accordion_title') }}</h2>
    </div>

    <div style="display:flex;flex-direction:column;gap:12px;max-width:860px;">
      @foreach(__('magnoolia.page.ehitusinfo.accordion_items') as $idx => $group)
      <details style="background:#f8f5f0;border-radius:12px;overflow:hidden;border:1px solid rgba(29,36,48,.07);"
               data-event="accordion_open" data-accordion-index="{{ $idx }}">
        <summary style="padding:18px 24px;font-size:16px;font-weight:700;color:#1d2430;cursor:pointer;
                        list-style:none;display:flex;justify-content:space-between;align-items:center;
                        user-select:none;"
                 onclick="this.parentElement.open ? this.querySelector('.mg-acc-icon').textContent='+'
                           : this.querySelector('.mg-acc-icon').textContent='−'">
          {{ $group['title'] }}
          <span class="mg-acc-icon" style="font-size:20px;font-weight:300;color:#c89443;width:24px;
                 text-align:center;flex-shrink:0;">+</span>
        </summary>
        <div style="padding:0 24px 20px;">
          <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:8px;">
            @foreach($group['items'] as $item)
            <li style="display:flex;gap:10px;align-items:flex-start;font-size:14px;color:#444;line-height:1.6;">
              <i class="fas fa-check" style="color:#c89443;flex-shrink:0;margin-top:3px;font-size:11px;"></i>
              {{ $item }}
            </li>
            @endforeach
          </ul>
        </div>
      </details>
      @endforeach
    </div>

    <p class="mg-seo-note" style="margin-top:20px;">
      {{ __('magnoolia.page.ehitusinfo.accordion_note') }}
    </p>
  </div>
</section>


{{-- ── Stages ────────────────────────────────────────────────── --}}
<section class="mg-page-section mg-page-section--white">
  <div class="container">
    <div class="mg-section-heading" style="margin-bottom:40px;">
      <div class="mg-section-heading__eyebrow">{{ __('magnoolia.page.ehitusinfo.stages_eyebrow') }}</div>
      <h2 class="mg-section-heading__title">{{ __('magnoolia.page.ehitusinfo.stages_title') }}</h2>
    </div>

    <div class="row gutter-y-28">
      @foreach(__('magnoolia.page.ehitusinfo.stages') as $stage)
      <div class="col-lg-6">
        <div style="background:#f8f5f0;border-radius:16px;padding:32px;height:100%;border:1px solid rgba(29,36,48,.06);">
          <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
            <span class="mg-stage-badge mg-stage-badge--{{ $stage['badge'] }}">{{ $stage['label'] }}</span>
            <span style="font-size:13px;color:#888;">{{ $stage['deadline'] }}</span>
          </div>
          <div style="font-size:22px;font-weight:700;color:#1d2430;margin-bottom:4px;">{{ $stage['homes'] }}</div>
          <div style="font-size:13px;color:#c89443;font-weight:600;margin-bottom:12px;">{{ $stage['addr'] }}</div>
          <p style="font-size:14px;color:#6f6a61;line-height:1.6;margin:0;">{{ $stage['note'] }}</p>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>

{{-- ── Includes / excludes disclaimer ─────────────────────── --}}
<section class="mg-page-section mg-page-section--warm">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-9">
        <div class="mg-section-heading mg-section-heading--center" style="margin-bottom:32px;">
          <div class="mg-section-heading__eyebrow">{{ __('magnoolia.page.ehitusinfo.includes_eyebrow') }}</div>
          <h2 class="mg-section-heading__title">{{ __('magnoolia.page.ehitusinfo.includes_title') }}</h2>
        </div>
        <div class="row gutter-y-24">
          <div class="col-md-6">
            <div style="background:#fff;border-radius:12px;padding:24px;height:100%;">
              <div style="font-size:12px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#4caf50;margin-bottom:14px;">
                <i class="fas fa-check-circle" style="margin-right:6px;"></i> {{ __('magnoolia.page.ehitusinfo.includes_label') }}
              </div>
              <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:8px;">
                @foreach(__('magnoolia.page.ehitusinfo.includes') as $item)
                <li style="font-size:13px;color:#444;display:flex;gap:8px;align-items:flex-start;">
                  <i class="fas fa-check" style="color:#4caf50;margin-top:3px;flex-shrink:0;font-size:11px;"></i>
                  {{ $item }}
                </li>
                @endforeach
              </ul>
            </div>
          </div>
          <div class="col-md-6">
            <div style="background:#fff;border-radius:12px;padding:24px;height:100%;">
              <div style="font-size:12px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#888;margin-bottom:14px;">
                <i class="fas fa-question-circle" style="margin-right:6px;"></i> {{ __('magnoolia.page.ehitusinfo.tbc_label') }}
              </div>
              <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:8px;">
                @foreach(__('magnoolia.page.ehitusinfo.tbc_items') as $item)
                <li style="font-size:13px;color:#444;display:flex;gap:8px;align-items:flex-start;">
                  <i class="fas fa-question" style="color:#aaa;margin-top:3px;flex-shrink:0;font-size:11px;"></i>
                  {{ $item }}
                </li>
                @endforeach
              </ul>
            </div>
          </div>
        </div>
        <p class="mg-seo-note" style="margin-top:20px;text-align:center;">
          {{ __('magnoolia.page.ehitusinfo.seo_note') }}
        </p>
      </div>
    </div>
  </div>
</section>

{{-- ── Answer Unit (AI-citable) ──────────────────────── --}}
@php
  $au = __('magnoolia.answer_unit.ehitusinfo');
  $au['cta_route'] = lroute('magnoolia.contact');
@endphp
@include('sections.magnoolia.answer-unit', ['unit' => $au])

{{-- ── FAQ ─────────────────────────────────────────────────── --}}
@include('sections.magnoolia.page-faq', [
  'eyebrow' => __('magnoolia.page.ehitusinfo.faq_eyebrow'),
  'title'   => __('magnoolia.page.ehitusinfo.faq_title'),
  'bg'      => 'cream',
  'faqs'    => __('magnoolia.page.ehitusinfo.faq_items'),
])

{{-- ── Internal links ──────────────────────────────────────── --}}
<section class="mg-page-section--cream mg-page-section--sm">
  <div class="container">
    <div class="mg-internal-links">
      <a href="{{ lroute('magnoolia.homes') }}" class="mg-internal-link"><i class="fas fa-table"></i> {{ __('magnoolia.page.ehitusinfo.link_homes') }}</a>
      <a href="{{ lroute('magnoolia.site-plan') }}" class="mg-internal-link"><i class="fas fa-map"></i> {{ __('magnoolia.page.ehitusinfo.link_plan') }}</a>
      <a href="{{ lroute('magnoolia.sisedisain') }}" class="mg-internal-link"><i class="fas fa-couch"></i> {{ __('magnoolia.page.ehitusinfo.link_int') }}</a>
      <a href="{{ lroute('magnoolia.contact') }}" class="mg-internal-link"><i class="fas fa-envelope"></i> {{ __('magnoolia.page.ehitusinfo.link_cont') }}</a>
    </div>
  </div>
</section>

@include('sections.magnoolia.page-cta', [
  'title'   => __('magnoolia.page.ehitusinfo.cta_title'),
  'sub'     => __('magnoolia.page.ehitusinfo.cta_sub'),
  'buttons' => [
    ['label' => __('magnoolia.page.ehitusinfo.cta_btn1'), 'url' => lroute('magnoolia.contact')],
    ['label' => __('magnoolia.page.ehitusinfo.cta_btn2'), 'url' => lroute('magnoolia.homes'), 'outline' => true],
  ]
])

@endsection
