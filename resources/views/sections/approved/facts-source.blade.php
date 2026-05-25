{{--
  SOURCE MAPPING — SCREENSHOT 09
  Screenshot:   09-facts-bar-inspiration-no-fake-stats.png
  Original demo: https://bracketweb.com/zoomvilla-php/index-4.php
  Source file:  php-template/parts/home4/funfact.php
  Original class: .funfact-one, .funfact-one__item, .funfact-one__count, .count-box, .count-text
  CSS required: zoomvilla.css (.funfact-one, .funfact-one__item, .funfact-one__item__icon)
  JS required:  jquery-appear.js + zoomvilla.js (count-up animation) — NOT used here
  Images:       None
  Reuse as-is:  NO — count-up animation with fake "20M", "260+" stats is FORBIDDEN
  Rebuild:      YES — replaced with static real Magnoolia facts, removed all .count-box/.count-text JS
  Risk:         NONE — static HTML, no JS dependency, no fake counters
  
  NOTE: Screenshot title says "inspiration-no-fake-stats" — this block deliberately avoids
  the funfact count-up pattern. It uses the same visual grid structure but with REAL static values.
--}}

<section class="funfact-one section-space" id="faktid" aria-label="Magnoolia projektifaktid">
    <div class="container">
        <div class="sec-title text-center" style="margin-bottom:40px;">
            <div class="sec-title__top justify-content-center">
                <span class="line-left"></span>
                <h6 class="sec-title__tagline bw-split-in-right">Projekt arvudes</h6>
                <span class="line-right"></span>
            </div>
            <h3 class="sec-title__title bw-split-in-left">Magnoolia faktid</h3>
        </div>

        <div class="row gutter-y-30">

            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                <div class="funfact-one__item wow fadeInUp" data-wow-duration="1500ms" data-wow-delay="100ms">
                    <div class="funfact-one__item__icon">
                        <i class="fas fa-home"></i>
                    </div>
                    <div class="funfact-one__item__content">
                        {{-- Static value — NO count-up JS --}}
                        <h3 class="funfact-one__count">19</h3>
                        <p class="funfact-one__funfact__text">A-klassi kodu</p>
                    </div>
                    <span class="funfact-one__item__shape"></span>
                </div>
            </div>

            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                <div class="funfact-one__item wow fadeInUp" data-wow-duration="1500ms" data-wow-delay="300ms">
                    <div class="funfact-one__item__icon">
                        <i class="fas fa-leaf"></i>
                    </div>
                    <div class="funfact-one__item__content">
                        <h3 class="funfact-one__count">A</h3>
                        <p class="funfact-one__funfact__text">Energiaklass</p>
                    </div>
                    <span class="funfact-one__item__shape"></span>
                </div>
            </div>

            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                <div class="funfact-one__item wow fadeInUp" data-wow-duration="1500ms" data-wow-delay="500ms">
                    <div class="funfact-one__item__icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="funfact-one__item__content">
                        <h3 class="funfact-one__count">20 min</h3>
                        <p class="funfact-one__funfact__text">Tallinnast</p>
                    </div>
                    <span class="funfact-one__item__shape"></span>
                </div>
            </div>

            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                <div class="funfact-one__item wow fadeInUp" data-wow-duration="1500ms" data-wow-delay="700ms">
                    <div class="funfact-one__item__icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="funfact-one__item__content">
                        <h3 class="funfact-one__count">2027</h3>
                        <p class="funfact-one__funfact__text">Valmimisaeg</p>
                    </div>
                    <span class="funfact-one__item__shape"></span>
                </div>
            </div>

        </div>
    </div>
</section>
