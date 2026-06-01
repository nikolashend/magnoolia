{{-- SOURCE: php-template/parts/home2/about.php | class: about-two --}}
    <section class="about-two" id="about">
        <div class="container">
            <div class="row align-items-end gutter-y-30">
                <div class="col-lg-6">
                    <div class="about-two__thumb hover:shine" style="width:1075px;min-height:839px;">
                        <img src="{{ asset('assets/images/magnoolia/Cam005.0000.jpg') }}" alt="Magnoolia A-energiaklassi kodud Vaela külas" style="width:100%;height:100%;min-height:839px;object-fit:cover;display:block;" loading="lazy" decoding="async">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="about-two__content">
                        <div class="sec-title text-start">
                            <div class="sec-title__top justify-content-start">
                                <span class="line-left"></span>
                                <h6 class="sec-title__tagline bw-split-in-right">{{ __('magnoolia.section.why_tagline') }}</h6>
                            </div>
                            <h3 class="sec-title__title bw-split-in-left">{!! __('magnoolia.section.why_title') !!}</h3>
                        </div>
                        <p class="about-two__text wow fadeInUp" data-wow-duration="1500ms" data-wow-delay="300ms">{{ __('magnoolia.section.why_desc') }}</p>
                        <ul class="about-two__list list-unstyled wow fadeInUp" data-wow-duration="1500ms" data-wow-delay="300ms">
                            <li><div class="about-two__list__item"><i class="icon-house"></i><span>{{ __('magnoolia.facts.rooms') }}</span></div></li>
                            <li><div class="about-two__list__item"><i class="icon-kitchen"></i><span>{{ __('magnoolia.facts.area') }}</span></div></li>
                            <li><div class="about-two__list__item"><i class="icon-bedroom"></i><span>{{ __('magnoolia.section.why_list_yard') }}</span></div></li>
                        </ul>
                        <div class="about-two__text wow fadeInUp" data-wow-duration="1500ms" data-wow-delay="300ms">
                            {{ __('magnoolia.section.why_list_energy') }}. <a href="#hinnad">{{ __('magnoolia.hero.cta_primary') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="about-two__funfact">
            <ul class="about-two__funfact__list list-unstyled">
                <li class="about-two__funfact__item wow fadeInUp" data-wow-duration="1500ms" data-wow-delay="100ms">
                    <div class="about-two__funfact__card">
                        <div class="about-two__funfact__card__icon"><i class="icon-buildings"></i></div>
                        <div class="about-two__funfact__card__content">
                            <h3 class="about-two__funfact__card__count">19</h3>
                            <p class="about-two__funfact__card__text">{{ __('magnoolia.hero.fact_label_homes') }}</p>
                        </div>
                    </div>
                </li>
                <li class="about-two__funfact__item wow fadeInUp" data-wow-duration="1500ms" data-wow-delay="300ms">
                    <div class="about-two__funfact__card">
                        <div class="about-two__funfact__card__icon"><i class="icon-house"></i></div>
                        <div class="about-two__funfact__card__content">
                            <h3 class="about-two__funfact__card__count">A</h3>
                            <p class="about-two__funfact__card__text">{{ __('magnoolia.hero.fact_label_energy') }}</p>
                        </div>
                    </div>
                </li>
                <li class="about-two__funfact__item wow fadeInUp" data-wow-duration="1500ms" data-wow-delay="500ms">
                    <div class="about-two__funfact__card">
                        <div class="about-two__funfact__card__icon"><i class="icon-pin"></i></div>
                        <div class="about-two__funfact__card__content">
                            <h3 class="about-two__funfact__card__count">20 min</h3>
                            <p class="about-two__funfact__card__text">{{ __('magnoolia.hero.fact_label_distance') }}</p>
                        </div>
                    </div>
                </li>
                <li class="about-two__funfact__item about-two__funfact__item--two wow fadeInRight" data-wow-duration="1500ms" data-wow-delay="500ms">
                    <div class="house-solituions">
                        <a href="#" class="house-solituions__icon"><i class="icon-arrow-up"></i></a>
                        <img src="{{ asset('assets/images/shapes/text-round-1-1.png') }}" alt="image" loading="lazy" decoding="async">
                    </div>
                </li>
            </ul>
        </div>
        <div class="about-two__happy-client wow fadeInUp" data-wow-duration="1500ms" data-wow-delay="300ms">
            <div class="about-two__happy-client__thumb">
                <div class="about-two__happy-client__thumb__item"><img src="{{ asset('assets/images/resources/avater-2-1.jpg') }}" alt="image" loading="lazy" decoding="async"><a href="#"><i class="fas fa-plus"></i></a></div>
                <div class="about-two__happy-client__thumb__item"><img src="{{ asset('assets/images/resources/avater-2-2.jpg') }}" alt="image" loading="lazy" decoding="async"><a href="#"><i class="fas fa-plus"></i></a></div>
                <div class="about-two__happy-client__thumb__item"><img src="{{ asset('assets/images/resources/avater-2-3.png') }}" alt="image" loading="lazy" decoding="async"><a href="#"><i class="fas fa-plus"></i></a></div>
                <div class="about-two__happy-client__thumb__item"><img src="{{ asset('assets/images/resources/avater-2-4.png') }}" alt="image" loading="lazy" decoding="async"><a href="#"><i class="fas fa-plus"></i></a></div>
            </div>
            <div class="about-two__happy-client__content">
                <h4 class="about-two__happy-client__title">{{ __('magnoolia.hero.stage_badge') }}</h4>
                <div class="about-two__happy-client__star">
                    <i class="icon-star"></i><i class="icon-star"></i><i class="icon-star"></i><i class="icon-star"></i><i class="icon-star"></i>
                    <span>{{ __('magnoolia.hero.eyebrow') }}</span>
                </div>
            </div>
        </div>
        <div class="about-two__shape wow fadeInLeft" data-wow-duration="1500ms" data-wow-delay="500ms">
            <img src="{{ asset('assets/images/shapes/about-2-2.png') }}" alt="shape" loading="lazy" decoding="async">
        </div>
    </section>
