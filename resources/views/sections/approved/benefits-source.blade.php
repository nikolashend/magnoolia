{{--
  SOURCE MAPPING — SCREENSHOT 02
  Screenshot:   02-benefit-cards-core-values.png
  Original demo: https://bracketweb.com/zoomvilla-php/index-2.php
  Source file:  php-template/parts/home2/features.php
  Original class: .feature-two, .feature-two__item
  CSS required: zoomvilla.css (.feature-two, .feature-two__item, .feature-two__item__icon)
  JS required:  wow.js (fade only)
  Images:       Background images per card — Magnoolia renders needed
  Reuse as-is:  NO — original uses kitchen/bath/dumbbell icons (FORBIDDEN)
  Rebuild:      YES — kept card layout + image-bg hover effect, replaced all icons and content
  Risk:         LOW — 3-col card grid, can extend to 6 cards with row wrap
--}}

<section class="feature-two" id="eelised">
    <div class="container">
        <div class="sec-title text-center" style="margin-bottom: 40px;">
            <div class="sec-title__top justify-content-center">
                <span class="line-left"></span>
                <h6 class="sec-title__tagline bw-split-in-right">Magnoolia eelised</h6>
                <span class="line-right"></span>
            </div>
            <h3 class="sec-title__title bw-split-in-left">Mida teeb Magnoolia eriliseks?</h3>
        </div>

        <div class="row gutter-y-30">

            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-duration="1500ms" data-wow-delay="100ms">
                <div class="feature-two__item">
                    <div class="feature-two__item__content">
                        <div class="feature-two__item__icon"><i class="fas fa-leaf"></i></div>
                        <h3 class="feature-two__item__title">A-energiaklass</h3>
                    </div>
                    <div class="feature-two__item__image">
                        {{-- [PLACEHOLDER] Replace with render: energiaklass/termiline pilt --}}
                        <div style="width:100%; height:220px; background:var(--mg-soft-grey); display:flex; align-items:center; justify-content:center;">
                            <span style="color:var(--mg-warm-grey); font-size:var(--text-xs);">[Render: energiaklass]</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-duration="1500ms" data-wow-delay="300ms">
                <div class="feature-two__item">
                    <div class="feature-two__item__content">
                        <div class="feature-two__item__icon"><i class="fas fa-tree"></i></div>
                        <h3 class="feature-two__item__title">Privaatne hooviala</h3>
                    </div>
                    <div class="feature-two__item__image">
                        {{-- [PLACEHOLDER] Replace with render: hooviala/terrass --}}
                        <div style="width:100%; height:220px; background:var(--mg-soft-grey); display:flex; align-items:center; justify-content:center;">
                            <span style="color:var(--mg-warm-grey); font-size:var(--text-xs);">[Render: hooviala]</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-duration="1500ms" data-wow-delay="500ms">
                <div class="feature-two__item">
                    <div class="feature-two__item__content">
                        <div class="feature-two__item__icon"><i class="fas fa-shield-alt"></i></div>
                        <h3 class="feature-two__item__title">Uusarenduse kindlus</h3>
                    </div>
                    <div class="feature-two__item__image">
                        {{-- [PLACEHOLDER] Replace with render: hoone konstruktsioon --}}
                        <div style="width:100%; height:220px; background:var(--mg-soft-grey); display:flex; align-items:center; justify-content:center;">
                            <span style="color:var(--mg-warm-grey); font-size:var(--text-xs);">[Render: ehitus]</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-duration="1500ms" data-wow-delay="200ms">
                <div class="feature-two__item">
                    <div class="feature-two__item__content">
                        <div class="feature-two__item__icon"><i class="fas fa-bolt"></i></div>
                        <h3 class="feature-two__item__title">EV laadimise valmidus</h3>
                    </div>
                    <div class="feature-two__item__image">
                        <div style="width:100%; height:220px; background:var(--mg-soft-grey); display:flex; align-items:center; justify-content:center;">
                            <span style="color:var(--mg-warm-grey); font-size:var(--text-xs);">[Render: garaaž/laadimine]</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-duration="1500ms" data-wow-delay="400ms">
                <div class="feature-two__item">
                    <div class="feature-two__item__content">
                        <div class="feature-two__item__icon"><i class="fas fa-map-marker-alt"></i></div>
                        <h3 class="feature-two__item__title">20 min Tallinnast</h3>
                    </div>
                    <div class="feature-two__item__image">
                        <div style="width:100%; height:220px; background:var(--mg-soft-grey); display:flex; align-items:center; justify-content:center;">
                            <span style="color:var(--mg-warm-grey); font-size:var(--text-xs);">[Kaart: Vaela asukoht]</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-duration="1500ms" data-wow-delay="600ms">
                <div class="feature-two__item">
                    <div class="feature-two__item__content">
                        <div class="feature-two__item__icon"><i class="fas fa-expand-arrows-alt"></i></div>
                        <h3 class="feature-two__item__title">Suur terrass</h3>
                    </div>
                    <div class="feature-two__item__image">
                        <div style="width:100%; height:220px; background:var(--mg-soft-grey); display:flex; align-items:center; justify-content:center;">
                            <span style="color:var(--mg-warm-grey); font-size:var(--text-xs);">[Render: terrass]</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
