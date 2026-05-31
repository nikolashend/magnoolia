@extends('layouts.app')

@section('title', __('magnoolia.page.kontakt.page_title'))
@section('meta_description', $page['description'] ?? '')
@section('og_title', $page['title'] ?? '')
@section('og_description', $page['description'] ?? '')

@section('content')
@php
  $base  = rtrim(config('magnoolia.seo.canonical_base', 'https://magnoolia.ee'), '/');
  $email = config('magnoolia.project.contact_email', 'diana@estlanda.ee');
  $phone = config('magnoolia.project.contact_phone', '+37258164078');
  $phoneFormatted = '+372 58 164 078';
@endphp

<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@graph": [
    {
      "@@type": "BreadcrumbList",
      "itemListElement": [
        { "@@type": "ListItem", "position": 1, "name": "Avaleht", "item": "{{ $base }}" },
        { "@@type": "ListItem", "position": 2, "name": "Kontakt", "item": "{{ $base }}/kontakt" }
      ]
    },
    {
      "@@type": "ContactPage",
      "@@id": "{{ $base }}/kontakt",
      "name": "Magnoolia kontakt",
      "description": "Võta ühendust Magnoolia müügiesindajaga",
      "mainEntity": {
        "@@type": "Organization",
        "name": "Estlanda Ehitus OÜ",
        "telephone": "{{ $phone }}",
        "email": "{{ $email }}",
        "address": {
          "@@type": "PostalAddress",
          "streetAddress": "Magnoolia tee",
          "addressLocality": "Vaela küla, Kiili vald",
          "addressRegion": "Harjumaa",
          "addressCountry": "EE"
        }
      }
    }
  ]
}
</script>

{{-- ── Hero ─────────────────────────────────────────────────── --}}
<div class="mg-page-hero">
  <div class="container">
    @include('partials.seo.breadcrumb', ['items' => [
      ['label' => __('magnoolia.nav.home'), 'url' => route('home')],
      ['label' => __('magnoolia.nav.contact')],
    ]])
    <div class="mg-page-hero__eyebrow">{{ __('magnoolia.page.kontakt.eyebrow') }}</div>
    <h1 class="mg-page-hero__title">{{ __('magnoolia.page.kontakt.page_h1') }}</h1>
    <p class="mg-page-hero__lead">{!! __('magnoolia.page.kontakt.lead') !!}</p>
    <div class="mg-page-hero__ctas">
      <a href="#kontaktivorm" class="zoomvilla-btn">{{ __('magnoolia.page.kontakt.cta_form') }} <i class="icon-angle-small-right"></i></a>
      <a href="tel:{{ $phone }}" class="zoomvilla-btn zoomvilla-btn--border">
        <i class="fas fa-phone" style="margin-right:8px;"></i>{{ $phoneFormatted }}
      </a>
    </div>
  </div>
</div>

{{-- ── Intent cards ─────────────────────────────────────────── --}}
<section class="mg-page-section mg-page-section--cream">
  <div class="container">
    <div class="mg-section-heading mg-section-heading--center" style="margin-bottom:40px;">
      <div class="mg-section-heading__eyebrow">{{ __('magnoolia.page.kontakt.intent_eyebrow') }}</div>
      <h2 class="mg-section-heading__title">{{ __('magnoolia.page.kontakt.intent_title') }}</h2>
    </div>

    <div class="row gutter-y-20">
      @foreach(__('magnoolia.page.kontakt.intents') as $card)
      <div class="col-lg-4 col-md-6">
        <div class="mg-intent-card">
          <div class="mg-intent-card__icon"><i class="{{ $card['icon'] }}"></i></div>
          <div class="mg-intent-card__title">{{ $card['title'] }}</div>
          <div class="mg-intent-card__sub">{{ $card['sub'] }}</div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>

{{-- ── Direct contact (horizontal full-width card) ──────────── --}}
<section class="mg-page-section mg-page-section--cream">
  <div class="container">
    <div style="background:#fff;border-radius:16px;padding:40px 48px;box-shadow:0 4px 20px rgba(29,36,48,.06);">
      <div style="font-size:11px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#c89443;margin-bottom:28px;">{{ __('magnoolia.page.kontakt.direct_label') }}</div>
      <div class="row gutter-y-20">
        <div class="col-lg-3 col-md-6">
          <div style="font-size:13px;color:#888;margin-bottom:6px;">{{ __('magnoolia.page.kontakt.direct_phone') }}</div>
          <a href="tel:{{ $phone }}" style="font-size:22px;font-weight:700;color:#1d2430;text-decoration:none;display:block;">
            {{ $phoneFormatted }}
          </a>
        </div>
        <div class="col-lg-3 col-md-6">
          <div style="font-size:13px;color:#888;margin-bottom:6px;">{{ __('magnoolia.page.kontakt.direct_email') }}</div>
          <a href="mailto:{{ $email }}" style="font-size:16px;font-weight:600;color:#c89443;text-decoration:none;display:block;word-break:break-all;">
            {{ $email }}
          </a>
        </div>
        <div class="col-lg-3 col-md-6">
          <div style="font-size:13px;color:#888;margin-bottom:6px;">{{ __('magnoolia.page.kontakt.direct_loc') }}</div>
          <div style="font-size:14px;color:#444;line-height:1.7;">{!! __('magnoolia.page.kontakt.direct_address') !!}</div>
        </div>
        <div class="col-lg-3 col-md-6" style="display:flex;align-items:flex-end;">
          <div style="font-size:12px;color:#aaa;line-height:1.7;">{{ __('magnoolia.page.kontakt.direct_note') }}</div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ── Contact form (full-width) ──────────────────────────── --}}
<div id="kontaktivorm">
  @include('sections.magnoolia.contact')
</div>

@endsection

@push('scripts')
<script>
// Pre-fill unit from URL parameter ?unit=X
document.addEventListener('DOMContentLoaded', function () {
  var params = new URLSearchParams(window.location.search);
  var unit   = params.get('unit');
  if (!unit) return;

  // Try select first, then text input
  var sel = document.querySelector('[name="unit"], select[name="unit_select"], #unit-select');
  if (sel && sel.tagName === 'SELECT') {
    for (var i = 0; i < sel.options.length; i++) {
      if (sel.options[i].value === unit || sel.options[i].text.indexOf(unit) !== -1) {
        sel.selectedIndex = i;
        break;
      }
    }
  } else {
    var txt = document.querySelector('[name="unit"], #unit, input[placeholder*="kodu"]');
    if (txt) txt.value = unit;
  }

  // Scroll to form
  var form = document.getElementById('kontaktivorm');
  if (form) { setTimeout(function(){ form.scrollIntoView({ behavior: 'smooth', block: 'start' }); }, 300); }
});
</script>
@endpush
