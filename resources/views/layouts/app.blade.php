<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- SEO: meta, OG, Twitter, hreflang, canonical --}}
    @include('partials.seo.meta')

    <!-- Favicon Icons -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/images/favicons/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/images/favicons/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/images/favicons/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('assets/images/favicons/site.webmanifest') }}">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Vendor CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendors/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/bootstrap-select/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/animate/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/owl-carousel/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/owl-carousel/css/owl.theme.default.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/slick/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/jquery-ui/jquery-ui.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/jquery-magnific-popup/jquery.magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/nouislider/nouislider.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/nouislider/nouislider.pips.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/zoomvilla-icons/style.css') }}">

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/zoomvilla.css') }}">

    <!-- Magnoolia Design System -->
    <link rel="stylesheet" href="{{ asset('assets/css/magnoolia.css') }}?v={{ filemtime(public_path('assets/css/magnoolia.css')) }}">

    {{-- LCP hero preload (homepage only) --}}
    @if(request()->routeIs('home'))
    <link rel="preload" as="image"
          href="{{ asset('assets/images/magnoolia/Cam001.0000.jpg') }}"
          imagesrcset="{{ asset('assets/images/magnoolia/Cam001.0000.jpg') }}"
          fetchpriority="high">
    @endif

    {{-- Reduced motion: respect user OS preference --}}
    <style>
    @media (prefers-reduced-motion: reduce) {
      *, *::before, *::after {
        animation-duration: 0.001ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.001ms !important;
        scroll-behavior: auto !important;
      }
    }
    </style>

    @stack('styles')
    @stack('head')
</head>

<body class="@yield('body_class', 'custom-cursor')">

    {{-- Preloader & cursor --}}
    @include('partials.preloader')

    {{-- Scroll to top --}}
    @include('partials.scroll-top')

    <div class="page-wrapper">

        {{-- Header --}}
        @include('partials.header')

        {{-- Page Content --}}
        @yield('content')

        {{-- Footer --}}
        @include('partials.footer')

    </div><!-- /.page-wrapper -->

    {{-- Mobile sticky CTA --}}
    @include('partials.mobile-cta')

    {{-- Unit detail modal (Phase 7) --}}
    @include('partials.unit-modal')

    {{-- Phase 26: Inquiry drawer --}}
    <x-magnoolia.inquiry-drawer />

    {{-- Mobile navigation drawer --}}
    @include('partials.mobile-menu')

    {{-- Search Popup --}}
    @include('partials.search-popup')

    <!-- Vendor JS -->
    <script src="{{ asset('assets/vendors/jquery/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/bootstrap-select/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/jquery-ui/jquery-ui.js') }}"></script>
    <script src="{{ asset('assets/vendors/jquery-ajaxchimp/jquery.ajaxchimp.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/jquery-appear/jquery.appear.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/jquery-circle-progress/jquery.circle-progress.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/jquery-magnific-popup/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/jquery-validate/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/nouislider/nouislider.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/wnumb/wNumb.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/owl-carousel/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/owl-carousel/js/owlcarousel2-filter.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/wow/wow.js') }}"></script>
    <script src="{{ asset('assets/vendors/imagesloaded/imagesloaded.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/isotope/isotope.js') }}"></script>
    <script src="{{ asset('assets/vendors/slick/slick.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/countdown/countdown.min.js') }}"></script>

    <!-- GSAP -->
    <script src="{{ asset('assets/vendors/gsap/gsap.js') }}"></script>
    <script src="{{ asset('assets/vendors/gsap/scrolltrigger.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/gsap/splittext.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/gsap/zoomvilla-split.js') }}"></script>

    <!-- Template JS -->
    <script src="{{ asset('assets/js/zoomvilla.js') }}"></script>

    @stack('scripts')

    {{-- Magnoolia dataLayer tracking bridge --}}
    <script>
    window.dataLayer = window.dataLayer || [];
    document.addEventListener('click', function(e) {
        var target = e.target.closest('[data-event]');
        if (!target) return;
        window.dataLayer.push({
            event:       target.dataset.event,
            page_locale: document.documentElement.lang || null,
            page_url:    window.location.href,
            unit_id:     target.dataset.unitId  || null,
            unit:        target.dataset.unit    || null,
            locale:      target.dataset.locale  || null,
            cta:         target.textContent.trim().slice(0, 80)
        });
    });
    // Form events
    (function() {
        var form = document.querySelector('form[data-event="contact_form_start"]');
        if (!form) return;
        var sent = false;
        form.addEventListener('focusin', function() {
            if (sent) return; sent = true;
            window.dataLayer.push({ event: 'contact_form_start', page_url: window.location.href });
        });
        form.addEventListener('submit', function() {
            window.dataLayer.push({ event: 'contact_form_submit', page_url: window.location.href });
        });
    })();
    </script>

    {{-- Schema JSON-LD --}}
    @include('partials.seo.schema')

    @if(app()->getLocale() !== 'et')
    <script>
    (function() {
        var locale = '{{ app()->getLocale() }}';
        document.addEventListener('click', function(e) {
            var a = e.target.closest('a[href]');
            if (!a) return;
            var href = a.getAttribute('href');
            if (!href || href.charAt(0) !== '/' || href.indexOf('//') === 0) return;
            if (/^\/(ru|en)(\/?$|\/)/.test(href)) return; // already prefixed
            e.preventDefault();
            window.location.href = '/' + locale + href;
        });
    })();
    </script>
    @endif
</body>

</html>
