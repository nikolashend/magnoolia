<div class="header-right-sidebar">
    <div class="header-right-sidebar__overlay header-right-sidebar__toggler"></div>
    <div class="header-right-sidebar__content">
        <span class="header-right-sidebar__close header-right-sidebar__toggler"><i class="fa fa-times"></i></span>
        <div class="header-right-sidebar__logo-box">
            <a href="{{ route('home') }}" aria-label="logo image">
                <img src="{{ asset('assets/images/logo-light.png') }}" width="300" alt="{{ config('app.name') }}">
            </a>
        </div>
        <div class="header-right-sidebar__container">
            <div class="header-right-sidebar__container__about">
                <h3 class="header-right-sidebar__container__title">{{ __('sidebar.title') }}</h3>
                <p class="header-right-sidebar__container__text">{{ __('sidebar.description') }}</p>
            </div>
            <div class="header-right-sidebar__container__contact">
                <h3 class="header-right-sidebar__container__title">{{ __('common.contact_us') }}</h3>
                <ul class="header-right-sidebar__container__list list-unstyled">
                    <li class="header-right-sidebar__container__list__item">
                        <div class="header-right-sidebar__container__icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="header-right-sidebar__container__list__content">
                            <span class="header-right-sidebar__container__list__title">{{ __('common.send_email') }}</span>
                            <a href="mailto:{{ config('contact.email', 'info@company.com') }}">{{ config('contact.email', 'info@company.com') }}</a>
                        </div>
                    </li>
                    <li class="header-right-sidebar__container__list__item">
                        <div class="header-right-sidebar__container__icon">
                            <i class="icon-phone-call"></i>
                        </div>
                        <div class="header-right-sidebar__container__list__content">
                            <span class="header-right-sidebar__container__list__title">{{ __('common.call_agent') }}</span>
                            <a href="tel:{{ config('contact.phone', '') }}">{{ config('contact.phone', '+372 000 000') }}</a>
                        </div>
                    </li>
                    <li class="header-right-sidebar__container__list__item">
                        <div class="header-right-sidebar__container__icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="header-right-sidebar__container__list__content">
                            <span class="header-right-sidebar__container__list__title">{{ __('common.opening_hours_label') }}</span>
                            <p>{{ __('common.opening_hours') }}</p>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="header-right-sidebar__container__newsletter-box">
                <h3 class="header-right-sidebar__container__title">{{ __('common.get_notification') }}</h3>
                <form action="{{ route('newsletter.subscribe') }}" method="POST" class="newsletter-box">
                    @csrf
                    <input type="email" name="email" placeholder="{{ __('common.email') }}">
                    <button type="submit" class="zoomvilla-btn">
                        {{ __('common.subscribe') }} <i class="icon-angle-small-right"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
