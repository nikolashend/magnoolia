{{--
    site-header.blade.php
    Main site header. Fixed, transparent on hero, solid on scroll.
    Phase 2: implement full mobile nav drawer.
--}}
<header class="site-header" role="banner">
    <div class="site-header__inner">

        {{-- Logo --}}
        <a href="{{ route('home') }}" class="site-header__logo" aria-label="{{ config('magnoolia.project.brand_name') }} — {{ __('magnoolia.nav.logo_back_aria') }}">
            @if(config('magnoolia.media.logo_dark'))
                <img src="{{ asset(config('magnoolia.media.logo_dark')) }}"
                     alt="{{ config('magnoolia.project.brand_name') }}"
                     width="140" height="40">
            @else
                <span class="site-header__logo-text">{{ config('magnoolia.project.brand_name') }}</span>
            @endif
        </a>

        {{-- Desktop navigation --}}
        <nav aria-label="{{ __('magnoolia.nav.main_nav_aria') }}" data-nav-menu>
            <ul class="site-header__nav" role="list">
                @foreach(config('magnoolia.navigation', []) as $item)
                    <li>
                        <a href="{{ route('home') }}#{{ $item['id'] }}">
                            {{ __($item['label_key'], [], app()->getLocale()) }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </nav>

        {{-- Right side: language switcher + CTA --}}
        <div class="site-header__right" style="display:flex;align-items:center;gap:var(--space-md);">
            <x-language-switcher />
            {{-- JS: opens inquiry drawer. No-JS: fallback to /kontakt --}}
            <button type="button"
                    class="cta-btn cta-btn--primary cta-btn--sm"
                    data-mg-inquiry-open
                    data-source-component="header_cta"
                    data-mg-analytics="magnoolia_cta_click"
                    style="padding: 0.5rem 1.25rem; font-size: var(--text-xs);">
                {{ __('magnoolia.nav.header_cta') }}
            </button>
            <noscript>
              <a href="{{ lroute('magnoolia.contact') }}"
                 class="cta-btn cta-btn--primary cta-btn--sm"
                 data-mg-inquiry-fallback
                 style="padding: 0.5rem 1.25rem; font-size: var(--text-xs);">
                {{ __('magnoolia.nav.header_cta') }}
              </a>
            </noscript>
        </div>

        {{-- Mobile nav toggle (Phase 2) --}}
        <button class="site-header__mobile-toggle"
                aria-expanded="false"
                aria-controls="mobile-nav"
                data-nav-toggle
                style="display:none;"
                aria-label="{{ __('magnoolia.nav.mobile_menu') }}">
            <span aria-hidden="true">☰</span>
        </button>

    </div>
</header>
