{{--
    mobile-sticky-cta.blade.php

    Fixed bottom bar — visible ONLY on small screens (CSS hides it on desktop).
    Provides always-accessible phone + contact shortcuts for mobile visitors.

    Phase 1: functional but unstyled beyond CSS tokens.
    Phase 8: integrate analytics tracking.
--}}

<div class="mobile-sticky-cta" aria-label="Kiirkontakt">

    <a
        href="tel:{{ config('magnoolia.project.contact_phone') }}"
        class="cta-btn cta-btn--secondary"
        onclick="window.Magnoolia?.trackCta('mobile_phone', { method: 'call' })"
    >
        <span aria-hidden="true">📞</span> Helista
    </a>

    <a
        href="#contact"
        class="cta-btn cta-btn--primary"
        onclick="window.Magnoolia?.trackCta('mobile_contact', { trigger: 'sticky_bar' })"
    >
        Küsi infot
    </a>

</div>
