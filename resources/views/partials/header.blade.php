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
                            $fallbackNavItems = collect([
                                [
                                    'label' => __('magnoolia.nav.about'),
                                    'href' => lroute('home') . '#about',
                                    'route_name' => 'home',
                                    'open_blank' => false,
                                ],
                                [
                                    'label' => __('magnoolia.nav.homes'),
                                    'href' => lroute('magnoolia.homes'),
                                    'route_name' => 'magnoolia.homes',
                                    'open_blank' => false,
                                ],
                                [
                                    'label' => __('magnoolia.nav.masterplan'),
                                    'href' => lroute('magnoolia.site-plan'),
                                    'route_name' => 'magnoolia.site-plan',
                                    'open_blank' => false,
                                ],
                                [
                                    'label' => __('magnoolia.nav.building'),
                                    'href' => lroute('magnoolia.construction'),
                                    'route_name' => 'magnoolia.construction',
                                    'open_blank' => false,
                                ],
                                [
                                    'label' => __('magnoolia.nav.contact'),
                                    'href' => lroute('magnoolia.contact'),
                                    'route_name' => 'magnoolia.contact',
                                    'open_blank' => false,
                                ],
                            ]);

                            try {
                                $navItems = \Illuminate\Support\Facades\Cache::remember('nav_items_active', 300, fn() =>
                                    \Illuminate\Support\Facades\Schema::hasTable('nav_items')
                                        ? \App\Models\NavItem::active()->ordered()->get()
                                        : collect()
                                );
                            } catch (\Throwable $e) {
                                $navItems = collect();
                            }

                            if ($navItems->isEmpty()) {
                                $navItems = $fallbackNavItems;
                            }

                            $currentRoute = request()->route()?->getName() ?? '';
                        @endphp
                        @foreach($navItems as $item)
                            @php
                                $routeName = is_array($item) ? ($item['route_name'] ?? null) : $item->route_name;
                                $href = is_array($item) ? ($item['href'] ?? '#') : $item->getHref();
                                $label = is_array($item) ? ($item['label'] ?? '') : $item->getLabel();
                                $openBlank = is_array($item) ? (bool) ($item['open_blank'] ?? false) : (bool) $item->open_blank;

                                $isCurrent = $routeName && (
                                    $currentRoute === $routeName ||
                                    $currentRoute === 'ru.' . $routeName ||
                                    $currentRoute === 'en.' . $routeName
                                );
                            @endphp
                            <li class="{{ $isCurrent ? 'current' : '' }}">
                                <a href="{{ $href }}"
                                   @if($openBlank) target="_blank" rel="noopener" @endif>
                                    {{ $label }}
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
                                       data-event="lang_switch" data-locale="{{ $locale }}"
                                       style="color:{{ app()->getLocale() === $locale ? '#CDA274' : '#1E1F24' }};font-size:12px;font-weight:600;text-decoration:none;opacity:{{ app()->getLocale() === $locale ? '1' : '0.5' }};">{{ $label }}</a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <div class="main-header__btn">
                        {{-- Phase 27: opens inquiry drawer (JS). No-JS: fallback to /kontakt --}}
                        <button type="button"
                                class="zoomvilla-btn"
                                data-mg-inquiry-open
                                data-source-component="header_cta"
                                data-mg-analytics="magnoolia_cta_click">
                            {{ __('magnoolia.contact.cta_inquiry') }}
                        </button>
                        <noscript>
                            <a href="{{ lroute('magnoolia.contact') }}#kontaktivorm"
                               class="zoomvilla-btn"
                               data-mg-inquiry-fallback>
                                {{ __('magnoolia.contact.cta_inquiry') }}
                            </a>
                        </noscript>
                    </div>
                    <div class="mobile-nav__btn"
                         data-nav-toggle
                         role="button"
                         aria-label="{{ __('magnoolia.nav.mobile_menu') }}"
                         aria-expanded="false"
                         aria-controls="mg-mobile-nav">
                        <span></span><span></span><span></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>