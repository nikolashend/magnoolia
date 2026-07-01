{{-- Prepared client copy (verbatim ET, RU/EN in sync) — EHITUSINFO / Tehniline kirjeldus: Tehnosüsteemid.
     Source: materials/_ehitusinfo_strings.txt (indices 21-41). --}}
@php
  $loc = app()->getLocale();
  $D = [
    'et' => [
      'eyebrow' => 'Tehniline kirjeldus',
      'title'   => 'Tehnosüsteemid',
      'rows' => [
        ['label' => 'Elekter', 'text' => 'Paigaldatud on mööbli funktsionaalselt paigutust arvesse võttes kõik pistikupesad, lülitid, LCD puutetundliku ekraaniga kütteregulaatorid. Samuti vannitubade ning esikute ripplaevalgustid. Kaugloetav elektriarvesti peakaitsmega 16A boksi kohta asub tänaval paiknevas liitumiskilbis, peakilp hooneosa tehnoruumis. Elektriliitumised ja tarbimine on kõikidel boksidel eraldiseisev, ei sõltu teistest tarbijatest (v.a. fono ja sissesõidu valgustus). Üld- ja välisvalgustus on ökonoomne LED lampidel põhinev - räästas spot-valgustid ning karniiside ja rõdude LED-ribad. Sissesõidutee äärde on paigaldatud pollar-tüüpi valgustid.'],
        ['label' => 'Päikesepaneelid', 'text' => 'Päikesepaneelid ei ole statsionaarselt hooneosa juurde kuuluv tehnosüsteem vaid on juurde tellitav lisandväärtus, vt. hinnatabel.'],
        ['label' => 'Side ja valve', 'text' => 'Paigaldatud on andmeside kaabeldus - lõppseadmed paigaldab ostja. Valvesignalisatsiooni ja suitsuandurite kaabelduse paigaldame lisatasu eest kuid statsionaarselt on teostatud vajalik valvesõrmistiku ning uksemagnetite kaabeldus.'],
        ['label' => 'Videofono', 'text' => 'Videofono telefon puutetundliku ekraaniga, tänaval jalgvärava kõrval kaameraga kutsepaneel. Kaamerate paigalduse tellimusel saab jalgvärava avada ka läbi appi.'],
        ['label' => 'Videovalve (kaamerad)', 'text' => 'Võimalus tellida lisatasu eest vastavalt hinnatabelile - videovalve 4 zoomiga välikaamerat HikVision hoone nurkades, salvestusseade, lisaks jalgvärava avamine läbi kaamera appi.'],
        ['label' => 'Ökonoomne küttesüsteem', 'text' => 'Hooned küttesüsteemiks on Alpha-Innotec maasoojuspump koos paikneva maaküttetorustikuga, tagades nii kõige soodsamad küttelahenduse kui ka sooja tarbevee. Korterites on vesipõrandaküte, mis on ruumide lõikes eraldi reguleeritav.'],
        ['label' => 'Jahutus', 'text' => 'Ridaelamu sektsiooni elutuba-kööki on paigaldatud aktiivjahutuse valmidus. Lõppseadmed koos appist juhtimisega saab iga uus elanik juurde tellida vastavalt hinnatabelile.'],
        ['label' => 'Sundventilatsioon', 'text' => 'Igal ridaelamu sektsioonil on autonoomsed soojustagastusega sissepuhke-väljatõmbe ventilatsiooniseadmed, paigaldatuna tehnoruumi. Seadme juhtpult toodud mugavamaks kasutamiseks välisukse kõrvale, lisaks nutiseadme rakendus seadme kaugjuhtimiseks. Köögi kohtväljatõmbesüsteem lahendatakse äratõmbetoruga otse katusele (ilma mootorita, köögikubu tarnida mootoriga).'],
        ['label' => 'Veevarustus ja kanalisatsioon', 'text' => 'Vajalik soe tarbevesi toodetakse maasoojuspumbaga. Külma vee peaarvesti paikneb hoonesektsiooni tehnoruumis. Hooned on ühendatud Kiili KVH OÜ ühisveevärgi- ja kanalisatsioonivõrku ja iga ridaelamu boks sõlmib eraldi tarbimislepingu vee-ettevõtjaga.'],
        ['label' => 'Sadevesi', 'text' => 'Sadeveed katuselt ja teekatetelt juhitakse maapinnas paiknevatesse sadevee immutuskastidesse. Sadevett ei juhita sadeveekanalisatsiooni, drenaaži ei rajata.'],
      ],
    ],
    'ru' => [
      'eyebrow' => 'Техническое описание',
      'title'   => 'Техносистемы',
      'rows' => [
        ['label' => 'Электрика', 'text' => 'Установлены все розетки, выключатели и регуляторы отопления с сенсорным LCD-экраном с учётом функциональной расстановки мебели. Также установлены потолочные светильники в ванных комнатах и прихожих. Электросчётчик с дистанционным считыванием и главным предохранителем 16А на бокс находится в уличном присоединительном щите, главный щит - в техническом помещении части здания. Электроприсоединение и потребление у каждого бокса раздельные и не зависят от других потребителей (кроме домофона и освещения въезда). Общее и наружное освещение экономичное, на основе LED-ламп - точечные светильники на карнизе, а также LED-ленты карнизов и балконов. Вдоль въездной дороги установлены светильники столбчатого типа (боллард).'],
        ['label' => 'Солнечные панели', 'text' => 'Солнечные панели не являются стационарной техносистемой, входящей в состав части здания, а представляют собой дополнительно заказываемую опцию, см. прайс-лист.'],
        ['label' => 'Связь и охрана', 'text' => 'Проложена кабельная разводка передачи данных - оконечные устройства устанавливает покупатель. Кабельную разводку охранной сигнализации и датчиков дыма устанавливаем за дополнительную плату, однако стационарно выполнена необходимая разводка охранной клавиатуры и дверных магнитов.'],
        ['label' => 'Видеодомофон', 'text' => 'Видеодомофон с сенсорным экраном, на улице у калитки - вызывная панель с камерой. При заказе установки камер калитку можно открывать также через приложение.'],
        ['label' => 'Видеонаблюдение (камеры)', 'text' => 'Возможность заказать за дополнительную плату согласно прайс-листу - видеонаблюдение: 4 наружные камеры с зумом HikVision по углам здания, устройство записи, а также открытие калитки через приложение камеры.'],
        ['label' => 'Экономичная система отопления', 'text' => 'Система отопления зданий - грунтовый тепловой насос Alpha-Innotec с расположенным рядом грунтовым контуром отопления, обеспечивающий как наиболее выгодное решение отопления, так и горячую хозяйственную воду. В квартирах устроен водяной тёплый пол с раздельной регулировкой по помещениям.'],
        ['label' => 'Охлаждение', 'text' => 'В гостиной-кухне секции рядного дома установлена готовность к активному охлаждению. Оконечные устройства с управлением через приложение каждый новый житель может дозаказать согласно прайс-листу.'],
        ['label' => 'Приточно-вытяжная вентиляция', 'text' => 'В каждой секции рядного дома имеются автономные приточно-вытяжные вентиляционные установки с рекуперацией тепла, смонтированные в техническом помещении. Панель управления установки для удобства использования вынесена рядом со входной дверью, дополнительно имеется приложение для смарт-устройства для дистанционного управления. Система местной вытяжки кухни решена вытяжной трубой напрямую на крышу (без двигателя, кухонную вытяжку поставить с двигателем).'],
        ['label' => 'Водоснабжение и канализация', 'text' => 'Необходимая горячая хозяйственная вода производится грунтовым тепловым насосом. Главный счётчик холодной воды находится в техническом помещении секции здания. Здания подключены к сети водопровода и канализации Kiili KVH OÜ, и каждый бокс рядного дома заключает отдельный договор потребления с водоснабжающим предприятием.'],
        ['label' => 'Дождевая вода', 'text' => 'Дождевые воды с крыши и дорожных покрытий отводятся в расположенные в грунте инфильтрационные короба дождевой воды. Дождевая вода не отводится в ливневую канализацию, дренаж не устраивается.'],
      ],
    ],
    'en' => [
      'eyebrow' => 'Technical description',
      'title'   => 'Building systems',
      'rows' => [
        ['label' => 'Electricity', 'text' => 'All power sockets, switches and heating controllers with an LCD touch screen are installed taking into account the functional layout of the furniture. Ceiling lights are also fitted in the bathrooms and hallways. A remotely readable electricity meter with a 16A main fuse per unit is located in the connection cabinet on the street, and the main distribution board is in the technical room of the building section. The electrical connection and consumption are separate for every unit and do not depend on other consumers (except the intercom and the driveway lighting). General and exterior lighting is economical LED-based - spot lights on the eaves and LED strips on the cornices and balconies. Bollard-type luminaires are installed along the driveway.'],
        ['label' => 'Solar panels', 'text' => 'Solar panels are not a fixed building system belonging to the building section but an additional value that can be ordered separately, see the price list.'],
        ['label' => 'Communications and security', 'text' => 'Data communication cabling is installed - the end devices are fitted by the buyer. We install the cabling for the security alarm and smoke detectors for an additional fee, but the necessary cabling for the security keypad and door magnets is provided as standard.'],
        ['label' => 'Video intercom', 'text' => 'A video intercom phone with a touch screen, and a camera call panel next to the pedestrian gate on the street. When camera installation is ordered, the pedestrian gate can also be opened via the app.'],
        ['label' => 'Video surveillance (cameras)', 'text' => 'Option to order for an additional fee according to the price list - video surveillance: 4 HikVision outdoor cameras with zoom at the corners of the building, a recording device, and additionally opening the pedestrian gate via the camera app.'],
        ['label' => 'Economical heating system', 'text' => 'The heating system of the buildings is an Alpha-Innotec ground-source heat pump with the ground-heat piping laid nearby, providing both the most economical heating solution and hot domestic water. The apartments have water-based underfloor heating that is separately adjustable per room.'],
        ['label' => 'Cooling', 'text' => 'Readiness for active cooling is installed in the living room-kitchen of the terraced-house section. Each new resident can order the end devices together with app control according to the price list.'],
        ['label' => 'Mechanical ventilation', 'text' => 'Each terraced-house section has autonomous supply-and-exhaust ventilation units with heat recovery, installed in the technical room. The control panel of the unit is brought next to the entrance door for more convenient use, and there is additionally a smart-device application for remote control of the unit. The kitchen local exhaust system is solved with an extraction duct directly to the roof (without a motor; the kitchen hood to be supplied with a motor).'],
        ['label' => 'Water supply and sewerage', 'text' => 'The necessary hot domestic water is produced by the ground-source heat pump. The main cold-water meter is located in the technical room of the building section. The buildings are connected to the public water supply and sewerage network of Kiili KVH OÜ, and each terraced-house unit concludes a separate consumption contract with the water utility.'],
        ['label' => 'Rainwater', 'text' => 'Rainwater from the roof and paved surfaces is directed into rainwater infiltration boxes located in the ground. Rainwater is not directed into the storm sewerage, and no drainage is installed.'],
      ],
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
    </div>
  </div>
</section>
