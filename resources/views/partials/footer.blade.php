{{-- MAGNOOLIA FOOTER Phase 2 --}}
<footer class="mg-footer" id="footer">
    <div class="mg-container">
        <div class="mg-footer__grid">

            {{-- Col 1: Brand --}}
            <div class="mg-footer__logo-col">
                <a href="{{ route('home') }}" style="text-decoration:none;">
                    <span class="mg-footer__brand">Magnoolia</span>
                    <span class="mg-footer__tagline">A-energiaklassi kodud</span>
                </a>
                <p class="mg-footer__desc">
                    Ridaelamu mugavus kohtub eramaja privaatsusega.
                    19 A-energiaklassi kodu Vaelas, Kiili vallas —
                    Tallinna lähedal, looduse keskel.
                </p>
                <div class="mg-footer__langs">
                    @foreach(['et' => 'EE', 'ru' => 'RU', 'en' => 'EN'] as $locale => $label)
                        <a href="{{ route('locale.switch', $locale) }}"
                           class="{{ app()->getLocale() === $locale ? 'active' : '' }}">{{ $label }}</a>
                    @endforeach
                </div>
            </div>

            {{-- Col 2: Projekt --}}
            <div>
                <span class="mg-footer__col-title">Projekt</span>
                <ul class="mg-footer__links">
                    <li><a href="{{ route('apartments.index') }}">Kodud ja hinnad</a></li>
                    <li><a href="{{ route('home') }}#asendiplaan">Asendiplaan</a></li>
                    <li><a href="{{ route('home') }}#asukoht">Asukoht</a></li>
                    <li><a href="{{ route('home') }}#ehitusinfo">Ehitusinfo</a></li>
                    <li><a href="{{ route('home') }}#sisedisain">Sisedisain</a></li>
                </ul>
            </div>

            {{-- Col 3: Ostjale --}}
            <div>
                <span class="mg-footer__col-title">Ostjale</span>
                <ul class="mg-footer__links">
                    <li><a href="{{ route('home') }}#ostuprotsess">Ostuprotsess</a></li>
                    <li><a href="{{ route('home') }}#finantseerimine">Finantseerimine</a></li>
                    <li><a href="{{ route('home') }}#faq">KKK</a></li>
                    <li><a href="{{ route('contact') }}">Võta ühendust</a></li>
                </ul>
            </div>

            {{-- Col 4: Kontakt --}}
            <div>
                <span class="mg-footer__col-title">Kontakt</span>
                <a href="tel:+37200000000" class="mg-footer__contact-item">
                    <i class="fas fa-phone" aria-hidden="true"></i>
                    <span>+372 000 0000</span>
                </a>
                <a href="mailto:info@magnoolia.ee" class="mg-footer__contact-item">
                    <i class="fas fa-envelope" aria-hidden="true"></i>
                    <span>info@magnoolia.ee</span>
                </a>
                <div class="mg-footer__contact-item">
                    <i class="fas fa-map-marker-alt" aria-hidden="true"></i>
                    <span>Magnoolia tee, Vaela küla,<br>Kiili vald, Harjumaa</span>
                </div>
                <div style="margin-top:20px;">
                    <a href="{{ route('contact') }}" class="mg-btn mg-btn--ghost mg-btn--sm">
                        Küsi pakkumist
                    </a>
                </div>
            </div>

        </div>{{-- /.mg-footer__grid --}}
    </div>

    <div class="mg-container">
        <div class="mg-footer__bottom">
            <p class="mg-footer__copy">&copy; {{ date('Y') }} Magnoolia / Estlanda OÜ. Kõik õigused kaitstud.</p>
            <ul class="mg-footer__bottom-links">
                <li><a href="#">Privaatsuspoliitika</a></li>
                <li><a href="#">Kasutustingimused</a></li>
                <li><a href="{{ route('contact') }}">Kontakt</a></li>
            </ul>
        </div>
    </div>
</footer>
