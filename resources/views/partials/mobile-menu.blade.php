<div class="mobile-nav__wrapper">
    <div class="mobile-nav__overlay mobile-nav__toggler"></div>
    <div class="mobile-nav__content">
        <span class="mobile-nav__close mobile-nav__toggler"><i class="icon-close"></i></span>
        <div class="logo-box">
            <a href="{{ route('home') }}" aria-label="logo image">
                <img src="{{ asset('assets/images/logo-light.png') }}" width="219" alt="{{ config('app.name') }}">
            </a>
        </div>
        <div class="mobile-nav__container"></div>
        <ul class="mobile-nav__contact list-unstyled">
            <li>
                <span class="mobile-nav__contact__icon"><i class="fa fa-envelope"></i></span>
                <a href="mailto:{{ config('contact.email', 'diana@estlanda.ee') }}">{{ config('contact.email', 'diana@estlanda.ee') }}</a>
            </li>
            <li>
                <span class="mobile-nav__contact__icon"><i class="fa fa-phone-alt"></i></span>
                <a href="tel:{{ config('contact.phone', '+37258164078') }}">{{ config('contact.phone', '+372 58 16 40 78') }}</a>
            </li>
        </ul>
        <div class="social-links">
            <a href="https://facebook.com"><i class="fab fa-facebook-f" aria-hidden="true"></i></a>
            <a href="https://x.com"><i class="fab fa-twitter" aria-hidden="true"></i></a>
            <a href="https://linkedin.com"><i class="fab fa-linkedin-in" aria-hidden="true"></i></a>
            <a href="https://google.com"><i class="fab fa-google" aria-hidden="true"></i></a>
        </div>
    </div>
</div>
