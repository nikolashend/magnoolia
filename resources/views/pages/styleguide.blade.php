@extends('layouts.app')

@section('title', 'Magnooolia Stilistika — Design System Preview')
@section('meta_description', 'Internal design system styleguide. Not for public use.')

@section('content')

<div class="mg-styleguide-header">
    <div class="mg-container">
        <h1>Magnoolia Design System</h1>
        <p>Internal styleguide — Phase 2 component & token preview</p>
    </div>
</div>

<div class="mg-container">

    {{-- ============ COLORS ============ --}}
    <div class="mg-sg-section">
        <div class="mg-sg-section__title">01 — Värvid / Colors</div>
        <div class="mg-sg-grid">
            @foreach([
                ['name'=>'--mg-black',      'hex'=>'#111111', 'bg'=>'#111111'],
                ['name'=>'--mg-charcoal',   'hex'=>'#1C1C1A', 'bg'=>'#1C1C1A'],
                ['name'=>'--mg-warm-dark',  'hex'=>'#2A2622', 'bg'=>'#2A2622'],
                ['name'=>'--mg-stone',      'hex'=>'#8D867C', 'bg'=>'#8D867C'],
                ['name'=>'--mg-warm-grey',  'hex'=>'#D8D2C8', 'bg'=>'#D8D2C8'],
                ['name'=>'--mg-soft-grey',  'hex'=>'#F3F1EC', 'bg'=>'#F3F1EC'],
                ['name'=>'--mg-off-white',  'hex'=>'#FAF8F3', 'bg'=>'#FAF8F3'],
                ['name'=>'--mg-wood',       'hex'=>'#B88756', 'bg'=>'#B88756'],
                ['name'=>'--mg-muted-gold', 'hex'=>'#C6A36A', 'bg'=>'#C6A36A'],
                ['name'=>'--mg-forest',     'hex'=>'#3F5A44', 'bg'=>'#3F5A44'],
                ['name'=>'--mg-sage',       'hex'=>'#8A9A82', 'bg'=>'#8A9A82'],
            ] as $swatch)
            <div class="mg-sg-swatch">
                <div class="mg-sg-swatch__color" style="background:{{ $swatch['bg'] }};"></div>
                <div class="mg-sg-swatch__info">
                    <div class="mg-sg-swatch__name">{{ $swatch['name'] }}</div>
                    <div class="mg-sg-swatch__hex">{{ $swatch['hex'] }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- ============ TYPOGRAPHY ============ --}}
    <div class="mg-sg-section">
        <div class="mg-sg-section__title">02 — Tüpograafia / Typography</div>

        @foreach([
            ['label'=>'Hero (60px)', 'class'=>'mg-text-5xl mg-fw-semibold', 'sample'=>'A-energiaklassi kodud'],
            ['label'=>'H1 (48px)', 'class'=>'mg-text-5xl mg-fw-semibold', 'sample'=>'Magnoolia · Vaela'],
            ['label'=>'H2 (36px)', 'class'=>'mg-text-4xl mg-fw-semibold', 'sample'=>'Miks Magnoolia?'],
            ['label'=>'H3 (30px)', 'class'=>'mg-text-3xl mg-fw-semibold', 'sample'=>'Privaatne hooviala'],
            ['label'=>'H4 (24px)', 'class'=>'mg-text-2xl mg-fw-semibold', 'sample'=>'A-klass · 2027'],
            ['label'=>'Lead (18px)', 'class'=>'mg-text-lg mg-text-stone', 'sample'=>'Ridaelamu mugavus kohtub eramaja privaatsusega.'],
            ['label'=>'Body (16px)', 'class'=>'mg-text-base', 'sample'=>'Magnoolia pakub 19 A-energiaklassi kodu Vaelas.'],
            ['label'=>'Small (14px)', 'class'=>'mg-text-sm mg-text-stone', 'sample'=>'Kiili vald · Harjumaa · Eesti'],
            ['label'=>'Eyebrow (11px)', 'class'=>'mg-text-xs mg-fw-semibold mg-text-gold', 'style'=>'letter-spacing:0.12em;text-transform:uppercase;', 'sample'=>'Uusarendus · Valmib 2027'],
        ] as $t)
        <div class="mg-sg-type-sample">
            <div class="mg-sg-type-label">{{ $t['label'] }}</div>
            <div class="{{ $t['class'] }}" @isset($t['style'])style="{{ $t['style'] }}"@endisset>{{ $t['sample'] }}</div>
        </div>
        @endforeach
    </div>

    {{-- ============ BUTTONS ============ --}}
    <div class="mg-sg-section">
        <div class="mg-sg-section__title">03 — Nupud / Buttons</div>
        <div class="mg-sg-btn-row">
            <a href="#" class="mg-btn mg-btn--primary">Primary — Vaata kodusid</a>
            <a href="#" class="mg-btn mg-btn--secondary">Secondary — Küsi pakkumist</a>
            <a href="#" class="mg-btn mg-btn--gold">Gold — Broneeri kohtumine</a>
            <a href="#" class="mg-btn mg-btn--link">Link — Loe rohkem</a>
        </div>
        <div class="mg-sg-btn-row" style="background:var(--mg-charcoal);padding:16px;border-radius:var(--radius-md);">
            <a href="#" class="mg-btn mg-btn--light">Light</a>
            <a href="#" class="mg-btn mg-btn--ghost">Ghost</a>
            <a href="#" class="mg-btn mg-btn--dark">Dark</a>
        </div>
        <div class="mg-sg-btn-row" style="margin-top:12px;">
            <a href="#" class="mg-btn mg-btn--primary mg-btn--sm">SM</a>
            <a href="#" class="mg-btn mg-btn--primary">MD</a>
            <a href="#" class="mg-btn mg-btn--primary mg-btn--lg">LG</a>
            <a href="#" class="mg-btn mg-btn--primary mg-btn--xl">XL</a>
        </div>
    </div>

    {{-- ============ BADGES ============ --}}
    <div class="mg-sg-section">
        <div class="mg-sg-section__title">04 — Staatused / Badges</div>
        <div style="display:flex;gap:10px;flex-wrap:wrap;">
            <span class="mg-badge mg-badge--available">Saadaval</span>
            <span class="mg-badge mg-badge--reserved">Broneeritud</span>
            <span class="mg-badge mg-badge--sold">Müüdud</span>
            <span class="mg-badge mg-badge--new">Uus</span>
            <span class="mg-badge mg-badge--energy">A-energiaklass</span>
        </div>
    </div>

    {{-- ============ FACT CARDS ============ --}}
    <div class="mg-sg-section">
        <div class="mg-sg-section__title">05 — Faktid / Fact Cards</div>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:16px;">
            <div class="mg-fact-card">
                <span class="mg-fact-card__value">19</span>
                <span class="mg-fact-card__label">A-klassi kodu</span>
            </div>
            <div class="mg-fact-card">
                <span class="mg-fact-card__value">A</span>
                <span class="mg-fact-card__label">Energiaklass</span>
            </div>
            <div class="mg-fact-card">
                <span class="mg-fact-card__value">600–1200 m²</span>
                <span class="mg-fact-card__label">Hooviala</span>
            </div>
            <div class="mg-fact-card">
                <span class="mg-fact-card__value">Suvi 2027</span>
                <span class="mg-fact-card__label">Valmimisaeg</span>
            </div>
        </div>
    </div>

    {{-- ============ BENEFIT CARDS ============ --}}
    <div class="mg-sg-section">
        <div class="mg-sg-section__title">06 — Eeliskaardid / Benefit Cards</div>
        <div class="mg-benefits-grid">
            <div class="mg-benefit-card">
                <div class="mg-benefit-card__icon"><i class="fas fa-leaf"></i></div>
                <h3 class="mg-benefit-card__title">Ridaelamu mugavus</h3>
                <p class="mg-benefit-card__text">Professionaalne arendaja, garantii, uusarenduse kindlus.</p>
            </div>
            <div class="mg-benefit-card">
                <div class="mg-benefit-card__icon"><i class="fas fa-tree"></i></div>
                <h3 class="mg-benefit-card__title">Eramaja privaatsus</h3>
                <p class="mg-benefit-card__text">Privaatne hooviala 600–1200 m², oma aed, oma rahu.</p>
            </div>
            <div class="mg-benefit-card">
                <div class="mg-benefit-card__icon"><i class="fas fa-shield-alt"></i></div>
                <h3 class="mg-benefit-card__title">Uusarenduse kindlus</h3>
                <p class="mg-benefit-card__text">Ehitusgarantii, kvaliteetsed materjalid, kaasaegne insener.</p>
            </div>
            <div class="mg-benefit-card">
                <div class="mg-benefit-card__icon"><i class="fas fa-bolt"></i></div>
                <h3 class="mg-benefit-card__title">Madalamad kulud</h3>
                <p class="mg-benefit-card__text">A-klass, maasoojus, solar-valmidus = madal igakuine arve.</p>
            </div>
        </div>
    </div>

    {{-- ============ CTA PANEL ============ --}}
    <div class="mg-sg-section">
        <div class="mg-sg-section__title">07 — CTA paneel</div>
        <div class="mg-cta-panel">
            <div>
                <h2 class="mg-cta-panel__title">Kas soovite näha, milline kodu sobib teie perele?</h2>
                <p class="mg-cta-panel__sub">Võtke ühendust — aitame valida õige lahenduse.</p>
            </div>
            <div class="mg-cta-panel__actions">
                <a href="{{ route('contact') }}" class="mg-btn mg-btn--gold mg-btn--lg">Küsi pakkumist</a>
                <a href="{{ route('apartments.index') }}" class="mg-btn mg-btn--ghost mg-btn--lg">Vaata kodusid</a>
            </div>
        </div>
    </div>

    {{-- ============ CONTACT CARDS ============ --}}
    <div class="mg-sg-section">
        <div class="mg-sg-section__title">08 — Kontaktkaardid / Contact Cards</div>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:24px;">
            <div class="mg-contact-card">
                <div class="mg-contact-card__photo"><div style="width:100%;height:100%;background:var(--mg-soft-grey);display:flex;align-items:center;justify-content:center;"><i class="fas fa-user" style="font-size:32px;color:var(--mg-warm-grey);"></i></div></div>
                <div class="mg-contact-card__name">Diana [perekonnanimi]</div>
                <div class="mg-contact-card__role">Müüginõustaja</div>
                <div class="mg-contact-card__contacts">
                    <span style="font-size:var(--text-xs);color:var(--mg-stone);">[Kontaktandmed kinnitamisel]</span>
                </div>
            </div>
            <div class="mg-contact-card">
                <div class="mg-contact-card__photo"><div style="width:100%;height:100%;background:var(--mg-soft-grey);display:flex;align-items:center;justify-content:center;"><i class="fas fa-user" style="font-size:32px;color:var(--mg-warm-grey);"></i></div></div>
                <div class="mg-contact-card__name">Jaanika [perekonnanimi]</div>
                <div class="mg-contact-card__role">Müüginõustaja</div>
                <div class="mg-contact-card__contacts">
                    <span style="font-size:var(--text-xs);color:var(--mg-stone);">[Kontaktandmed kinnitamisel]</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ============ UNIT CARD PREVIEW ============ --}}
    <div class="mg-sg-section">
        <div class="mg-sg-section__title">09 — Ühik kaart / Unit Card Preview</div>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:24px;">
            <div class="mg-unit-card">
                <div class="mg-unit-card__image">
                    <div style="width:100%;height:100%;background:var(--mg-soft-grey);display:flex;align-items:center;justify-content:center;">
                        <span style="color:var(--mg-stone);font-size:var(--text-sm);">[Kodu render]</span>
                    </div>
                </div>
                <div class="mg-unit-card__body">
                    <span class="mg-badge mg-badge--available" style="margin-bottom:8px;">Saadaval</span>
                    <div class="mg-unit-card__title">Magnoolia 07</div>
                    <div class="mg-unit-card__meta">
                        <span>142 m²</span>
                        <span>4 tuba</span>
                        <span>Hooviala 780 m²</span>
                    </div>
                    <div class="mg-unit-card__price">[Hind kinnitamisel]</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ============ SPACING ============ --}}
    <div class="mg-sg-section">
        <div class="mg-sg-section__title">10 — Vahe & Layout tokens</div>
        <div class="mg-sg-grid">
            @foreach([
                '--section-y-sm: 56px', '--section-y-md: 88px', '--section-y-lg: 120px',
                '--radius-sm: 8px', '--radius-md: 14px', '--radius-lg: 22px', '--radius-xl: 32px',
                '--container-max: 1240px', '--container-wide: 1440px',
            ] as $token)
            <div class="mg-info-card">
                <code style="font-family:var(--font-mono);font-size:var(--text-xs);">{{ $token }}</code>
            </div>
            @endforeach
        </div>
    </div>

</div>{{-- /.mg-container --}}

@endsection
