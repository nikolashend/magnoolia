@extends('layouts.app')

@section('title', $page['title'] ?? 'Küsi Magnoolia kodu pakkumist')
@section('meta_description', $page['description'] ?? '')
@section('og_title', $page['title'] ?? 'Küsi Magnoolia kodu pakkumist')
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
        { "@@type": "ListItem", "position": 2, "name": "Kontakt", "item": "{{ $canonicalBase }}/kontakt" }
      ]
    },
    {
      "@@type": "ContactPage",
      "@@id": "{{ $canonicalBase }}/kontakt",
      "name": "Küsi Magnoolia kodu pakkumist",
      "description": "Küsi Diana Talilt vaba kodu saadavust, hinda ja plaani. Vastame 1 tööpäeva jooksul.",
      "url": "{{ $canonicalBase }}/kontakt"
    },
    {
      "@@type": "Organization",
      "@@id": "{{ $canonicalBase }}/#organization",
      "name": "Magnoolia ridaelamukodud",
      "url": "{{ $canonicalBase }}",
      "contactPoint": {
        "@@type": "ContactPoint",
        "telephone": "+372-58-16-40-78",
        "contactType": "sales",
        "availableLanguage": ["Estonian","Russian","English"]
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
                ['label' => 'Kontakt'],
            ]
        ])
        <h1 style="font-size:clamp(28px,4vw,44px);font-weight:700;color:#fff;margin:16px 0 20px;line-height:1.2;">
            {{ $page['h1'] ?? 'Küsi Magnoolia kodu kohta pakkumist' }}
        </h1>
        <p style="color:rgba(255,255,255,.7);font-size:17px;line-height:1.75;max-width:700px;margin-bottom:24px;">
            Saatke päring ja Diana Tali täpsustab teie soovile vastava kodu saadavuse,
            hinna ja järgmised sammud. Vastame 1 tööpäeva jooksul.
        </p>
        <div style="display:flex;gap:24px;flex-wrap:wrap;align-items:center;">
            <a href="tel:+37258164078" style="color:#c89443;font-weight:700;font-size:18px;text-decoration:none;display:flex;align-items:center;gap:10px;">
                <i class="fas fa-phone"></i> +372 58 16 40 78
            </a>
            <a href="mailto:info@magnoolia.ee" style="color:rgba(255,255,255,.6);font-size:15px;text-decoration:none;">
                <i class="fas fa-envelope" style="margin-right:6px;"></i>info@magnoolia.ee
            </a>
        </div>
    </div>
</section>

{{-- ── Contact form section ────────────────────────────────────── --}}
@include('sections.magnoolia.contact')

{{-- ── Trust quick links ───────────────────────────────────────── --}}
<section style="background:#f7f4ef;padding:48px 0;">
    <div class="container">
        <div class="row gutter-y-20">
            <div class="col-lg-4 col-md-6">
                <a href="{{ route('magnoolia.homes') }}"
                   style="display:flex;align-items:center;gap:14px;background:#fff;border-radius:14px;padding:20px 22px;text-decoration:none;border:1px solid rgba(29,36,48,.07);transition:border-color .2s;"
                   onmouseover="this.style.borderColor='#c89443'" onmouseout="this.style.borderColor='rgba(29,36,48,.07)'">
                    <i class="fas fa-table" style="color:#c89443;font-size:22px;flex-shrink:0;"></i>
                    <div>
                        <div style="font-weight:700;color:#1d2430;font-size:15px;">Kodud ja hinnad</div>
                        <div style="font-size:13px;color:#6f6a61;margin-top:2px;">Vaata hinnatabelit</div>
                    </div>
                </a>
            </div>
            <div class="col-lg-4 col-md-6">
                <a href="{{ route('magnoolia.site-plan') }}"
                   style="display:flex;align-items:center;gap:14px;background:#fff;border-radius:14px;padding:20px 22px;text-decoration:none;border:1px solid rgba(29,36,48,.07);transition:border-color .2s;"
                   onmouseover="this.style.borderColor='#c89443'" onmouseout="this.style.borderColor='rgba(29,36,48,.07)'">
                    <i class="fas fa-map" style="color:#c89443;font-size:22px;flex-shrink:0;"></i>
                    <div>
                        <div style="font-weight:700;color:#1d2430;font-size:15px;">Asendiplaan</div>
                        <div style="font-size:13px;color:#6f6a61;margin-top:2px;">Vaata kodude paiknemist</div>
                    </div>
                </a>
            </div>
            <div class="col-lg-4 col-md-6">
                <a href="{{ route('magnoolia.location') }}"
                   style="display:flex;align-items:center;gap:14px;background:#fff;border-radius:14px;padding:20px 22px;text-decoration:none;border:1px solid rgba(29,36,48,.07);transition:border-color .2s;"
                   onmouseover="this.style.borderColor='#c89443'" onmouseout="this.style.borderColor='rgba(29,36,48,.07)'">
                    <i class="fas fa-map-marker-alt" style="color:#c89443;font-size:22px;flex-shrink:0;"></i>
                    <div>
                        <div style="font-weight:700;color:#1d2430;font-size:15px;">Asukoht</div>
                        <div style="font-size:13px;color:#6f6a61;margin-top:2px;">Vaela küla, Kiili vald</div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</section>

{{-- ── ?unit= prefill script ───────────────────────────────────── --}}
@if($selectedUnit)
<script>
document.addEventListener('DOMContentLoaded', function () {
    var unitId = '{{ $selectedUnit }}';
    // Prefill the unit select
    var sel = document.getElementById('mg-selected-unit-select');
    if (sel) {
        for (var i = 0; i < sel.options.length; i++) {
            if (sel.options[i].value === unitId) {
                sel.selectedIndex = i;
                break;
            }
        }
    }
    // Prefill the message textarea
    var ta = document.querySelector('textarea[name="message"]');
    if (ta && !ta.value) {
        ta.value = 'Olen huvitatud kodust ' + unitId + '. Palun võtke ühendust.';
    }
    // Scroll to form
    var form = document.getElementById('mg-contact-form');
    if (form) {
        setTimeout(function () {
            form.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }, 300);
    }
});
</script>
@endif

@endsection
