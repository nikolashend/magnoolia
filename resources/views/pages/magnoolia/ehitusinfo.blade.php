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
    <h1 class="mg-page-hero__title">{{ __('magnoolia.page.ehitusinfo.page_h1') }}</h1>
    <p class="mg-page-hero__lead">
      {{ __('magnoolia.page.ehitusinfo.lead') }}
    </p>
    <p class="mg-page-hero__note">
      {{ __('magnoolia.page.ehitusinfo.note') }}
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

{{-- ── Phase 28: Material specifications (source: Excel/project docs) ── --}}
<section class="mg-page-section mg-page-section--cream" id="materjalid">
  <div class="container">
    <div class="mg-section-heading" style="margin-bottom:40px;">
      <div class="mg-section-heading__eyebrow">
        {{ app()->getLocale()==='ru' ? 'Характеристики' : (app()->getLocale()==='en' ? 'Specifications' : 'Karakteristikud') }}
      </div>
      <h2 class="mg-section-heading__title">
        {{ app()->getLocale()==='ru' ? 'Материалы и технические решения' : (app()->getLocale()==='en' ? 'Materials and technical solutions' : 'Materjalid ja tehnilised lahendused') }}
      </h2>
      <p class="mg-section-heading__subtitle">
        {{ app()->getLocale()==='ru' ? 'Технические и отделочные решения уточняются в окончательном предложении о продаже и проектной документации. Изображения и образцы материалов носят иллюстративный характер.' : (app()->getLocale()==='en' ? 'Technical and finish solutions are confirmed in the final sales offer and project documentation. Images and material samples are illustrative.' : 'Tehnilised ja viimistluslahendused täpsustatakse lõplikus müügipakkumises ja projektdokumentatsioonis. Pildid ja materjalinäidised on illustratiivsed.') }}
      </p>
    </div>

    <div class="row gutter-y-28">

      {{-- Tile specifications --}}
      <div class="col-lg-6">
        <div style="background:#fff;border-radius:16px;padding:28px;height:100%;border:1px solid rgba(29,36,48,.06);">
          <h3 style="font-size:16px;font-weight:700;color:#1d2430;margin:0 0 20px;display:flex;align-items:center;gap:10px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#c89443" stroke-width="2" aria-hidden="true"><rect x="3" y="3" width="8" height="8"/><rect x="13" y="3" width="8" height="8"/><rect x="3" y="13" width="8" height="8"/><rect x="13" y="13" width="8" height="8"/></svg>
            {{ app()->getLocale()==='ru' ? 'Плиточная отделка' : (app()->getLocale()==='en' ? 'Tile specification' : 'Plaadilahendus') }}
          </h3>
          <table style="width:100%;border-collapse:collapse;font-size:13px;">
            <thead>
              <tr style="border-bottom:2px solid #f0ede8;">
                <th style="text-align:left;padding:8px 4px;color:#888;font-weight:600;font-size:11px;letter-spacing:.06em;text-transform:uppercase;">
                  {{ app()->getLocale()==='ru' ? 'Расположение' : (app()->getLocale()==='en' ? 'Location' : 'Asukoht') }}
                </th>
                <th style="text-align:left;padding:8px 4px;color:#888;font-weight:600;font-size:11px;letter-spacing:.06em;text-transform:uppercase;">
                  {{ app()->getLocale()==='ru' ? 'Тип плитки' : (app()->getLocale()==='en' ? 'Tile type' : 'Plaaditüüp') }}
                </th>
                <th style="text-align:right;padding:8px 4px;color:#888;font-weight:600;font-size:11px;letter-spacing:.06em;text-transform:uppercase;">
                  {{ app()->getLocale()==='ru' ? 'Площадь' : (app()->getLocale()==='en' ? 'Area' : 'Pind') }}
                </th>
              </tr>
            </thead>
            <tbody>
              @foreach([
                ['loc_et' => 'Vannituba, sauna eesruum, duširuumid', 'loc_ru' => 'Ванная, предбанник, душ', 'loc_en' => 'Bathroom, sauna anteroom, showers', 'type' => '60×120 cm', 'area' => '~121 m²'],
                ['loc_et' => 'Esik, WC, leiliruumid, eesr., vannituba', 'loc_ru' => 'Прихожая, WC, сауна, предбанник', 'loc_en' => 'Hallway, WC, sauna rooms, anteroom', 'type' => '60×60 cm', 'area' => '~104 m²'],
              ] as $row)
              <tr style="border-bottom:1px solid #f3f4f6;">
                <td style="padding:10px 4px;color:#444;line-height:1.4;vertical-align:top;">
                  {{ app()->getLocale()==='ru' ? $row['loc_ru'] : (app()->getLocale()==='en' ? $row['loc_en'] : $row['loc_et']) }}
                </td>
                <td style="padding:10px 4px;font-weight:600;color:#1d2430;white-space:nowrap;vertical-align:top;">{{ $row['type'] }}</td>
                <td style="padding:10px 4px;text-align:right;color:#c89443;font-weight:600;vertical-align:top;white-space:nowrap;">{{ $row['area'] }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
          <p style="font-size:11px;color:#aaa;margin-top:12px;font-style:italic;">
            {{ app()->getLocale()==='ru' ? 'По данным проектной документации (Magnoolia tee). Конкретные материалы уточняются.' : (app()->getLocale()==='en' ? 'Based on project documentation (Magnoolia tee). Specific materials subject to confirmation.' : 'Andmed projektdokumentatsiooni alusel (Magnoolia tee). Konkreetsed materjalid täpsustuvad.') }}
          </p>
        </div>
      </div>

      {{-- Sanitary fittings --}}
      <div class="col-lg-6">
        <div style="background:#fff;border-radius:16px;padding:28px;height:100%;border:1px solid rgba(29,36,48,.06);">
          <h3 style="font-size:16px;font-weight:700;color:#1d2430;margin:0 0 20px;display:flex;align-items:center;gap:10px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#c89443" stroke-width="2" aria-hidden="true"><path d="M4 12a8 8 0 0 1 16 0Z"/><path d="M2 12h20"/><path d="M4 12v8"/><path d="M20 12v8"/></svg>
            {{ app()->getLocale()==='ru' ? 'Санитарная арматура' : (app()->getLocale()==='en' ? 'Sanitary fittings' : 'Sanitaarvarustus') }}
          </h3>
          <table style="width:100%;border-collapse:collapse;font-size:13px;">
            <thead>
              <tr style="border-bottom:2px solid #f0ede8;">
                <th style="text-align:left;padding:8px 4px;color:#888;font-weight:600;font-size:11px;letter-spacing:.06em;text-transform:uppercase;">
                  {{ app()->getLocale()==='ru' ? 'Элемент' : (app()->getLocale()==='en' ? 'Element' : 'Element') }}
                </th>
                <th style="text-align:right;padding:8px 4px;color:#888;font-weight:600;font-size:11px;letter-spacing:.06em;text-transform:uppercase;">
                  {{ app()->getLocale()==='ru' ? 'Кол-во / дом' : (app()->getLocale()==='en' ? 'Qty / home' : 'Tk / kodu') }}
                </th>
              </tr>
            </thead>
            <tbody>
              @foreach([
                ['name_et' => 'Dušisegistid', 'name_ru' => 'Душевые смесители', 'name_en' => 'Shower fittings', 'qty' => '2'],
                ['name_et' => 'Eraldi WC segistid', 'name_ru' => 'Смесители для WC', 'name_en' => 'Separate WC faucets', 'qty' => '~1–2'],
                ['name_et' => 'Vanni segistid', 'name_ru' => 'Смесители для ванны', 'name_en' => 'Bath faucets', 'qty' => '~1'],
                ['name_et' => 'Vannitoa kraanid', 'name_ru' => 'Краны для раковины', 'name_en' => 'Bathroom sink faucets', 'qty' => '2'],
              ] as $row)
              <tr style="border-bottom:1px solid #f3f4f6;">
                <td style="padding:10px 4px;color:#444;line-height:1.4;">
                  {{ app()->getLocale()==='ru' ? $row['name_ru'] : (app()->getLocale()==='en' ? $row['name_en'] : $row['name_et']) }}
                </td>
                <td style="padding:10px 4px;text-align:right;font-weight:600;color:#1d2430;">{{ $row['qty'] }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
          <p style="font-size:11px;color:#aaa;margin-top:12px;font-style:italic;">
            {{ app()->getLocale()==='ru' ? 'Точные марки подтверждаются в договоре. Иллюстративно.' : (app()->getLocale()==='en' ? 'Exact brands confirmed in contract. Illustrative.' : 'Täpsed margid kinnitatakse lepingus. Illustratiivne.') }}
          </p>
        </div>
      </div>

      {{-- Cross-link to Sisedisain --}}
      <div class="col-12">
        <div style="background:#1d2430;border-radius:12px;padding:20px 28px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;">
          <div style="color:#fff;">
            <div style="font-size:11px;color:#c89443;font-weight:700;letter-spacing:.1em;text-transform:uppercase;margin-bottom:4px;">
              {{ app()->getLocale()==='ru' ? 'Дизайн интерьера' : (app()->getLocale()==='en' ? 'Interior design' : 'Siseviimistlus') }}
            </div>
            <div style="font-size:15px;font-weight:600;">
              {{ app()->getLocale()==='ru' ? 'Материалы, отделка и дополнительные опции подробно описаны на странице Sisedisain' : (app()->getLocale()==='en' ? 'Materials, finishes and add-on options are detailed on the Sisedisain page' : 'Materjalid, viimistlus ja lisavalikud on üksikasjalikult kirjeldatud sisedisaini lehel') }}
            </div>
          </div>
          <a href="{{ lroute('magnoolia.sisedisain') }}"
             style="flex-shrink:0;background:#c89443;color:#fff;text-decoration:none;padding:10px 24px;border-radius:8px;font-size:13px;font-weight:700;white-space:nowrap;">
            {{ app()->getLocale()==='ru' ? 'Дизайн интерьера →' : (app()->getLocale()==='en' ? 'Interior design →' : 'Sisedisain →') }}
          </a>
        </div>
      </div>

    </div>
  </div>
</section>

{{-- ── Phase 31: premium interior-finish & equipment standard ──────── --}}
@include('partials.magnoolia.interior-finish-section')

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
