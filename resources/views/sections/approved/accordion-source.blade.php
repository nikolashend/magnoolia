{{--
  SOURCE MAPPING — SCREENSHOT 03
  Screenshot:   03-accordion-ostuprotsess-ehitusinfo-faq.php
  Original demo: https://bracketweb.com/zoomvilla-php/index-5.php
  Source file:  php-template/parts/home5/faq.php
  Original class: .faq-page, .faq-accordion, .zoomvilla-accordion, .accordion, .accordion-title, .accordion-content
  CSS required: zoomvilla.css (.faq-accordion, .zoomvilla-accordion, .accordion-title, .accordion-content)
  JS required:  zoomvilla.js (custom accordion — already loaded globally)
  Images:       None required (text-only accordion)
  Reuse as-is:  YES (structure) — content replaced with Magnoolia FAQ/ostuprotsess/ehitusinfo
  Rebuild:      NO — accordion JS pattern works perfectly
  Risk:         LOW — pure CSS/JS accordion, no image dependencies
--}}

<section class="faq-page section-space" id="faq">
    <div class="container">
        <div class="row gutter-y-30">

            {{-- LEFT: Section header + context --}}
            <div class="col-lg-5">
                <div class="faq-page__content wow fadeInLeft" data-wow-duration="1500ms" data-wow-delay="300ms">
                    <div class="sec-title text-start">
                        <div class="sec-title__top justify-content-start">
                            <span class="line-left"></span>
                            <h6 class="sec-title__tagline bw-split-in-right">Ostuprotsess &amp; KKK</h6>
                        </div>
                        <h3 class="sec-title__title bw-split-in-left">
                            Kuidas toimub<br>kodu ostmine?
                        </h3>
                    </div>
                    <p class="faq-page__text">
                        Oleme siin, et muuta ostuprotsess lihtsaks ja läbipaistvaks.
                        Vastame teie küsimustele — võtke ühendust, kui soovite isiklikku nõustamist.
                    </p>
                    <div class="faq-page__feature">
                        {{-- [PLACEHOLDER] Replace with Magnoolia müügiesindajate foto --}}
                        <div class="faq-page__feature__thumb hover:shine"
                             style="background:var(--mg-soft-grey); border-radius:var(--mg-radius-md); width:80px; height:80px; display:flex; align-items:center; justify-content:center;">
                            <i class="fas fa-users" style="font-size:28px; color:var(--mg-warm-grey);"></i>
                        </div>
                        <div class="faq-page__feature__content">
                            <h4 class="faq-page__feature__title">Diana &amp; Jaanika</h4>
                            <ul class="faq-page__feature__list list-unstyled">
                                <li><i class="icon-check-1"></i><span>Müügiesindajad ootavad teid</span></li>
                                <li><i class="icon-check-1"></i><span>Broneeringud &amp; vaatamised</span></li>
                            </ul>
                        </div>
                    </div>
                    <div style="margin-top: 24px;">
                        <a href="{{ route('contact') }}" class="zoomvilla-btn">
                            Küsi pakkumist <i class="icon-angle-small-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            {{-- RIGHT: Accordion questions --}}
            <div class="col-lg-7 wow fadeInRight" data-wow-duration="1500ms" data-wow-delay="300ms">
                <div class="faq-accordion faq-accordion--two zoomvilla-accordion" data-grp-name="zoomvilla-accordion">
                    <div class="apartment-two__content">

                        <div class="accordion active">
                            <div class="accordion-title">
                                <h4><div class="accordion-title__number"></div>Milline on ostuprotsess?</h4>
                                <span class="accordion-title__icon"><i class="icon-arrow-top"></i></span>
                            </div>
                            <div class="accordion-content">
                                <div class="inner">
                                    <p><span>Samm-sammult:</span> Vaata kodusid → broneeri vaatamine → vali kodu →
                                    allkirjasta broneerimisleping → sõlmi müügileping notaris → kodu üleandmine valmimise järel.</p>
                                </div>
                            </div>
                        </div>

                        <div class="accordion">
                            <div class="accordion-title">
                                <h4><div class="accordion-title__number"></div>Kas on võimalik kasutada kodulaenu?</h4>
                                <span class="accordion-title__icon"><i class="icon-arrow-top"></i></span>
                            </div>
                            <div class="accordion-content">
                                <div class="inner">
                                    <p><span>Jah:</span> Magnoolia kodude ostmiseks saab kasutada kodu- või ehituslaenu.
                                    Oleme teinud koostööd pankadega ning aitame leida parima lahenduse.</p>
                                </div>
                            </div>
                        </div>

                        <div class="accordion">
                            <div class="accordion-title">
                                <h4><div class="accordion-title__number"></div>Millal on planeeritav valmimisaeg?</h4>
                                <span class="accordion-title__icon"><i class="icon-arrow-top"></i></span>
                            </div>
                            <div class="accordion-content">
                                <div class="inner">
                                    <p><span>Suvi 2027:</span> Ehitustöid alustati 2026. aastal.
                                    Ehituse edusamme saate jälgida kodulehe ehitusinfo sektsioonis.</p>
                                </div>
                            </div>
                        </div>

                        <div class="accordion">
                            <div class="accordion-title">
                                <h4><div class="accordion-title__number"></div>Mis on A-energiaklassi eelised?</h4>
                                <span class="accordion-title__icon"><i class="icon-arrow-top"></i></span>
                            </div>
                            <div class="accordion-content">
                                <div class="inner">
                                    <p><span>Madal kulu:</span> A-energiaklassi kodu tähendab maasoojuspumpa,
                                    soojustagastusega ventilatsiooni ja hästi isoleeritud konstruktsiooni.
                                    Igakuised küttearved on märgatavalt madalamad.</p>
                                </div>
                            </div>
                        </div>

                        <div class="accordion">
                            <div class="accordion-title">
                                <h4><div class="accordion-title__number"></div>Mis on lisatud hinda?</h4>
                                <span class="accordion-title__icon"><i class="icon-arrow-top"></i></span>
                            </div>
                            <div class="accordion-content">
                                <div class="inner">
                                    <p><span>Sisustuspakett:</span> [PLACEHOLDER] Täpsed detailid sisustuspaketi kohta
                                    lisatakse peagi. Küsimuste korral võtke ühendust müügiesindajaga.</p>
                                </div>
                            </div>
                        </div>

                        <div class="accordion">
                            <div class="accordion-title">
                                <h4><div class="accordion-title__number"></div>Ehitusinfo — kus näen ehituse edenemist?</h4>
                                <span class="accordion-title__icon"><i class="icon-arrow-top"></i></span>
                            </div>
                            <div class="accordion-content">
                                <div class="inner">
                                    <p><span>Ehitusblogi:</span> [PLACEHOLDER] Regulaarsed ehitusuuendused ilmuvad
                                    kodulehe ehitusinfo sektsioonis. Tellige uudiskiri, et saada teavitusi.</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
