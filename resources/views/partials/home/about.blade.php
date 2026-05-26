{{-- MAGNOOLIA "MIKS MAGNOOLIA?" — Phase 2 --}}
<section class="mg-section" id="miks-magnoolia" aria-labelledby="about-title">
    <div class="mg-container">
        <div class="row gutter-y-50 align-items-center">

            {{-- Image column --}}
            <div class="col-lg-5">
                <div class="mg-about-section__image-wrap">
                    {{-- Placeholder until real render is available --}}
                    <div class="mg-image-frame--placeholder" style="aspect-ratio:4/5; display:flex; align-items:center; justify-content:center; background:var(--mg-soft-grey); border-radius:var(--mg-radius-xl);">
                        <i class="fas fa-image" style="font-size:48px; color:var(--mg-warm-grey);"></i>
                        <p style="color:var(--mg-warm-grey); font-size:var(--text-sm); margin-top:12px;">[Render: Magnoolia välisvaade]</p>
                    </div>
                    <div class="mg-about-section__badge">
                        <div class="mg-about-section__badge-value">A</div>
                        <div class="mg-about-section__badge-label">Energiaklass</div>
                    </div>
                </div>
            </div>

            {{-- Content column --}}
            <div class="col-lg-7">
                <div class="mg-eyebrow">Miks Magnoolia?</div>
                <h2 class="mg-section__title" id="about-title">
                    Ridaelamu mugavus.<br>Eramaja privaatsus.
                </h2>
                <p class="mg-section__lead" style="max-width:100%;">
                    Magnoolia on uusarendus, mis pakub parimat mõlemast maailmast —
                    uusarenduse kindlusest kuni privaatse hoovialani, kõrgkvaliteetsest
                    ehitusest kuni A-energiaklassi madalate kuludeni.
                </p>

                <ul class="mg-about-features">
                    <li><i class="fas fa-check-circle"></i><span>Privaatne hooviala 600–1200 m² — laste ja perekonna jaoks</span></li>
                    <li><i class="fas fa-check-circle"></i><span>A-energiaklass — madalad igakuised kulud</span></li>
                    <li><i class="fas fa-check-circle"></i><span>Maasoojuspump, ventilatsioon, päikesepaneeli valmidus</span></li>
                    <li><i class="fas fa-check-circle"></i><span>Elektriautode laadimise valmidus iga kodu juures</span></li>
                    <li><i class="fas fa-check-circle"></i><span>Tallinn 20 minutit — Kiili vald, Vaela küla</span></li>
                    <li><i class="fas fa-check-circle"></i><span>Kvaliteetne sisekujundus, terrass, katusealune parkimine</span></li>
                </ul>

                <div style="display:flex; gap:12px; flex-wrap:wrap; margin-top:8px;">
                    <a href="#hinnad" class="mg-btn mg-btn--primary">
                        Vaata kodusid
                    </a>
                    <a href="{{ route('contact') }}" class="mg-btn mg-btn--secondary">
                        Küsi pakkumist
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>
