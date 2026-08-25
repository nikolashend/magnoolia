{{-- SOURCE: php-template/parts/home1/city-house.php | class: city-house section-space --}}
    <section class="city-house section-space" id="property">
        <div class="city-house__top">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <div class="sec-title text-start">
                            <div class="sec-title__top justify-content-start">
                                <span class="line-left"></span>
                                <h6 class="sec-title__tagline bw-split-in-right">{{ __('magnoolia.section.gallery_eyebrow') }}</h6>
                            </div>
                            <h3 class="sec-title__title bw-split-in-left">{{ __('magnoolia.section.gallery_title') }}</h3>
                        </div>
                    </div>
                    <div class="col-lg-4"><div class="city-house__custome-navs text-end"></div></div>
                </div>
            </div>
        </div>
        <div class="city-house__bottom wow fadeInUp" data-wow-duration="1500ms" data-wow-delay="300ms">
            <div class="container-fluid">
                <div class="city-house__carousel zoomvilla-owl__carousel zoomvilla-owl__carousel--basic-nav owl-carousel owl-theme" data-owl-options='{"items":1,"margin":0,"loop":true,"smartSpeed":700,"navContainer":".city-house__custome-navs","nav":true,"dots":true,"navText":["<span class=\"icon-angle-small-right\"><\/span>","<span class=\"icon-angle-small-right\"><\/span>"],"autoplay":true,"autoplayHoverPause":true,"responsive":{"0":{"items":1},"576":{"items":2},"992":{"items":3},"1466":{"items":4}}}'>
                    @php
                        // Phase 36 Module C — the published list wins; with nothing
                        // published mg_list() returns [] and the shipped array below
                        // is used unchanged.
                        $mgSizes = '(max-width:575px) 100vw, (max-width:991px) 50vw, (max-width:1465px) 33vw, 25vw';
                        $mgCards = [];
                        foreach (mg_list('home.gallery_cards') as $row) {
                            $mgCards[] = [
                                'attrs' => mg_img_path($row['image_path'] ?? '', $mgSizes),
                                'alt'   => $row['image_alt'] ?? ($row['alt'] ?? ''),
                                'title' => $row['title'] ?? '',
                                'f'     => array_values(array_filter([
                                    ['i' => $row['cap1_icon'] ?? '', 'v' => $row['cap1'] ?? ''],
                                    ['i' => $row['cap2_icon'] ?? '', 'v' => $row['cap2'] ?? ''],
                                    ['i' => $row['cap3_icon'] ?? '', 'v' => $row['cap3'] ?? ''],
                                ], fn ($f) => filled($f['v']))),
                            ];
                        }
                        if ($mgCards === []) {
                            foreach (__('magnoolia.section.gallery_cards') as $gc) {
                                $mgCards[] = [
                                    'attrs' => mg_img($gc['img'], $mgSizes),
                                    'alt'   => $gc['alt'],
                                    'title' => $gc['title'],
                                    'f'     => $gc['f'],
                                ];
                            }
                        }
                    @endphp
                    @foreach($mgCards as $gc)
                    <div class="item"><div class="city-house__card">
                        <div class="city-house__card__image"><img {!! $gc['attrs'] !!} alt="{{ $gc['alt'] }}" loading="lazy" decoding="async"><div class="city-house__card__popup"><a href="{{ lroute('home') }}#hinnad">{{ __('magnoolia.section.gallery_view') }}</a></div></div>
                        <div class="city-house__card__content"><span class="city-house__card__number">{{ str_pad($loop->index + 1, 2, '0', STR_PAD_LEFT) }}</span><div class="city-house__card__hover"></div><h4 class="city-house__card__title"><a href="{{ lroute('home') }}#hinnad">{{ $gc['title'] }}</a></h4><ul class="city-house__card__list list-unstyled">@foreach($gc['f'] as $feat)<li class="city-house__card__list__item"><i class="{{ $feat['i'] }}"></i><span>{{ $feat['v'] }}</span></li>@endforeach</ul></div>
                    </div></div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
