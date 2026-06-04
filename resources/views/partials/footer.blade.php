{{-- MAGNOOLIA FOOTER Phase 2 --}}
<footer class="mg-footer" id="footer">
    <div class="mg-container">
        <div class="mg-footer__grid">

            {{-- Col 1: Brand --}}
            <div class="mg-footer__logo-col">
                <a href="{{ route('home') }}" style="text-decoration:none;">
                    <span class="mg-footer__brand">Magnoolia</span>
                    <span class="mg-footer__tagline">{{ __('magnoolia.footer.tagline') }}</span>
                </a>
                <p class="mg-footer__desc">
                    {{ __('magnoolia.footer.desc') }}
                </p>
                <div class="mg-footer__langs">
                    @foreach(['et' => 'EE', 'ru' => 'RU', 'en' => 'EN'] as $locale => $label)
                        <a href="{{ locale_url($locale) }}"
                           class="{{ app()->getLocale() === $locale ? 'active' : '' }}">{{ $label }}</a>
                    @endforeach
                </div>
            </div>

            {{-- Col 2: Projekt --}}
            <div>
                <span class="mg-footer__col-title">{{ __('magnoolia.footer.col_project') }}</span>
                <ul class="mg-footer__links">
                    <li><a href="{{ lroute('magnoolia.homes') }}">{{ __('magnoolia.footer.nav_homes') }}</a></li>
                    <li><a href="{{ lroute('magnoolia.site-plan') }}">{{ __('magnoolia.footer.nav_site_plan') }}</a></li>
                    <li><a href="{{ lroute('magnoolia.location') }}">{{ __('magnoolia.footer.nav_location') }}</a></li>
                    <li><a href="{{ lroute('magnoolia.construction') }}">{{ __('magnoolia.footer.nav_construction') }}</a></li>
                    <li><a href="{{ lroute('magnoolia.sisedisain') }}">{{ __('magnoolia.footer.nav_interior') }}</a></li>
                    <li><a href="{{ lroute('magnoolia.arhitektuur') }}">{{ __('magnoolia.footer.nav_architecture') }}</a></li>
                    <li><a href="{{ lroute('magnoolia.galerii') }}">{{ __('magnoolia.footer.nav_gallery') }}</a></li>
                </ul>
            </div>

            {{-- Col 3: Ostjale --}}
            <div>
                <span class="mg-footer__col-title">{{ __('magnoolia.footer.col_buyer') }}</span>
                <ul class="mg-footer__links">
                    <li><a href="{{ lroute('magnoolia.ostuprotsess') }}">{{ __('magnoolia.footer.nav_purchase') }}</a></li>
                    <li><a href="{{ lroute('magnoolia.finantseerimine') }}">{{ __('magnoolia.footer.nav_financing') }}</a></li>
                    <li><a href="{{ lroute('magnoolia.kkk') }}">{{ __('magnoolia.footer.nav_faq') }}</a></li>
                    <li><a href="{{ lroute('magnoolia.contact') }}">{{ __('magnoolia.footer.nav_contact') }}</a></li>
                </ul>
            </div>

            {{-- Col 4: Kontakt --}}
            <div>
                <span class="mg-footer__col-title">{{ __('magnoolia.footer.col_contact') }}</span>
                <div style="font-size:12px;color:rgba(255,255,255,.45);letter-spacing:.06em;text-transform:uppercase;margin-bottom:10px;">{{ __('magnoolia.contact.name') }} &mdash; {{ __('magnoolia.footer.diana_role') }}</div>
                <a href="tel:+37258164078" class="mg-footer__contact-item">
                    <i class="fas fa-phone" aria-hidden="true"></i>
                    <span>+372 58 16 40 78</span>
                </a>
                <a href="mailto:diana@estlanda.ee" class="mg-footer__contact-item">
                    <i class="fas fa-envelope" aria-hidden="true"></i>
                    <span>diana@estlanda.ee</span>
                </a>
                <div class="mg-footer__contact-item">
                    <i class="fas fa-map-marker-alt" aria-hidden="true"></i>
                    <span>Magnoolia tee, Vaela küla,<br>Kiili vald, Harjumaa</span>
                </div>
                <div style="margin-top:20px;">
                    <a href="{{ lroute('magnoolia.contact') }}" class="mg-btn mg-btn--ghost mg-btn--sm">
                        {{ __('magnoolia.footer.cta') }}
                    </a>
                </div>
            </div>

        </div>{{-- /.mg-footer__grid --}}
    </div>

    <div class="mg-container">
        <div class="mg-footer__bottom">
            <p class="mg-footer__copy">&copy; {{ date('Y') }} Magnoolia / Estlanda OÜ. {{ __('magnoolia.footer.copy') }}</p>
            <ul class="mg-footer__bottom-links">
                <li><span>{{ __('magnoolia.footer.privacy') }}</span></li>
                <li><span>{{ __('magnoolia.footer.terms') }}</span></li>
                <li><a href="{{ lroute('magnoolia.contact') }}">{{ __('magnoolia.footer.nav_contact') }}</a></li>
            </ul>
        </div>
    </div>
</footer>
