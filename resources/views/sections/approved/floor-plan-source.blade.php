{{-- ══════════════════════════════════════════════════════════════
    FLOOR PLAN — Phase 11 Recovery
    2 typology cards: 3 koduga (Plaan A) + 4 koduga (Plaan B)
    Each card: I korrus + II korrus labeled sections,
    "Suurenda plaani" → plan lightbox, "Laadi alla" → download.
    Plan lightbox: type label, floor label, large img, contact CTA.
    Config-driven: plan_type addresses auto-listed.
    ══════════════════════════════════════════════════════════════ --}}
@php
    $units = config('magnoolia.units', []);

    $typeAUnits = array_values(array_filter($units, fn($u) => ($u['plan_type'] ?? null) === 'type-a'));
    $typeBUnits = array_values(array_filter($units, fn($u) => ($u['plan_type'] ?? null) === 'type-b'));

    $formatAddresses = function (array $list): string {
        $addrs = array_values(array_filter(array_map(fn($u) => $u['address'] ?? '', $list)));
        if (count($addrs) > 5) {
            return implode(', ', array_slice($addrs, 0, 5)) . ' …';
        }
        return $addrs ? implode(', ', $addrs) : '';
    };

    $floor1 = asset('assets/images/magnoolia/PR03023_PP_AR-5-01_Esimese korruse plaan_page-0001.jpg');
    $floor2 = asset('assets/images/magnoolia/PR03023_PP_AR-5-02_Teise korruse plaan_page-0001.jpg');

    $plans = [
        [
            'id'         => 'plaan-a',
            'label'      => 'Plaan A',
            'badge'      => '3 koduga ridaelamu',
            'badge_css'  => 'mg-plan-badge--a',
            'desc'       => 'Rohkem privaatsust, selge kodude jaotus, sobib perele, kes hindab omaette kodutunnet.',
            'units'      => $typeAUnits,
            'floor1_src' => $floor1,
            'floor2_src' => $floor2,
            'floor1_alt' => 'Plaan A – esimese korruse plaan',
            'floor2_alt' => 'Plaan A – teise korruse plaan',
        ],
        [
            'id'         => 'plaan-b',
            'label'      => 'Plaan B',
            'badge'      => '4 koduga ridaelamu',
            'badge_css'  => 'mg-plan-badge--b',
            'desc'       => 'Efektiivne ja läbimõeldud plaanilahendus, kus kodud säilitavad privaatsuse ning praktilise ruumikasutuse.',
            'units'      => $typeBUnits,
            'floor1_src' => $floor1,
            'floor2_src' => $floor2,
            'floor1_alt' => 'Plaan B – esimese korruse plaan',
            'floor2_alt' => 'Plaan B – teise korruse plaan',
        ],
    ];
@endphp

