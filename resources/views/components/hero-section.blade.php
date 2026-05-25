{{--
    hero-section.blade.php

    Full-viewport hero with media background, headline, CTAs, and facts mini-row.

    Phase 1: placeholder visual — dark background with text only.
    Phase 2: add real render/video background.
    Phase 3+: add parallax, final copy, conversion-optimized CTA.

    Props:
    - $headline (string) — main headline (placeholder in Phase 1)
    - $subtext (string) — short description line
    - $ctaPrimaryLabel (string)
    - $ctaPrimaryHref (string)
    - $ctaSecondaryLabel (string)
    - $ctaSecondaryHref (string)
--}}
@props([
    'headline'          => config('magnoolia.project.slogan', 'Kodu, mis kestab'),
    'subtext'           => config('magnoolia.project.location'),
    'ctaPrimaryLabel'   => 'Vaata kodusid',
    'ctaPrimaryHref'    => '#homes',
    'ctaSecondaryLabel' => 'Küsi infot',
    'ctaSecondaryHref'  => '#contact',
])

<section class="hero" aria-label="Avaleht — peamine vaade">

    {{-- Background media --}}
    <div class="hero__media" aria-hidden="true">
        @if(config('magnoolia.media.hero_video'))
            <video
                poster="{{ asset(config('magnoolia.media.hero_poster', '')) }}"
                autoplay muted loop playsinline
                aria-hidden="true"
            >
                <source src="{{ asset(config('magnoolia.media.hero_video')) }}" type="video/mp4">
            </video>
        @elseif(config('magnoolia.media.hero_poster'))
            <img src="{{ asset(config('magnoolia.media.hero_poster')) }}"
                 alt=""
                 loading="eager"
                 fetchpriority="high">
        @else
            {{-- Phase 1 placeholder: neutral dark background --}}
            <div style="width:100%;height:100%;background:linear-gradient(135deg, #1a1714 0%, #2d2925 100%);"></div>
        @endif
    </div>

    <div class="hero__overlay" aria-hidden="true"></div>

    <div class="hero__content">

        <span class="hero__eyebrow">{{ config('magnoolia.project.brand_name') }} · {{ config('magnoolia.project.completion') }}</span>

        <h1 class="hero__headline">{{ $headline }}</h1>

        <p class="hero__subtext">{{ $subtext }}</p>

        <div class="hero__ctas">
            <x-cta-button
                href="{{ $ctaPrimaryHref }}"
                label="{{ $ctaPrimaryLabel }}"
                variant="primary"
                cta-id="hero_primary"
            />
            <x-cta-button
                href="{{ $ctaSecondaryHref }}"
                label="{{ $ctaSecondaryLabel }}"
                variant="secondary"
                style="color:var(--color-off-white);border-color:rgba(245,243,240,0.4);"
                cta-id="hero_secondary"
            />
        </div>

        {{-- Facts mini-row --}}
        <div class="hero__facts">
            <x-facts-bar dark />
        </div>

    </div>

</section>
