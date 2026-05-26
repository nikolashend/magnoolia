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
window.mgFloor1Img  = '{{ $floor1 }}';
window.mgFloor2Img  = '{{ $floor2 }}';
window._mgCurrentUnit = null;
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
                        aria-label="Sulge"
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
                    Ülevaade
                </button>
                <button onclick="mgTab(1)" id="mg-tab-1"
                        style="padding:14px 18px;border:none;border-bottom:2px solid transparent;margin-bottom:-1px;
                               background:none;cursor:pointer;font-size:14px;font-weight:600;color:#9a9490;
                               transition:color .2s,border-color .2s;">
                    1.&nbsp;korrus
                </button>
                <button onclick="mgTab(2)" id="mg-tab-2"
                        style="padding:14px 18px;border:none;border-bottom:2px solid transparent;margin-bottom:-1px;
                               background:none;cursor:pointer;font-size:14px;font-weight:600;color:#9a9490;
                               transition:color .2s,border-color .2s;">
                    2.&nbsp;korrus
                </button>
            </div>
        </div>

        {{-- ── Tab: Ülevaade ─────────────────────────────────── --}}
        <div id="mg-panel-overview" style="padding:24px 28px;flex:1;">

            {{-- Specs grid --}}
            <div id="mg-specs-grid"
                 style="display:grid;grid-template-columns:1fr 1fr;gap:1px;background:rgba(29,36,48,.08);
                        border-radius:12px;overflow:hidden;margin-bottom:24px;">
                {{-- populated by JS --}}
            </div>

            {{-- Technical features --}}
            <div style="margin-bottom:24px;">
                <h4 style="font-size:12px;font-weight:700;color:#9a9490;text-transform:uppercase;
                           letter-spacing:.08em;margin-bottom:14px;">Tehnilised lahendused</h4>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;">
                    @foreach([
                        ['icon' => 'icon-trophy',    'text' => 'A-energiaklass'],
                        ['icon' => 'icon-flooring',  'text' => 'Põrandaküte igas toas'],
                        ['icon' => 'icon-house',     'text' => 'Soojustagastusega ventilatsioon'],
                        ['icon' => 'icon-real-estate','text' => 'Maasoojuspump'],
                        ['icon' => 'icon-garage',    'text' => 'EV-laadimise valmidus'],
                        ['icon' => 'icon-real-estate','text' => 'Päikesepaneelide valmidus'],
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

            {{-- Disclaimer --}}
            <p style="font-size:12px;color:#9a9490;line-height:1.7;margin:0;">
                Plaanilahendus võib sõltuvalt kodust erineda.
                Täpse plaani saadame valitud kodu kohta.
            </p>
        </div>

        {{-- ── Tab: 1. korrus ────────────────────────────────── --}}
        <div id="mg-panel-floor1" style="padding:24px 28px;flex:1;display:none;">
            <h4 style="font-size:14px;font-weight:700;color:#1d2430;margin-bottom:16px;">1. korrus</h4>
            <a id="mg-floor1-link" href="{{ $floor1 }}" class="img-popup">
                <img id="mg-floor1-img"
                     src="{{ $floor1 }}"
                     alt="Magnoolia 1. korruse plaan"
                     loading="lazy"
                     width="424" height="413"
                     style="width:100%;height:auto;border-radius:8px;border:1px solid rgba(29,36,48,.1);">
            </a>
            <div style="margin-top:16px;">
                <a id="mg-floor1-download"
                   href="{{ $floor1 }}"
                   download
                   style="display:inline-flex;align-items:center;gap:8px;font-size:13px;
                          color:#c89443;font-weight:600;text-decoration:none;">
                    <i class="icon-download"></i>
                    Laadi alla
                </a>
            </div>
            <p style="font-size:12px;color:#9a9490;margin-top:16px;line-height:1.7;">
                Tüüplahendus. Täpne plaan sõltub valitud kodust.
            </p>
        </div>

        {{-- ── Tab: 2. korrus ────────────────────────────────── --}}
        <div id="mg-panel-floor2" style="padding:24px 28px;flex:1;display:none;">
            <h4 style="font-size:14px;font-weight:700;color:#1d2430;margin-bottom:16px;">2. korrus</h4>
            <a id="mg-floor2-link" href="{{ $floor2 }}" class="img-popup">
                <img id="mg-floor2-img"
                     src="{{ $floor2 }}"
                     alt="Magnoolia 2. korruse plaan"
                     loading="lazy"
                     width="371" height="428"
                     style="width:100%;height:auto;border-radius:8px;border:1px solid rgba(29,36,48,.1);">
            </a>
            <div style="margin-top:16px;">
                <a id="mg-floor2-download"
                   href="{{ $floor2 }}"
                   download
                   style="display:inline-flex;align-items:center;gap:8px;font-size:13px;
                          color:#c89443;font-weight:600;text-decoration:none;">
                    <i class="icon-download"></i>
                    Laadi alla
                </a>
            </div>
            <p style="font-size:12px;color:#9a9490;margin-top:16px;line-height:1.7;">
                Tüüplahendus. Täpne plaan sõltub valitud kodust.
            </p>
        </div>

        {{-- ── CTA footer (sticky) ───────────────────────────── --}}
        <div style="padding:20px 28px;border-top:1px solid rgba(29,36,48,.08);
                    flex-shrink:0;background:#fff;position:sticky;bottom:0;">
            <button id="mg-modal-cta-btn"
                    class="zoomvilla-btn"
                    style="width:100%;justify-content:center;border:none;cursor:pointer;
                           font-size:15px;padding:14px;">
                Küsi pakkumist <i class="icon-angle-small-right"></i>
            </button>
        </div>
    </div>
</div>

{{-- ── Modal JavaScript ─────────────────────────────────────── --}}
<script>
(function () {
    var LABELS = {
        available: 'Vaba',
        reserved:  'Broneeritud',
        sold:      'Müüdud',
        tbc:       'Täpsustamisel'
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

    window.mgOpenUnit = function (unitId) {
        var units  = window.mgUnitsData  || [];
        var stages = window.mgStagesData || {};
        var unit   = null;
        for (var i = 0; i < units.length; i++) {
            if (units[i].id === unitId) { unit = units[i]; break; }
        }
        if (!unit) return;
        window._mgCurrentUnit = unit;

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
                ? '<span style="font-size:13px;color:#6f6a61;">Valmib&nbsp;' + unit.completion + '</span>'
                : '');

        /* Specs grid */
        document.getElementById('mg-specs-grid').innerHTML =
            specCell('Tube',      unit.rooms || '—')
            + specCell('Netopind',  fmtArea(unit.net_area) || '—')
            + specCell('Terrass',   fmtArea(unit.terrace_area) || '—')
            + specCell('R\u00f5du',      fmtArea(unit.balcony_area) || '—')
            + specCell('Panipaik',  unit.storage_area ? fmtArea(unit.storage_area) : 'Jah')
            + specCell('Parkimine', (unit.parking || 2) + '\u00d7');

        /* Price */
        var priceEl = document.getElementById('mg-unit-price-row');
        if (unit.price) {
            priceEl.innerHTML =
                '<div style="display:flex;justify-content:space-between;align-items:center;">'
                + '<span style="font-size:13px;color:#6f6a61;">Hind</span>'
                + '<span style="font-size:24px;font-weight:700;color:#1d2430;">\u20ac\u00a0'
                + Number(unit.price).toLocaleString('et-EE') + '</span>'
                + '</div>';
        } else {
            priceEl.innerHTML =
                '<div style="display:flex;justify-content:space-between;align-items:center;">'
                + '<span style="font-size:13px;color:#6f6a61;">Hind</span>'
                + '<span style="font-size:16px;font-weight:700;color:#c89443;">K\u00fcsi hinnainfot</span>'
                + '</div>';
        }

        /* CTA button */
        var ctaBtn = document.getElementById('mg-modal-cta-btn');
        if (st === 'sold') {
            ctaBtn.innerHTML = 'Vaata vabu kodusid <i class="icon-angle-small-right"></i>';
            ctaBtn.onclick = function () {
                mgCloseUnit();
                if (window.mgFilter) { window.mgFilter('available'); }
                setTimeout(function () {
                    var el = document.getElementById('hinnad');
                    if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }, 400);
            };
        } else if (st === 'reserved') {
            ctaBtn.innerHTML = 'K\u00fcsi saadavust <i class="icon-angle-small-right"></i>';
            ctaBtn.onclick = window.mgSelectAndContact;
        } else {
            ctaBtn.innerHTML = 'K\u00fcsi pakkumist <i class="icon-angle-small-right"></i>';
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
            /* Prefill visible select in contact form */
            var sel = document.getElementById('mg-selected-unit-select');
            if (sel) {
                for (var i = 0; i < sel.options.length; i++) {
                    if (sel.options[i].value === unit.address) {
                        sel.selectedIndex = i;
                        break;
                    }
                }
            }
        }
        mgCloseUnit();
        setTimeout(function () {
            var el = document.getElementById('kontakt');
            if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }, 400);
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
