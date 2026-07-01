@php
  $loc = app()->getLocale();
  $D = [
    'et' => [
      'eyebrow' => 'Planeering, disain ja miljöö',
      'title'   => 'Planeering — disain — miljöö',
      'items' => [
        'Väärtust loob madal hoonestustihedus – tagab piisavalt avarust ja privaatsust',
        'Esimese korruse ägedad paralleel-lükandaknad pääsuga terrassile',
        'Piirdeaed ja distantsilt avatava väravaga hoovid',
        'Maja eest algavad kergliiklusteed, rohkelt võimalusi sportimiseks',
        'Iga boksi juurde kuulub väga suur (500...900 m²) ainukasutuses olev haljasala',
        'Haljasalad piiritletud elupuuhekiga, igal boksil ehituslikud istutuskastid',
        'Liigendatud fassaadiga ja klaasitud rõdudega atraktiivne arhitektuur',
        'Funktsionaalsed ruumiplaneeringud',
        'Ridaelamutel puuduvad ühised seinad ja on eraldatud autovarjualustega',
        'Kõikidel boksidel lõunasuunalised rõdud, panoraamvaated maast-laeni akendest',
        'Puitlaudisega terrassid 1. korruse lõuna- ja läänesuunal',
        'Eluruumide 2,8 meetrit kõrged laed',
        'Igal boksil oma tehno-majapidamisruum; lisaks mitteköetav panipaik',
        'Efektsed ribipostidel lahendusega autovarjualune 2-le sõidukile',
        'Prügiaedikud isikliku konteineriga – tasud ainult enda jäätmete eest',
        'Kompromissitu ehituskvaliteet; garantii 2 aastat',
      ],
    ],
    'ru' => [
      'eyebrow' => 'Планировка, дизайн и среда',
      'title'   => 'Планировка — дизайн — среда',
      'items' => [
        'Ценность создаёт низкая плотность застройки – обеспечивает достаточно простора и приватности',
        'Впечатляющие параллельно-раздвижные окна первого этажа с выходом на террасу',
        'Ограждённые дворы с воротами, открываемыми на расстоянии',
        'Прямо от дома начинаются пешеходно-велосипедные дорожки, множество возможностей для спорта',
        'К каждому боксу относится очень большой (500...900 м²) участок озеленения в единоличном пользовании',
        'Зелёные участки ограничены живой изгородью из туи, у каждого бокса строительные ящики для растений',
        'Привлекательная архитектура с расчленённым фасадом и остеклёнными балконами',
        'Функциональные планировки помещений',
        'У рядных домов нет общих стен, и они разделены навесами для автомобилей',
        'У всех боксов балконы, ориентированные на юг, панорамные виды из окон от пола до потолка',
        'Террасы с деревянным настилом на южной и западной стороне 1-го этажа',
        'Потолки жилых помещений высотой 2,8 метра',
        'У каждого бокса собственное технико-хозяйственное помещение; дополнительно неотапливаемая кладовая',
        'Эффектный навес для 2 автомобилей на рёберных стойках',
        'Мусорные площадки с личным контейнером – платите только за собственные отходы',
        'Бескомпромиссное качество строительства; гарантия 2 года',
      ],
    ],
    'en' => [
      'eyebrow' => 'Layout, design and setting',
      'title'   => 'Layout — design — setting',
      'items' => [
        'Value is created by low building density – ensuring plenty of space and privacy',
        'Striking parallel sliding windows on the first floor with access to the terrace',
        'Fenced yards with gates that open remotely',
        'Pedestrian and cycling paths start right from the house, plenty of opportunities for sport',
        'Each unit comes with a very large (500...900 m²) green area for sole use',
        'Green areas bordered by an arborvitae hedge, each unit with built-in planting boxes',
        'Attractive architecture with an articulated facade and glazed balconies',
        'Functional room layouts',
        'The terraced houses have no shared walls and are separated by carports',
        'All units have south-facing balconies, panoramic views through floor-to-ceiling windows',
        'Timber-decked terraces on the south and west side of the 1st floor',
        'Living spaces with 2.8-metre high ceilings',
        'Each unit has its own utility/technical room; plus an unheated storage space',
        'A striking carport for 2 vehicles with a ribbed-post design',
        'Waste enclosures with a personal container – pay only for your own waste',
        'Uncompromising build quality; 2-year warranty',
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
