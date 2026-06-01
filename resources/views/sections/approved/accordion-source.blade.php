{{-- SOURCE: php-template/parts/home2/apartment.php | class: apartment-two section-space --}}
    <section class="apartment-two section-space">
        <div class="container">
            <div class="apartment-two__top">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <div class="sec-title text-start">
                            <div class="sec-title__top justify-content-start">
                                <span class="line-left"></span>
                                <h6 class="sec-title__tagline bw-split-in-right">{{ __('magnoolia.faq_home.eyebrow') }}</h6>
                                <span class=" "></span>
                            </div>
                            <h3 class="sec-title__title bw-split-in-left">{{ __('magnoolia.faq_home.title') }}</h3>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <p class="apartment-two__top__text">{{ __('magnoolia.faq_home.intro') }}</p>
                    </div>
                </div>
            </div>
            <div class="apartment-two__inner wow fadeInUp" data-wow-duration='1500ms' data-wow-delay='300ms'>
                <div class="row gutter-y-30">
                    <div class="col-lg-6">
                        <div class="apartment-two__thumb hover:shine">
                            <img src="{{ asset('assets/images/magnoolia/magnoolia_cam09.jpg') }}" alt="Magnoolia kodu" style="width:533px;height:493px;object-fit:cover;display:block;" loading="lazy" decoding="async">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="faq-accordion zoomvilla-accordion" data-grp-name="zoomvilla-accordion">
                            <div class="apartment-two__content">
                                @foreach(__('magnoolia.faq_home.items') as $fi => $fitem)
                                <div class="accordion {{ $fi === 0 ? 'active' : '' }}">
                                    <div class="accordion-title">
                                        <h4><span class="accordion-title__number"></span>{{ $fitem['q'] }}</h4><span class="accordion-title__icon"><i class="icon-arrow-top"></i></span>
                                    </div>
                                    <div class="accordion-content">
                                        <div class="inner">
                                            <p>{{ $fitem['a'] }}</p>
                                            <ul class="accordion-list list-unstyled">
                                                @foreach($fitem['list'] as $li => $text)
                                                <li><div class="accordion-list__item"><i class="{{ $fitem['icons'][$li] }}"></i><span>{{ $text }}</span></div></li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
