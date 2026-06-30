{{-- SOURCE: php-template/parts/home1/about.php | class: about-one section-space --}}
    <section class="about-one section-space" id="why-magnoolia" style="background-color:#ffffff;">
        <div class="container">
            <div class="row gutter-y-50">
                <div class="col-lg-6 wow fadeInLeft" data-wow-duration="1500ms" data-wow-delay="300ms">
                    <div class="about-one__image">
                        <div class="about-one__image__item">
                            <div class="about-one__image__item__inner hover:shine" style="overflow:hidden;width:409px;height:480px;">
                                <img {!! mg_img('Cam004.0000.jpg', '410px') !!} alt="Magnoolia ridaelamud välisvaade" class="about-one__image__one" style="width:409px;height:480px;object-fit:cover;object-position:center;display:block;" loading="lazy" decoding="async">
                            </div>
                            <div class="about-one__experience wow fadeInUp" data-wow-duration="1500ms" data-wow-delay="300ms">
                                <h3 class="about-one__experience__title">{{ __('magnoolia.section.why_completion') }}</h3>
                            </div>
                        </div>
                        <div class="about-one__image__item-two hover:shine">
                            <div class="about-one__image__icon">
                                <img src="{{ asset('assets/images/shapes/house-1-1.png') }}" alt="" loading="lazy" decoding="async">
                            </div>
                            <img {!! mg_img('Interior 4.jpg', '340px') !!} alt="Magnoolia sisevaade" class="about-one__image__one" style="width:336px;height:429px;object-fit:cover;display:block;" loading="lazy" decoding="async">
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="about-one__content">
                        <div class="sec-title text-start">
                            <div class="sec-title__top justify-content-start">
                                <span class="line-left"></span>
                                <h6 class="sec-title__tagline bw-split-in-right">{{ __('magnoolia.section.why_eyebrow') }}</h6>
                            </div>
                            <h3 class="sec-title__title bw-split-in-left">{!! __('magnoolia.section.about_title') !!}</h3>
                        </div>
                        <p class="about-one__text wow fadeInUp" data-wow-duration="1500ms" data-wow-delay="300ms">{{ __('magnoolia.section.why_body2') }}</p>
                        <div class="about-one__features">
                            <ul class="about-one__features__list list-unstyled">
                                <li class="wow fadeInUp" data-wow-duration="1500ms"><i class="icon-check-star"></i><span>{{ __('magnoolia.section.why_list_energy') }}</span></li>
                                <li class="wow fadeInUp" data-wow-duration="1600ms"><i class="icon-check-star"></i><span>{{ __('magnoolia.section.why_list_yard') }}</span></li>
                                <li class="wow fadeInUp" data-wow-duration="1700ms"><i class="icon-check-star"></i><span>{{ __('magnoolia.section.why_list_ev') }}</span></li>
                            </ul>
                        </div>
                        <div class="about-one__btn">
                            <a href="#hinnad" class="zoomvilla-btn">{{ __('magnoolia.section.why_cta') }} <i class="icon-angle-small-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="about-one__shape">
            <img src="{{ asset('assets/images/shapes/about-shape-1-1.png') }}" alt="" loading="lazy" decoding="async">
        </div>
    </section>
