{{--
    logo-intro.blade.php

    Brand wordmark placeholder shown at top of homepage.
    Phase 2 may add a subtle reveal animation.
    Can be disabled by removing <x-logo-intro /> from home.blade.php.
--}}

<div class="logo-intro" aria-hidden="true">
    <div class="logo-intro__wordmark">
        @if(config('magnoolia.media.logo_dark'))
            <img src="{{ asset(config('magnoolia.media.logo_dark')) }}"
                 alt="{{ config('magnoolia.project.brand_name') }}"
                 width="200" height="60"
                 loading="eager">
        @else
            {{ config('magnoolia.project.brand_name') }}
        @endif
    </div>
</div>
