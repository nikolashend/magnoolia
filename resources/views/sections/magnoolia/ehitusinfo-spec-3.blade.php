{{-- Prepared client copy (verbatim ET, RU/EN in sync) — EHITUSINFO / Tehniline kirjeldus:
     Siseviimistlus. Source: materials/_ehitusinfo_strings.txt (indices 42–56). --}}
@php
  $loc = app()->getLocale();
  $D = [
    'et' => [
      'eyebrow' => 'Tehniline kirjeldus',
      'title'   => 'Siseviimistlus',
      'rows' => [
        ['label' => 'Põrandad', 'text' => 'Esiku ja vannitubade põrandal on suuremõõtmeline keraamiline plaat. Tubade põrandaid katab ühelipiline tammeparkett või kalasaba mustris parkett. Puidust põrandaliistud.'],
        ['label' => 'Laed', 'text' => 'I k, tubade betoonlaed on tasandatud ja viimistletud. Värvitud kipsplaadist ripplaed on vannitubades ja esikutes. II k lagi puitfermide alt on kaetud kipsplaadiga.'],
        ['label' => 'Seinad', 'text' => 'Kiviseinad krohvitud ja värvitud, kipsplaatidest seinad pahteldatud ja värvitud. Valdavalt siseviimistluses maalrivalge, aksent-seinad eritoonides.'],
        ['label' => 'Vannitoa pinnakatted', 'text' => 'Vannitoa, wc ja sauna eesruumi põrandad plaaditud 60x60cm plaadiga, dušši vesi valgub ühepoolse kaldega renn-trappi. Pesuruumides seintel disaineri valikul kuni 120cm suurused keraamilised plaadid; wc-s plaaditud wc-poti tagune, ülejäänud seinad värvitud. Laed kaetud niiskuskindla värviga.'],
        ['label' => 'Vannitoa tehnika ja sisustus', 'text' => 'Ainult kvaliteetsed kaubamärgid — Damixa dušši- ja valamusegistid, RAK wc-potid ning Balteco valamud. Paigaldatavad sanitaarseadmed on valitavad vastavalt siseviimistluspaketile. Duššinurk on eraldatud laeni ulatuva klaasseinaga. Balteco valamukapp ja lisatasu eest peegel vastavalt hinnatabelile.'],
        ['label' => 'Saunad', 'text' => 'Sauna voodri- ja lavalauana kasutame haava- ja lepalaudist. Lavad on nö. platvorm-tüüpi ehk suurem osa sauna põrandast on tõstetud ning Harvia kerised on "uputatud" sauna puitpõrandasse. Sauna esisein on avaruse loomiseks maast laeni karastatud klaasist.'],
        ['label' => 'Sisetrepid', 'text' => 'Sisetrepid on puidust. Seinale kinnitatud puidust käsipuu metallkanduritel. Trepipiire on terasest varbpiire, kaetud puidust käsipuuga.'],
      ],
    ],
    'ru' => [
      'eyebrow' => 'Техническое описание',
      'title'   => 'Внутренняя отделка',
      'rows' => [
        ['label' => 'Полы', 'text' => 'В прихожей и ванных комнатах на полу уложена крупноформатная керамическая плитка. Полы комнат покрыты однополосным дубовым паркетом или паркетом «ёлочкой». Деревянные напольные плинтусы.'],
        ['label' => 'Потолки', 'text' => 'На 1 этаже бетонные потолки комнат выровнены и отделаны. Подвесной потолок из окрашенного гипсокартона выполнен в ванных комнатах и прихожих. Потолок 2 этажа под деревянными фермами обшит гипсокартоном.'],
        ['label' => 'Стены', 'text' => 'Каменные стены оштукатурены и окрашены, стены из гипсокартона зашпаклёваны и окрашены. Во внутренней отделке преимущественно малярно-белый цвет, акцентные стены в особых тонах.'],
        ['label' => 'Покрытия ванной комнаты', 'text' => 'Полы ванной, туалета и предбанника сауны выложены плиткой 60x60 см, вода из душа стекает в желобный трап с односторонним уклоном. В моечных помещениях на стенах по выбору дизайнера керамическая плитка размером до 120 см; в туалете облицована стена за унитазом, остальные стены окрашены. Потолки покрыты влагостойкой краской.'],
        ['label' => 'Сантехника и оснащение ванной комнаты', 'text' => 'Только качественные бренды — душевые и умывальные смесители Damixa, унитазы RAK и раковины Balteco. Устанавливаемое сантехническое оборудование выбирается в соответствии с пакетом внутренней отделки. Душевой угол отделён стеклянной стенкой до потолка. Тумба под раковину Balteco, а зеркало за дополнительную плату согласно прайс-листу.'],
        ['label' => 'Сауны', 'text' => 'В качестве вагонки и полкового бруса сауны мы используем осиновую и ольховую доску. Полки платформенного типа, то есть большая часть пола сауны приподнята, а каменки Harvia «утоплены» в деревянный пол сауны. Передняя стена сауны для создания ощущения простора выполнена из закалённого стекла от пола до потолка.'],
        ['label' => 'Внутренние лестницы', 'text' => 'Внутренние лестницы деревянные. Закреплённый на стене деревянный поручень на металлических кронштейнах. Ограждение лестницы — стальное стержневое, покрытое деревянным поручнем.'],
      ],
    ],
    'en' => [
      'eyebrow' => 'Technical description',
      'title'   => 'Interior finish',
      'rows' => [
        ['label' => 'Floors', 'text' => 'The hallway and bathroom floors have large-format ceramic tiles. Room floors are covered with single-strip oak parquet or herringbone-pattern parquet. Wooden skirting boards.'],
        ['label' => 'Ceilings', 'text' => 'On the 1st floor the concrete ceilings of the rooms are levelled and finished. A suspended ceiling of painted plasterboard is fitted in the bathrooms and hallways. The 2nd-floor ceiling beneath the timber trusses is clad with plasterboard.'],
        ['label' => 'Walls', 'text' => 'Stone walls are plastered and painted, plasterboard walls are filled and painted. The interior finish is predominantly painter\'s white, with accent walls in special tones.'],
        ['label' => 'Bathroom surface finishes', 'text' => 'The floors of the bathroom, WC and sauna anteroom are tiled with 60x60 cm tiles, with the shower water draining into a single-slope channel drain. In the wet rooms the walls carry ceramic tiles up to 120 cm at the designer\'s choice; in the WC the wall behind the toilet is tiled and the remaining walls are painted. The ceilings are covered with moisture-resistant paint.'],
        ['label' => 'Bathroom fittings and furnishings', 'text' => 'Only quality brands — Damixa shower and basin mixers, RAK toilets and Balteco basins. The sanitary fixtures to be installed are selectable according to the interior finish package. The shower corner is separated by a glass wall reaching to the ceiling. A Balteco vanity cabinet, and a mirror for an additional charge according to the price list.'],
        ['label' => 'Saunas', 'text' => 'For the sauna cladding and bench boards we use aspen and alder boarding. The benches are of the so-called platform type, meaning most of the sauna floor is raised and the Harvia heaters are "sunk" into the sauna\'s wooden floor. The front wall of the sauna is made of tempered glass from floor to ceiling to create a sense of spaciousness.'],
        ['label' => 'Interior stairs', 'text' => 'The interior stairs are wooden. A wooden handrail is fixed to the wall on metal brackets. The stair railing is a steel bar railing topped with a wooden handrail.'],
      ],
    ],
  ];
  $d = $D[$loc] ?? $D['et'];
@endphp

<section class="mg-page-section mg-page-section--white">
  <div class="container">
    <div class="mg-section-heading" style="margin-bottom:28px;">
      <div class="mg-section-heading__eyebrow">{{ $d['eyebrow'] }}</div>
      <h2 class="mg-section-heading__title">{{ $d['title'] }}</h2>
    </div>
    <div style="max-width:960px;margin:0 auto;">
      @foreach($d['rows'] as $r)
      <div class="row gutter-y-8" style="padding:16px 0;border-top:1px solid #ececec;">
        <div class="col-lg-3"><div style="font-weight:700;color:#1d2430;font-size:15px;">{{ $r['label'] }}</div></div>
        <div class="col-lg-9"><div style="color:#6f6a61;font-size:14px;line-height:1.7;">{{ $r['text'] }}</div></div>
      </div>
      @endforeach
    </div>
  </div>
</section>
