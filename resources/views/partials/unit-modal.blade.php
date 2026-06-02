{{-- ══════════════════════════════════════════════════════════════
    UNIT DETAIL MODAL — Phase 7
    Slide-in panel triggered by mgOpenUnit(unitId).
    Triggers: Hinnad table rows / mobile cards / Asendiplaan list items.
    Data source: window.mgUnitsData (exported from config/magnoolia.php).
    UX flow: open → view unit → CTA → prefill contact form → scroll to #kontakt.
    ══════════════════════════════════════════════════════════════ --}}
@php
    $floor1 = asset('assets/images/magnoolia/PR03023_PP_AR-5-01_Esimese korruse plaan_page-0001.jpg');
    $floor2 = asset('assets/images/magnoolia/PR03023_PP_AR-5-02_Teise korruse plaan_page-0001.jpg');
@endphp

{{-- ── Unit data islands (JSON) ──────────────────────────────── --}}
<script>
window.mgUnitsData  = @json(config('magnoolia.units'));
window.mgStagesData = @json(config('magnoolia.stages'));
window.mgI18n       = @json(__('magnoolia.modal'));
window.mgFloor1Img  = '{{ $floor1 }}';
window.mgFloor2Img  = '{{ $floor2 }}';
window._mgCurrentUnit = null;
window._mgLastFocus = null;
</script>

