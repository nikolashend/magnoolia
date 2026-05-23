<section class="page-header">
    <div class="page-header__bg"
         style="background-image: url({{ asset('assets/images/backgrounds/page-header-bg.jpg') }});"></div>
    <div class="container">
        <div class="page-header__content">
            <h2 class="page-header__title bw-split-in-right">{{ $pageTitle ?? '' }}</h2>
            <ul class="zoomvilla-breadcrumb list-unstyled">
                <li><a href="{{ route('home') }}">{{ __('menu.home') }}</a></li>
                @foreach($breadcrumbs ?? [] as $label => $url)
                    @if($loop->last)
                        <li><span>{{ $label }}</span></li>
                    @else
                        <li><a href="{{ $url }}">{{ $label }}</a></li>
                    @endif
                @endforeach
            </ul>
        </div>
    </div>
</section>
