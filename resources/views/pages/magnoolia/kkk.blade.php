@extends('layouts.app')

@section('title', __('magnoolia.page.kkk.page_title'))
@section('meta_description', $page['description'] ?? '')

@section('content')
@php
  $base = rtrim(config('magnoolia.seo.canonical_base', config('app.url', url('/'))), '/');

  $groups = __('magnoolia.page.kkk.groups');
@endphp

<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@graph": [
    {
      "@@type": "BreadcrumbList",
      "itemListElement": [
        { "@@type": "ListItem", "position": 1, "name": "Avaleht", "item": "{{ $base }}" },
        { "@@type": "ListItem", "position": 2, "name": "KKK", "item": "{{ $base }}/kkk" }
      ]
    },
    {
      "@@type": "FAQPage",
      "mainEntity": [
        @foreach($groups as $gi => $group)
        @foreach($group['faqs'] as $fi => $faq)
        {
          "@@type": "Question",
          "name": "{{ addslashes($faq['q']) }}",
          "acceptedAnswer": { "@@type": "Answer", "text": "{{ addslashes($faq['a']) }}" }
        }{{ ($gi < count($groups) - 1 || $fi < count($group['faqs']) - 1) ? ',' : '' }}
        @endforeach
        @endforeach
      ]
    }
  ]
}
</script>

{{-- ── Hero ─────────────────────────────────────────────────── --}}
<div class="mg-page-hero">
  <div class="container">
    @include('partials.seo.breadcrumb', ['items' => [
      ['label' => __('magnoolia.nav.home'), 'url' => route('home')],
      ['label' => __('magnoolia.nav.faq')],
    ]])
    <div class="mg-page-hero__eyebrow">{{ __('magnoolia.page.kkk.eyebrow') }}</div>
    <h1 class="mg-page-hero__title">{{ __('magnoolia.page.kkk.page_h1') }}</h1>
    <p class="mg-page-hero__lead">
      {{ __('magnoolia.page.kkk.lead') }}
    </p>
    <div class="mg-page-hero__ctas">
      <a href="#kkk-sisu" class="zoomvilla-btn">{{ __('magnoolia.page.kkk.cta_view') }} <i class="icon-angle-small-right"></i></a>
      <a href="{{ lroute('magnoolia.contact') }}" class="zoomvilla-btn zoomvilla-btn--border">{{ __('magnoolia.page.kkk.cta_ask') }}</a>
    </div>
  </div>
</div>

{{-- ── Group navigation ─────────────────────────────────────── --}}
<section class="mg-page-section mg-page-section--cream" style="padding-top:32px;padding-bottom:32px;">
  <div class="container">
    <div style="display:flex;flex-wrap:wrap;gap:10px;justify-content:center;">
      @foreach($groups as $group)
      <a href="#{{ $group['id'] }}"
         style="display:inline-flex;align-items:center;gap:8px;padding:9px 18px;border-radius:100px;background:#fff;border:1px solid rgba(29,36,48,.1);font-size:13px;font-weight:600;color:#1d2430;text-decoration:none;transition:all .2s;"
         onmouseover="this.style.background='#c89443';this.style.color='#fff';this.style.borderColor='#c89443';"
         onmouseout="this.style.background='#fff';this.style.color='#1d2430';this.style.borderColor='rgba(29,36,48,.1)';">
        <i class="{{ $group['icon'] }}" style="font-size:12px;"></i>
        {{ $group['title'] }}
      </a>
      @endforeach
    </div>
  </div>
</section>

{{-- ── All FAQ groups ───────────────────────────────────────── --}}
<section class="mg-page-section mg-page-section--white" id="kkk-sisu">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-9">
        @foreach($groups as $group)
        <div id="{{ $group['id'] }}" style="margin-bottom:56px;">
          <div style="display:flex;align-items:center;gap:12px;margin-bottom:28px;padding-bottom:16px;border-bottom:2px solid rgba(200,148,67,.3);">
            <div style="width:40px;height:40px;background:rgba(200,148,67,.12);border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
              <i class="{{ $group['icon'] }}" style="color:#c89443;font-size:16px;"></i>
            </div>
            <h2 style="font-size:20px;font-weight:700;color:#1d2430;margin:0;">{{ $group['title'] }}</h2>
          </div>

          <div style="display:flex;flex-direction:column;gap:12px;"
               itemscope itemtype="https://schema.org/FAQPage">
            @foreach($group['faqs'] as $faq)
            <div class="mg-faq-card"
                 itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
              <button class="mg-faq-card__q" type="button" aria-expanded="false"
                      onclick="mgToggleFaq(this)">
                <span itemprop="name">{{ $faq['q'] }}</span>
                <i class="fas fa-plus" aria-hidden="true"></i>
              </button>
              <div class="mg-faq-card__a"
                   itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer"
                   hidden>
                <p itemprop="text">{{ $faq['a'] }}</p>
              </div>
            </div>
            @endforeach
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </div>
</section>

{{-- ── CTA ──────────────────────────────────────────────────── --}}
<section class="mg-page-section mg-page-section--cream" style="padding:48px 0;">
  <div class="container text-center">
    <div style="font-size:20px;font-weight:700;color:#1d2430;margin-bottom:8px;">{{ __('magnoolia.page.kkk.no_answer') }}</div>
    <p style="font-size:15px;color:#6f6a61;margin-bottom:24px;">{{ __('magnoolia.page.kkk.no_answer_sub') }}</p>
    <div style="display:flex;flex-wrap:wrap;gap:12px;justify-content:center;">
      <a href="{{ lroute('magnoolia.contact') }}" class="zoomvilla-btn">{{ __('magnoolia.page.kkk.ask_diana') }}</a>
      <a href="tel:+37258164078" class="zoomvilla-btn zoomvilla-btn--border">
        <i class="fas fa-phone" style="margin-right:8px;"></i>+372 58 164 078
      </a>
    </div>
  </div>
</section>

{{-- ── Internal links ──────────────────────────────────────── --}}
<section class="mg-page-section--white mg-page-section--sm">
  <div class="container">
    <div class="mg-internal-links">
      <a href="{{ lroute('magnoolia.homes') }}" class="mg-internal-link"><i class="fas fa-table"></i> {{ __('magnoolia.page.kkk.link_homes') }}</a>
      <a href="{{ lroute('magnoolia.ostuprotsess') }}" class="mg-internal-link"><i class="fas fa-list-ol"></i> {{ __('magnoolia.page.kkk.link_proc') }}</a>
      <a href="{{ lroute('magnoolia.finantseerimine') }}" class="mg-internal-link"><i class="fas fa-calculator"></i> {{ __('magnoolia.page.kkk.link_fin') }}</a>
      <a href="{{ lroute('magnoolia.contact') }}" class="mg-internal-link"><i class="fas fa-envelope"></i> {{ __('magnoolia.page.kkk.link_cont') }}</a>
    </div>
  </div>
</section>

@endsection

@push('scripts')
<script>
function mgToggleFaq(btn) {
  var isOpen  = btn.getAttribute('aria-expanded') === 'true';
  var answer  = btn.nextElementSibling;
  var icon    = btn.querySelector('i');

  if (isOpen) {
    btn.setAttribute('aria-expanded', 'false');
    answer.hidden = true;
    icon.classList.replace('fa-minus', 'fa-plus');
  } else {
    btn.setAttribute('aria-expanded', 'true');
    answer.hidden = false;
    icon.classList.replace('fa-plus', 'fa-minus');
  }
}
</script>
@endpush
