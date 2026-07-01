{{-- Prepared client copy (verbatim ET, RU/EN in sync) — KONTAKT themed terms:
     Ostuprotsess (Broneerimine / Võlaõiguslik / Asjaõiguslik) + Hind sisaldab / ei sisalda.
     Source: prepared client copy. --}}
@php
  $loc = app()->getLocale();
  $A = [
    'et' => [
      'eyebrow' => 'Ostuprotsess',
      'title'   => 'Broneerimisest omandini',
      'steps' => [
        ['t' => 'Broneerimine', 'b' => 'Broneerimise soovil allkirjastatakse kliendiga broneerimiskokkulepe, mis kehtib kuni 10 päeva või kuni võlaõigusliku lepingu sõlmimiseni. Broneerimiskokkuleppe pikendamiseks tuleb ostjal tasuda 4000 € broneerimistasu, mille võrra vähendatakse ostulepingu lõppmaksumust.'],
        ['t' => 'Võlaõiguslik leping', 'b' => 'Notariaalne võlaõiguslik ostu-müügileping sõlmitakse perioodiks, kui ridamajade ehitusprotsess veel kestab. Võlaõigusliku lepingu sõlmimisel tasub ostja 10% müügihinnast.'],
        ['t' => 'Asjaõiguslik leping', 'b' => 'Ühe kuu jooksul peale ridaelamu valmimist sõlmitakse notariaalne asjaõiguslik leping, millega kaasneb lepingu objekti omandiõiguse üleminek ning ostja tasub ülejäänud 90% ostusummast.'],
      ],
      'inclTitle' => 'Hind sisaldab',
      'incl' => [
        'Siseviimistlust vastavalt siseviimistluspaketile',
        'Andmeside ja kaabeltelevisiooni valmidust',
        'Päikesepaneelide kaabeldust',
        'Jahutuse valmidust (torustik välja ehitatud)',
        'Elektrisõiduki laadimispunkti valmidust (kaablitoru)',
        'Kaks varjualusega parkimiskohta sõidukitele, külm panipaik',
        'Liitumistasusid tehnovõrkudega',
        'Kasutuskorralepinguga haljasalad isiklikuks kasutamiseks hoonesektsiooni juures',
        'Käibemaksu',
      ],
      'exclTitle' => 'Müügihind ei sisalda',
      'excl' => [
        'Notaritasusid ja riigilõive',
        'Hinnakirjas välja toodud lisatööde, seadmete ja teenuste maksumust',
        'Teenus- ja hoolduslepinguid (andmeside, elekter, vesi ja kanalisatsioon)',
        'Mööblit ja valgusteid (v.a. ühiskasutatava ala valgustus, sanruumide ja esiku valgustid)',
        'Valvesignalisatsiooni ja suitsuandurite kaabeldust ning lõppseadmeid (lisatasu eest)',
      ],
      'note' => 'Täpsed tingimused, tähtajad ja kohustused fikseeritakse broneerimislepingus ja müügilepingus.',
    ],
    'ru' => [
      'eyebrow' => 'Процесс покупки',
      'title'   => 'От брони до собственности',
      'steps' => [
        ['t' => 'Бронирование', 'b' => 'При желании забронировать с клиентом подписывается соглашение о бронировании, которое действует до 10 дней или до заключения обязательственно-правового договора. Для продления соглашения о бронировании покупатель должен внести плату за бронирование 4000 €, на которую уменьшается итоговая стоимость договора купли-продажи.'],
        ['t' => 'Обязательственно-правовой договор', 'b' => 'Нотариальный обязательственно-правовой договор купли-продажи заключается на период, пока ещё продолжается процесс строительства рядных домов. При заключении обязательственно-правового договора покупатель уплачивает 10% от цены продажи.'],
        ['t' => 'Вещно-правовой договор', 'b' => 'В течение одного месяца после завершения рядного дома заключается нотариальный вещно-правовой договор, с которым переходит право собственности на объект договора, и покупатель уплачивает оставшиеся 90% от суммы покупки.'],
      ],
      'inclTitle' => 'Цена включает',
      'incl' => [
        'Внутреннюю отделку согласно пакету внутренней отделки',
        'Готовность для передачи данных и кабельного телевидения',
        'Кабельную разводку для солнечных панелей',
        'Готовность к охлаждению (трубопровод проложен)',
        'Готовность точки зарядки электромобиля (кабель-канал)',
        'Два парковочных места под навесом для транспорта, холодная кладовая',
        'Плату за подключение к техническим сетям',
        'Зелёные зоны для личного пользования у секции здания по договору о порядке пользования',
        'Налог с оборота',
      ],
      'exclTitle' => 'Цена не включает',
      'excl' => [
        'Нотариальные сборы и государственные пошлины',
        'Стоимость указанных в прайс-листе дополнительных работ, оборудования и услуг',
        'Договоры на обслуживание и техобслуживание (передача данных, электричество, вода и канализация)',
        'Мебель и светильники (кроме освещения помещений общего пользования, светильников санузлов и прихожей)',
        'Кабельную разводку и оконечные устройства охранной сигнализации и датчиков дыма (за дополнительную плату)',
      ],
      'note' => 'Точные условия, сроки и обязательства фиксируются в договоре о бронировании и договоре купли-продажи.',
    ],
    'en' => [
      'eyebrow' => 'Purchase process',
      'title'   => 'From reservation to ownership',
      'steps' => [
        ['t' => 'Reservation', 'b' => 'On the wish to reserve, a reservation agreement is signed with the client, valid for up to 10 days or until the conclusion of the law-of-obligations contract. To extend the reservation agreement, the buyer must pay a reservation fee of 4000 €, by which the final price of the purchase contract is reduced.'],
        ['t' => 'Law-of-obligations contract', 'b' => 'A notarial law-of-obligations purchase-and-sale contract is concluded for the period while the construction of the terraced houses is still ongoing. On concluding the law-of-obligations contract, the buyer pays 10% of the sale price.'],
        ['t' => 'Right-in-rem contract', 'b' => 'Within one month after completion of the terraced house, a notarial right-in-rem contract is concluded, which entails the transfer of ownership of the object of the contract, and the buyer pays the remaining 90% of the purchase sum.'],
      ],
      'inclTitle' => 'The price includes',
      'incl' => [
        'Interior finishing according to the interior finishing package',
        'Readiness for data connection and cable television',
        'Cabling for solar panels',
        'Cooling readiness (piping built out)',
        'Readiness of an electric-vehicle charging point (cable conduit)',
        'Two covered parking spaces for vehicles, a cold storage room',
        'Connection fees for utility networks',
        'Green areas for personal use next to the building section under a use-arrangement agreement',
        'Value-added tax',
      ],
      'exclTitle' => 'The price does not include',
      'excl' => [
        'Notary fees and state duties',
        'The cost of additional works, equipment and services listed in the price list',
        'Service and maintenance contracts (data connection, electricity, water and sewerage)',
        'Furniture and light fittings (except lighting of the common-use area, and the fittings of the bathrooms and hallway)',
        'Cabling and terminal devices for the security alarm and smoke detectors (for an additional fee)',
      ],
      'note' => 'The exact terms, deadlines and obligations are fixed in the reservation contract and the sale contract.',
    ],
  ];
  $a = $A[$loc] ?? $A['et'];
