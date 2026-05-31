<header class="main-header main-header--two sticky-header sticky-header--normal{{ request()->routeIs('home', '*.home') ? '' : ' mg-header--inner' }}">
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
                        @php
                            $navItems = \Illuminate\Support\Facades\Cache::remember('nav_items_active', 300, fn() =>
                                \App\Models\NavItem::active()->ordered()->get()
                            );
                            $currentRoute = request()->route()?->getName() ?? '';
                        @endphp
                        @foreach($navItems as $item)
                            @php
                                $isCurrent = $item->route_name && (
                                    $currentRoute === $item->route_name ||
                                    $currentRoute === 'ru.' . $item->route_name ||
                                    $currentRoute === 'en.' . $item->route_name
                                );
                            @endphp
                            <li class="{{ $isCurrent ? 'current' : '' }}">
                                <a href="{{ $item->getHref() }}"
                                   @if($item->open_blank) target="_blank" rel="noopener" @endif>
                                    {{ $item->getLabel() }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </nav>
                <div class="main-header__middle__right">
                    <div class="main-header__info">
                        @php
                            $activeLocales = \App\Services\LanguageSettingsService::activeLocales();
                            $localeLabels  = ['et' => 'ET', 'ru' => 'RU', 'en' => 'EN'];
                        @endphp
                        <div style="display:flex;gap:8px;align-items:center;">
                            @foreach($localeLabels as $locale => $label)
                                @if(in_array($locale, $activeLocales))
                                    <a href="{{ locale_url($locale) }}"
                                       style="color:{{ app()->getLocale() === $locale ? '#CDA274' : '#1E1F24' }};font-size:12px;font-weight:600;text-decoration:none;opacity:{{ app()->getLocale() === $locale ? '1' : '0.5' }};">{{ $label }}</a>
                                @endif
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