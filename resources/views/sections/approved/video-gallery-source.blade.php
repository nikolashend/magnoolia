{{--
  SOURCE MAPPING — SCREENSHOT 07
  Screenshot:   07-premium-gallery-video-preview.png
  Original demo: https://bracketweb.com/zoomvilla-php/index-3.php
  Source file:  php-template/parts/home3/video.php
  Original class: .video-three, .video-three__bg, .video-three__inner, .video-popup
  CSS required: zoomvilla.css (.video-three, .video-three__bg, .video-three__video)
  JS required:  jquery-magnific-popup.js (.video-popup class triggers YouTube popup — already global)
  Images:       Magnoolia exterior render as background — [PLACEHOLDER]
  Reuse as-is:  YES — structure is clean: full-width bg image + centered play button
  Rebuild:      PARTIAL — replaced demo YouTube URL and text, bg image placeholder
  Risk:         LOW — magnific-popup already loaded globally, simple markup
--}}

<div class="video-three" id="video">
    {{-- [PLACEHOLDER] Replace background with real Magnoolia exterior photo/render --}}
    <div class="video-three__bg"
         style="background-image: url('{{ asset('assets/images/backgrounds/video-bg-1-1.jpg') }}');">
        {{-- TODO: replace with Magnoolia render: style="background-image: url('{{ asset('assets/images/magnoolia/hero-render.jpg') }}');" --}}
    </div>

    <div class="video-three__inner wow fadeInUp" data-wow-duration="1500ms" data-wow-delay="300ms">
        <h2 class="video-three__title">Magnoolia — vaata tutvustavat videot</h2>
        <div class="video-three__video">
            {{-- [PLACEHOLDER] Replace YouTube URL with real Magnoolia promo video --}}
            <a class="video-popup" href="https://www.youtube.com/watch?v=PLACEHOLDER">
                <i class="icon-play-1"></i>
            </a>
        </div>
    </div>
</div>
