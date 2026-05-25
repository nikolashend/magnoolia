{{--
  SOURCE MAPPING — SCREENSHOT 05
  Screenshot:   05-floor-plans-main-section.png
  Original demo: https://bracketweb.com/zoomvilla-php/index.php (home1) + index-4.php (home4)
  Source files: php-template/parts/home1/property-plans.php   → .property-plans, .property-plans__list
                php-template/parts/home4/process-plan.php     → .process-plan, .process-plan__list
  CSS required: zoomvilla.css (.property-plans, .property-plans__list, .property-plans__list__item, .property-plans__image)
  JS required:  wow.js (fade only)
  Images:       Floor plan SVG/PNG per unit type — [PLACEHOLDER] until architect delivers files
  Reuse as-is:  YES (structure) — left: spec table, center: floor plan image, right: description + download
  Rebuild:      PARTIAL — 3-col layout kept, fake prices/floors replaced with Magnoolia specs
  Risk:         LOW — static layout, tab switching for multiple unit types added as enhancement
--}}

<section class="process-plan section-space" id="plaanid">
    <div class="container">
        <div class="sec-title text-center">
            <div class="sec-title__top justify-content-center">
                <span class="line-left"></span>
                <h6 class="sec-title__tagline bw-split-in-right">Korruse plaanid</h6>
                <span class="line-right"></span>
            </div>
            <h3 class="sec-title__title bw-split-in-left">
                Tutvuge kodude<br>planeeringuga
            </h3>
        </div>

        {{-- Unit type tabs (Phase 3: add JS tab switching) --}}
        <div style="display:flex; justify-content:center; gap:12px; margin-bottom:40px; flex-wrap:wrap;">
            <button class="zoomvilla-btn" style="min-width:140px;">Tüüp A — 3 tuba</button>
            <button class="zoomvilla-btn" style="min-width:140px; opacity:0.5;">Tüüp B — 4 tuba</button>
            <button class="zoomvilla-btn" style="min-width:140px; opacity:0.5;">Tüüp C — 5 tuba</button>
        </div>

        <div class="row gutter-y-30">

            {{-- LEFT: Spec table --}}
            <div class="col-lg-4 wow fadeInLeft" data-wow-duration="1500ms" data-wow-delay="300ms">
                <div class="process-plan__left">
                    <ul class="process-plan__list list-unstyled">
                        <li class="process-plan__list__item"><span>Tüüp</span><span>A — 3 tuba</span></li>
                        <li class="process-plan__list__item"><span>Pind</span><span>[m²] — PLACEHOLDER</span></li>
                        <li class="process-plan__list__item"><span>Magamistoad</span><span>[arv]</span></li>
                        <li class="process-plan__list__item"><span>Vannitoad</span><span>[arv]</span></li>
                        <li class="process-plan__list__item"><span>Terrass</span><span>[m²]</span></li>
                        <li class="process-plan__list__item"><span>Hooviala</span><span>600–1200 m²</span></li>
                        <li class="process-plan__list__item"><span>Parkimine</span><span>Katusealune</span></li>
                        <li class="process-plan__list__item"><span>Hind</span><span>[€] — PLACEHOLDER</span></li>
                    </ul>
                </div>
                <a href="{{ route('contact') }}" class="zoomvilla-btn" style="margin-top:24px; display:inline-flex;">
                    Broneeri vaatamine <i class="icon-angle-small-right"></i>
                </a>
            </div>

            {{-- CENTER: Floor plan image --}}
            <div class="col-lg-4 wow fadeInUp" data-wow-duration="1500ms" data-wow-delay="300ms">
                <div class="process-plan__thumb hover:shine">
                    {{-- [PLACEHOLDER] Replace with actual floor plan PNG/SVG from architect --}}
                    <div style="aspect-ratio:3/4; background:var(--mg-soft-grey); border-radius:var(--mg-radius-md);
                                display:flex; align-items:center; justify-content:center; flex-direction:column; gap:12px;">
                        <i class="fas fa-drafting-compass" style="font-size:48px; color:var(--mg-warm-grey);"></i>
                        <span style="color:var(--mg-warm-grey); font-size:var(--text-sm);">[Korruse plaan: Tüüp A]</span>
                    </div>
                </div>
            </div>

            {{-- RIGHT: Description + download --}}
            <div class="col-lg-4 wow fadeInRight" data-wow-duration="1500ms" data-wow-delay="300ms">
                <div class="process-plan__right">
                    <p class="process-plan__text">
                        <span class="process-plan__highlite">Tüüp A</span> on kolmetoaline ridaelamukorter
                        privaatse hoovialaga ja katusealuse parkimisega.
                        Planeering toetab kaasaegset eluviisi — avatud elamine + köök.
                    </p>
                    <p class="process-plan__text">
                        [PLACEHOLDER] Täpsem kirjeldus, ruumijaotus ja eripärad lisatakse
                        arhitekti lõplike jooniste valmimise järel.
                    </p>
                    <div class="process-plan__btn__download">
                        <div class="process-plan__btn__icon">
                            <img src="{{ asset('assets/images/shapes/sheet-1-1.png') }}" alt="">
                        </div>
                        <div class="process-plan__btn__content">
                            <p class="process-plan__btn__text">Korruse plaan PDF</p>
                            {{-- [PLACEHOLDER] Link to actual PDF when available --}}
                            <a href="{{ route('contact') }}"><span>Küsi faili</span></a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
