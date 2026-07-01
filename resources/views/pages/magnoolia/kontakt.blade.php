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
<div class="mg-page-hero" style="background-image:linear-gradient(to right, rgba(29,36,48,.88) 60%, rgba(29,36,48,.5)), url('{{ asset('assets/images/magnoolia/Cam005.0000.jpg') }}');background-size:cover;background-position:center;">
  <div class="container">
    @include('partials.seo.breadcrumb', ['items' => [
      ['label' => __('magnoolia.nav.home'), 'url' => route('home')],
      ['label' => __('magnoolia.nav.contact')],
    ]])
    <div class="mg-page-hero__eyebrow">{{ __('magnoolia.page.kontakt.eyebrow') }}</div>
    <h1 class="mg-page-hero__title">{{ mg_text('page.kontakt.page_h1') }}</h1>
    <p class="mg-page-hero__lead">{!! mg_text('page.kontakt.lead') !!}</p>
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

{{-- ── Phase 26: Diana Tali — müügikonsultant ──────────────── --}}
<section class="mg-page-section mg-page-section--white">
  <div class="container">
    <div style="max-width:760px;margin:0 auto;">
      <div style="background:#fff;border-radius:16px;padding:36px;box-shadow:0 4px 20px rgba(29,36,48,.06);display:flex;align-items:flex-start;gap:28px;flex-wrap:wrap;">
        <div style="flex-shrink:0;">
          @if(file_exists(public_path('assets/magnoolia/people/diana-tali.webp')))
          <img src="{{ asset('assets/magnoolia/people/diana-tali.webp') }}"
               alt="Diana Tali — Müügikonsultant"
               width="96" height="96"
               style="width:96px;height:96px;border-radius:50%;object-fit:cover;">
          @else
          <div style="width:96px;height:96px;border-radius:50%;background:#e5ddd0;display:flex;align-items:center;justify-content:center;">
            <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#9c8b7e" stroke-width="1.5" aria-hidden="true"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
          </div>
          @endif
        </div>
        <div style="flex:1;min-width:200px;">
          <div style="font-size:11px;color:#c89443;font-weight:700;letter-spacing:.1em;text-transform:uppercase;margin-bottom:8px;">{{ __('magnoolia.contact.sales_label') }}</div>
          <div style="font-size:26px;font-weight:700;color:#1d2430;margin-bottom:4px;">Diana Tali</div>
          <div style="font-size:14px;color:#888;margin-bottom:20px;">{{ __('magnoolia.contact.sales_title') }}</div>
          <div style="display:flex;flex-wrap:wrap;gap:12px;">
            <a href="tel:+37258164078"
               data-mg-analytics="magnoolia_phone_click"
               style="display:inline-flex;align-items:center;gap:8px;padding:12px 20px;background:#1d2430;color:#fff;text-decoration:none;border-radius:8px;font-size:15px;font-weight:700;">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 13a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 9.57a16 16 0 0 0 6.29 6.29l.94-.94a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
              +372 58 16 40 78
            </a>
            <a href="mailto:diana@estlanda.ee"
               data-mg-analytics="magnoolia_email_click"
               style="display:inline-flex;align-items:center;gap:8px;padding:12px 20px;background:#f8f5f0;color:#c89443;text-decoration:none;border-radius:8px;font-size:14px;font-weight:700;border:1.5px solid #c89443;">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
              diana@estlanda.ee
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ── Phase 35: BIGBANK financing partner ─────────────────── --}}
<section class="mg-page-section mg-page-section--white" id="bigbank">
  <div class="container">
    <div style="background:#fff;border:1px solid rgba(29,36,48,.08);border-radius:16px;padding:34px 40px;display:flex;gap:32px;align-items:center;flex-wrap:wrap;box-shadow:0 4px 20px rgba(29,36,48,.06);">
      @php $bbExt = collect(['svg','webp','png'])->first(fn ($e) => file_exists(public_path('assets/magnoolia/logos/bigbank.'.$e))); @endphp
      <div style="flex:0 0 auto;">
        @if($bbExt)
          <img src="{{ asset('assets/magnoolia/logos/bigbank.'.$bbExt) }}" alt="Bigbank" style="height:46px;width:auto;display:block;" loading="lazy" decoding="async">
        @else
          <div style="font-size:28px;font-weight:900;color:#003DA5;letter-spacing:-.02em;">Bigbank</div>
        @endif
      </div>
      <div style="flex:1 1 320px;min-width:280px;">
        <div style="font-size:11px;color:#c89443;font-weight:700;letter-spacing:.1em;text-transform:uppercase;margin-bottom:8px;">{{ __('magnoolia.page.finantseerimine.bigbank_eyebrow') }}</div>
        <h3 style="font-size:19px;font-weight:700;color:#1d2430;margin:0 0 10px;">{{ __('magnoolia.page.finantseerimine.bigbank_title') }}</h3>
        <p style="font-size:14px;color:#6f6a61;line-height:1.7;margin:0 0 16px;">{{ __('magnoolia.page.finantseerimine.bigbank_body') }}</p>
        <a href="{{ lroute('magnoolia.finantseerimine') }}#bigbank" class="zoomvilla-btn">{{ __('magnoolia.page.finantseerimine.cta_read') }} <i class="icon-angle-small-right"></i></a>
      </div>
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
          <a href="tel:{{ $phone }}"
             data-mg-analytics="magnoolia_phone_click"
             style="font-size:22px;font-weight:700;color:#1d2430;text-decoration:none;display:block;">
            {{ $phoneFormatted }}
          </a>
        </div>
        <div class="col-lg-3 col-md-6">
          <div style="font-size:13px;color:#888;margin-bottom:6px;">{{ __('magnoolia.page.kontakt.direct_email') }}</div>
          <a href="mailto:{{ $email }}"
             data-mg-analytics="magnoolia_email_click"
             style="font-size:16px;font-weight:600;color:#c89443;text-decoration:none;display:block;word-break:break-all;">
            {{ $email }}
          </a>
        </div>
        <div class="col-lg-3 col-md-6">
          <div style="font-size:13px;color:#888;margin-bottom:6px;">{{ __('magnoolia.page.kontakt.direct_loc') }}</div>
          <div style="font-size:14px;color:#444;line-height:1.7;">{!! __('magnoolia.page.kontakt.direct_address') !!}</div>
        </div>
        <div class="col-lg-3 col-md-6" style="display:flex;align-items:flex-end;">
          <div style="font-size:12px;color:#aaa;line-height:1.7;">{{ mg_text('page.kontakt.direct_note') }}</div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ── Ostuprotsess ja hinnatingimused (prepared client copy) ── --}}
@include('sections.magnoolia.kontakt-terms')

{{-- ── Contact form (full-width) ──────────────────────────── --}}{{-- Answer unit above form --}}
@php
  $au = __('magnoolia.answer_unit.kontakt');
  $au['cta_route'] = '#kontaktivorm';
@endphp
@include('sections.magnoolia.answer-unit', ['unit' => $au])
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
