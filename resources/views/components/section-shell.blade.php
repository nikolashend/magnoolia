{{--
    section-shell.blade.php

    Reusable section wrapper with consistent spacing and structure.
    All content sections of the homepage should use this as their outer wrapper.

    Props:
    - $id (string) — anchor ID for navigation
    - $eyebrow (string) — small label above title
    - $title (string) — section heading
    - $description (string) — optional sub-paragraph
    - $theme (string) — 'light' | 'alt' | 'dark'  (default: light)
    - $centered (bool) — center-align text  (default: false)
    - $narrow (bool) — use narrow container  (default: false)
--}}
@props([
    'id'          => '',
    'eyebrow'     => '',
    'title'       => '',
    'description' => '',
    'theme'       => 'light',
    'centered'    => false,
    'narrow'      => false,
])

@php
    $sectionClass = 'section-shell section--' . $theme;
    $innerClass   = 'section-shell__inner' . ($narrow ? ' container--narrow' : '');
    $textAlign    = $centered ? 'text-align:center;' : '';
@endphp

<section
    @if($id) id="{{ $id }}" @endif
    class="{{ $sectionClass }}"
>
    <div class="{{ $innerClass }}" style="{{ $textAlign }}">

        @if($eyebrow)
            <span class="section-shell__eyebrow">{{ $eyebrow }}</span>
        @endif

        @if($title)
            <h2 class="section-shell__title">{{ $title }}</h2>
        @endif

        @if($description)
            <p class="section-shell__description" @if($centered) style="margin-left:auto;margin-right:auto;" @endif>
                {{ $description }}
            </p>
        @endif

        {{ $slot }}

    </div>
</section>
