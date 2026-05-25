{{--
    cta-button.blade.php

    Props:
    - $href (string) — link destination
    - $label (string) — button text
    - $variant (string) — 'primary' | 'secondary'  (default: primary)
    - $size (string) — 'normal' | 'small'  (default: normal)
    - $type (string) — 'a' | 'button'  (default: a)
    - $ctaId (string) — tracking ID placeholder for Phase 8

    Future phases will wire $ctaId to GA4 / Google Ads events.
    Do NOT add real tracking code in Phase 1.
--}}
@props([
    'href'    => '#',
    'label'   => 'Küsi infot',
    'variant' => 'primary',
    'size'    => 'normal',
    'type'    => 'a',
    'ctaId'   => '',
])

@php
    $classes  = 'cta-btn cta-btn--' . $variant . ($size === 'small' ? ' cta-btn--sm' : '');
    $inlStyle = $size === 'small' ? 'padding: 0.5rem 1.25rem; font-size: var(--text-xs);' : null;
    $trackJs  = $ctaId ? "window.Magnoolia?.trackCta('" . $ctaId . "', {label: '" . addslashes($label) . "'})" : null;
@endphp

@if($type === 'button')
    <button
        {{ $attributes->merge(['class' => $classes]) }}
        @if($inlStyle) style="{{ $inlStyle }}" @endif
        @if($ctaId) data-cta-id="{{ $ctaId }}" @endif
        @if($trackJs) onclick="{{ $trackJs }}" @endif
    >{{ $label }}</button>
@else
    <a
        href="{{ $href }}"
        {{ $attributes->merge(['class' => $classes]) }}
        @if($inlStyle) style="{{ $inlStyle }}" @endif
        @if($ctaId) data-cta-id="{{ $ctaId }}" @endif
        @if($trackJs) onclick="{{ $trackJs }}" @endif
    >{{ $label }}</a>
@endif
