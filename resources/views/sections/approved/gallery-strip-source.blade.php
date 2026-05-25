{{--
  SOURCE MAPPING — SCREENSHOT 04
  Screenshot:   04-horizontal-gallery-renders-strip.png
  Original demo: https://bracketweb.com/zoomvilla-php/index-3.php
  Source file:  php-template/parts/home3/slider-area.php
  Original class: .slider-area, .slider-area__three, .slider-area__text-slider
  CSS required: zoomvilla.css (.slider-area, .slider-area__item) + custom scroll animation
  JS required:  NONE (pure CSS marquee scroll) — original used text ticker, we replace with image grid
  Images:       Magnoolia render photos — replace placeholders below
  Reuse as-is:  NO — original is a text marquee ticker (PROPERTY/HOUSE/Building). Not suitable.
  Rebuild:      YES — replaced text ticker with horizontal photo gallery strip. CSS scroll animation added.
  Risk:         LOW — pure CSS, no JS slider dependency
--}}

<section class="section-space" id="galerii" style="overflow:hidden;">
    <div class="container">
        <div class="sec-title text-center" style="margin-bottom: 40px;">
            <div class="sec-title__top justify-content-center">
                <span class="line-left"></span>
                <h6 class="sec-title__tagline bw-split-in-right">Renderdused</h6>
                <span class="line-right"></span>
            </div>
            <h3 class="sec-title__title bw-split-in-left">Vaata, milline Magnoolia välja näeb</h3>
        </div>
    </div>

    {{-- Horizontal scrolling render strip -- pure CSS marquee, no JS --}}
    <div class="mg-gallery-strip" aria-label="Magnoolia renderduste galerii">
        <div class="mg-gallery-strip__track">

            {{-- [PLACEHOLDER] Replace each item with real Magnoolia render --}}
            @foreach(range(1, 6) as $i)
            <div class="mg-gallery-strip__item">
                <div style="width:360px; height:240px; background:var(--mg-soft-grey);
                            border-radius:var(--mg-radius-md); display:flex; align-items:center;
                            justify-content:center; flex-direction:column; gap:8px; flex-shrink:0;">
                    <i class="fas fa-image" style="font-size:32px; color:var(--mg-warm-grey);"></i>
                    <span style="color:var(--mg-warm-grey); font-size:var(--text-xs);">[Render {{ $i }}]</span>
                </div>
            </div>
            @endforeach

            {{-- Duplicate set for seamless loop --}}
            @foreach(range(1, 6) as $i)
            <div class="mg-gallery-strip__item" aria-hidden="true">
                <div style="width:360px; height:240px; background:var(--mg-soft-grey);
                            border-radius:var(--mg-radius-md); display:flex; align-items:center;
                            justify-content:center; flex-direction:column; gap:8px; flex-shrink:0;">
                    <i class="fas fa-image" style="font-size:32px; color:var(--mg-warm-grey);"></i>
                    <span style="color:var(--mg-warm-grey); font-size:var(--text-xs);">[Render {{ $i }}]</span>
                </div>
            </div>
            @endforeach

        </div>
    </div>

    <div class="container" style="margin-top: 32px; text-align:center;">
        <a href="#" class="zoomvilla-btn">
            Vaata kõiki renderdusi <i class="icon-angle-small-right"></i>
        </a>
    </div>
</section>

{{--
  CSS to add to magnoolia.css:
  .mg-gallery-strip { overflow:hidden; }
  .mg-gallery-strip__track { display:flex; gap:16px; width:max-content; animation: mgGalleryScroll 30s linear infinite; }
  .mg-gallery-strip__track:hover { animation-play-state:paused; }
  .mg-gallery-strip__item { flex-shrink:0; }
  @keyframes mgGalleryScroll { 0%{transform:translateX(0)} 100%{transform:translateX(-50%)} }
--}}