@endphp

<section class="mg-page-section mg-page-section--white">
  <div class="container">
    <div class="mg-section-heading" style="margin-bottom:36px;">
      <div class="mg-section-heading__eyebrow">{{ $a['eyebrow'] }}</div>
      <h2 class="mg-section-heading__title">{{ $a['title'] }}</h2>
    </div>

    <div class="row gutter-y-28" style="margin-bottom:44px;">
      @foreach($a['steps'] as $i => $s)
      <div class="col-lg-4">
        <div style="background:#fff;border:1px solid rgba(29,36,48,.08);border-radius:16px;padding:28px;height:100%;">
          <div style="width:46px;height:46px;background:rgba(200,148,67,.15);border-radius:11px;display:flex;align-items:center;justify-content:center;margin-bottom:16px;">
            <span style="color:#c89443;font-size:20px;font-weight:800;line-height:1;">{{ $i + 1 }}</span>
          </div>
          <h3 style="font-size:18px;font-weight:700;color:#1d2430;margin:0 0 10px;">{{ $s['t'] }}</h3>
          <p style="font-size:14.5px;color:#6f6a61;line-height:1.75;margin:0;">{{ $s['b'] }}</p>
        </div>
      </div>
      @endforeach
    </div>

    <div class="row gutter-y-28">
      <div class="col-lg-6">
        <div style="background:#faf7f1;border-radius:16px;padding:28px;height:100%;">
          <h3 style="font-size:18px;font-weight:700;color:#1d2430;margin:0 0 18px;">{{ $a['inclTitle'] }}</h3>
          <ul style="list-style:none;margin:0;padding:0;">
            @foreach($a['incl'] as $item)
            <li style="display:flex;align-items:flex-start;gap:12px;padding:9px 0;border-bottom:1px solid rgba(29,36,48,.06);">
              <span style="flex-shrink:0;width:22px;height:22px;border-radius:50%;background:rgba(200,148,67,.15);display:flex;align-items:center;justify-content:center;margin-top:1px;">
                <i class="fas fa-check" style="color:#c89443;font-size:11px;"></i>
              </span>
              <span style="font-size:14.5px;color:#4c4a44;line-height:1.65;">{{ $item }}</span>
            </li>
            @endforeach
          </ul>
        </div>
      </div>

      <div class="col-lg-6">
        <div style="background:#fff;border:1px solid rgba(29,36,48,.08);border-radius:16px;padding:28px;height:100%;">
          <h3 style="font-size:18px;font-weight:700;color:#1d2430;margin:0 0 18px;">{{ $a['exclTitle'] }}</h3>
          <ul style="list-style:none;margin:0;padding:0;">
            @foreach($a['excl'] as $item)
            <li style="display:flex;align-items:flex-start;gap:12px;padding:9px 0;border-bottom:1px solid rgba(29,36,48,.06);">
              <span style="flex-shrink:0;width:22px;height:22px;border-radius:50%;background:rgba(29,36,48,.06);display:flex;align-items:center;justify-content:center;margin-top:1px;">
                <i class="fas fa-minus" style="color:#9a938a;font-size:11px;"></i>
              </span>
              <span style="font-size:14.5px;color:#6f6a61;line-height:1.65;">{{ $item }}</span>
            </li>
            @endforeach
          </ul>
        </div>
      </div>
    </div>

    <p style="font-size:13px;color:#9a938a;line-height:1.7;margin:22px 0 0;font-style:italic;">{{ $a['note'] }}</p>
  </div>
</section>
