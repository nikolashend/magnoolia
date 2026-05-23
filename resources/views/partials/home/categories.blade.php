<section class="catagory-area section-space-bottom">
    <div class="container">
        <div class="row gutter-y-30">
            @php
            $categories = [
                ['icon' => 'icon-kitchen', 'title_key' => 'home.categories.kitchen'],
                ['icon' => 'icon-bedroom', 'title_key' => 'home.categories.bedroom'],
                ['icon' => 'icon-restaurant', 'title_key' => 'home.categories.restaurant'],
                ['icon' => 'icon-bath', 'title_key' => 'home.categories.bathroom'],
            ];
            $delays = [100, 300, 500, 700];
            @endphp

            @foreach($categories as $index => $cat)
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 wow fadeInUp"
                 data-wow-duration="1500ms" data-wow-delay="{{ $delays[$index] }}ms">
                <div class="catagory-item">
                    <div class="catagory-item__icon">
                        <i class="{{ $cat['icon'] }}"></i>
                    </div>
                    <div class="catagory-item__content">
                        <span class="catagory-item__number"></span>
                        <h3 class="catagory-item__title">{!! nl2br(__($cat['title_key'])) !!}</h3>
                    </div>
                    <span class="catagory-item__shape"></span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
