{{--
  SOURCE MAPPING — SCREENSHOT 08
  Screenshot:   08-contact-team-diana-jaanika-layout.png
  Original demo: https://bracketweb.com/zoomvilla-php/index-2.php
  Source files: php-template/parts/home2/team.php    → .team-card-two, .team-card-two__image, .team-card-two__content
                php-template/parts/home2/contact.php → .contact-two, .contact-two__content, .contact-two__image
  CSS required: zoomvilla.css (.team-card-two, .contact-two, .sec-title)
  JS required:  wow.js
  Images:       Diana + Jaanika photos — [PLACEHOLDER] until real photos received
  Reuse as-is:  YES (structure) — team card layout + contact section structure are correct
  Rebuild:      PARTIAL — 2 agents only (not 4), social links removed, real contact info added
  Risk:         MEDIUM — depends on receiving real photos of Diana & Jaanika
--}}

{{-- TEAM SECTION: Diana & Jaanika --}}
<section class="team-two section-space" id="meeskond">
    <div class="container">
        <div class="sec-title text-center">
            <div class="sec-title__top justify-content-center">
                <span class="line-left"></span>
                <h6 class="sec-title__tagline bw-split-in-right">Müügiesindajad</h6>
                <span class="line-right"></span>
            </div>
            <h3 class="sec-title__title bw-split-in-left">Teie kontaktid Magnoolias</h3>
        </div>

        <div class="row gutter-y-30 justify-content-center">

            {{-- Diana --}}
            <div class="col-xl-4 col-lg-4 col-md-6">
                <div class="team-card-two wow fadeInUp" data-wow-duration="1500ms" data-wow-delay="100ms">
                    <div class="team-card-two__image">
                        {{-- [PLACEHOLDER] Replace src with Diana's real photo --}}
                        <div style="width:100%; aspect-ratio:3/4; background:var(--mg-soft-grey);
                                    display:flex; align-items:center; justify-content:center;">
                            <i class="fas fa-user" style="font-size:64px; color:var(--mg-warm-grey);"></i>
                        </div>
                        <div class="team-card-two__social__link">
                            <a href="tel:[PLACEHOLDER]"><i class="fas fa-phone" aria-hidden="true"></i></a>
                            <a href="mailto:[PLACEHOLDER]"><i class="fas fa-envelope" aria-hidden="true"></i></a>
                        </div>
                    </div>
                    <div class="team-card-two__content">
                        <div class="team-card-two__info">
                            <h3 class="team-card-two__name">Diana [Perekonnanimi]</h3>
                            <p class="team-card-two__designation">Müügiesindaja</p>
                        </div>
                        <span class="team-card-two__social"></span>
                    </div>
                </div>
            </div>

            {{-- Jaanika --}}
            <div class="col-xl-4 col-lg-4 col-md-6">
                <div class="team-card-two wow fadeInUp" data-wow-duration="1500ms" data-wow-delay="300ms">
                    <div class="team-card-two__image">
                        {{-- [PLACEHOLDER] Replace src with Jaanika's real photo --}}
                        <div style="width:100%; aspect-ratio:3/4; background:var(--mg-soft-grey);
                                    display:flex; align-items:center; justify-content:center;">
                            <i class="fas fa-user" style="font-size:64px; color:var(--mg-warm-grey);"></i>
                        </div>
                        <div class="team-card-two__social__link">
                            <a href="tel:[PLACEHOLDER]"><i class="fas fa-phone" aria-hidden="true"></i></a>
                            <a href="mailto:[PLACEHOLDER]"><i class="fas fa-envelope" aria-hidden="true"></i></a>
                        </div>
                    </div>
                    <div class="team-card-two__content">
                        <div class="team-card-two__info">
                            <h3 class="team-card-two__name">Jaanika [Perekonnanimi]</h3>
                            <p class="team-card-two__designation">Müügiesindaja</p>
                        </div>
                        <span class="team-card-two__social"></span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- CONTACT SECTION: call to action + form --}}
<section class="contact-two section-space" id="kontakt">
    <div class="contact-two__bg"></div>
    <div class="container">
        <div class="row gutter-y-30">

            <div class="col-xl-6">
                <div class="contact-two__content">
                    <div class="sec-title text-start">
                        <div class="sec-title__top justify-content-start">
                            <span class="line-left"></span>
                            <h6 class="sec-title__tagline bw-split-in-right">Võtke ühendust</h6>
                        </div>
                        <h3 class="sec-title__title bw-split-in-left">Küsige pakkumist<br>juba täna</h3>
                    </div>
                    <p class="contact-two__text wow fadeInUp" data-wow-duration="1500ms" data-wow-delay="300ms">
                        Diana ja Jaanika ootavad teie kõnet või kirja.
                        Aitame leida teie perele sobiva kodu ja vastame kõikidele küsimustele.
                    </p>
                    <ul class="contact-two__list list-unstyled wow fadeInUp" data-wow-duration="1500ms" data-wow-delay="300ms">
                        <li class="contact-two__list__item">
                            <i class="fas fa-arrow-circle-right"></i>
                            <span>Tasuta konsultatsioon</span>
                        </li>
                        <li class="contact-two__list__item">
                            <i class="fas fa-arrow-circle-right"></i>
                            <span>Vaatamised kohapeal</span>
                        </li>
                        <li class="contact-two__list__item">
                            <i class="fas fa-arrow-circle-right"></i>
                            <span>Kiire vastus 1 tööpäeva jooksul</span>
                        </li>
                    </ul>
                    <div class="contact-two__btn">
                        <a href="{{ route('contact') }}" class="zoomvilla-btn">
                            Saada päring <i class="icon-angle-small-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-xl-6">
                <div class="contact-two__image">
                    {{-- [PLACEHOLDER] Replace with team photo or project exterior --}}
                    <div style="width:100%; aspect-ratio:4/3; background:var(--mg-soft-grey);
                                border-radius:var(--mg-radius-md); display:flex; align-items:center;
                                justify-content:center; flex-direction:column; gap:12px;">
                        <i class="fas fa-building" style="font-size:48px; color:var(--mg-warm-grey);"></i>
                        <span style="color:var(--mg-warm-grey); font-size:var(--text-sm);">[Foto: Magnoolia projekt]</span>
                    </div>
                    <div class="contact-two__contact-box">
                        <div class="contact-two__icon"><i class="icon-phone-call"></i></div>
                        <div class="contact-two__image__content">
                            <h5 class="contact-two__image__title">Helistage meile</h5>
                            {{-- [PLACEHOLDER] Replace with real phone --}}
                            <a class="contact-two__image__link" href="tel:+37200000000">+372 000 0000</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
