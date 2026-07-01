{{-- Prepared client copy (verbatim ET, RU/EN in sync) — ASUKOHT themed detail:
     Tervis ja sport / Transport / Haridus ja kultuur / Vaba aeg.
     Source: materials/1 Koduka tekst Magnoolia. --}}
@php
  $loc = app()->getLocale();
  $A = [
    'et' => [
      'eyebrow' => 'Teenused ja vaba aeg',
      'title'   => 'Vaela ja Kiili — kõik vajalik käeulatuses',
      'cards' => [
        ['i' => 'fas fa-heartbeat', 't' => 'Tervis ja spordivõimalused', 'b' => 'Sobib hästi nii aktiivsele perele kui ka harrastussportlasele – mängu- ja spordiväljakuid nii pallimängudele, rattasõiduks kui ka jooksusõpradele; valgustatud kergliiklusteede võrgustik ühendab Vaela, Kiili, Luige, Kangru ja Tallinna suunda; Vaela terviserada, Kiili Spordihall ja staadion, Fitness 24/7 jõusaal, FV Padel ja Tennis Kiili keskuses; noorte seas populaarne Kiili Skate park ja discgolfi rajad – kõik jalutuskäigu kaugusel.'],
        ['i' => 'fas fa-route', 't' => 'Transport', 'b' => 'Tänu Viljandi maanteele ja Tallinna ringtee uutele liiklussõlmedele on tagatud kiire ja regulaarne ühendus kesklinnaga ka ühistranspordi kasutajale – 25...35 min, sõiduautoga 15...20 min. Elu rohelises ja turvalises keskkonnas ilma linnaga ühendust kaotamata.'],
        ['i' => 'fas fa-graduation-cap', 't' => 'Haridus ja kultuur', 'b' => 'Väga hea juurdepääsuga – Kiili Gümnaasium; Kiili Lasteaed asukohaga nii Kiili keskuses kui Vaela külas; Kiili Kunstide Kool; kultuuri- ja noorsooasutused – Kiili Rahvamaja ja Raamatukogu, Kiili Valla Noortekeskus; ligidal ka Rae Cultural Center Jüris.'],
        ['i' => 'fas fa-mug-hot', 't' => 'Vaba aeg', 'b' => 'Mõne minuti kaugusel paiknevad kõik kohvikud, pizzeriad ja pubid; IKEA ja Kurna Park jäävad jalutuskäigu kaugusele, Kiili keskuse kauplused ja Jüri äripiirkond samuti ligidal; terviserajad, mänguväljakud, madalseiklusrada ja Kiili keskuse puhkeala ning park – kvaliteetsed vaba aja veetmise võimalused aastaringselt.'],
      ],
    ],
    'ru' => [
      'eyebrow' => 'Услуги и досуг',
      'title'   => 'Vaela и Kiili — всё необходимое рядом',
      'cards' => [
        ['i' => 'fas fa-heartbeat', 't' => 'Здоровье и спорт', 'b' => 'Хорошо подходит как активной семье, так и любителю спорта – игровые и спортивные площадки для игр с мячом, велоспорта и любителей бега; сеть освещённых пешеходно-велосипедных дорожек соединяет направления Vaela, Kiili, Luige, Kangru и Таллина; оздоровительная тропа Vaela, спортивный зал и стадион Kiili, тренажёрный зал Fitness 24/7, FV Padel и теннис в центре Kiili; популярный среди молодёжи скейт-парк Kiili и трассы диск-гольфа – всё в пешей доступности.'],
        ['i' => 'fas fa-route', 't' => 'Транспорт', 'b' => 'Благодаря Вильяндискому шоссе и новым транспортным развязкам Таллинской окружной дороги обеспечено быстрое и регулярное сообщение с центром города и для пользователей общественного транспорта – 25...35 мин, на автомобиле 15...20 мин. Жизнь в зелёной и безопасной среде, не теряя связи с городом.'],
        ['i' => 'fas fa-graduation-cap', 't' => 'Образование и культура', 'b' => 'С очень хорошей доступностью – Кийлиская гимназия; детский сад Kiili, расположенный как в центре Kiili, так и в деревне Vaela; Кийлиская школа искусств; учреждения культуры и молодёжи – Народный дом и библиотека Kiili, Молодёжный центр волости Kiili; рядом также Rae Cultural Center в Юри.'],
        ['i' => 'fas fa-mug-hot', 't' => 'Досуг', 'b' => 'В нескольких минутах находятся все кафе, пиццерии и пабы; IKEA и Kurna Park в пешей доступности, магазины центра Kiili и деловой район Юри также рядом; оздоровительные тропы, игровые площадки, верёвочный парк и зона отдыха с парком в центре Kiili – качественные возможности для отдыха круглый год.'],
      ],
    ],
    'en' => [
      'eyebrow' => 'Services and leisure',
      'title'   => 'Vaela and Kiili — everything you need within reach',
      'cards' => [
        ['i' => 'fas fa-heartbeat', 't' => 'Health and sport', 'b' => 'Well suited to an active family and amateur athletes alike – play and sports grounds for ball games, cycling and running; a lit network of light-traffic paths connects the Vaela, Kiili, Luige, Kangru and Tallinn directions; the Vaela health trail, Kiili Sports Hall and stadium, a Fitness 24/7 gym, FV Padel and tennis in the Kiili centre; the Kiili Skate park popular among young people and disc-golf courses – all within walking distance.'],
        ['i' => 'fas fa-route', 't' => 'Transport', 'b' => 'Thanks to the Viljandi road and the new junctions of the Tallinn ring road, fast and regular connection to the city centre is ensured also for public-transport users – 25...35 min, by car 15...20 min. Life in a green and safe environment without losing the connection to the city.'],
        ['i' => 'fas fa-graduation-cap', 't' => 'Education and culture', 'b' => 'With very good access – Kiili Gymnasium; Kiili Kindergarten located both in the Kiili centre and in Vaela village; Kiili School of Arts; culture and youth institutions – Kiili Community House and Library, Kiili Municipality Youth Centre; the Rae Cultural Center in Jüri is also nearby.'],
        ['i' => 'fas fa-mug-hot', 't' => 'Leisure', 'b' => 'All cafés, pizzerias and pubs are a few minutes away; IKEA and Kurna Park are within walking distance, the Kiili centre shops and the Jüri business district are also nearby; health trails, playgrounds, a low ropes course and the Kiili centre recreation area and park – quality leisure options year-round.'],
      ],
    ],
  ];
  $a = $A[$loc] ?? $A['et'];
@endphp

<section class="mg-page-section mg-page-section--cream">
  <div class="container">
    <div class="mg-section-heading" style="margin-bottom:36px;">
      <div class="mg-section-heading__eyebrow">{{ $a['eyebrow'] }}</div>
      <h2 class="mg-section-heading__title">{{ $a['title'] }}</h2>
    </div>
    <div class="row gutter-y-28">
      @foreach($a['cards'] as $c)
      <div class="col-lg-6">
        <div style="background:#fff;border-radius:16px;padding:28px;height:100%;">
          <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px;">
            <div style="width:46px;height:46px;background:rgba(200,148,67,.15);border-radius:11px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
              <i class="{{ $c['i'] }}" style="color:#c89443;font-size:18px;"></i>
            </div>
            <h3 style="font-size:18px;font-weight:700;color:#1d2430;margin:0;">{{ $c['t'] }}</h3>
          </div>
          <p style="font-size:14.5px;color:#6f6a61;line-height:1.75;margin:0;">{{ $c['b'] }}</p>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>
