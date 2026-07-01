{{--
  Phase 34.2 SEO/Ads landing — /ru/novyj-dom-v-harjumaa
  Primary keyword: "новый дом в Харьюмаа". Self-contained, dev-managed, RU, indexable.
  Facts used are verified from the live site only (no invented numbers/claims).
--}}
@extends('layouts.app')

@section('title', 'Новый дом в Харьюмаа | Рядные дома класса A рядом с Таллином')
@section('meta_description', 'Новый дом в Харьюмаа: 19 рядных домов класса A в деревне Вaела, волость Кийли. 4–5 комнат, свой двор, терраса и балкон. Простор для семьи рядом с Таллином. Смотрите цены и планировки.')

@section('content')
@php
  $base = rtrim(config('magnoolia.seo.canonical_base', 'https://magnoolia.ee'), '/');
  $url  = $base . '/ru/novyj-dom-v-harjumaa';

  $ldName = 'Новый дом в Харьюмаа — рядный дом класса A рядом с Таллином';
  $ldDesc = 'Новые рядные дома класса A в деревне Вaела, волость Кийли, Харьюмаа — со своим двором, террасой и балконом, недалеко от Таллина.';

  // Единый источник для видимого FAQ И для FAQPage JSON-LD (не могут разойтись).
  $faqs = [
    ['q' => 'Где именно находится этот новый дом в Харьюмаа?',
     'a' => 'Проект Magnoolia расположен на улице Магнолии в деревне Вaела, волость Кийли, уезд Харьюмаа. Это тихая жилая среда недалеко от Таллина — дорога занимает примерно 20 минут в зависимости от маршрута и трафика.'],
    ['q' => 'Насколько такой дом просторнее квартиры?',
     'a' => 'Дома Magnoolia — это 4–5 комнат на двух этажах, чистая площадь около 129 м² (до примерно 143 м²). Здесь достаточно места для семьи, детской и домашнего кабинета — заметно больше, чем в типовой квартире.'],
    ['q' => 'Есть ли у дома свой двор и парковка?',
     'a' => 'Да. У каждого дома есть приватный двор, а также терраса и балкон. Парковка решена рядом с домом — предусмотрен собственный навес для автомобиля (карпорт).'],
    ['q' => 'Сколько стоит новый дом и когда он будет готов?',
     'a' => 'Цены I этапа опубликованы в прайс-листе, цены II этапа уточняются. Строительство идёт очередями, I этап планируется к сдаче в 2027 году. Актуальные цены и наличие смотрите в разделе «Дома и цены».'],
    ['q' => 'Насколько экономичен дом класса A в содержании?',
     'a' => 'Все дома относятся к энергоклассу A: геотермальный тепловой насос, вентиляция с рекуперацией тепла и напольное отопление. Такое решение помогает удерживать расходы на содержание ниже, чем в старом жилом фонде.'],
    ['q' => 'Кто застройщик проекта?',
     'a' => 'Застройщик — Estlanda OÜ, компания работает с 2009 года. По вопросам покупки и просмотра вас проконсультирует Диана.'],
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
      "name": @json($ldName),
      "description": @json($ldDesc),
      "inLanguage": "ru-EE",
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
      ['label' => 'Главная', 'url' => lroute('home')],
      ['label' => 'Новый дом в Харьюмаа'],
    ]])
    <div class="mg-page-hero__eyebrow">Новый дом в Харьюмаа · деревня Вaела, волость Кийли</div>
    <h1 class="mg-page-hero__title">Новый дом в Харьюмаа — рядный дом класса A рядом с Таллином</h1>
    <p class="mg-page-hero__lead">Magnoolia — это 19 новых рядных домов класса A в деревне Вaела, волость Кийли. Пространство для всей семьи: 4–5 комнат, свой двор, терраса и балкон и удобное сообщение с Таллином.</p>
    <p class="mg-page-hero__note">деревня Вaела · волость Кийли · Харьюмаа · примерно 20 мин до Таллина (в зависимости от трафика)</p>
    <div class="mg-page-hero__ctas">
      <a href="{{ lroute('magnoolia.homes') }}" class="zoomvilla-btn" data-mg-analytics="magnoolia_cta_click">Смотреть цены и планировки <i class="icon-angle-small-right"></i></a>
      <a href="{{ lroute('magnoolia.contact') }}" class="zoomvilla-btn zoomvilla-btn--border" data-mg-inquiry-open data-source-component="lp_novyj-dom-v-harjumaa_hero" data-mg-analytics="magnoolia_cta_click">Запросить предложение</a>
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
          <p style="font-size:16px;color:#3a3530;line-height:1.75;margin:0;">Magnoolia — это новый рядный посёлок в Харьюмаа: 19 домов класса A в деревне Вaела, волость Кийли. Каждый дом — 4–5 комнат (около 129 м², до примерно 143 м²) с приватным двором, террасой, балконом и собственным местом для машины. Геотермальный тепловой насос, вентиляция с рекуперацией и напольное отопление удерживают расходы на содержание низкими. До Таллина — примерно 20 минут.</p>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ── Почему стоит переехать из квартиры ────────────────────── --}}
