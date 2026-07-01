{{-- Prepared client copy (verbatim, black text) — "EKSKLUSIIVSED A-ENERGIAKLASSI
     RIDAELAMUD" intro narrative. Source: materials/1 Koduka tekst Magnoolia. ET/RU/EN in sync. --}}
@php
  $loc = app()->getLocale();
  $N = [
    'et' => [
      'eyebrow' => 'Tutvustus',
      'title'   => 'Eksklusiivsed A-energiaklassi ridaelamud',
      'p' => [
        'Tallinna lähiümbruse valdadest on Kiili valla asumid mõne aasta jooksul teinud läbi kiire arengu, kuhu on lisandunud arvukalt nii uusi elamuid kui ka kaubanduspindasid. Suur mööblikauplus IKEA on koduhoovist praktiliselt näha, selle kõrvale rajatud kodupood Selver, esimene Decathlon sporditarvete kauplus Eestis ja muud kaubanduspinnad. Jalutuskäigu kaugusel paikneb uhiuus Vaela lasteaed, lähim kool ja spordihoone paiknevad Kiili keskuses.',
        'Uhiuuele Magnoolia tänavale on välja ehitatud kogu vajalik taristu ning rajatud sõidu- ja kõnniteed, tänavavalgustus ja elektri liitumine. 2026. a. kevadel alustatakse esimeste ridaelamute ehitusega. Kokku on detailplaneeringus üheksateist 3- ja 4-sektsioonilist ridaelamuboksi. Kõik hooneosad on ilma ühise vaheseinata ja üksteisest eraldatud autovarjualustega, võimaldades parima privaatsuse. Iga hoonesektsiooni elektri-, vee- ja prügimajandus on individuaalne ning ei pea naabri soovidest lähtuma. Suured lõuna- ja läänesuunalised terrassid ja väga ägedad terrassile avanevad lükandaknad, samuti väga suured isiklikud hoovialad tagavad mõnusa äraolemise — elad küll ridaelamus, kuid tunned end eramaja omanikuna.',
      ],
    ],
    'ru' => [
      'eyebrow' => 'Знакомство',
      'title'   => 'Эксклюзивные рядные дома класса A',
      'p' => [
        'Среди волостей ближайшего пригорода Таллина посёлки волости Кийли за несколько лет пережили быстрое развитие: здесь появилось множество как новых жилых домов, так и торговых площадей. Большой мебельный магазин IKEA виден практически со двора, рядом с ним построен продуктовый магазин Selver, первый в Эстонии магазин спорттоваров Decathlon и другие торговые площади. В нескольких минутах ходьбы находится новый детский сад Vaela, а ближайшая школа и спортивный зал — в центре Кийли.',
        'На новой улице Magnoolia построена вся необходимая инфраструктура: проезжие и пешеходные дороги, уличное освещение и подключение электричества. Весной 2026 года начнётся строительство первых рядных домов. Всего по детальной планировке предусмотрено девятнадцать 3- и 4-секционных боксов рядных домов. Все части зданий — без общей стены и отделены друг от друга навесами для автомобилей, что обеспечивает максимальную приватность. Электро-, водо- и мусорное хозяйство каждой секции индивидуально и не зависит от пожеланий соседа. Большие террасы, ориентированные на юг и запад, потрясающие раздвижные окна, открывающиеся на террасу, а также очень большие личные дворовые участки обеспечивают приятное времяпрепровождение — вы живёте в рядном доме, но чувствуете себя владельцем частного дома.',
      ],
    ],
    'en' => [
      'eyebrow' => 'Introduction',
      'title'   => 'Exclusive A-energy-class terraced houses',
      'p' => [
        'Among the municipalities on Tallinn’s doorstep, the settlements of Kiili municipality have gone through rapid growth in just a few years, gaining numerous new homes as well as retail space. The large IKEA furniture store is practically visible from the yard, with a Selver grocery store built next to it, the first Decathlon sports store in Estonia and other retail nearby. The brand-new Vaela kindergarten is within walking distance, and the nearest school and sports hall are in the Kiili centre.',
        'All the necessary infrastructure has been built on the new Magnoolia street — roads and pavements, street lighting and electrical connection. Construction of the first terraced houses begins in spring 2026. The detailed plan comprises nineteen 3- and 4-section terraced-house units in total. All building sections are without a shared wall and separated from one another by carports, allowing the best possible privacy. Each section’s electricity, water and waste management is individual and does not depend on the neighbour’s wishes. Large south- and west-facing terraces and striking sliding doors opening onto the terrace, together with very large private yards, ensure pleasant living — you live in a terraced house but feel like the owner of a detached home.',
      ],
    ],
  ];
  $n = $N[$loc] ?? $N['et'];
@endphp

<section class="section-space" style="background:#ffffff;" id="tutvustus">
  <div class="container">
    <div class="row align-items-center gutter-y-40">
      <div class="col-lg-6">
        <div class="hover:shine" style="border-radius:20px;overflow:hidden;">
          <img {!! mg_img('magnoolia_cam09.jpg', '(max-width:991px) 100vw, 560px') !!} alt="{{ $n['title'] }}" loading="lazy" decoding="async" style="width:100%;height:100%;object-fit:cover;display:block;">
        </div>
      </div>
      <div class="col-lg-6">
        <div class="sec-title text-start" style="margin-bottom:20px;">
          <div class="sec-title__top justify-content-start">
            <span class="line-left"></span>
            <h6 class="sec-title__tagline">{{ $n['eyebrow'] }}</h6>
          </div>
          <h2 class="sec-title__title">{{ $n['title'] }}</h2>
        </div>
        @foreach($n['p'] as $para)
          <p style="font-size:15.5px;color:#4a4540;line-height:1.85;margin:0 0 16px;">{{ $para }}</p>
        @endforeach
        <a href="{{ lroute('magnoolia.homes') }}" class="zoomvilla-btn" style="margin-top:8px;" data-mg-analytics="magnoolia_cta_click">{{ ['ru'=>'Цены и планировки','en'=>'Prices and plans'][$loc] ?? 'Hinnad ja plaanid' }} <i class="icon-angle-small-right"></i></a>
      </div>
    </div>
  </div>
</section>
