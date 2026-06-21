{{-- MAGNOOLIA HERO — uses original .main-slider-two structure from Zoomvilla index-2 --}}
{{-- No OWL carousel dependency — single static slide wrapped in .active to trigger visibility CSS --}}
<section class="main-slider-two" id="home">
    <div class="active">
        <div class="main-slider-two__item">

            {{-- Hero background: video → Cam001 desktop → Cam004 mobile --}}
            @if(config('magnoolia.media.hero_video'))
                <div class="main-slider-two__bg" style="background:none;overflow:hidden;">
                    <video
                        poster="{{ asset('assets/images/magnoolia/Cam001.0000.jpg') }}"
                        autoplay muted loop playsinline
                        style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;"
                        aria-hidden="true"
                    >
                        <source src="{{ asset(config('magnoolia.media.hero_video')) }}" type="video/mp4">
                    </video>
                </div>
            @else
                <div class="main-slider-two__bg main-slider-two__bg--photo"></div>
            @endif

            <div class="container">
                <div class="row gutter-y-40 align-items-end">
                    <div class="col-xl-8 col-lg-9 col-md-12">
                        <div class="main-slider-two__content">

                            <h5 class="main-slider-two__subtitle">
                                <span class="line-left"></span>
                                {!! __('magnoolia.hero.eyebrow') !!}
                            </h5>

                            <h1 class="main-slider-two__title">
                                {!! mg_text('hero.h1') !!}
                            </h1>

                            <p class="main-slider-two__text">
                                {{ mg_text('hero.subheadline') }}
                            </p>

                            <div class="main-slider-two__btn">
                                <a href="#hinnad" class="zoomvilla-btn">
                                    {{ mg_text('hero.cta_primary') }} <i class="icon-angle-small-right"></i>
                                </a>
                                <a href="#kontakt" class="zoomvilla-btn zoomvilla-btn--border" style="margin-left:16px;">
                                    {{ __('magnoolia.hero.cta_secondary') }} <i class="icon-angle-small-right"></i>
                                </a>
                            </div>

                            {{-- Quick facts row --}}
                            <div class="mg-hero-facts">
                                <div class="mg-hero-fact"><strong>19</strong><span>{{ __('magnoolia.hero.fact_label_homes') }}</span></div>
                                <div class="mg-hero-fact"><strong>~129 m²</strong><span>{{ __('magnoolia.hero.fact_label_area') }}</span></div>
                                <div class="mg-hero-fact"><strong>4–5</strong><span>{{ __('magnoolia.hero.fact_label_rooms') }}</span></div>
                                <div class="mg-hero-fact"><strong>{{ __('magnoolia.hero.stage_year') }}</strong><span>{{ __('magnoolia.hero.fact_label_stage') }}</span></div>
                                <div class="mg-hero-fact"><strong>{{ __('magnoolia.hero.distance_value') }}</strong><span>{{ __('magnoolia.hero.fact_label_distance') }}</span></div>
                            </div>
                            <p style="margin-top:12px;margin-bottom:0;font-size:12px;
                                       color:rgba(255,255,255,.5);letter-spacing:.02em;">
                                {{ __('magnoolia.hero.stage_address_note') }}
                            </p>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>