<ul class="main-menu__list">

    <li class="{{ request()->routeIs('home') ? 'current' : '' }}">
        <a href="{{ route('home') }}">{{ __('menu.home') }}</a>
    </li>

    <li class="{{ request()->routeIs('about') ? 'current' : '' }}">
        <a href="{{ route('about') }}">{{ __('menu.about') }}</a>
    </li>

    <li class="dropdown {{ request()->routeIs('apartments.*') ? 'current' : '' }}">
        <a href="{{ route('apartments.index') }}">{{ __('menu.apartments') }} <i class="fas fa-plus"></i></a>
        <ul>
            <li><a href="{{ route('apartments.index') }}">{{ __('menu.all_apartments') }}</a></li>
        </ul>
    </li>

    <li class="dropdown {{ request()->routeIs('services*') ? 'current' : '' }}">
        <a href="{{ route('services.index') }}">{{ __('menu.services') }} <i class="fas fa-plus"></i></a>
        <ul>
            <li><a href="{{ route('services.index') }}">{{ __('menu.all_services') }}</a></li>
        </ul>
    </li>

    <li class="dropdown {{ request()->routeIs('blog*') ? 'current' : '' }}">
        <a href="{{ route('blog.index') }}">{{ __('menu.blog') }} <i class="fas fa-plus"></i></a>
        <ul>
            <li><a href="{{ route('blog.index') }}">{{ __('menu.all_posts') }}</a></li>
        </ul>
    </li>

    <li class="{{ request()->routeIs('gallery*') ? 'current' : '' }}">
        <a href="{{ route('gallery.index') }}">{{ __('menu.gallery') }}</a>
    </li>

    <li class="{{ request()->routeIs('contact') ? 'current' : '' }}">
        <a href="{{ route('contact') }}">{{ __('menu.contact') }}</a>
    </li>

</ul>
