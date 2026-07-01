{{-- Energiasäästlik — A-energiaklass. Self-contained section partial. ET verbatim (client copy), RU/EN faithful translations, in sync. --}}
@php
  $loc = app()->getLocale();
  $D = [
    'et' => [
      'eyebrow' => 'Energiasäästlik, A-energiaklass',
      'title'   => 'Energiasäästlik — A-energiaklass',
      'items' => [
        'Soojapidavad välisseinad Bauroc EcoTerm 500 ja 375 plokist',
        'Individuaalsed võrgulahendusega maasoojuspumbad Alpha-Innotec',
        'Päikesepaneelide paigalduse valmidus – kaabeldus teostatud',
        'Individuaalne soojustagastusega sundventilatsioon Komfovent Domekt',
        'Vesipõrandakütte juhtimine ruumipõhiselt LCD termostaadist',
        'Hea heli- ja soojapidavusega kolmekordsed aknad; vastavalt energiamärgisele vajalikus mahus päikesekaitseklaas',
        'Jahutuse väljaehitamise võimalus (torustik välja ehitatud)',
        'Elektriautode laadimisjaama valmidus autovarjualuse juures (kaablitoru paigaldatud)',
        'Hoone väike energeetiline „jalajälg" – soe kodu, madalad kõrvalkulud',
      ],
    ],
    'ru' => [
      'eyebrow' => 'Энергоэффективность, класс A',
      'title'   => 'Энергоэффективный — класс A',
      'items' => [
        'Теплоизолирующие наружные стены из блоков Bauroc EcoTerm 500 и 375',
        'Индивидуальные грунтовые тепловые насосы Alpha-Innotec с сетевым решением',
        'Готовность к установке солнечных панелей – кабельная разводка выполнена',
        'Индивидуальная приточно-вытяжная вентиляция с рекуперацией тепла Komfovent Domekt',
        'Покомнатное управление водяным тёплым полом с ЖК-термостата',
        'Тройные окна с хорошей звуко- и теплоизоляцией; солнцезащитное стекло в объёме, необходимом согласно энергетической маркировке',
        'Возможность обустройства охлаждения (трубопровод проложен)',
        'Готовность к установке зарядной станции для электромобилей у навеса для машины (кабельная труба проложена)',
        'Небольшой энергетический „след" здания – тёплый дом, низкие эксплуатационные расходы',
      ],
    ],
    'en' => [
      'eyebrow' => 'Energy-efficient, A-class',
      'title'   => 'Energy-efficient — A-class',
      'items' => [
        'Heat-retaining exterior walls of Bauroc EcoTerm 500 and 375 blocks',
        'Individual ground-source heat pumps Alpha-Innotec with a grid solution',
        'Readiness for solar panel installation – cabling completed',
        'Individual mechanical ventilation with heat recovery Komfovent Domekt',
        'Room-based control of underfloor water heating from an LCD thermostat',
        'Triple-glazed windows with good sound and thermal insulation; solar-control glass to the extent required by the energy label',
        'Option to install cooling (piping already in place)',
        'Readiness for an electric-vehicle charging station by the carport (cable conduit installed)',
        'A small energy „footprint" for the building – a warm home with low running costs',
      ],
    ],
  ];
  $d = $D[$loc] ?? $D['et'];
@endphp

<section class="mg-page-section mg-page-section--white">
  <div class="container">
    <div class="mg-section-heading" style="margin-bottom:32px;">
      <div class="mg-section-heading__eyebrow">{{ $d['eyebrow'] }}</div>
      <h2 class="mg-section-heading__title">{{ $d['title'] }}</h2>
    </div>
    <div class="row gutter-y-16">
      @foreach($d['items'] as $it)
      <div class="col-lg-6">
        <div style="display:flex;gap:12px;align-items:flex-start;background:#fff;border-radius:12px;padding:16px 20px;height:100%;">
          <i class="fas fa-check" style="color:#c89443;margin-top:4px;flex-shrink:0;"></i>
          <span style="font-size:14.5px;color:#3a3530;line-height:1.6;">{{ $it }}</span>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>
