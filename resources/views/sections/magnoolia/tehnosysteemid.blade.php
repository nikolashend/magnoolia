@php
  $loc = app()->getLocale();
  $D = [
    'et' => [
      'eyebrow' => 'Kaasaegsed tehnosüsteemid',
      'title'   => 'Kaasaegsed tehnosüsteemid, kvaliteetne siseviimistlus',
      'items' => [
        'Elektri peakaitse 16A ridaelamuboksi kohta',
        'Individuaalsed kaugloetavad elektriarvestid, asuvad tänava liitumiskilbis',
        'Tarbevee arvestus ridaelamuboksi-põhine, eraldi leping teenusepakkujaga',
        'Enefiti optiline sidekaabli lahendus',
        'Intelligentne soojuspumba ja ventilatsiooniseadme automaatika',
        'Bisly nutikodu lahendused (lisatasu eest)',
        'Schneider Sedna lülitite ja pistikute sari',
        'Vannitubade, esikute ja WC süvistatavad ripplaevalgustid paigaldatud',
        'Puutetundliku ekraaniga videofono, kaameraga kutsepaneel jalgväraval',
        'Kvaliteetne Damixa sanitaartehnika ja RAK keraamika',
        'Vannitubades maitsekalt valitud kuni 120 cm suured seinaplaadid',
        'Ühelipiline tamme spoonparkett; valikuna kalasabamustris parkett',
        'Siseuksed – tammespooniga sileuksed või värvitud tahveluksed',
        'Saunade ja duššide eksklusiivsed klaasseinad',
        'Sauna efektsete platvorm-lavade ja Harvia Cilindro keristega; saunade LED-valgustus',
        'Ainult kvaliteetsed siseviimistlusmaterjalid',
        'Lisavõimalusena sisearhitekti erilahendused lisatasu eest',
      ],
    ],
    'ru' => [
      'eyebrow' => 'Современные техносистемы',
      'title'   => 'Современные техносистемы, качественная внутренняя отделка',
      'items' => [
        'Главный автомат электросети 16A на бокс рядного дома',
        'Индивидуальные счётчики электроэнергии с дистанционным считыванием, расположены в уличном присоединительном щите',
        'Учёт потребления воды по каждому боксу рядного дома, отдельный договор с поставщиком услуги',
        'Оптическое решение кабеля связи Enefit',
        'Интеллектуальная автоматика теплового насоса и вентиляционной установки',
        'Решения умного дома Bisly (за дополнительную плату)',
        'Серия выключателей и розеток Schneider Sedna',
        'В ванных комнатах, прихожих и туалетах установлены встраиваемые потолочные светильники',
        'Видеодомофон с сенсорным экраном, вызывная панель с камерой на калитке',
        'Качественная сантехника Damixa и керамика RAK',
        'В ванных комнатах со вкусом подобранная крупная настенная плитка размером до 120 см',
        'Однополосный дубовый шпонированный паркет; на выбор паркет с рисунком «ёлочка»',
        'Межкомнатные двери – гладкие двери со шпоном дуба или крашеные филёнчатые двери',
        'Эксклюзивные стеклянные стенки для саун и душевых',
        'Сауны с эффектными платформенными полками и каменками Harvia Cilindro; LED-освещение саун',
        'Только качественные материалы внутренней отделки',
        'В качестве дополнительной возможности – индивидуальные решения архитектора интерьера за отдельную плату',
      ],
    ],
    'en' => [
      'eyebrow' => 'Modern building systems',
      'title'   => 'Modern building systems, quality interior finish',
      'items' => [
        'Main electrical fuse of 16A per terraced-house unit',
        'Individual remotely read electricity meters, located in the street connection cabinet',
        'Water consumption metered per terraced-house unit, separate contract with the service provider',
        'Enefit optical communication cable solution',
        'Intelligent heat-pump and ventilation-unit automation',
        'Bisly smart-home solutions (for an additional fee)',
        'Schneider Sedna range of switches and sockets',
        'Recessed ceiling downlights installed in bathrooms, hallways and WCs',
        'Video intercom with touchscreen, camera call panel at the pedestrian gate',
        'Quality Damixa sanitary fittings and RAK ceramics',
        'Tastefully chosen large wall tiles up to 120 cm in the bathrooms',
        'Single-strip oak veneer parquet; herringbone-pattern parquet as an option',
        'Interior doors – flush doors with oak veneer or painted panel doors',
        'Exclusive glass walls for the saunas and showers',
        'Saunas with striking platform benches and Harvia Cilindro heaters; LED lighting in the saunas',
        'Only quality interior finishing materials',
        'As an additional option, bespoke interior architect solutions for an extra fee',
      ],
    ],
  ];
  $d = $D[$loc] ?? $D['et'];
@endphp

<section class="mg-page-section mg-page-section--cream">
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
