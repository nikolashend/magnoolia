<header class="main-header main-header--two sticky-header sticky-header--normal">
    <div class="main-header__group-shape"><span></span><span></span><span></span></div>
    <div class="container-fluid">
        <div class="main-header__inner">
            <div class="main-header__logo logo-retina">
                <a href="{{ route('home') }}" aria-label="Magnoolia koduleht" style="text-decoration:none;">
                    <span style="font-size:22px;font-weight:700;color:#1E1F24;letter-spacing:0.05em;line-height:1;">Magnoolia</span>
                </a>
            </div>
            <div class="main-header__middle">
                <nav class="main-header__nav main-menu">
                    <ul class="main-menu__list">
                        <li class="{{ request()->routeIs('home*') ? 'current' : '' }}">
                            <a href="{{ route('home') }}">Avaleht</a>
                        </li>
                        <li class="{{ request()->routeIs('home*') ? 'current' : '' }}">
                            <a href="{{ route('home') }}#hinnad">Kodud ja hinnad</a>
                        </li>
                        <li><a href="{{ route('home') }}#asendiplaan">Asendiplaan</a></li>
                        <li><a href="{{ route('home') }}#asukoht">Asukoht</a></li>
                        <li><a href="{{ route('home') }}#ehitusinfo">Ehitusinfo</a></li>
                        <li>
                            <a href="{{ route('home') }}#kontakt">Kontakt</a>
                        </li>
                    </ul>
                </nav>
                <div class="main-header__middle__right">
                    <div class="main-header__info">
                        <div style="display:flex;gap:8px;align-items:center;">
                            @foreach(['et' => 'ET', 'ru' => 'RU', 'en' => 'EN'] as $locale => $label)
                                <a href="{{ route('locale.switch', $locale) }}"
                                   style="color:{{ app()->getLocale() === $locale ? '#CDA274' : '#1E1F24' }};font-size:12px;font-weight:600;text-decoration:none;opacity:{{ app()->getLocale() === $locale ? '1' : '0.5' }};">{{ $label }}</a>
                            @endforeach
                        </div>
                    </div>
                    <div class="main-header__btn">
                        <a href="{{ route('home') }}#kontakt" class="zoomvilla-btn">Küsi pakkumist</a>
                    </div>
                    <div class="mobile-nav__btn mobile-nav__toggler">
                        <span></span><span></span><span></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>