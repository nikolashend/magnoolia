@php
    $units = config('magnoolia.units', []);

    $typeAUnits = array_values(array_filter($units, fn($unit) => ($unit['plan_type'] ?? null) === 'type-a'));
    $typeBUnits = array_values(array_filter($units, fn($unit) => ($unit['plan_type'] ?? null) === 'type-b'));

    $formatAddresses = function (array $list) {
        $addresses = array_map(fn($item) => $item['address'] ?? '', $list);
        $addresses = array_values(array_filter($addresses));
        if (count($addresses) > 6) {
            return implode(', ', array_slice($addresses, 0, 6)) . ' …';
        }
        return implode(', ', $addresses);
    };

    $floor1 = asset('assets/images/magnoolia/PR03023_PP_AR-5-01_Esimese korruse plaan_page-0001.jpg');
    $floor2 = asset('assets/images/magnoolia/PR03023_PP_AR-5-02_Teise korruse plaan_page-0001.jpg');
@endphp

<section class="section-space" id="plaanid" style="background:#fbfaf7;">
    <div class="container">

        <div class="sec-title text-center" style="margin-bottom:42px;">
            <div class="sec-title__top justify-content-center">
                <span class="line-left"></span>
                <h6 class="sec-title__tagline bw-split-in-right">Korrusplaanid</h6>
                <span class="line-right"></span>
            </div>
            <h3 class="sec-title__title bw-split-in-left">Läbimõeldud plaanid pereeluks</h3>
            <p style="color:#6f6a61;font-size:15px;line-height:1.75;margin:14px auto 0;max-width:720px;">
                Avatud elutsoon, 4–5 tuba, terrass, rõdu ja panipaik — ruumilahendus,
                mis sobib perele, kodukontorile ja külalistele.
            </p>
        </div>

        <div class="row gutter-y-30">

            <div class="col-lg-6">
                <article class="mg-typology-card wow fadeInUp" data-wow-duration="1200ms">
                    <div class="mg-typology-card__head">
                        <span class="mg-typology-card__badge">Typoloogia A</span>
                        <h4>3 koduga ridaelamu</h4>
                        <p>4 tuba · ~129,6 m² · 2 korrust</p>
                    </div>

                    <div class="mg-typology-card__grid">
                        <a href="{{ $floor1 }}" class="img-popup" aria-label="Ava 3 koduga tüübi esimese korruse plaan">
                            <img src="{{ $floor1 }}"
                                 alt="3 koduga ridaelamu esimese korruse plaan"
                                 width="424" height="413"
                                 loading="lazy" decoding="async">
                        </a>
                        <a href="{{ $floor2 }}" class="img-popup" aria-label="Ava 3 koduga tüübi teise korruse plaan">
                            <img src="{{ $floor2 }}"
                                 alt="3 koduga ridaelamu teise korruse plaan"
                                 width="371" height="428"
                                 loading="lazy" decoding="async">
                        </a>
                    </div>

                    <div class="mg-typology-card__facts">
                        <div><span>Korruseid</span><strong>2</strong></div>
                        <div><span>Tube</span><strong>4</strong></div>
                        <div><span>Parkimine</span><strong>2 kohta</strong></div>
                        <div><span>Staatus</span><strong>Täpsustamisel</strong></div>
                    </div>

                    <p class="mg-typology-card__homes">
                        Sellesse tüüpi kuuluvad kodud (config): {{ $formatAddresses($typeAUnits) ?: 'Täpsustamisel' }}
                    </p>
                </article>
            </div>

            <div class="col-lg-6">
                <article class="mg-typology-card wow fadeInUp" data-wow-duration="1200ms" data-wow-delay="120ms">
                    <div class="mg-typology-card__head">
                        <span class="mg-typology-card__badge">Typoloogia B</span>
                        <h4>4 koduga ridaelamu</h4>
                        <p>5 tuba · ~143,2 m² · 2 korrust</p>
                    </div>

                    <div class="mg-typology-card__grid">
                        <a href="{{ $floor1 }}" class="img-popup" aria-label="Ava 4 koduga tüübi esimese korruse plaan">
                            <img src="{{ $floor1 }}"
                                 alt="4 koduga ridaelamu esimese korruse plaan"
                                 width="424" height="413"
                                 loading="lazy" decoding="async">
                        </a>
                        <a href="{{ $floor2 }}" class="img-popup" aria-label="Ava 4 koduga tüübi teise korruse plaan">
                            <img src="{{ $floor2 }}"
                                 alt="4 koduga ridaelamu teise korruse plaan"
                                 width="371" height="428"
                                 loading="lazy" decoding="async">
                        </a>
                    </div>

                    <div class="mg-typology-card__facts">
                        <div><span>Korruseid</span><strong>2</strong></div>
                        <div><span>Tube</span><strong>5</strong></div>
                        <div><span>Parkimine</span><strong>2 kohta</strong></div>
                        <div><span>Staatus</span><strong>Täpsustamisel</strong></div>
                    </div>

                    <p class="mg-typology-card__homes">
                        Sellesse tüüpi kuuluvad kodud (config): {{ $formatAddresses($typeBUnits) ?: 'Täpsustamisel' }}
                    </p>
                </article>
            </div>

        </div>

        <div style="display:flex;gap:12px;flex-wrap:wrap;justify-content:center;margin-top:30px;">
            <a href="#hinnad" class="zoomvilla-btn">Vaata kodusid ja hindu <i class="icon-angle-small-right"></i></a>
            <a href="#kontakt" class="zoomvilla-btn zoomvilla-btn--border">Küsi valitud kodu plaani <i class="icon-angle-small-right"></i></a>
            <a href="{{ $floor1 }}" download class="mg-plan-download">
                <i class="fas fa-download" aria-hidden="true"></i> Laadi plaan alla
            </a>
        </div>

        <p style="font-size:13px;color:#9a9490;text-align:center;max-width:760px;margin:18px auto 0;line-height:1.7;">
            Plaanid on informatiivsed. Lõplikud mõõdud ja detailid täpsustatakse müügiprotsessis ja projekti dokumentatsioonis.
        </p>

    </div>
</section>
