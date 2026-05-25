{{-- MAGNOOLIA HEADER Phase 2 --}}
<header class="mg-header" id="mg-header">
    <div class="mg-container">
        <div class="mg-header__inner">
            <a href="{{ route('home') }}" class="mg-header__logo" aria-label="Magnoolia koduleht">
                <span class="mg-header__logo-wordmark">
                    <span class="logo-name">Magnoolia</span>
                    <span class="logo-tagline">Vaela · Kiili · Harjumaa</span>
                </span>
            </a>
            <nav class="mg-header__nav" aria-label="Peamenüü">
                <ul>
                    <li class="{{ request()->routeIs('home*') ? 'current' : '' }}"><a href="{{ route('home') }}">Avaleht</a></li>
                    <li class="{{ request()->routeIs('apartments*') ? 'current' : '' }}"><a href="{{ route('apartments.index') }}">Kodud ja hinnad</a></li>
                    <li><a href="{{ route('home') }}#asendiplaan">Asendiplaan</a></li>
                    <li><a href="{{ route('home') }}#asukoht">Asukoht</a></li>
                    <li><a href="{{ route('home') }}#ehitusinfo">Ehitusinfo</a></li>
                    <li><a href="{{ route('home') }}#sisedisain">Sisedisain</a></li>
                    <li class="{{ request()->routeIs('contact') ? 'current' : '' }}"><a href="{{ route('contact') }}">Kontakt</a></li>
                </ul>
            </nav>
            <div class="mg-header__right">
                <nav class="mg-lang-switcher" aria-label="Keelevalik">
                    @foreach(['et' => 'EE', 'ru' => 'RU', 'en' => 'EN'] as $locale => $label)
                        <a href="{{ route('locale.switch', $locale) }}"
                           class="{{ app()->getLocale() === $locale ? 'active' : '' }}"
                           hreflang="{{ $locale }}">{{ $label }}</a>
                    @endforeach
                </nav>
                <a href="{{ route('contact') }}" class="mg-btn mg-btn--primary mg-btn--sm" id="header-cta-desktop" style="display:none;">
                    Küsi pakkumist
                </a>
                <button class="mg-header__hamburger" id="mg-menu-toggle"
                    aria-expanded="false" aria-controls="mg-mobile-nav" aria-label="Ava menüü">
                    <span></span><span></span><span></span>
                </button>
            </div>
        </div>
    </div>
</header>

<nav class="mg-mobile-nav" id="mg-mobile-nav" aria-label="Mobiilimenüü" aria-hidden="true">
    <div class="mg-mobile-nav__header">
        <span class="mg-header__logo-wordmark">
            <span class="logo-name">Magnoolia</span>
            <span class="logo-tagline">Vaela · Kiili · Harjumaa</span>
        </span>
        <button class="mg-mobile-nav__close" id="mg-menu-close" aria-label="Sulge">&#215;</button>
    </div>
    <ul>
        <li><a href="{{ route('home') }}">Avaleht</a></li>
        <li><a href="{{ route('apartments.index') }}">Kodud ja hinnad</a></li>
        <li><a href="{{ route('home') }}#asendiplaan">Asendiplaan</a></li>
        <li><a href="{{ route('home') }}#asukoht">Asukoht</a></li>
        <li><a href="{{ route('home') }}#ehitusinfo">Ehitusinfo</a></li>
        <li><a href="{{ route('home') }}#sisedisain">Sisedisain</a></li>
        <li><a href="{{ route('home') }}#faq">KKK</a></li>
        <li><a href="{{ route('contact') }}">Kontakt</a></li>
    </ul>
    <div class="mg-mobile-nav__footer">
        <div class="mg-mobile-nav__langs">
            @foreach(['et' => 'EE', 'ru' => 'RU', 'en' => 'EN'] as $locale => $label)
                <a href="{{ route('locale.switch', $locale) }}" class="mg-btn mg-btn--secondary mg-btn--sm">{{ $label }}</a>
            @endforeach
        </div>
        <a href="{{ route('contact') }}" class="mg-btn mg-btn--primary">Küsi pakkumist</a>
        <a href="{{ route('apartments.index') }}" class="mg-btn mg-btn--secondary">Vaata kodusid</a>
    </div>
</nav>

@push('scripts')
<script>
(function(){
    var toggle=document.getElementById('mg-menu-toggle'),
        close=document.getElementById('mg-menu-close'),
        nav=document.getElementById('mg-mobile-nav'),
        header=document.getElementById('mg-header'),
        cta=document.getElementById('header-cta-desktop');
    function open(){nav.classList.add('open');nav.setAttribute('aria-hidden','false');toggle.setAttribute('aria-expanded','true');document.body.style.overflow='hidden';}
    function shut(){nav.classList.remove('open');nav.setAttribute('aria-hidden','true');toggle.setAttribute('aria-expanded','false');document.body.style.overflow='';}
    toggle.addEventListener('click',open);
    close.addEventListener('click',shut);
    nav.querySelectorAll('a').forEach(function(a){a.addEventListener('click',shut);});
    window.addEventListener('scroll',function(){
        if(window.scrollY>60){header.classList.add('mg-header--scrolled');if(cta)cta.style.display='inline-flex';}
        else{header.classList.remove('mg-header--scrolled');if(cta)cta.style.display='none';}
    },{passive:true});
})();
</script>
@endpush