{{-- ══ FLOOR PLAN SECTION ═══════════════════════════════════════ --}}
<section class="section-space" id="plaanid" style="background:#fbfaf7;">
    <div class="container">

        {{-- Section header --}}
        <div class="sec-title text-center" style="margin-bottom:48px;">
            <div class="sec-title__top justify-content-center">
                <span class="line-left"></span>
                <h6 class="sec-title__tagline bw-split-in-right">Korrusplaanid</h6>
                <span class="line-right"></span>
            </div>
            <h2 class="sec-title__title bw-split-in-left">Läbimõeldud plaanid eri kodutüüpidele</h2>
            <p class="mg-section-subtitle">
                Magnoolia kodud on planeeritud avatud elutsooni, 4–5 toa, terrassi/rõdu ja praktiliste
                panipaikade loogikaga. Vaadake plaanitüüpe ning küsige Diana käest konkreetse kodu
                täpset plaani ja saadavust.
            </p>
        </div>

        {{-- Typology cards --}}
        <div class="row gutter-y-40">
            @foreach($plans as $idx => $plan)
            <div class="col-lg-6">
                <article class="mg-plan-card wow fadeInUp"
                         data-wow-duration="1200ms"
                         data-wow-delay="{{ $idx * 120 }}ms"
                         data-plan-type="{{ $plan['id'] }}">

                    {{-- Card head --}}
                    <div class="mg-plan-card__head">
                        <div class="mg-plan-card__badges">
                            <span class="mg-plan-badge {{ $plan['badge_css'] }}">{{ $plan['label'] }}</span>
                            <span class="mg-plan-type-chip">{{ $plan['badge'] }}</span>
                        </div>
                        <p class="mg-plan-card__desc">{{ $plan['desc'] }}</p>
                        @if($plan['units'])
                        <p class="mg-plan-card__units">
                            <span class="mg-plan-card__units-label">Aadressid:</span>
                            {{ $formatAddresses($plan['units']) }}
                        </p>
                        @else
                        <p class="mg-plan-card__units">Täpsustamisel</p>
                        @endif
                    </div>

                    {{-- Floor sections --}}
                    <div class="mg-plan-card__floors">

                        {{-- I korrus --}}
                        <div class="mg-plan-floor">
                            <div class="mg-plan-floor__label">
                                <span class="mg-plan-floor__num">I korrus</span>
                            </div>
                            <div class="mg-plan-floor__img-wrap">
                                <img src="{{ $plan['floor1_src'] }}"
                                     alt="{{ $plan['floor1_alt'] }}"
                                     width="560" height="545"
                                     loading="lazy" decoding="async"
                                     class="mg-plan-floor__img">
                                <div class="mg-plan-floor__overlay">
                                    <button type="button"
                                            class="mg-plan-enlarge"
                                            onclick="mgOpenPlanLightbox('{{ $plan['label'] }}','I korrus','{{ $plan['floor1_src'] }}','{{ $plan['floor1_alt'] }}')"
                                            aria-label="Suurenda {{ $plan['label'] }} I korruse plaani">
                                        <i class="icon-zoom-1" aria-hidden="true"></i>
                                        Suurenda plaani
                                    </button>
                                </div>
                            </div>
                            <div class="mg-plan-floor__actions">
                                <a href="{{ $plan['floor1_src'] }}"
                                   download
                                   class="mg-plan-dl">
                                    <i class="icon-download" aria-hidden="true"></i> Laadi alla
                                </a>
                            </div>
                        </div>

                        <div class="mg-plan-floors-divider" aria-hidden="true"></div>

                        {{-- II korrus --}}
                        <div class="mg-plan-floor">
                            <div class="mg-plan-floor__label">
                                <span class="mg-plan-floor__num">II korrus</span>
                            </div>
                            <div class="mg-plan-floor__img-wrap">
                                <img src="{{ $plan['floor2_src'] }}"
                                     alt="{{ $plan['floor2_alt'] }}"
                                     width="560" height="646"
                                     loading="lazy" decoding="async"
                                     class="mg-plan-floor__img">
                                <div class="mg-plan-floor__overlay">
                                    <button type="button"
                                            class="mg-plan-enlarge"
                                            onclick="mgOpenPlanLightbox('{{ $plan['label'] }}','II korrus','{{ $plan['floor2_src'] }}','{{ $plan['floor2_alt'] }}')"
                                            aria-label="Suurenda {{ $plan['label'] }} II korruse plaani">
                                        <i class="icon-zoom-1" aria-hidden="true"></i>
                                        Suurenda plaani
                                    </button>
                                </div>
                            </div>
                            <div class="mg-plan-floor__actions">
                                <a href="{{ $plan['floor2_src'] }}"
                                   download
                                   class="mg-plan-dl">
                                    <i class="icon-download" aria-hidden="true"></i> Laadi alla
                                </a>
                            </div>
                        </div>

                    </div>{{-- /.mg-plan-card__floors --}}

                    {{-- Card CTAs --}}
                    <div class="mg-plan-card__ctas">
                        <a href="#hinnad" class="zoomvilla-btn">
                            Vaata sobivaid kodusid <i class="icon-angle-small-right" aria-hidden="true"></i>
                        </a>
                        <a href="#kontakt" class="zoomvilla-btn zoomvilla-btn--border">
                            Küsi selle plaani kohta <i class="icon-angle-small-right" aria-hidden="true"></i>
                        </a>
                    </div>

                </article>
            </div>
            @endforeach
        </div>{{-- /.row --}}

        {{-- Disclaimer --}}
        <p class="mg-plan-disclaimer">
            <strong>NB!</strong> Plaanid on illustratiivsed ja võivad sõltuvalt konkreetsest kodust erineda.
            Täpne plaan, ruumijaotus ja tehnilised detailid kinnitatakse konkreetse aadressi põhjal.
        </p>

    </div>
</section>

