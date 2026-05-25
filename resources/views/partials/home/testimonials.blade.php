{{-- MAGNOOLIA BENEFIT CARDS + CTA — Phase 2 (replaces fake testimonials) --}}

{{-- Benefits grid --}}
<section class="mg-section mg-section--soft" id="eelised" aria-labelledby="benefits-title">
    <div class="mg-container">
        <div class="mg-text-center" style="margin-bottom:48px;">
            <div class="mg-eyebrow" style="justify-content:center;">Magnoolia eelised</div>
            <h2 class="mg-section__title mg-section__title--centered" id="benefits-title">
                Mida teeb Magnoolia eriliseks?
            </h2>
        </div>

        <div class="mg-benefits-grid">

            <div class="mg-benefit-card">
                <div class="mg-benefit-card__icon">
                    <i class="fas fa-leaf" aria-hidden="true"></i>
                </div>
                <h3 class="mg-benefit-card__title">A-energiaklass</h3>
                <p class="mg-benefit-card__text">
                    Maasoojuspump, kontrollitud ventilatsioon ja päikesepaneeli valmidus
                    hoiavad igakuised kulud madalal.
                </p>
            </div>

            <div class="mg-benefit-card">
                <div class="mg-benefit-card__icon">
                    <i class="fas fa-tree" aria-hidden="true"></i>
                </div>
                <h3 class="mg-benefit-card__title">Privaatne hooviala</h3>
                <p class="mg-benefit-card__text">
                    Iga kodu juurde kuulub 600–1200 m² privaatne hooviala.
                    Eramaja privaatsus ridaelamu mugavusel.
                </p>
            </div>

            <div class="mg-benefit-card">
                <div class="mg-benefit-card__icon">
                    <i class="fas fa-shield-alt" aria-hidden="true"></i>
                </div>
                <h3 class="mg-benefit-card__title">Uusarenduse kindlus</h3>
                <p class="mg-benefit-card__text">
                    Garantii, kvaliteetmaterjalid ja professionaalne arendaja —
                    ei mingit isehakkamise muret.
                </p>
            </div>

            <div class="mg-benefit-card">
                <div class="mg-benefit-card__icon">
                    <i class="fas fa-bolt" aria-hidden="true"></i>
                </div>
                <h3 class="mg-benefit-card__title">EV laadimise valmidus</h3>
                <p class="mg-benefit-card__text">
                    Elektriautode laadimisvalmidus iga kodu katusealuse
                    parkimiskoha juures.
                </p>
            </div>

            <div class="mg-benefit-card">
                <div class="mg-benefit-card__icon">
                    <i class="fas fa-map-marker-alt" aria-hidden="true"></i>
                </div>
                <h3 class="mg-benefit-card__title">Tallinna lähedus</h3>
                <p class="mg-benefit-card__text">
                    Vaela küla, Kiili vald — 20 minutit Tallinnast.
                    Loodus ja privaatsus, linnaelu kättesaadavus.
                </p>
            </div>

            <div class="mg-benefit-card">
                <div class="mg-benefit-card__icon">
                    <i class="fas fa-expand-arrows-alt" aria-hidden="true"></i>
                </div>
                <h3 class="mg-benefit-card__title">Suur terrass</h3>
                <p class="mg-benefit-card__text">
                    Avar terrass on iga kodu loomulik pikendus —
                    ideaalne perekondlikuks kogunemiseks ja välisõhu nautimiseks.
                </p>
            </div>

        </div>
    </div>
</section>

{{-- Hinnad ja plaanid placeholder --}}
<section class="mg-section mg-section--white" id="hinnad">
    <div class="mg-container">
        <div class="mg-eyebrow">Hinnad ja plaanid</div>
        <h2 class="mg-section__title" style="margin-bottom:40px;">Valige oma kodu</h2>
        <div class="mg-placeholder-section">
            <div class="mg-placeholder-section__label">Hinnatabel</div>
            <div class="mg-placeholder-section__title">Kodude valik, plaanid ja hinnad</div>
            <p class="mg-placeholder-section__note">[Phase 3: interaktiivne hinnatabel ja plaanide galerii]</p>
            <div style="margin-top:24px;">
                <a href="{{ route('apartments.index') }}" class="mg-btn mg-btn--primary">
                    Vaata saadaolevaid kodusid
                </a>
            </div>
        </div>
    </div>
</section>

{{-- Masterplan placeholder --}}
<section class="mg-section mg-section--soft" id="asendiplaan">
    <div class="mg-container">
        <div class="mg-eyebrow">Asendiplaan</div>
        <h2 class="mg-section__title" style="margin-bottom:40px;">Kõik 19 kodu kaardil</h2>
        <div class="mg-placeholder-section">
            <div class="mg-placeholder-section__label">Interaktiivne asendiplaan</div>
            <div class="mg-placeholder-section__title">Asendiplaani kaart koos saadavuse näitajatega</div>
            <p class="mg-placeholder-section__note">[Phase 4: SVG/interaktiivne asendiplaan]</p>
        </div>
    </div>
</section>

{{-- CTA panel --}}
<section class="mg-section mg-section--white" aria-labelledby="cta-title">
    <div class="mg-container">
        <div class="mg-cta-panel">
            <div>
                <h2 class="mg-cta-panel__title" id="cta-title">
                    Kas soovite näha, milline kodu sobib teie perele?
                </h2>
                <p class="mg-cta-panel__sub">
                    Võtke ühendust — aitame valida õige lahenduse.
                </p>
            </div>
            <div class="mg-cta-panel__actions">
                <a href="{{ route('contact') }}" class="mg-btn mg-btn--gold mg-btn--lg">
                    Küsi pakkumist
                </a>
                <a href="{{ route('apartments.index') }}" class="mg-btn mg-btn--ghost mg-btn--lg">
                    Vaata kodusid
                </a>
            </div>
        </div>
    </div>
</section>
