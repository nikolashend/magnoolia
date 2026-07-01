{{--
  Phase 34.2 SEO/Ads landing — /ru/taunhaus-rjadom-s-tallinnom
  Primary keyword (RU): "таунхаус рядом с Таллином". Standalone, dev-managed, RU, indexable.
  Facts used are verified from the live site only (no invented numbers/claims).
--}}
@extends('layouts.app')

@section('title', 'Таунхаусы рядом с Таллином | Новые дома класса A в Харьюмаа')
@section('meta_description', 'Magnoolia — 19 таунхаусов класса A в деревне Vaela, волость Kiili, Харьюмаа. Частный двор, терраса и балкон, до Таллина примерно 20 минут. Смотрите цены и планировки.')

@section('content')
@php
  $base = rtrim(config('magnoolia.seo.canonical_base', 'https://magnoolia.ee'), '/');
  $url  = $base . '/ru/taunhaus-rjadom-s-tallinnom';

  // Единый источник для видимого FAQ И для FAQPage JSON-LD (не могут разойтись).
  $faqs = [
    ['q' => 'Где расположены таунхаусы Magnoolia?',
     'a' => 'Проект находится в деревне Vaela, волость Kiili, Харьюмаа, на улице Magnoolia tee. Это спокойное окружение рядом с Таллином: дорога до города занимает примерно 20 минут в зависимости от маршрута и трафика.'],
    ['q' => 'Есть ли у каждого дома собственный двор?',
     'a' => 'Да. У каждого таунхауса Magnoolia есть частный двор, а также терраса и балкон. Это даёт ощущение отдельного дома без забот, связанных с обслуживанием большого участка.'],
    ['q' => 'Сколько комнат в домах и какая площадь?',
     'a' => 'Дома рассчитаны на 4–5 комнат, чистая площадь около 129 м² (в отдельных планировках до примерно 143 м²). Планировки продуманы для повседневной жизни семьи.'],
    ['q' => 'Какое энергетическое решение используется?',
     'a' => 'Все дома относятся к классу энергоэффективности A: геотермальный тепловой насос, вентиляция с рекуперацией тепла и напольное отопление помогают удерживать эксплуатационные расходы ниже.'],
    ['q' => 'Когда дома будут готовы и какие цены?',
     'a' => 'Строительство идёт этапами, I этап готов в 2027 году. Цены домов I этапа указаны в прайс-листе, цены II этапа уточняются. Актуальные данные смотрите в разделе «Дома и цены».'],
    ['q' => 'Кто застройщик проекта?',
     'a' => 'Застройщик — Estlanda OÜ, работает с 2009 года. По вопросам покупки консультирует Диана — она поможет подобрать подходящий дом и ответит на вопросы.'],
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
      "name": @json('Таунхаусы рядом с Таллином — новые дома класса A в Харьюмаа'),
      "description": @json('Таунхаусы класса A рядом с Таллином в деревне Vaela (волость Kiili Харьюмаа) — с частным двором террасой и балконом.'),
      "inLanguage": "ru-RU",
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
      ['label' => 'Главная', 'url' => route('ru.home')],
      ['label' => 'Таунхаусы рядом с Таллином'],
    ]])
    <div class="mg-page-hero__eyebrow">Таунхаусы рядом с Таллином · деревня Vaela, волость Kiili</div>
    <h1 class="mg-page-hero__title">Таунхаусы рядом с Таллином — новые дома класса A с частным двором</h1>
    <p class="mg-page-hero__lead">Magnoolia — это 19 таунхаусов класса энергоэффективности A в деревне Vaela, волость Kiili. 4–5 комнат, частный двор, терраса и балкон, а также удобная связь с Таллином.</p>
    <p class="mg-page-hero__note">Деревня Vaela · волость Kiili · Харьюмаа · до Таллина примерно 20 минут (в зависимости от трафика)</p>
    <div class="mg-page-hero__ctas">
      <a href="{{ lroute('magnoolia.homes') }}" class="zoomvilla-btn" data-mg-analytics="magnoolia_cta_click">Смотреть цены и планировки <i class="icon-angle-small-right"></i></a>
      <a href="{{ lroute('magnoolia.contact') }}" class="zoomvilla-btn zoomvilla-btn--border" data-mg-inquiry-open data-source-component="lp_taunhaus-rjadom-s-tallinnom_hero" data-mg-analytics="magnoolia_cta_click">Запросить предложение</a>
    </div>
  </div>
