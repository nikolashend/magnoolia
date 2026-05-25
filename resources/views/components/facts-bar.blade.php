{{--
    facts-bar.blade.php

    Renders key project facts from config/magnoolia.php.
    Used inside hero-section and optionally as a standalone section.

    Props:
    - $dark (bool) — use light text for dark backgrounds (default: false)
--}}
@props(['dark' => false])

@php
    $facts = config('magnoolia.facts', []);
@endphp

<div class="facts-bar" role="list" aria-label="Projekti faktid">
    @foreach($facts as $fact)
        <div class="facts-bar__item" role="listitem">
            <span class="facts-bar__value"
                  @if($dark) style="color:var(--color-off-white);" @endif>
                {{ $fact['value'] }}
            </span>
            <span class="facts-bar__label"
                  @if($dark) style="color:rgba(245,243,240,0.6);" @endif>
                {{ $fact['label'] }}
            </span>
        </div>
    @endforeach
</div>
