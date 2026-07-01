{{-- Prepared client copy (verbatim ET, RU/EN in sync) — EHITUSINFO themed spec:
     Ehituslik lahendus (construction solution).
     Source: materials/_ehitusinfo_strings.txt (indices 0..20). --}}
@php
  $loc = app()->getLocale();
  $D = [
    'et' => [
      'eyebrow' => 'Tehniline kirjeldus',
      'title'   => 'Ehituslik lahendus',
      'rows' => [
        ['label' => 'Vundament', 'text' => 'Hooned on rajatud raudbetoonist lintvundamendile'],
        ['label' => 'Soojapidavad välisseinad', 'text' => 'Bauroc Eco-Term 375 ja 500mm välissein tagab eriti hea soojuspidavuse ja kontrollitud sisekliima. Fassaadid on krohvitud silikoonkrohviga, osaliselt rõdude ja akendevahelised pinnad kaetud roovitusel puidus laudisega; karniisid ja räästad on kaetud dekoratiivse ehitusplaadiga.'],
        ['label' => 'Helipidavad siseseinad', 'text' => 'Ridaelamu sektsioonid on üksteisest eraldatud kuuride ja autovarjualusega, tagades suurema privaatsuse. Siseseinad on üldjuhul metall-karkassil seinad, kaetud erikõva kipsplaadiga. Trepi küljeseinad on laotud kergplokist.'],
        ['label' => 'Vahelaed', 'text' => 'Raudbetoon õõnespaneelid. Paneeli peal sammumüra tõkestav isolatsiooniplaat 50mm ja betoonist põrandaplaat küttetorudega. Esimese korruse aluspõranda all 200mm EPS soojustust.'],
        ['label' => 'Ilmastikukindel katus', 'text' => 'Katuse koos räästaelementidega moodustavad puidust fermid/talad. Katuslase soojustusena kasutatakse puistevilla fermi alumise vöö tasapinnas keskmise paksususega min. 400 mm. Katusekatteks on halli värvi PVC rullmaterjal.'],
        ['label' => 'Aknad', 'text' => '3-kordse paketiga selektiivklaasiga ja soojapidavad PVC aknad. Lõuna- ja läänesuuna akendel energiamärgisele vastavalt solar-faktoriga klaasid ruumide ülekuumenemise vältimiseks. Aknad on nn. saksa-tüüpi sissepoole avanevad.'],
        ['label' => 'Uksed', 'text' => 'Elamute siseuksed on vastavalt paketile kas siledad spoonuksed või värvitud tahveluksed. Välisuksed on puidust sileuksed.'],
        ['label' => 'Trepid ja lillekastid', 'text' => 'Trepi ja mademed sissepääsu ees on valatud betoonist, mille pind on libeduse takistamiseks "harjatud" mustriga. Viimistlemata betoonist jäävad ka maja ees olevad lillekastid. Lillekastide pealmised pinnad kaetakse vetika/samblikutõrjeks sobivad vahendiga (pikaajalise kaitse tagab ekspluatatsioonis täiendav impregneerimine).'],
        ['label' => 'Rõdud, terrassid', 'text' => 'Rõdude põrandad on kaetud halli PVC-kattega. Eksklusiivsust lisavad klaasitud rõdupiirded. 1. korruse terrasside kandekonstruktsioon on immutatud puidust, terrassikate 95 kuni 120mm laiune rihveldatud pinnaga pruuni immutuse laudisega.'],
        ['label' => 'Sõidukite varjualune', 'text' => 'Iga ridaelamu sektsiooni juurde kuulub efektne liimpuitpostide ja laudisest laega varjualune kahele sõidukile. Katuse peakandjad on liimpuittalad. Katuse mahulise struktuuri moodustavad ogaplaatfermid. Katusekatteks on PVC.'],
      ],
    ],
    'ru' => [
      'eyebrow' => 'Техническое описание',
      'title'   => 'Строительное решение',
      'rows' => [
        ['label' => 'Фундамент', 'text' => 'Здания возведены на ленточном фундаменте из железобетона'],
        ['label' => 'Теплоизолирующие наружные стены', 'text' => 'Наружная стена Bauroc Eco-Term 375 и 500 мм обеспечивает особенно высокую теплоизоляцию и контролируемый микроклимат. Фасады оштукатурены силиконовой штукатуркой, частично поверхности балконов и простенки между окнами покрыты деревянной обшивкой по обрешётке; карнизы и свесы покрыты декоративной строительной плитой.'],
        ['label' => 'Звукоизолирующие внутренние стены', 'text' => 'Секции рядного дома отделены друг от друга сараями и навесами для автомобилей, что обеспечивает большую приватность. Внутренние стены, как правило, выполнены на металлическом каркасе и покрыты особо твёрдым гипсокартоном. Боковые стены лестницы выложены из лёгких блоков.'],
        ['label' => 'Междуэтажные перекрытия', 'text' => 'Пустотные железобетонные панели. Поверх панели изоляционная плита 50 мм для гашения ударного шума и бетонная плита пола с трубами отопления. Под черновым полом первого этажа 200 мм теплоизоляции EPS.'],
        ['label' => 'Всепогодная крыша', 'text' => 'Крышу вместе с элементами свеса образуют деревянные фермы/балки. В качестве утепления кровли используется задувная вата в плоскости нижнего пояса фермы средней толщиной мин. 400 мм. Кровельное покрытие — рулонный ПВХ-материал серого цвета.'],
        ['label' => 'Окна', 'text' => 'Тёплые ПВХ-окна с трёхкамерным стеклопакетом и селективным стеклом. На окнах южного и западного направлений в соответствии с энергетической маркировкой установлены стёкла с солнечным фактором для предотвращения перегрева помещений. Окна так называемого немецкого типа, открывающиеся внутрь.'],
        ['label' => 'Двери', 'text' => 'Внутренние двери жилых помещений в зависимости от пакета — либо гладкие шпонированные двери, либо крашеные филёнчатые двери. Наружные двери — гладкие деревянные.'],
        ['label' => 'Лестницы и цветочные ящики', 'text' => 'Лестница и площадки перед входом отлиты из бетона, поверхность которого имеет "щёткованный" рисунок для предотвращения скольжения. Из необработанного бетона остаются и цветочные ящики перед домом. Верхние поверхности цветочных ящиков покрываются средством, подходящим для защиты от водорослей/лишайника (долговременную защиту обеспечивает дополнительная пропитка в процессе эксплуатации).'],
        ['label' => 'Балконы, террасы', 'text' => 'Полы балконов покрыты серым ПВХ-покрытием. Эксклюзивности добавляют остеклённые балконные ограждения. Несущая конструкция террас первого этажа — из пропитанной древесины, террасное покрытие из рифлёной доски шириной от 95 до 120 мм с коричневой пропиткой.'],
        ['label' => 'Навес для автомобилей', 'text' => 'К каждой секции рядного дома относится эффектный навес на два автомобиля со стойками из клеёного бруса и обшитым доской потолком. Главные несущие элементы крыши — балки из клеёного бруса. Объёмную структуру крыши образуют гвоздевые фермы. Кровельное покрытие — ПВХ.'],
      ],
    ],
    'en' => [
      'eyebrow' => 'Technical description',
      'title'   => 'Construction solution',
      'rows' => [
        ['label' => 'Foundation', 'text' => 'The buildings are built on a reinforced-concrete strip foundation'],
        ['label' => 'Thermally insulating exterior walls', 'text' => 'The Bauroc Eco-Term 375 and 500mm exterior wall provides especially good thermal insulation and a controlled indoor climate. The facades are rendered with silicone plaster; balcony surfaces and areas between windows are partly clad with wooden boarding on battens; cornices and eaves are covered with decorative building board.'],
        ['label' => 'Sound-insulating interior walls', 'text' => 'The terraced-house sections are separated from one another by sheds and carports, ensuring greater privacy. Interior walls are generally metal-frame walls covered with extra-hard plasterboard. The side walls of the staircase are laid from lightweight blocks.'],
        ['label' => 'Intermediate floors', 'text' => 'Reinforced-concrete hollow-core panels. On top of the panel an impact-sound insulation board of 50mm and a concrete floor slab with heating pipes. Under the sub-floor of the first floor 200mm of EPS insulation.'],
        ['label' => 'Weatherproof roof', 'text' => 'The roof together with the eaves elements is formed by wooden trusses/beams. Blown wool with an average thickness of min. 400 mm is used as roof insulation in the plane of the lower chord of the truss. The roof covering is a grey PVC roll material.'],
        ['label' => 'Windows', 'text' => 'Thermally insulating PVC windows with a triple-glazed unit and selective glass. On the south- and west-facing windows, glass with a solar factor is fitted in accordance with the energy label to prevent overheating of the rooms. The windows are of the so-called German type, opening inwards.'],
        ['label' => 'Doors', 'text' => 'The interior doors of the dwellings are, depending on the package, either smooth veneered doors or painted panel doors. The exterior doors are smooth wooden doors.'],
        ['label' => 'Stairs and flower boxes', 'text' => 'The steps and landings in front of the entrance are cast from concrete, whose surface has a "brushed" pattern to prevent slipping. The flower boxes in front of the house also remain of untreated concrete. The top surfaces of the flower boxes are coated with an agent suitable for algae/lichen control (long-term protection is ensured by additional impregnation during use).'],
        ['label' => 'Balconies, terraces', 'text' => 'The balcony floors are covered with a grey PVC covering. Glazed balcony railings add exclusivity. The load-bearing structure of the first-floor terraces is of impregnated wood; the terrace covering is of ribbed brown-impregnated boarding 95 to 120mm wide.'],
        ['label' => 'Vehicle carport', 'text' => 'Each terraced-house section includes a striking carport for two vehicles with glued-laminated timber posts and a boarded ceiling. The main roof supports are glued-laminated timber beams. The volumetric structure of the roof is formed by nail-plate trusses. The roof covering is PVC.'],
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
