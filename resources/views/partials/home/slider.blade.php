<section class="main-slider">
    <div class="main-slider__carousel zoomvilla-owl__carousel owl-carousel owl-theme" data-owl-options='{
            "items": 1,
            "margin": 30,
            "loop": true,
            "smartSpeed": 700,
            "nav": false,
            "dotsData": true,
            "dots": true,
            "autoplay": true,
            "autoplayTimeout": 5000,
            "autoplayHoverPause": true,
            "animateOut": "fadeOut",
            "animateIn": "fadeIn"
        }'>

        @foreach($sliderItems ?? [] as $slide)
        <div class="main-slider__item" data-dot="<span>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>">
            <div class="container">
                <div class="row gutter-y-40 align-items-center">
                    <div class="col-xl-7">
                        <div class="main-slider__content">
                            <h5 class="main-slider__subtitle">
                                <span class="line-left"></span>
                                <span>{{ $slide['subtitle'] }}</span>
                            </h5>
                            <div class="main-slider__title">
                                <h2 class="main-slider__title__text">
                                    {!! $slide['title'] !!}
                                </h2>
                            </div>
                            <p class="main-slider__text">{{ $slide['text'] }}</p>
                            <ul class="main-slider__list list-unstyled">
                                @foreach($slide['stats'] ?? [] as $stat)
                                <li>
                                    <div class="main-slider__list__item">
                                        <i class="{{ $stat['icon'] }}"></i>
                                        <span>{{ $stat['label'] }}</span>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="main-slider__image">
                <div class="main-slider__image__item hover:shine">
                    <img src="{{ $slide['image'] }}" alt="{{ $slide['subtitle'] }}">
                </div>
                <div class="main-slider__location">
                    <div class="main-slider__location__icon"><i class="icon-pin"></i></div>
                    <p class="main-slider__location__text">{{ $slide['location'] }}</p>
                </div>
            </div>
            <div class="main-slider__round">
                <img src="{{ asset('assets/images/shapes/hero-shape-1-2.png') }}" alt="image">
            </div>
            <div class="main-slider__shape">
                <img src="{{ asset('assets/images/shapes/hero-shape-1-1.png') }}" alt="image">
            </div>
        </div>
        @endforeach

        {{-- Fallback static slides when no DB data --}}
        @if(empty($sliderItems) || count($sliderItems) === 0)
        <div class="main-slider__item" data-dot="<span>01</span>">
            <div class="container">
                <div class="row gutter-y-40 align-items-center">
                    <div class="col-xl-7">
                        <div class="main-slider__content">
                            <h5 class="main-slider__subtitle">
                                <span class="line-left"></span>
                                <span>{{ __('home.slider.subtitle') }}</span>
                            </h5>
                            <div class="main-slider__title">
                                <h2 class="main-slider__title__text">
                                    {!! __('home.slider.title_1') !!}
                                </h2>
                            </div>
                            <p class="main-slider__text">{{ __('home.slider.text') }}</p>
                            <ul class="main-slider__list list-unstyled">
                                <li>
                                    <div class="main-slider__list__item">
                                        <i class="icon-house"></i>
                                        <span>{{ __('home.slider.stat_apartments') }}</span>
                                    </div>
                                </li>
                                <li>
                                    <div class="main-slider__list__item">
                                        <i class="icon-labyrinth"></i>
                                        <span>{{ __('home.slider.stat_square') }}</span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="main-slider__image">
                <div class="main-slider__image__item hover:shine">
                    <img src="{{ asset('assets/images/backgrounds/slider-1-1.jpg') }}" alt="{{ __('home.slider.subtitle') }}">
                </div>
                <div class="main-slider__location">
                    <div class="main-slider__location__icon"><i class="icon-pin"></i></div>
                    <p class="main-slider__location__text">{{ __('home.slider.location') }}</p>
                </div>
            </div>
            <div class="main-slider__round">
                <img src="{{ asset('assets/images/shapes/hero-shape-1-2.png') }}" alt="image">
            </div>
            <div class="main-slider__shape">
                <img src="{{ asset('assets/images/shapes/hero-shape-1-1.png') }}" alt="image">
            </div>
        </div>
        @endif
    </div>
</section>
