<header class="main-header main-header--one sticky-header sticky-header--normal">
    <div class="topbar">
        <div class="container">
            <div class="topbar__inner">
                <p class="topbar__info">
                    <span>{{ __('common.opening_hours_label') }}:</span> {{ __('common.opening_hours') }}
                </p>
                <div class="topbar__right">
                    <ul class="topbar__list list-unstyled">
                        <li class="topbar__list__item"><a href="{{ route('login') }}">{{ __('common.login') }}</a></li>
                        <li class="topbar__list__item"><a href="{{ route('register') }}">{{ __('common.register') }}</a></li>
                        <li class="topbar__list__item"><a href="{{ route('contact') }}">{{ __('common.help') }}</a></li>
                    </ul>

                    {{-- Language Switcher --}}
                    <div class="topbar__lang">
                        @foreach (['et' => 'EST', 'en' => 'ENG', 'ru' => 'RUS'] as $locale => $label)
                            <a href="{{ route('locale.switch', $locale) }}"
                               class="{{ app()->getLocale() === $locale ? 'active' : '' }}">{{ $label }}</a>
                        @endforeach
                    </div>

                    <div class="topbar__social">
                        <a href="https://facebook.com"><i class="fab fa-facebook-f" aria-hidden="true"></i></a>
                        <a href="https://x.com"><i class="fab fa-twitter" aria-hidden="true"></i></a>
                        <a href="https://linkedin.com"><i class="fab fa-linkedin-in" aria-hidden="true"></i></a>
                        <a href="https://google.com"><i class="fab fa-google" aria-hidden="true"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="main-header__bottom">
        <div class="container-fluid">
            <div class="main-header__inner">
                <div class="main-header__logo logo-retina">
                    <a href="{{ route('home') }}">
                        <img src="{{ asset('assets/images/logo-dark.png') }}" alt="{{ config('app.name') }}" width="327" height="68">
                    </a>
                </div>
                <div class="main-header__middle">
                    <nav class="main-header__nav main-menu">
                        @include('partials.menu')
                    </nav>
                    <div class="main-header__middle__right">
                        <div class="main-header__info">
                            <a href="javascript:void(0);" class="main-header__info__item search-toggler">
                                <i class="icon-search"></i>
                            </a>
                        </div>
                        <div class="main-header__btn">
                            <a href="{{ route('contact') }}" class="zoomvilla-btn">
                                {{ __('common.contact_us') }}<i class="icon-angle-small-right"></i>
                            </a>
                        </div>
                        <div class="mobile-nav__btn mobile-nav__toggler">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </div>
                </div>
                <div class="main-header__right">
                    <a href="javascript:void(0)" class="main-header__laptop-menu header-right-sidebar__toggler">
                        <i class="icon-laptop-menu" aria-hidden="true"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="main-header__shape">
        <img src="{{ asset('assets/images/shapes/header-top.png') }}" alt="shape">
    </div>
</header>

@include('partials.mobile-menu')
