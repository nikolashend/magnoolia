{{--
    language-switcher.blade.php

    Props:
    - $dark (bool) - dark variant for use on dark backgrounds

    Links to the three locale roots: /, /ru, /en
    Active state is determined by app()->getLocale()
--}}
@props(['dark' => false])

@php
    $current = app()->getLocale();
    $locales = [
        'et' => ['url' => '/',    'label' => 'ET'],
        'ru' => ['url' => '/ru',  'label' => 'RU'],
        'en' => ['url' => '/en',  'label' => 'EN'],
    ];
    $mutedColor = $dark ? 'rgba(245,243,240,0.4)' : null;
@endphp

<nav class="lang-switcher" aria-label="Keelivalik">
    @foreach($locales as $code => $locale)
        <a href="{{ $locale['url'] }}"
           class="lang-switcher__link {{ $current === $code ? 'lang-switcher__link--active' : '' }}"
           lang="{{ $code }}"
           aria-label="Vaheta keel: {{ $locale['label'] }}"
           {{ $current === $code ? 'aria-current=page' : '' }}
           @if($dark) style="color: {{ $current === $code ? 'rgba(245,243,240,0.9)' : 'rgba(245,243,240,0.4)' }};" @endif
        >{{ $locale['label'] }}</a>
        @if(!$loop->last)
            <span class="lang-switcher__sep" aria-hidden="true">·</span>
        @endif
    @endforeach
</nav>
