{{-- material-specs-section.blade.php
     Moved here from /ehitusinfo (client request): the materials & technical-solutions
     block belongs on the design (Sisedisain) page. --}}
{{-- ── Phase 28: Material specifications (source: Excel/project docs) ── --}}
<section class="mg-page-section mg-page-section--cream" id="materjalid">
  <div class="container">
    <div class="mg-section-heading" style="margin-bottom:40px;">
      <div class="mg-section-heading__eyebrow">
        {{ app()->getLocale()==='ru' ? 'Характеристики' : (app()->getLocale()==='en' ? 'Specifications' : 'Karakteristikud') }}
      </div>
      <h2 class="mg-section-heading__title">
        {{ app()->getLocale()==='ru' ? 'Материалы и технические решения' : (app()->getLocale()==='en' ? 'Materials and technical solutions' : 'Materjalid ja tehnilised lahendused') }}
      </h2>
      <p class="mg-section-heading__subtitle">
        {{ app()->getLocale()==='ru' ? 'Технические и отделочные решения уточняются в окончательном предложении о продаже и проектной документации. Изображения и образцы материалов носят иллюстративный характер.' : (app()->getLocale()==='en' ? 'Technical and finish solutions are confirmed in the final sales offer and project documentation. Images and material samples are illustrative.' : 'Tehnilised ja viimistluslahendused täpsustatakse lõplikus müügipakkumises ja projektdokumentatsioonis. Pildid ja materjalinäidised on illustratiivsed.') }}
      </p>
    </div>

    <div class="row gutter-y-28">

      {{-- Tile specifications --}}
      <div class="col-lg-6">
        <div style="background:#fff;border-radius:16px;padding:28px;height:100%;border:1px solid rgba(29,36,48,.06);">
          <h3 style="font-size:16px;font-weight:700;color:#1d2430;margin:0 0 20px;display:flex;align-items:center;gap:10px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#c89443" stroke-width="2" aria-hidden="true"><rect x="3" y="3" width="8" height="8"/><rect x="13" y="3" width="8" height="8"/><rect x="3" y="13" width="8" height="8"/><rect x="13" y="13" width="8" height="8"/></svg>
            {{ app()->getLocale()==='ru' ? 'Плиточная отделка' : (app()->getLocale()==='en' ? 'Tile specification' : 'Plaadilahendus') }}
          </h3>
          <table style="width:100%;border-collapse:collapse;font-size:13px;">
            <thead>
              <tr style="border-bottom:2px solid #f0ede8;">
                <th style="text-align:left;padding:8px 4px;color:#888;font-weight:600;font-size:11px;letter-spacing:.06em;text-transform:uppercase;">
                  {{ app()->getLocale()==='ru' ? 'Расположение' : (app()->getLocale()==='en' ? 'Location' : 'Asukoht') }}
                </th>
                <th style="text-align:left;padding:8px 4px;color:#888;font-weight:600;font-size:11px;letter-spacing:.06em;text-transform:uppercase;">
                  {{ app()->getLocale()==='ru' ? 'Тип плитки' : (app()->getLocale()==='en' ? 'Tile type' : 'Plaaditüüp') }}
                </th>
                <th style="text-align:right;padding:8px 4px;color:#888;font-weight:600;font-size:11px;letter-spacing:.06em;text-transform:uppercase;">
                  {{ app()->getLocale()==='ru' ? 'Площадь' : (app()->getLocale()==='en' ? 'Area' : 'Pind') }}
                </th>
              </tr>
            </thead>
            <tbody>
              @foreach([
                ['loc_et' => 'Vannituba, sauna eesruum, duširuumid', 'loc_ru' => 'Ванная, предбанник, душ', 'loc_en' => 'Bathroom, sauna anteroom, showers', 'type' => '60×120 cm', 'area' => '~121 m²'],
                ['loc_et' => 'Esik, WC, leiliruumid, eesr., vannituba', 'loc_ru' => 'Прихожая, WC, сауна, предбанник', 'loc_en' => 'Hallway, WC, sauna rooms, anteroom', 'type' => '60×60 cm', 'area' => '~104 m²'],
              ] as $row)
              <tr style="border-bottom:1px solid #f3f4f6;">
                <td style="padding:10px 4px;color:#444;line-height:1.4;vertical-align:top;">
                  {{ app()->getLocale()==='ru' ? $row['loc_ru'] : (app()->getLocale()==='en' ? $row['loc_en'] : $row['loc_et']) }}
                </td>
                <td style="padding:10px 4px;font-weight:600;color:#1d2430;white-space:nowrap;vertical-align:top;">{{ $row['type'] }}</td>
                <td style="padding:10px 4px;text-align:right;color:#c89443;font-weight:600;vertical-align:top;white-space:nowrap;">{{ $row['area'] }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
          <p style="font-size:11px;color:#aaa;margin-top:12px;font-style:italic;">
            {{ app()->getLocale()==='ru' ? 'По данным проектной документации (Magnoolia tee). Конкретные материалы уточняются.' : (app()->getLocale()==='en' ? 'Based on project documentation (Magnoolia tee). Specific materials subject to confirmation.' : 'Andmed projektdokumentatsiooni alusel (Magnoolia tee). Konkreetsed materjalid täpsustuvad.') }}
          </p>
        </div>
      </div>

      {{-- Sanitary fittings --}}
      <div class="col-lg-6">
        <div style="background:#fff;border-radius:16px;padding:28px;height:100%;border:1px solid rgba(29,36,48,.06);">
          <h3 style="font-size:16px;font-weight:700;color:#1d2430;margin:0 0 20px;display:flex;align-items:center;gap:10px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#c89443" stroke-width="2" aria-hidden="true"><path d="M4 12a8 8 0 0 1 16 0Z"/><path d="M2 12h20"/><path d="M4 12v8"/><path d="M20 12v8"/></svg>
            {{ app()->getLocale()==='ru' ? 'Санитарная арматура' : (app()->getLocale()==='en' ? 'Sanitary fittings' : 'Sanitaarvarustus') }}
          </h3>
          <table style="width:100%;border-collapse:collapse;font-size:13px;">
            <thead>
              <tr style="border-bottom:2px solid #f0ede8;">
                <th style="text-align:left;padding:8px 4px;color:#888;font-weight:600;font-size:11px;letter-spacing:.06em;text-transform:uppercase;">
                  {{ app()->getLocale()==='ru' ? 'Элемент' : (app()->getLocale()==='en' ? 'Element' : 'Element') }}
                </th>
                <th style="text-align:right;padding:8px 4px;color:#888;font-weight:600;font-size:11px;letter-spacing:.06em;text-transform:uppercase;">
                  {{ app()->getLocale()==='ru' ? 'Кол-во / дом' : (app()->getLocale()==='en' ? 'Qty / home' : 'Tk / kodu') }}
                </th>
              </tr>
            </thead>
            <tbody>
              @foreach([
                ['name_et' => 'Dušisegistid', 'name_ru' => 'Душевые смесители', 'name_en' => 'Shower fittings', 'qty' => '2'],
                ['name_et' => 'Eraldi WC segistid', 'name_ru' => 'Смесители для WC', 'name_en' => 'Separate WC faucets', 'qty' => '~1–2'],
                ['name_et' => 'Vanni segistid', 'name_ru' => 'Смесители для ванны', 'name_en' => 'Bath faucets', 'qty' => '~1'],
                ['name_et' => 'Vannitoa kraanid', 'name_ru' => 'Краны для раковины', 'name_en' => 'Bathroom sink faucets', 'qty' => '2'],
              ] as $row)
              <tr style="border-bottom:1px solid #f3f4f6;">
                <td style="padding:10px 4px;color:#444;line-height:1.4;">
                  {{ app()->getLocale()==='ru' ? $row['name_ru'] : (app()->getLocale()==='en' ? $row['name_en'] : $row['name_et']) }}
                </td>
                <td style="padding:10px 4px;text-align:right;font-weight:600;color:#1d2430;">{{ $row['qty'] }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
          <p style="font-size:11px;color:#aaa;margin-top:12px;font-style:italic;">
            {{ app()->getLocale()==='ru' ? 'Точные марки подтверждаются в договоре. Иллюстративно.' : (app()->getLocale()==='en' ? 'Exact brands confirmed in contract. Illustrative.' : 'Täpsed margid kinnitatakse lepingus. Illustratiivne.') }}
          </p>
        </div>
      </div>

      {{-- Cross-link to Sisedisain --}}
      <div class="col-12">
        <div style="background:#1d2430;border-radius:12px;padding:20px 28px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;">
          <div style="color:#fff;">
            <div style="font-size:11px;color:#c89443;font-weight:700;letter-spacing:.1em;text-transform:uppercase;margin-bottom:4px;">
              {{ app()->getLocale()==='ru' ? 'Дизайн интерьера' : (app()->getLocale()==='en' ? 'Interior design' : 'Siseviimistlus') }}
            </div>
            <div style="font-size:15px;font-weight:600;">
              {{ app()->getLocale()==='ru' ? 'Материалы, отделка и дополнительные опции подробно описаны на странице Sisedisain' : (app()->getLocale()==='en' ? 'Materials, finishes and add-on options are detailed on the Sisedisain page' : 'Materjalid, viimistlus ja lisavalikud on üksikasjalikult kirjeldatud sisedisaini lehel') }}
            </div>
          </div>
          <a href="{{ lroute('magnoolia.sisedisain') }}"
             style="flex-shrink:0;background:#c89443;color:#fff;text-decoration:none;padding:10px 24px;border-radius:8px;font-size:13px;font-weight:700;white-space:nowrap;">
            {{ app()->getLocale()==='ru' ? 'Дизайн интерьера →' : (app()->getLocale()==='en' ? 'Interior design →' : 'Sisedisain →') }}
          </a>
        </div>
      </div>

    </div>
  </div>
</section>

{{-- ── Phase 31: premium interior-finish & equipment standard ──────── --}}
@include('partials.magnoolia.interior-finish-section')
