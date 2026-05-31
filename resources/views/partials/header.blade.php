<header class="main-header main-header--two sticky-header sticky-header--normal">
    <div class="main-header__group-shape"><span></span><span></span><span></span></div>
    <div class="container-fluid">
        <div class="main-header__inner">
            <div class="main-header__logo logo-retina">
                <a href="{{ lroute('home') }}" aria-label="Magnoolia koduleht" style="text-decoration:none;">
                    <span style="font-size:22px;font-weight:700;color:#1E1F24;letter-spacing:0.05em;line-height:1;">Magnoolia</span>
                </a>
            </div>
            <div class="main-header__middle">
                <nav class="main-header__nav main-menu">
                    <ul class="main-menu__list">
                        <li class="{{ request()->routeIs('home', '*.home') ? 'current' : '' }}">
                            <a href="{{ lroute('home') }}">{{ __('magnoolia.nav.home') }}</a>
                        </li>
                        <li class="{{ request()->routeIs('magnoolia.homes', '*.magnoolia.homes') ? 'current' : '' }}">
                            <a href="{{ lroute('magnoolia.homes') }}">{{ __('magnoolia.nav.homes') }}</a>
                        </li>
                        <li class="{{ request()->routeIs('magnoolia.site-plan', '*.magnoolia.site-plan') ? 'current' : '' }}">
                            <a href="{{ lroute('magnoolia.site-plan') }}">{{ __('magnoolia.nav.masterplan') }}</a>
                        </li>
                        <li class="{{ request()->routeIs('magnoolia.location', '*.magnoolia.location') ? 'current' : '' }}">
                            <a href="{{ lroute('magnoolia.location') }}">{{ __('magnoolia.nav.location') }}</a>
                        </li>
                        <li class="{{ request()->routeIs('magnoolia.construction', '*.magnoolia.construction') ? 'current' : '' }}">
                            <a href="{{ lroute('magnoolia.construction') }}">{{ __('magnoolia.nav.building') }}</a>
                        </li>
                        <li class="{{ request()->routeIs('magnoolia.contact', '*.magnoolia.contact') ? 'current' : '' }}">
                            <a href="{{ lroute('magnoolia.contact') }}">{{ __('magnoolia.nav.contact') }}</a>
                        </li>
                    </ul>
                </nav>
                <div class="main-header__middle__right">
                    <div class="main-header__info">
                        <div style="display:flex;gap:8px;align-items:center;">
                            @foreach(['et' => 'ET', 'ru' => 'RU', 'en' => 'EN'] as $locale => $label)
                                <a href="{{ locale_url($locale) }}"
                                   style="color:{{ app()->getLocale() === $locale ? '#CDA274' : '#1E1F24' }};font-size:12px;font-weight:600;text-decoration:none;opacity:{{ app()->getLocale() === $locale ? '1' : '0.5' }};">{{ $label }}</a>
                            @endforeach
                        </div>
                    </div>
                    <div class="main-header__btn">
                        <a href="{{ lroute('magnoolia.contact') }}" class="zoomvilla-btn">{{ __('magnoolia.contact.cta_inquiry') }}</a>
                    </div>
                    <div class="mobile-nav__btn mobile-nav__toggler">
                        <span></span><span></span><span></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>