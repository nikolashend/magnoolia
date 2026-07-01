{{-- Prepared client copy (verbatim ET, RU/EN in sync) — EHITUSINFO themed detail:
     Tehniline kirjeldus / Keskkond.
     Source: materials/_ehitusinfo_strings.txt (indices 57-68). --}}
@php
  $loc = app()->getLocale();
  $D = [
    'et' => [
      'eyebrow' => 'Tehniline kirjeldus',
      'title'   => 'Keskkond',
      'rows' => [
        ['label' => 'Mugav parkimine', 'text' => 'Igale ridaelamu sektsioonile on tagatud 2 varikatusega parkimiskohta. Ridaelamu sissesõidutee on ühiskasutuses, võõrastele sissesõit takistatud autoväravaga. Väravate avamine toimub mobiiltelefoniga, võimalusel ka läbi foto süsteemi. Erilahendusega lisatav sõiduki gsm-anduriga värava avanemise süsteem. Elektriautode laadimise võimalus lisatasu eest vastavalt sõiduki mudelile ja laadimisfunktsioonile. Laadimise valmidus kaablitoru näol laadimispunktini sisaldub hinnas.'],
        ['label' => 'Teekatted, valgustus', 'text' => 'Sissesõidutee on asfalteeritud, parkimiskohad ning kasutusõigusega sissesõidud on kaetud sillutiskiviga. Sissesõidutee on valgustatud disain LED-tehnoloogial pollar-valgustitega.'],
        ['label' => 'Haljastus', 'text' => 'Ridaelamute isikliku kasutusõigusega sektsiooni hoovialad piiratakse privaatsuse tagamiseks elupuuhekiga. Maastikku mitmekesistatakse mõnede istutavate põõsaste ja lehtpuudega. Ilutaimed hoovis ja ehitatud istutuskastides rajab iga elanik ise oma äranägemise ja soovidele vastavalt.'],
        ['label' => 'Piirdeaed', 'text' => 'Kinnistule sissesõitu piiravad teraspostidel puitlippidest auto- ja jalgvärav. Kõik kinnistud on piiratud keevisvõrkaiaga, millest kasvab ajapikku läbi elupuuhekk. Ridaelamute sektsioone jääb eraldama ainult elupuuhekk, aedasid ei ole ette nähtud.'],
        ['label' => 'Prügimajandus', 'text' => 'Jäätmetele rajatakse eraldiseisev prügiaedik, mille arhitektuur haakub suurepäraselt hoone üldise arhitektuurilise kontseptsiooniga ning viimistlus on sarnane sissepääsu väravatega - puitlippidega kaetud aedik. Prügiveo saab iga ridaelamu boks tellida vajadusel iseseisvalt.'],
      ],
      'note' => '* kirjeldus on informatiivne, võib sisaldada ebatäpsusi. Hoone ehitatakse vastavalt projektile.',
    ],
    'ru' => [
      'eyebrow' => 'Техническое описание',
      'title'   => 'Среда и территория',
      'rows' => [
        ['label' => 'Удобная парковка', 'text' => 'Для каждой секции таунхауса обеспечено 2 парковочных места под навесом. Въездная дорога таунхауса находится в общем пользовании, въезд посторонним ограничен автоматическими воротами. Открытие ворот осуществляется с мобильного телефона, при возможности также через систему видеодомофона. По специальному решению добавляется система открытия ворот с gsm-датчиком автомобиля. Возможность зарядки электромобилей за дополнительную плату в зависимости от модели автомобиля и функции зарядки. Готовность к зарядке в виде кабель-канала до точки зарядки включена в цену.'],
        ['label' => 'Дорожные покрытия, освещение', 'text' => 'Въездная дорога асфальтирована, парковочные места и въезды с правом пользования покрыты брусчаткой. Въездная дорога освещена дизайнерскими болларными светильниками на LED-технологии.'],
        ['label' => 'Озеленение', 'text' => 'Дворовые участки секций таунхаусов с личным правом пользования ограждаются живой изгородью из туи для обеспечения приватности. Ландшафт разнообразится несколькими высаживаемыми кустарниками и лиственными деревьями. Декоративные растения во дворе и в построенных цветочных ящиках высаживает каждый житель сам по своему усмотрению и пожеланиям.'],
        ['label' => 'Ограждение', 'text' => 'Въезд на участок ограничивают автомобильные и пешеходные ворота из деревянных штакетин на стальных столбах. Все участки огорожены сварной сетчатой оградой, сквозь которую со временем прорастает живая изгородь из туи. Секции таунхаусов разделяет только живая изгородь из туи, заборы не предусмотрены.'],
        ['label' => 'Обращение с отходами', 'text' => 'Для отходов создаётся отдельно стоящий мусорный загон, архитектура которого прекрасно сочетается с общей архитектурной концепцией здания, а отделка схожа с въездными воротами - загон, обшитый деревянными штакетинами. Вывоз мусора каждый бокс таунхауса может при необходимости заказать самостоятельно.'],
      ],
      'note' => '* описание носит информативный характер, может содержать неточности. Здание строится в соответствии с проектом.',
    ],
    'en' => [
      'eyebrow' => 'Technical description',
      'title'   => 'Environment',
      'rows' => [
        ['label' => 'Convenient parking', 'text' => 'Each terraced-house section is provided with 2 covered parking spaces. The terraced house driveway is in shared use, with access for strangers blocked by an automatic gate. The gates are opened by mobile phone, and where possible also via the video-intercom system. As a special solution, a gate-opening system with a vehicle gsm sensor can be added. Charging of electric cars is available for an additional fee depending on the car model and charging function. Charging readiness in the form of a cable conduit to the charging point is included in the price.'],
        ['label' => 'Road surfaces, lighting', 'text' => 'The driveway is asphalted, the parking spaces and driveways with usage rights are covered with paving stone. The driveway is lit with designer LED-technology bollard luminaires.'],
        ['label' => 'Landscaping', 'text' => 'The yard areas of the terraced-house sections with personal usage rights are enclosed with a thuja hedge to ensure privacy. The landscape is diversified with some planted shrubs and deciduous trees. Ornamental plants in the yard and in the built planting boxes are established by each resident themselves according to their own judgement and wishes.'],
        ['label' => 'Fencing', 'text' => 'Access to the plot is restricted by vehicle and pedestrian gates made of wooden slats on steel posts. All plots are enclosed with a welded mesh fence, through which a thuja hedge grows over time. Only a thuja hedge separates the terraced-house sections; fences are not foreseen.'],
        ['label' => 'Waste management', 'text' => 'A separate free-standing waste enclosure is built for waste, the architecture of which fits perfectly with the overall architectural concept of the building, and the finish is similar to the entrance gates - an enclosure clad with wooden slats. Waste removal can be ordered independently by each terraced-house unit if needed.'],
      ],
      'note' => '* the description is informative and may contain inaccuracies. The building is constructed in accordance with the project.',
    ],
  ];
  $d = $D[$loc] ?? $D['et'];
@endphp

<section class="mg-page-section mg-page-section--cream">
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
      <p style="margin-top:18px;font-size:12.5px;color:#9a948a;font-style:italic;">{{ $d['note'] }}</p>
    </div>
  </div>
</section>
