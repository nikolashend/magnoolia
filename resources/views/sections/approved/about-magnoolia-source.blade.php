{{--
  SOURCE MAPPING — SCREENSHOT 01
  Screenshot:   01-about-miks-magnoolia-layout.png
  Original demo: https://bracketweb.com/zoomvilla-php/index-2.php
  Source file:  php-template/parts/home2/about.php
  Original class: .about-two
  CSS required: zoomvilla.css (.about-two, .sec-title, .about-two__thumb, .about-two__content, .about-two__list)
  JS required:  wow.js (fade animations only — lightweight)
  Images:       Magnoolia renders (placeholder until real photos available)
  Reuse as-is:  NO — fake stats, kitchen/fitness icons, fake client avatars removed
  Rebuild:      YES — kept 2-column image+content layout, removed funfact bar and happy client block
  Risk:         LOW — clean 2-col layout, standard Bootstrap grid
--}}

<section class="about-two" id="miks-magnoolia">
    <div class="container">
        <div class="row align-items-center gutter-y-30">

            {{-- LEFT: Project render --}}
            <div class="col-lg-6">
                <div class="about-two__thumb hover:shine">
                    {{-- [PLACEHOLDER] Replace with real Magnoolia exterior render --}}
                    <div style="aspect-ratio:4/5; background:var(--mg-soft-grey); border-radius:var(--mg-radius-xl); display:flex; align-items:center; justify-content:center; flex-direction:column; gap:12px;">
                        <i class="fas fa-image" style="font-size:48px; color:var(--mg-warm-grey);"></i>
                        <span style="color:var(--mg-warm-grey); font-size:var(--text-sm);">[Render: Magnoolia välisvaade]</span>
                    </div>
                </div>
            </div>

            {{-- RIGHT: Content --}}
            <div class="col-lg-6">
                <div class="about-two__content">
                    <div class="sec-title text-start">
                        <div class="sec-title__top justify-content-start">
                            <span class="line-left"></span>
                            <h6 class="sec-title__tagline bw-split-in-right">Miks Magnoolia?</h6>
                        </div>
                        <h3 class="sec-title__title bw-split-in-left">
                            Ridaelamu mugavus.<br>Eramaja privaatsus.
                        </h3>
                    </div>

                    <p class="about-two__text wow fadeInUp" data-wow-duration="1500ms" data-wow-delay="300ms">
                        Magnoolia on uusarendus Vaelas, Kiili vallas, mis ühendab ridaelamu mugavuse
                        ja eramaja privaatsuse. 19 A-energiaklassi kodu privaatse hoovialaga,
                        terrassiga ja katusealuse parkimisega — 20 minutit Tallinnast.
                    </p>

                    {{-- Feature list — ONLY real Magnoolia features, NO kitchen/bath/fitness icons --}}
                    <ul class="about-two__list list-unstyled wow fadeInUp" data-wow-duration="1500ms" data-wow-delay="300ms">
                        <li>
                            <div class="about-two__list__item">
                                <i class="fas fa-leaf"></i>
                                <span>A-energiaklass &amp; maasoojuspump</span>
                            </div>
                        </li>
                        <li>
                            <div class="about-two__list__item">
                                <i class="fas fa-tree"></i>
                                <span>600–1200 m² privaatne hooviala</span>
                            </div>
                        </li>
                        <li>
                            <div class="about-two__list__item">
                                <i class="fas fa-bolt"></i>
                                <span>EV laadimise valmidus</span>
                            </div>
                        </li>
                    </ul>

                    <div class="about-two__text wow fadeInUp" data-wow-duration="1500ms" data-wow-delay="300ms">
                        <a href="{{ route('apartments.index') }}" class="zoomvilla-btn">
                            Vaata kodusid <i class="icon-angle-small-right"></i>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
    {{-- shape removed — Magnoolia has no decorative shapes in this style --}}
</section>