{{-- ══ PLAN LIGHTBOX MODAL ════════════════════════════════════ --}}
<div id="mg-plan-lightbox"
     role="dialog"
     aria-modal="true"
     aria-labelledby="mg-plan-lb-title"
     aria-hidden="true"
     tabindex="-1"
     style="display:none;position:fixed;inset:0;z-index:9100;">

    {{-- Backdrop --}}
    <div id="mg-plan-lb-backdrop"
         onclick="mgClosePlanLightbox()"
         style="position:absolute;inset:0;background:rgba(10,14,24,.82);backdrop-filter:blur(3px);"></div>

    {{-- Panel --}}
    <div id="mg-plan-lb-panel"
         style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%) scale(.96);
                width:min(860px,96vw);max-height:90vh;background:#fff;border-radius:20px;
                overflow:hidden;display:flex;flex-direction:column;
                box-shadow:0 32px 80px rgba(0,0,0,.45);
                transition:transform .28s cubic-bezier(.4,0,.2,1),opacity .28s;opacity:0;"
         role="document">

        {{-- Header --}}
        <div style="padding:22px 28px 18px;border-bottom:1px solid rgba(29,36,48,.1);
                    display:flex;justify-content:space-between;align-items:center;flex-shrink:0;gap:16px;">
            <div>
                <div id="mg-plan-lb-badge"
                     style="display:inline-block;background:#1d2430;color:#fff;font-size:11px;font-weight:700;
                            padding:3px 10px;border-radius:20px;letter-spacing:.06em;margin-bottom:8px;"></div>
                <h3 id="mg-plan-lb-title"
                    style="font-size:17px;font-weight:700;color:#1d2430;margin:0;line-height:1.2;"></h3>
            </div>
            <button onclick="mgClosePlanLightbox()"
                    id="mg-plan-lb-close"
                    aria-label="Sulge"
                    style="width:44px;height:44px;min-width:44px;border-radius:50%;border:1.5px solid rgba(29,36,48,.15);
                           background:transparent;cursor:pointer;font-size:20px;color:#6f6a61;
                           display:flex;align-items:center;justify-content:center;transition:all .2s;"
                    onmouseover="this.style.background='#f5f0e5';this.style.borderColor='#c89443'"
                    onmouseout="this.style.background='transparent';this.style.borderColor='rgba(29,36,48,.15)'">
                &times;
            </button>
        </div>

        {{-- Image area --}}
        <div style="flex:1;overflow-y:auto;padding:24px 28px;display:flex;justify-content:center;align-items:flex-start;">
            <img id="mg-plan-lb-img"
                 src=""
                 alt=""
                 style="max-width:100%;height:auto;border-radius:10px;border:1px solid rgba(29,36,48,.08);display:block;">
        </div>

        {{-- Footer CTA --}}
        <div style="padding:18px 28px;border-top:1px solid rgba(29,36,48,.08);
                    display:flex;gap:12px;flex-wrap:wrap;flex-shrink:0;background:#fff;">
            <a href="#kontakt"
               onclick="mgClosePlanLightbox()"
               class="zoomvilla-btn"
               style="flex:1;min-width:160px;justify-content:center;text-align:center;">
                Küsi selle plaani kohta <i class="icon-angle-small-right" aria-hidden="true"></i>
            </a>
            <a id="mg-plan-lb-dl"
               href=""
               download
               class="mg-plan-dl"
               style="min-height:48px;align-self:center;">
                <i class="icon-download" aria-hidden="true"></i> Laadi alla
            </a>
        </div>

    </div>
</div>

<script>
(function () {
    var _mgPlanLbLastFocus = null;

    window.mgOpenPlanLightbox = function (planLabel, floorLabel, imgSrc, imgAlt) {
        _mgPlanLbLastFocus = document.activeElement;

        var lb     = document.getElementById('mg-plan-lightbox');
        var panel  = document.getElementById('mg-plan-lb-panel');
        var badge  = document.getElementById('mg-plan-lb-badge');
        var title  = document.getElementById('mg-plan-lb-title');
        var img    = document.getElementById('mg-plan-lb-img');
        var dlLink = document.getElementById('mg-plan-lb-dl');

        badge.textContent = planLabel;
        title.textContent = floorLabel;
        img.src           = imgSrc;
        img.alt           = imgAlt || (planLabel + ' \u2013 ' + floorLabel);
        if (dlLink) { dlLink.href = imgSrc; }

        lb.style.display = 'block';
        lb.removeAttribute('aria-hidden');
        document.body.style.overflow = 'hidden';

        requestAnimationFrame(function () {
            requestAnimationFrame(function () {
                panel.style.transform = 'translate(-50%,-50%) scale(1)';
                panel.style.opacity   = '1';
            });
        });

        setTimeout(function () {
            var closeBtn = document.getElementById('mg-plan-lb-close');
            if (closeBtn) closeBtn.focus();
        }, 280);
    };

    window.mgClosePlanLightbox = function () {
        var lb    = document.getElementById('mg-plan-lightbox');
        var panel = document.getElementById('mg-plan-lb-panel');
        if (!lb || lb.style.display === 'none') return;

        panel.style.transform = 'translate(-50%,-50%) scale(.96)';
        panel.style.opacity   = '0';

        setTimeout(function () {
            lb.style.display = 'none';
            lb.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
            var img = document.getElementById('mg-plan-lb-img');
            if (img) img.src = '';
            if (_mgPlanLbLastFocus && typeof _mgPlanLbLastFocus.focus === 'function') {
                _mgPlanLbLastFocus.focus();
            }
        }, 280);
    };

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            var lb = document.getElementById('mg-plan-lightbox');
            if (lb && lb.style.display !== 'none') mgClosePlanLightbox();
        }
    });
})();
</script>
