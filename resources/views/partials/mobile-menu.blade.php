{{--
    mobile-menu.blade.php
    Phase 27: Full-feature Magnoolia mobile navigation drawer.
    Overlay is OUTSIDE the nav container to avoid backdrop-filter covering the drawer.
--}}

{{-- Overlay: sits behind the drawer, above the page --}}
<div class="mg-mobile-nav__overlay" id="mg-mobile-nav-overlay" onclick="mgMobileNavClose()" aria-hidden="true"></div>

{{-- Drawer --}}
<div class="mg-mobile-nav" id="mg-mobile-nav" aria-hidden="true" role="dialog" aria-modal="true" aria-label="{{ __('magnoolia.nav.mobile_menu') }}">

    {{-- Header --}}
    <div class="mg-mobile-nav__header">
        <a href="{{ route('home') }}" class="mg-mobile-nav__logo" onclick="mgMobileNavClose()" style="display:flex;align-items:center;">
            @if(file_exists(public_path('assets/magnoolia/logos/magnoolia-logo2-mob-2x.webp')))
                <img src="{{ asset('assets/magnoolia/logos/magnoolia-logo2-mob-2x.webp') }}"
                     alt="Magnoolia Kodud"
                     width="274" height="88"
                     loading="eager"
                     decoding="async"
                     style="height:40px;width:auto;display:block;object-fit:contain;">
            @else
                {{ config('magnoolia.project.brand_name') }}
            @endif
        </a>
        <button type="button" class="mg-mobile-nav__close" onclick="mgMobileNavClose()" aria-label="{{ __('magnoolia.nav.close') }}">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
    </div>

    {{-- Primary CTA --}}
    <div class="mg-mobile-nav__cta-primary">
        <button type="button"
                class="mg-mobile-nav__cta-btn"
                data-mg-inquiry-open
                data-source-component="mobile_nav_cta"
                data-mg-analytics="magnoolia_cta_click"
                onclick="mgMobileNavClose()">
            {{ __('magnoolia.nav.header_cta') }}
        </button>
        <noscript>
            <a href="{{ lroute('magnoolia.contact') }}#kontaktivorm" class="mg-mobile-nav__cta-btn">
                {{ __('magnoolia.nav.header_cta') }}
            </a>
        </noscript>
    </div>

    {{-- Navigation links --}}
    <nav aria-label="{{ __('magnoolia.nav.mobile_nav_label') }}" class="mg-mobile-nav__links">
        <ul role="list">
            {{-- Phase 35: same structure as the desktop top menu --}}
            <li><a href="{{ route('home') }}" onclick="mgMobileNavClose()">{{ __('magnoolia.nav.about') }}</a></li>
            <li><a href="{{ lroute('magnoolia.location') }}" onclick="mgMobileNavClose()">{{ __('magnoolia.nav.location') }}</a></li>
            <li><a href="{{ lroute('magnoolia.galerii') }}" onclick="mgMobileNavClose()">{{ __('magnoolia.nav.gallery') }}</a></li>
            <li><a href="{{ lroute('magnoolia.homes') }}" onclick="mgMobileNavClose()">{{ __('magnoolia.nav.homes') }}</a></li>
            <li><a href="{{ lroute('magnoolia.arhitektuur') }}" onclick="mgMobileNavClose()">{{ __('magnoolia.nav.architecture') }}</a></li>
            <li><a href="{{ lroute('magnoolia.sisedisain') }}" onclick="mgMobileNavClose()">{{ __('magnoolia.nav.interior') }}</a></li>
            <li><a href="{{ lroute('magnoolia.construction') }}" onclick="mgMobileNavClose()">{{ __('magnoolia.nav.building') }}</a></li>
            <li><a href="{{ lroute('magnoolia.developer') }}" onclick="mgMobileNavClose()">{{ __('magnoolia.nav.developer') }}</a></li>
            <li><a href="{{ lroute('magnoolia.contact') }}" onclick="mgMobileNavClose()">{{ __('magnoolia.nav.contact') }}</a></li>
        </ul>
    </nav>

    {{-- Diana contact --}}
    <div class="mg-mobile-nav__contact">
        <div class="mg-mobile-nav__contact-label">
            {{ __('magnoolia.contact.sales_label') }}
        </div>
        <a href="tel:{{ config('magnoolia.project.contact_phone') }}" class="mg-mobile-nav__contact-phone">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.31h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 9a16 16 0 0 0 6 6l1.27-.93a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7a2 2 0 0 1 1.72 2.01z"/></svg>
            +372 58 16 40 78
        </a>
    </div>

    {{-- Language switcher --}}
    <div class="mg-mobile-nav__lang">
        <x-language-switcher />
    </div>

</div>

<script>
(function () {
    function mgMobileNavOpen() {
        var nav     = document.getElementById('mg-mobile-nav');
        var overlay = document.getElementById('mg-mobile-nav-overlay');
        if (nav) {
            nav.classList.add('is-open');
            nav.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
        }
        if (overlay) overlay.classList.add('is-active');
        document.querySelectorAll('[data-nav-toggle]').forEach(function (btn) {
            btn.setAttribute('aria-expanded', 'true');
        });
    }
    function mgMobileNavClose() {
        var nav     = document.getElementById('mg-mobile-nav');
        var overlay = document.getElementById('mg-mobile-nav-overlay');
        if (nav) {
            nav.classList.remove('is-open');
            nav.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
        }
        if (overlay) overlay.classList.remove('is-active');
        document.querySelectorAll('[data-nav-toggle]').forEach(function (btn) {
            btn.setAttribute('aria-expanded', 'false');
        });
    }
    window.mgMobileNavOpen  = mgMobileNavOpen;
    window.mgMobileNavClose = mgMobileNavClose;

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[data-nav-toggle]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var nav = document.getElementById('mg-mobile-nav');
                if (nav && nav.classList.contains('is-open')) {
                    mgMobileNavClose();
                } else {
                    mgMobileNavOpen();
                }
            });
        });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') mgMobileNavClose();
        });
    });
})();
</script>
