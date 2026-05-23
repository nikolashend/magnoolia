<section class="testimonials-one">
    <div class="container">
        <div class="testimonials-one__inner section-space">
            <div class="sec-title text-center">
                <div class="sec-title__top justify-content-center">
                    <span class="line-left"></span>
                    <h6 class="sec-title__tagline bw-split-in-right">{{ __('home.testimonials.tagline') }}</h6>
                    <span class="line-right"></span>
                </div>
                <h3 class="sec-title__title bw-split-in-left">{{ __('home.testimonials.title') }}</h3>
            </div>
            <div class="testimonials-one__thumb hover:shine wow fadeInLeft" data-wow-duration="1500ms" data-wow-delay="300ms">
                <img src="{{ asset('assets/images/resources/testiomonial-bg-1-1.jpg') }}" alt="testimonials">
            </div>
            <div class="row justify-content-end">
                <div class="col-xl-6 col-lg-8 col-md-12">
                    <div class="testimonials-one__carousel zoomvilla-slick__carousel wow fadeInRight"
                         data-wow-duration="1500ms" data-wow-delay="300ms"
                         data-slick-options='{
                            "slidesToShow": 2,
                            "slidesToScroll": 1,
                            "autoplay": false,
                            "vertical": true,
                            "focusOnSelect": true,
                            "verticalSwiping": true,
                            "dots": false,
                            "arrows": true,
                            "prevArrow": "<button class=\"testimonials-one__slick-button testimonials-one__slick-button--prev\"><i class=\"icon-arrow-top\"></i></button>",
                            "nextArrow": "<button class=\"testimonials-one__slick-button testimonials-one__slick-button--next\"><i class=\"icon-arrow-top\"></i></button>",
                            "responsive": [{"breakpoint":992,"settings":{"slidesToShow":2,"vertical":false,"verticalSwiping":false}},{"breakpoint":767,"settings":{"slidesToShow":1,"vertical":false,"verticalSwiping":false}}]
                        }'>

                        @forelse($testimonials ?? [] as $testimonial)
                        <div class="item">
                            <div class="testimonials-one__item">
                                <div class="testimonials-one__item__image">
                                    <img src="{{ $testimonial->avatar_url }}" alt="{{ $testimonial->name }}">
                                    <div class="testimonials-one__item__quite">
                                        <i class="icon-quotes"></i>
                                    </div>
                                </div>
                                <div class="testimonials-one__item__content">
                                    <p class="testimonials-one__item__text">{{ $testimonial->text }}</p>
                                    <div class="testimonials-one__item__user">
                                        <h4 class="testimonials-one__item__user__name">{{ $testimonial->name }}</h4>
                                        <p class="testimonials-one__item__user__dec">{{ $testimonial->position }}</p>
                                    </div>
                                </div>
                                <div class="testimonials-one__item__star">
                                    @for($i = 0; $i < $testimonial->rating; $i++)
                                    <i class="icon-star"></i>
                                    @endfor
                                </div>
                            </div>
                        </div>
                        @empty
                        {{-- Fallback static testimonial --}}
                        <div class="item">
                            <div class="testimonials-one__item">
                                <div class="testimonials-one__item__image">
                                    <img src="{{ asset('assets/images/resources/avater-1-1.png') }}" alt="client">
                                    <div class="testimonials-one__item__quite"><i class="icon-quotes"></i></div>
                                </div>
                                <div class="testimonials-one__item__content">
                                    <p class="testimonials-one__item__text">{{ __('home.testimonials.placeholder_text') }}</p>
                                    <div class="testimonials-one__item__user">
                                        <h4 class="testimonials-one__item__user__name">{{ __('home.testimonials.placeholder_name') }}</h4>
                                        <p class="testimonials-one__item__user__dec">{{ __('home.testimonials.placeholder_role') }}</p>
                                    </div>
                                </div>
                                <div class="testimonials-one__item__star">
                                    <i class="icon-star"></i><i class="icon-star"></i><i class="icon-star"></i>
                                    <i class="icon-star"></i><i class="icon-star"></i>
                                </div>
                            </div>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