<section class="mg-page-section mg-page-section--white">
  <div class="container">
    <div class="mg-section-heading" style="margin-bottom:36px;">
      <div class="mg-section-heading__eyebrow">Почему Magnoolia</div>
      <h2 class="mg-section-heading__title">Новый дом, в котором семье есть где расти</h2>
    </div>
    <div class="row gutter-y-28">
      @php
        $feat = [
          ['icon' => 'fas fa-vector-square', 'title' => '4–5 комнат, ~129 м²', 'body' => 'Продуманные планировки на двух этажах — место для детей, гостей и домашнего кабинета вместо тесной квартиры.'],
          ['icon' => 'fas fa-seedling',      'title' => 'Свой двор', 'body' => 'У каждого дома приватный двор, терраса и балкон — открытое пространство, которое принадлежит только вам.'],
          ['icon' => 'fas fa-bolt',          'title' => 'Энергокласс A',      'body' => 'Геотермальный тепловой насос, вентиляция с рекуперацией и напольное отопление — энергоэффективный дом с меньшими счетами.'],
          ['icon' => 'fas fa-car',           'title' => 'Своя парковка',       'body' => 'Место для машины рядом с домом с навесом — больше не нужно искать парковку по утрам.'],
          ['icon' => 'fas fa-map-marker-alt','title' => 'Рядом с Таллином',    'body' => 'Деревня Вaела, волость Кийли — спокойная среда примерно в 20 минутах от Таллина.'],
          ['icon' => 'fas fa-home',          'title' => '19 отдельных домов',  'body' => 'Низкая плотность застройки и продуманный проект — атмосфера частного дома, а не многоквартирника.'],
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

{{-- ── Переезд из квартиры (page-specific angle) ─────────────── --}}
<section class="mg-page-section mg-page-section--cream">
  <div class="container">
    <div class="mg-section-heading" style="margin-bottom:32px;">
      <div class="mg-section-heading__eyebrow">Переезд из квартиры</div>
      <h2 class="mg-section-heading__title">Когда квартиры уже мало для семьи</h2>
    </div>
    <div class="row gutter-y-16">
      @foreach([
        'Отдельная комната каждому ребёнку и место для работы из дома',
        'Свой двор для игр, гриля и отдыха — без общих дворов и очередей',
        'Меньше шума от соседей, чем в многоквартирном доме',
        'Своя парковка у дома, а не поиск места на улице',
        'Простор для детей и домашних питомцев',
        'Новый дом класса A вместо ремонта старого жилья',
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
          <h2 class="mg-section-heading__title">19 домов, две очереди строительства</h2>
        </div>
        <p style="font-size:15px;color:#6f6a61;line-height:1.75;margin:0 0 18px;">Планировки, площади, статус и цены всех домов собраны на одной странице. Цены I этапа опубликованы, цены II этапа уточняются. Наличие меняется — рекомендуем следить за прайс-листом и запросить предложение.</p>
        <a href="{{ lroute('magnoolia.homes') }}" class="zoomvilla-btn" data-mg-analytics="magnoolia_cta_click">Смотреть дома и цены <i class="icon-angle-small-right"></i></a>
      </div>
      <div class="col-lg-5">
        <a href="{{ lroute('magnoolia.site-plan') }}" style="display:block;border-radius:16px;overflow:hidden;text-decoration:none;">
          <img {!! mg_img('Cam005.0000.jpg', '(max-width:991px) 100vw, 460px') !!} alt="Рядные дома Magnoolia в деревне Вaела — вид проекта" loading="lazy" decoding="async" style="width:100%;height:auto;display:block;border-radius:16px;">
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
          ['t' => 'Кому подходит?', 'b' => 'Семьям, которые выросли из квартиры и хотят больше пространства и свой двор, но без хлопот с содержанием отдельного частного дома. Подходит и тем, кто работает удалённо и ценит спокойную среду рядом с Таллином.'],
          ['t' => 'Что узнает покупатель?', 'b' => 'Планировки и площади домов, размеры двора, террасы и балкона, парковку, энергетическое решение, очереди и сроки готовности, а также актуальный прайс-лист — всё собрано в разделе «Дома и цены».'],
          ['t' => 'Каков следующий шаг?', 'b' => 'Посмотрите доступные дома и цены, выберите подходящий вариант и запросите персональное предложение. Консультант по продажам Диана поможет подобрать оптимальное решение.'],
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
  'eyebrow' => 'Вопросы и ответы',
  'title'   => 'Часто задаваемые вопросы',
  'bg'      => 'white',
  'faqs'    => $faqs,
])

{{-- ── Internal links ────────────────────────────────────────── --}}
<section class="mg-page-section--cream mg-page-section--sm">
  <div class="container">
    <div class="mg-internal-links">
      <a href="{{ lroute('magnoolia.homes') }}" class="mg-internal-link"><i class="fas fa-table"></i> Дома и цены</a>
      <a href="{{ lroute('magnoolia.site-plan') }}" class="mg-internal-link"><i class="fas fa-map"></i> Генплан</a>
      <a href="{{ lroute('magnoolia.location') }}" class="mg-internal-link"><i class="fas fa-map-marker-alt"></i> Расположение</a>
      <a href="{{ lroute('magnoolia.finantseerimine') }}" class="mg-internal-link"><i class="fas fa-percent"></i> Финансирование</a>
      <a href="{{ lroute('magnoolia.ostuprotsess') }}" class="mg-internal-link"><i class="fas fa-list-ol"></i> Процесс покупки</a>
      <a href="{{ lroute('magnoolia.kkk') }}" class="mg-internal-link"><i class="fas fa-question-circle"></i> Вопросы и ответы</a>
      <a href="{{ lroute('magnoolia.contact') }}" class="mg-internal-link"><i class="fas fa-envelope"></i> Контакты</a>
    </div>
  </div>
</section>

@include('sections.magnoolia.page-cta', [
  'title'   => 'Найдите свой новый дом в Харьюмаа',
  'sub'     => 'Посмотрите доступные дома и запросите персональное предложение.',
  'buttons' => [
    ['label' => 'Смотреть дома и цены', 'url' => lroute('magnoolia.homes')],
    ['label' => 'Запросить предложение', 'url' => lroute('magnoolia.contact'), 'outline' => true, 'inquiry' => true, 'source' => 'lp_novyj-dom-v-harjumaa_cta'],
  ]
])

@endsection