</div>

{{-- ── Коротко (AEO answer block) ────────────────────────────── --}}
<section class="mg-page-section mg-page-section--warm">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-9">
        <div style="background:#fff;border-radius:16px;padding:30px 34px;border-left:4px solid #c89443;">
          <div style="font-size:11px;color:#c89443;font-weight:700;letter-spacing:.12em;text-transform:uppercase;margin-bottom:10px;">Коротко</div>
          <p style="font-size:16px;color:#3a3530;line-height:1.75;margin:0;">Magnoolia — новый проект таунхаусов в Харьюмаа: 19 домов класса A в деревне Vaela, волость Kiili. Каждый дом рассчитан на 4–5 комнат (чистая площадь около 129 м²), с частным двором, террасой и балконом, а также собственным местом для парковки. Геотермальный тепловой насос, вентиляция с рекуперацией и напольное отопление удерживают расходы ниже. До Таллина — примерно 20 минут в зависимости от маршрута и трафика.</p>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ── Почему Magnoolia ──────────────────────────────────────── --}}
<section class="mg-page-section mg-page-section--white">
  <div class="container">
    <div class="mg-section-heading" style="margin-bottom:36px;">
      <div class="mg-section-heading__eyebrow">Почему Magnoolia</div>
      <h2 class="mg-section-heading__title">Комфорт таунхауса, ощущение частного дома</h2>
    </div>
    <div class="row gutter-y-28">
      @php
        $feat = [
          ['icon' => 'fas fa-vector-square', 'title' => '4–5 комнат, ~129 м²', 'body' => 'Просторные, продуманные планировки для всей семьи — достаточно места как для повседневной жизни, так и для домашнего кабинета.'],
          ['icon' => 'fas fa-seedling',      'title' => 'Частный двор',       'body' => 'У каждого дома собственный двор, а также терраса и балкон — открытое пространство, которое принадлежит только вам.'],
          ['icon' => 'fas fa-bolt',          'title' => 'Класс A',            'body' => 'Геотермальный тепловой насос, вентиляция с рекуперацией тепла и напольное отопление — энергоэффективный дом с более низкими расходами.'],
          ['icon' => 'fas fa-car',           'title' => 'Своя парковка',      'body' => 'Удобное парковочное решение с навесом рядом с домом — без ежедневного поиска свободного места.'],
          ['icon' => 'fas fa-map-marker-alt','title' => 'Рядом с Таллином',   'body' => 'Деревня Vaela, волость Kiili — спокойная среда примерно в 20 минутах от Таллина, в зависимости от трафика.'],
          ['icon' => 'fas fa-home',          'title' => '19 отдельных домов', 'body' => 'Невысокая плотность застройки и продуманный проект — без ощущения многоквартирного дома.'],
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

{{-- ── Жизнь рядом с городом, но не в городе (уникальный ракурс) ── --}}
<section class="mg-page-section mg-page-section--cream">
  <div class="container">
    <div class="mg-section-heading" style="margin-bottom:32px;">
      <div class="mg-section-heading__eyebrow">Баланс города и тишины</div>
      <h2 class="mg-section-heading__title">Близко к Таллину, но в своём ритме</h2>
    </div>
    <div class="row gutter-y-16">
      @foreach([
        'До Таллина примерно 20 минут — в зависимости от маршрута и трафика',
        'Своя улица Magnoolia tee в спокойной деревне Vaela',
        'Частный двор и терраса вместо общего двора многоэтажки',
        'Тише, чем в квартире — меньше соседского шума за стеной',
        'Подходит для семьи с детьми и домашними животными',
        'Класс A — предсказуемые и более низкие расходы на содержание',
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

{{-- ── Доступные дома (teaser → BOFU) ────────────────────────── --}}
<section class="mg-page-section mg-page-section--white">
  <div class="container">
    <div class="row align-items-center gutter-y-28">
      <div class="col-lg-7">
        <div class="mg-section-heading" style="margin-bottom:14px;">
          <div class="mg-section-heading__eyebrow">Доступные дома</div>
          <h2 class="mg-section-heading__title">19 домов, два этапа</h2>
        </div>
        <p style="font-size:15px;color:#6f6a61;line-height:1.75;margin:0 0 18px;">Планировки, площади, статус и цены всех домов собраны на одной странице. Цены I этапа публичны, цены II этапа уточняются. Наличие меняется — рекомендуем следить за прайс-листом и запросить предложение.</p>
        <a href="{{ lroute('magnoolia.homes') }}" class="zoomvilla-btn" data-mg-analytics="magnoolia_cta_click">Смотреть дома и цены <i class="icon-angle-small-right"></i></a>
      </div>
      <div class="col-lg-5">
        <a href="{{ lroute('magnoolia.site-plan') }}" style="display:block;border-radius:16px;overflow:hidden;text-decoration:none;">
          <img {!! mg_img('Cam005.0000.jpg', '(max-width:991px) 100vw, 460px') !!} alt="Таунхаусы Magnoolia в деревне Vaela — вид с улицы" loading="lazy" decoding="async" style="width:100%;height:auto;display:block;border-radius:16px;">
        </a>
      </div>
    </div>
  </div>
</section>

{{-- ── Кому подходит / Что узнает покупатель / Следующий шаг ─── --}}
<section class="mg-page-section mg-page-section--warm">
  <div class="container">
    <div class="row gutter-y-28">
      @php
        $aeo = [
          ['t' => 'Кому подходит?', 'b' => 'Семьям, которым тесно в квартире и хочется больше пространства и собственный двор, но без нагрузки по обслуживанию отдельного дома. Подойдёт и тем, кто работает удалённо и ценит спокойное окружение рядом с Таллином.'],
          ['t' => 'Что узнает покупатель?', 'b' => 'Планировки и площади домов, размеры двора, террасы и балкона, парковку, энергетическое решение, этапы и сроки готовности, а также актуальный прайс-лист — всё собрано в разделе «Дома и цены».'],
          ['t' => 'Каким будет следующий шаг?', 'b' => 'Посмотрите доступные дома и цены, выберите подходящий вариант и запросите персональное предложение. Консультант по продажам Диана поможет подобрать лучшее решение.'],
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

{{-- ── FAQ (видимый + микроразметка; JSON-LD выше построен из тех же $faqs) ── --}}
@include('sections.magnoolia.page-faq', [
  'eyebrow' => 'Вопросы и ответы',
  'title'   => 'Частые вопросы',
  'bg'      => 'white',
  'faqs'    => $faqs,
])

{{-- ── Internal links ────────────────────────────────────────── --}}
<section class="mg-page-section--cream mg-page-section--sm">
  <div class="container">
    <div class="mg-internal-links">
      <a href="{{ lroute('magnoolia.homes') }}" class="mg-internal-link"><i class="fas fa-table"></i> Дома и цены</a>
      <a href="{{ lroute('magnoolia.site-plan') }}" class="mg-internal-link"><i class="fas fa-map"></i> План участка</a>
      <a href="{{ lroute('magnoolia.location') }}" class="mg-internal-link"><i class="fas fa-map-marker-alt"></i> Расположение</a>
      <a href="{{ lroute('magnoolia.finantseerimine') }}" class="mg-internal-link"><i class="fas fa-percent"></i> Финансирование</a>
      <a href="{{ lroute('magnoolia.ostuprotsess') }}" class="mg-internal-link"><i class="fas fa-list-ol"></i> Процесс покупки</a>
      <a href="{{ lroute('magnoolia.contact') }}" class="mg-internal-link"><i class="fas fa-envelope"></i> Контакты</a>
    </div>
  </div>
</section>

@include('sections.magnoolia.page-cta', [
  'title'   => 'Найдите свой таунхаус рядом с Таллином',
  'sub'     => 'Посмотрите доступные дома и запросите персональное предложение.',
  'buttons' => [
    ['label' => 'Смотреть дома и цены', 'url' => lroute('magnoolia.homes')],
    ['label' => 'Запросить предложение', 'url' => lroute('magnoolia.contact'), 'outline' => true, 'inquiry' => true, 'source' => 'lp_taunhaus-rjadom-s-tallinnom_cta'],
  ]
])

@endsection