{{-- ── Modal overlay ──────────────────────────────────────────── --}}
<div id="mg-modal-overlay"
     role="dialog"
     aria-modal="true"
     aria-labelledby="mg-unit-title"
     aria-hidden="true"
     tabindex="-1"
     style="display:none;position:fixed;inset:0;z-index:9000;">

    {{-- Backdrop --}}
    <div id="mg-modal-backdrop"
         onclick="mgCloseUnit()"
         style="position:absolute;inset:0;background:rgba(13,18,28,.65);backdrop-filter:blur(2px);"></div>

    {{-- Slide-in panel --}}
    <div id="mg-modal-panel"
         style="position:absolute;top:0;right:0;width:480px;max-width:100vw;height:100%;
                background:#ffffff;overflow-y:auto;
                box-shadow:-12px 0 60px rgba(0,0,0,.25);
                transform:translateX(100%);transition:transform .35s cubic-bezier(.4,0,.2,1);
                display:flex;flex-direction:column;">

        {{-- ── Panel header ──────────────────────────────────── --}}
        <div style="padding:28px 28px 20px;border-bottom:1px solid rgba(29,36,48,.08);flex-shrink:0;">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:12px;">
                <div style="flex:1;min-width:0;">
                    <div id="mg-unit-stage-badge" style="margin-bottom:8px;"></div>
                    <h2 id="mg-unit-title"
                        style="font-size:22px;font-weight:700;color:#1d2430;margin:0 0 10px;line-height:1.2;"></h2>
                    <div id="mg-unit-status-row"
                         style="display:flex;align-items:center;flex-wrap:wrap;gap:10px;"></div>
                </div>
                <button onclick="mgCloseUnit()"
                        aria-label="{{ __('magnoolia.modal.close_label') }}"
                        id="mg-modal-close-btn"
                        style="width:40px;height:40px;border-radius:50%;border:1.5px solid rgba(29,36,48,.15);
                               background:transparent;cursor:pointer;display:flex;align-items:center;
                               justify-content:center;font-size:18px;color:#6f6a61;flex-shrink:0;
                               transition:background .2s,border-color .2s;"
                        onmouseover="this.style.background='#f5f0e5';this.style.borderColor='#c89443'"
                        onmouseout="this.style.background='transparent';this.style.borderColor='rgba(29,36,48,.15)'">
                    ×
                </button>
            </div>
        </div>

        {{-- ── Tabs ───────────────────────────────────────────── --}}
        <div style="flex-shrink:0;border-bottom:1px solid rgba(29,36,48,.08);">
            <div style="display:flex;padding:0 28px;gap:0;">
                <button onclick="mgTab(0)" id="mg-tab-0"
                        style="padding:14px 18px;border:none;border-bottom:2px solid #c89443;margin-bottom:-1px;
                               background:none;cursor:pointer;font-size:14px;font-weight:600;color:#1d2430;
                               transition:color .2s,border-color .2s;">
                    {{ __('magnoolia.modal.tab_overview') }}
                </button>
                <button onclick="mgTab(1)" id="mg-tab-1"
                        style="padding:14px 18px;border:none;border-bottom:2px solid transparent;margin-bottom:-1px;
                               background:none;cursor:pointer;font-size:14px;font-weight:600;color:#9a9490;
                               transition:color .2s,border-color .2s;">
                    {{ __('magnoolia.modal.tab_floor1') }}
                </button>
                <button onclick="mgTab(2)" id="mg-tab-2"
                        style="padding:14px 18px;border:none;border-bottom:2px solid transparent;margin-bottom:-1px;
                               background:none;cursor:pointer;font-size:14px;font-weight:600;color:#9a9490;
                               transition:color .2s,border-color .2s;">
                    {{ __('magnoolia.modal.tab_floor2') }}
                </button>
            </div>
        </div>

        {{-- ── Tab: Ülevaade ─────────────────────────────────── --}}
        <div id="mg-panel-overview" style="padding:24px 28px;flex:1;">

            {{-- Specs grid --}}
            <div id="mg-specs-grid"
                 style="display:grid;grid-template-columns:1fr 1fr;gap:1px;background:rgba(29,36,48,.08);
                        border-radius:12px;overflow:hidden;margin-bottom:16px;">
                {{-- populated by JS --}}
            </div>

            {{-- Plan type link (shown only when plan_type is known) --}}
            <div id="mg-modal-plan-link-row" style="display:none;margin-bottom:20px;">
                {{-- populated by JS --}}
            </div>

            {{-- Technical features --}}
            <div style="margin-bottom:24px;">
                <h4 style="font-size:12px;font-weight:700;color:#9a9490;text-transform:uppercase;
                           letter-spacing:.08em;margin-bottom:14px;">{{ __('magnoolia.modal.tech_heading') }}</h4>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;">
                    @foreach([
                        ['icon' => 'icon-trophy',    'text' => __('magnoolia.modal.feat_energy')],
                        ['icon' => 'icon-flooring',  'text' => __('magnoolia.modal.feat_floor_heat')],
                        ['icon' => 'icon-house',     'text' => __('magnoolia.modal.feat_ventilation')],
                        ['icon' => 'icon-real-estate','text' => __('magnoolia.modal.feat_heat_pump')],
                        ['icon' => 'icon-garage',    'text' => __('magnoolia.modal.feat_ev')],
                        ['icon' => 'icon-real-estate','text' => __('magnoolia.modal.feat_solar')],
                    ] as $feat)
                    <div style="display:flex;align-items:center;gap:10px;background:#f7f4ef;border-radius:8px;padding:10px 12px;">
                        <i class="{{ $feat['icon'] }}"
                           style="color:#c89443;font-size:14px;width:16px;flex-shrink:0;"></i>
                        <span style="font-size:12px;color:#4a4540;line-height:1.3;">{{ $feat['text'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Price block --}}
            <div id="mg-unit-price-row"
                 style="background:#f7f4ef;border-radius:12px;padding:18px 20px;margin-bottom:16px;">
                {{-- populated by JS --}}
            </div>

            <div id="mg-fit-note"
                 style="background:#fff8ec;border:1px solid rgba(200,148,67,.35);border-radius:10px;
                        padding:14px 16px;margin-bottom:16px;display:none;">
                {{-- populated by JS --}}
            </div>

            {{-- Disclaimer --}}
            <p style="font-size:12px;color:#9a9490;line-height:1.7;margin:0;">
                {{ __('magnoolia.modal.plan_disclaimer') }}
            </p>
        </div>

        {{-- ── Tab: 1. korrus ────────────────────────────────── --}}
        <div id="mg-panel-floor1" style="padding:24px 28px;flex:1;display:none;">
            <h4 style="font-size:14px;font-weight:700;color:#1d2430;margin-bottom:16px;">{{ __('magnoolia.modal.tab_floor1') }}</h4>
            <a id="mg-floor1-link" href="{{ $floor1 }}" class="img-popup"
               data-event="floor_plan_view" data-floor="1">
                <img id="mg-floor1-img"
                     src="{{ $floor1 }}"
                     alt="{{ __('magnoolia.modal.floor1_alt') }}"
                     loading="lazy"
                     width="424" height="413"
                     style="width:100%;height:auto;border-radius:8px;border:1px solid rgba(29,36,48,.1);">
            </a>
            <div style="margin-top:16px;">
                <a id="mg-floor1-download"
                   href="{{ $floor1 }}"
                   download
                   data-event="floor_plan_download" data-floor="1"
                   style="display:inline-flex;align-items:center;gap:8px;font-size:13px;
                          color:#c89443;font-weight:600;text-decoration:none;">
                    <i class="icon-download"></i>
                    {{ __('magnoolia.modal.download') }}
                </a>
            </div>
            <p style="font-size:12px;color:#9a9490;margin-top:16px;line-height:1.7;">
                {{ __('magnoolia.modal.floor_disclaimer') }}
            </p>
        </div>

        {{-- ── Tab: 2. korrus ────────────────────────────────── --}}
        <div id="mg-panel-floor2" style="padding:24px 28px;flex:1;display:none;">
            <h4 style="font-size:14px;font-weight:700;color:#1d2430;margin-bottom:16px;">{{ __('magnoolia.modal.tab_floor2') }}</h4>
            <a id="mg-floor2-link" href="{{ $floor2 }}" class="img-popup"
               data-event="floor_plan_view" data-floor="2">
                <img id="mg-floor2-img"
                     src="{{ $floor2 }}"
                     alt="{{ __('magnoolia.modal.floor2_alt') }}"
                     loading="lazy"
                     width="371" height="428"
                     style="width:100%;height:auto;border-radius:8px;border:1px solid rgba(29,36,48,.1);">
            </a>
            <div style="margin-top:16px;">
                <a id="mg-floor2-download"
                   href="{{ $floor2 }}"
                   download
                   data-event="floor_plan_download" data-floor="2"
                   style="display:inline-flex;align-items:center;gap:8px;font-size:13px;
                          color:#c89443;font-weight:600;text-decoration:none;">
                    <i class="icon-download"></i>
                    {{ __('magnoolia.modal.download') }}
                </a>
            </div>
            <p style="font-size:12px;color:#9a9490;margin-top:16px;line-height:1.7;">
                {{ __('magnoolia.modal.floor_disclaimer') }}
            </p>
        </div>

        {{-- ── CTA footer (sticky) ───────────────────────────── --}}
        <div style="padding:20px 28px;border-top:1px solid rgba(29,36,48,.08);
                    flex-shrink:0;background:#fff;position:sticky;bottom:0;">
            <button id="mg-modal-cta-btn"
                    class="zoomvilla-btn"
                    data-event="unit_modal_cta"
                    style="width:100%;justify-content:center;border:none;cursor:pointer;
                           font-size:15px;padding:14px;margin-bottom:10px;">
                {{ __('magnoolia.modal.cta_main') }} <i class="icon-angle-small-right"></i>
            </button>
            <a href="tel:+37258164078"
               data-event="phone_click" data-page="unit_modal"
               style="display:flex;align-items:center;justify-content:center;gap:8px;
                      font-size:14px;font-weight:600;color:#6f6a61;text-decoration:none;
                      padding:8px;border-radius:8px;transition:color .2s;"
               onmouseover="this.style.color='#c89443'" onmouseout="this.style.color='#6f6a61'">
                <i class="fas fa-phone" style="color:#c89443;font-size:13px;"></i>
                {{ __('magnoolia.modal.cta_phone') }}
            </a>
        </div>
    </div>
</div>

{{-- ── Modal JavaScript ─────────────────────────────────────── --}}
<script>
(function () {
    var I18N = window.mgI18n || {};
    var LABELS = {
        available: I18N.status_available || 'Vaba',
        reserved:  I18N.status_reserved  || 'Broneeritud',
        sold:      I18N.status_sold      || 'Müüdud',
        tbc:       I18N.status_tbc       || 'Täpsustamisel'
    };
    var COLORS = {
        available: { bg: '#e8f5e9', color: '#2e7d32' },
        reserved:  { bg: '#fff3e0', color: '#e65100' },
        sold:      { bg: '#eeeeee', color: '#757575' },
        tbc:       { bg: '#f0ebe3', color: '#8a7e6e' }
    };
    var STAGE_BG = { '1': '#1d2430', '2': '#2c3441' };

    function specCell(label, value) {
        return '<div style="background:#fff;padding:14px 16px;">'
            + '<div style="font-size:11px;color:#9a9490;text-transform:uppercase;'
            + 'letter-spacing:.05em;margin-bottom:4px;">' + label + '</div>'
            + '<div style="font-size:16px;font-weight:700;color:#1d2430;">' + (value || '—') + '</div>'
            + '</div>';
    }

    function fmtArea(v) {
        return v ? (parseFloat(v).toFixed(1) + ' m²') : null;
    }

    function fitNote(unit, status) {
        var stageMsg = String(unit.stage || 1) === '1'
            ? (I18N.stage1_advantage || 'I etapi kodu eelis on varasem valmimine (kevad 2027).')
            : (I18N.stage2_advantage || 'II etapi kodu eelis on hilisem etapp ja paindlikum valik.');

        if (status === 'sold') {
            return I18N.fit_sold || 'See kodu on müüdud. Vaata vabu kodusid samas arenduses.';
        }
        if (status === 'reserved') {
            return (I18N.fit_reserved || '%s Kodu on hetkel broneeritud.').replace('%s', stageMsg);
        }
        if (status === 'tbc') {
            return (I18N.fit_tbc || '%s Detailid on täpsustamisel.').replace('%s', stageMsg);
        }
        return (I18N.fit_available || '%s Kodu on saadaval.').replace('%s', stageMsg);
    }

    window.mgOpenUnit = function (unitId) {
        var units  = window.mgUnitsData  || [];
        var stages = window.mgStagesData || {};
        var unit   = null;
        for (var i = 0; i < units.length; i++) {
            if (units[i].id === unitId) { unit = units[i]; break; }
        }
        if (!unit) return;
        window._mgCurrentUnit = unit;
        window._mgLastFocus = document.activeElement;

        var st     = unit.status || 'tbc';
        var stCol  = COLORS[st]  || COLORS.tbc;
        var stLbl  = LABELS[st]  || st;
        var sNum   = String(unit.stage || 1);
        var sCfg   = stages[sNum] || null;

        /* Stage badge */
        var stageEl = document.getElementById('mg-unit-stage-badge');
        if (sCfg) {
            stageEl.innerHTML = '<span style="display:inline-flex;align-items:center;gap:6px;'
                + 'background:' + (STAGE_BG[sNum] || '#1d2430') + ';color:rgba(255,255,255,.85);'
                + 'font-size:11px;font-weight:700;padding:3px 10px;border-radius:20px;letter-spacing:.06em;">'
                + (sCfg.label || '') + ' \u00b7 ' + (sCfg.completion || '') + '</span>';
        } else {
            stageEl.innerHTML = '';
        }

        /* Title */
        document.getElementById('mg-unit-title').textContent = unit.address || '';

        /* Status row */
        document.getElementById('mg-unit-status-row').innerHTML =
            '<span style="display:inline-block;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:700;'
            + 'background:' + stCol.bg + ';color:' + stCol.color + ';">' + stLbl + '</span>'
            + (unit.completion
                ? '<span style="font-size:13px;color:#6f6a61;">' + (I18N.completion_prefix || 'Valmib') + '\u00a0' + unit.completion + '</span>'
                : '');

        /* Specs grid */
        var ptLabel = unit.plan_type === 'type-a' ? (I18N.plan_a || 'Plaan A') : (unit.plan_type === 'type-b' ? (I18N.plan_b || 'Plaan B') : (I18N.plan_tbc || 'Täpsustamisel'));
        document.getElementById('mg-specs-grid').innerHTML =
            specCell(I18N.spec_rooms      || 'Tube',       unit.rooms || '—')
            + specCell(I18N.spec_area     || 'Netopind',   fmtArea(unit.net_area) || '—')
            + specCell(I18N.spec_terrace  || 'Terrass',    fmtArea(unit.terrace_area) || '—')
            + specCell(I18N.spec_balcony  || 'R\u00f5du',  fmtArea(unit.balcony_area) || '—')
            + specCell(I18N.spec_storage  || 'Panipaik',   unit.storage_area ? fmtArea(unit.storage_area) : (I18N.plan_tbc || 'Täpsustamisel'))
            + specCell(I18N.spec_yard     || 'Hooviala',   unit.private_yard_area ? fmtArea(unit.private_yard_area) : (I18N.plan_tbc || 'Täpsustamisel'))
            + specCell(I18N.spec_parking  || 'Parkimine',  (unit.parking || 2) + '\u00d7')
            + specCell(I18N.spec_completion || 'Valmimine', unit.completion || (I18N.plan_tbc || 'Täpsustamisel'))
            + specCell(I18N.spec_plan_type || 'Plaanit\u00fc\u00fcp', ptLabel);

        /* Plan link row */
        var planLinkRow = document.getElementById('mg-modal-plan-link-row');
        if (planLinkRow) {
            if (unit.plan_type) {
                planLinkRow.style.display = 'flex';
                planLinkRow.style.alignItems = 'center';
                planLinkRow.style.gap = '10px';
                planLinkRow.style.flexWrap = 'wrap';
                var planHlType = unit.plan_type || '';
                planLinkRow.innerHTML =
                    '<a href="#plaanid" onclick="mgCloseUnit();setTimeout(function(){mgHighlightPlan(\'' + planHlType + '\')},450);return true;" '
                    + 'style="display:inline-flex;align-items:center;gap:6px;font-size:13px;font-weight:600;'
                    + 'color:#c89443;text-decoration:none;" '
                    + 'aria-label="' + (I18N.view_plan_aria || 'Vaata korrusplaane') + '">'
                    + '<i class="icon-real-estate" aria-hidden="true"></i>'
                    + (I18N.view_plan_label || 'Vaata %s plaani').replace('%s', ptLabel)
                    + '</a>';
            } else {
                planLinkRow.style.display = 'none';
                planLinkRow.innerHTML = '';
            }
        }

        /* Price */
        var priceEl = document.getElementById('mg-unit-price-row');
        if (unit.price) {
            priceEl.innerHTML =
                '<div style="display:flex;justify-content:space-between;align-items:center;">'
                + '<span style="font-size:13px;color:#6f6a61;">' + (I18N.price_label || 'Hind') + '</span>'
                + '<span style="font-size:24px;font-weight:700;color:#1d2430;">\u20ac\u00a0'
                + Number(unit.price).toLocaleString('et-EE') + '</span>'
                + '</div>';
        } else {
            priceEl.innerHTML =
                '<div style="display:flex;justify-content:space-between;align-items:center;">'
                + '<span style="font-size:13px;color:#6f6a61;">' + (I18N.price_label || 'Hind') + '</span>'
                + '<span style="font-size:16px;font-weight:700;color:#c89443;">' + (I18N.price_tbc || 'Hind täpsustamisel') + '</span>'
                + '</div>';
        }

        var fit = document.getElementById('mg-fit-note');
        if (fit) {
            fit.style.display = 'block';
            fit.innerHTML = '<div style="font-size:11px;letter-spacing:.06em;text-transform:uppercase;color:#9a9490;margin-bottom:6px;">' + (I18N.fit_heading || 'Miks see kodu v\u00f5ib sobida') + '</div>'
                + '<p style="margin:0;font-size:13px;line-height:1.55;color:#4a4540;">' + fitNote(unit, st) + '</p>';
        }

        /* CTA button */
        var ctaBtn = document.getElementById('mg-modal-cta-btn');
        if (st === 'sold') {
            ctaBtn.innerHTML = (I18N.cta_sold || 'Vaata vabu kodusid') + ' <i class="icon-angle-small-right"></i>';
            ctaBtn.onclick = function () {
                mgCloseUnit();
                if (window.mgFilter) { window.mgFilter('available'); }
                setTimeout(function () {
                    var el = document.getElementById('hinnad');
                    if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }, 400);
            };
        } else if (st === 'reserved') {
            ctaBtn.innerHTML = (I18N.cta_reserved || 'K\u00fcsi saadavust') + ' <i class="icon-angle-small-right"></i>';
            ctaBtn.onclick = window.mgSelectAndContact;
        } else {
            ctaBtn.innerHTML = (I18N.cta_available || 'K\u00fcsi pakkumist') + ' <i class="icon-angle-small-right"></i>';
            ctaBtn.onclick = window.mgSelectAndContact;
        }

        /* Show modal */
        mgTab(0);
        var overlay = document.getElementById('mg-modal-overlay');
        var panel   = document.getElementById('mg-modal-panel');
        overlay.style.display = 'block';
        overlay.removeAttribute('aria-hidden');
        document.body.style.overflow = 'hidden';
        requestAnimationFrame(function () {
            panel.style.transform = 'translateX(0)';
        });
        /* Focus management */
        setTimeout(function () {
            var closeBtn = document.getElementById('mg-modal-close-btn');
            if (closeBtn) closeBtn.focus();
        }, 360);
    };

    window.mgCloseUnit = function () {
        var panel   = document.getElementById('mg-modal-panel');
        var overlay = document.getElementById('mg-modal-overlay');
        if (!overlay || overlay.style.display === 'none') return;
        panel.style.transform = 'translateX(100%)';
        setTimeout(function () {
            overlay.style.display = 'none';
            overlay.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
            window._mgCurrentUnit = null;
            if (window._mgLastFocus && typeof window._mgLastFocus.focus === 'function') {
                window._mgLastFocus.focus();
            }
        }, 360);
    };

    window.mgTab = function (idx) {
        var ids = ['mg-panel-overview', 'mg-panel-floor1', 'mg-panel-floor2'];
        var tbs = ['mg-tab-0', 'mg-tab-1', 'mg-tab-2'];
        ids.forEach(function (id, i) {
            var el = document.getElementById(id);
            if (el) el.style.display = (i === idx) ? 'block' : 'none';
        });
        tbs.forEach(function (id, i) {
            var btn = document.getElementById(id);
            if (!btn) return;
            btn.style.color            = (i === idx) ? '#1d2430'        : '#9a9490';
            btn.style.borderBottomColor = (i === idx) ? '#c89443'       : 'transparent';
            btn.style.fontWeight       = (i === idx) ? '700'            : '600';
        });
    };

    window.mgSelectAndContact = function () {
        var unit = window._mgCurrentUnit;
        if (unit) {
            /* Prefill select */
            var sel = document.getElementById('mg-selected-unit-select');
            if (sel) {
                for (var i = 0; i < sel.options.length; i++) {
                    if (sel.options[i].value === unit.address) {
                        sel.selectedIndex = i;
                        break;
                    }
                }
            }
            /* Prefill message textarea if empty */
            var msg = document.querySelector('textarea[name="message"]');
            if (msg && msg.value.trim() === '') {
                msg.value = (I18N.prefill_greeting || 'Tere! Soovin lisainfot valitud Magnoolia kodu kohta: %s.').replace('%s', unit.address) + '\n'
                    + (I18N.prefill_action || 'Palun saatke t\u00e4pne saadavus, plaan ja pakkumine.');
            }
        }
        mgCloseUnit();
        setTimeout(function () {
            var el = document.getElementById('kontakt');
            if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }, 400);
    };

    window.mgHighlightPlan = function (planType) {
        var typeMap = { 'type-a': 'plaan-a', 'type-b': 'plaan-b' };
        var key = typeMap[planType] || planType;
        var cards = document.querySelectorAll('[data-plan-type]');
        var target = null;
        cards.forEach(function (card) {
            if (card.dataset.planType === key) { target = card; }
        });
        if (!target) return;
        target.scrollIntoView({ behavior: 'smooth', block: 'center' });
        target.classList.add('is-plan-highlighted');
        setTimeout(function () { target.classList.remove('is-plan-highlighted'); }, 2800);
    };

    /* ESC key */
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            var overlay = document.getElementById('mg-modal-overlay');
            if (overlay && overlay.style.display !== 'none') mgCloseUnit();
        }
    });
})();
</script>
