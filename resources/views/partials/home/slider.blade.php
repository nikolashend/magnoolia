{{-- MAGNOOLIA HERO — Phase 2 --}}
<section class="mg-hero" id="home" aria-label="Magnoolia peateade">
    <div class="mg-hero__bg" aria-hidden="true">
        {{-- Placeholder: hero background render --}}
        {{-- <img src="{{ asset('assets/images/magnoolia/renders/hero-exterior.jpg') }}" alt="" loading="eager"> --}}
    </div>

    <div class="mg-container" style="width:100%; position:relative; z-index:1;">
        <div class="mg-hero__content">

            {{-- Location badge --}}
            <div class="mg-hero__location">
                <i class="fas fa-map-marker-alt" aria-hidden="true"></i>
                Vaela küla · Kiili vald · Harjumaa
            </div>

            {{-- Eyebrow --}}
            <div class="mg-hero__eyebrow">Uusarendus · Valmib suvi 2027</div>

            {{-- H1 --}}
            <h1 class="mg-hero__title">
                A-energiaklassi kodud<br>
                <strong>Tallinna lähedal</strong>
            </h1>

            {{-- Lead --}}
            <p class="mg-hero__lead">
                Magnoolia ühendab ridaelamu mugavuse, eramaja privaatsuse ja
                uusarenduse kindluse Vaelas, Kiili vallas — 20 minutit Tallinnast.
            </p>

            {{-- CTAs --}}
            <div class="mg-hero__actions">
                <a href="{{ route('apartments.index') }}" class="mg-btn mg-btn--gold mg-btn--lg">
                    <i class="fas fa-home" aria-hidden="true"></i>
                    Vaata kodusid
                </a>
                <a href="{{ route('contact') }}" class="mg-btn mg-btn--ghost mg-btn--lg">
                    Küsi pakkumist
                </a>
            </div>

            {{-- Quick facts --}}
            <div class="mg-hero__meta">
                <div class="mg-hero__meta-item">
                    <span class="value">19</span>
                    <span class="label">kodu</span>
                </div>
                <div class="mg-hero__meta-item">
                    <span class="value">A</span>
                    <span class="label">energiaklass</span>
                </div>
                <div class="mg-hero__meta-item">
                    <span class="value">2027</span>
                    <span class="label">valmimisaeg</span>
                </div>
                <div class="mg-hero__meta-item">
                    <span class="value">20 min</span>
                    <span class="label">Tallinnast</span>
                </div>
            </div>

        </div>
    </div>
</section>
