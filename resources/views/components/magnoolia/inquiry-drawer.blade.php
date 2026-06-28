{{--
    magnoolia/inquiry-drawer.blade.php

    Phase 26 — Inquiry drawer/modal.
    Triggered by any element with [data-mg-inquiry-open].
    Collects unit context from data attributes on the trigger.
    Falls back gracefully when JS is disabled.

    Include once in layouts.app before </body>.
--}}
@php
  $phone          = config('magnoolia.project.contact_phone', '+37258164078');
  $phoneFormatted = '+372 58 16 40 78';
  $email          = config('magnoolia.project.contact_email', 'diana@estlanda.ee');
  $locale         = app()->getLocale();
  $campaign       = config('magnoolia.campaign', []);
  $showCampaign   = !empty($campaign['enabled']) && !empty($campaign['amount_eur']);
@endphp

{{-- Overlay backdrop --}}
<div id="mg-inquiry-overlay"
     role="dialog"
     aria-modal="true"
     aria-labelledby="mg-inquiry-title"
     style="display:none;position:fixed;inset:0;z-index:9500;background:rgba(0,0,0,.55);">

  {{-- Drawer panel (right side) --}}
  <div id="mg-inquiry-panel"
       style="position:absolute;top:0;right:0;width:min(480px,100vw);height:100%;background:#fff;box-shadow:-8px 0 32px rgba(0,0,0,.18);overflow-y:auto;display:flex;flex-direction:column;">

    {{-- Header --}}
    <div style="background:#1d2430;padding:28px 32px 20px;flex-shrink:0;">
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
        <div style="font-size:11px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#c89443;">
          Magnoolia
        </div>
        <button id="mg-inquiry-close"
                type="button"
                aria-label="{{ __('magnoolia.inquiry.close_label') }}"
                style="background:none;border:none;cursor:pointer;color:#fff;font-size:22px;line-height:1;padding:4px 8px;">
          &#x2715;
        </button>
      </div>
      <h2 id="mg-inquiry-title" style="font-size:20px;font-weight:700;color:#fff;margin:0 0 8px;">
        {{ __('magnoolia.inquiry.title') }}
      </h2>
      <p style="font-size:13px;color:rgba(255,255,255,.7);margin:0;" id="mg-inquiry-unit-hint"></p>
    </div>

    {{-- Campaign ribbon --}}
    @if($showCampaign)
    <div style="background:#c89443;padding:10px 32px;font-size:12px;font-weight:700;color:#fff;letter-spacing:.04em;flex-shrink:0;">
      @if($locale === 'ru') {{ $campaign['body_short_ru'] ?? '' }}
      @elseif($locale === 'en') {{ $campaign['body_short_en'] ?? '' }}
      @else {{ $campaign['body_short_et'] ?? '' }}
      @endif
    </div>
    @endif

    {{-- Form --}}
    <div style="padding:28px 32px;flex:1;">
      <form id="mg-inquiry-form"
            method="POST"
            action="{{ lroute('magnoolia.contact.send') }}"
            novalidate>
        @csrf

        {{-- Hidden context fields --}}
        <input type="hidden" name="unit_key"          id="mg-ctx-unit-key"       value="">
        <input type="hidden" name="unit_slug"         id="mg-ctx-unit-slug"      value="">
        <input type="hidden" name="unit_address"      id="mg-ctx-unit-address"   value="">
        <input type="hidden" name="stage"             id="mg-ctx-stage"          value="">
        <input type="hidden" name="status"            id="mg-ctx-status"         value="">
        <input type="hidden" name="price_public"      id="mg-ctx-price-public"   value="">
        <input type="hidden" name="source_component"  id="mg-ctx-source"         value="">
        <input type="hidden" name="published_version" id="mg-ctx-pub-version"    value="{{ $mgPublic['meta']['version'] ?? '' }}">
        <input type="hidden" name="locale"            value="{{ $locale }}">
        <input type="hidden" name="utm_source"        id="mg-ctx-utm-source"     value="">
        <input type="hidden" name="utm_medium"        id="mg-ctx-utm-medium"     value="">
        <input type="hidden" name="utm_campaign"      id="mg-ctx-utm-campaign"   value="">
        <input type="hidden" name="page_url"          id="mg-ctx-page-url"       value="">
        <input type="hidden" name="referrer"          id="mg-ctx-referrer"       value="">
        <input type="hidden" name="form_type"         value="inquiry_drawer">

        <div style="margin-bottom:16px;">
          <label for="mg-inq-name" style="display:block;font-size:12px;font-weight:600;color:#555;margin-bottom:6px;text-transform:uppercase;letter-spacing:.06em;">
            {{ __('magnoolia.form.name') }} <span style="color:#c89443;">*</span>
          </label>
          <input type="text" id="mg-inq-name" name="name" required autocomplete="name"
                 style="width:100%;padding:12px 16px;border:1.5px solid #ddd;border-radius:8px;font-size:15px;outline:none;transition:border-color .2s;"
                 placeholder="{{ __('magnoolia.form.name_placeholder') }}">
        </div>

        <div style="margin-bottom:16px;">
          <label for="mg-inq-phone" style="display:block;font-size:12px;font-weight:600;color:#555;margin-bottom:6px;text-transform:uppercase;letter-spacing:.06em;">
            {{ __('magnoolia.form.phone') }} <span style="color:#c89443;">*</span>
          </label>
          <input type="tel" id="mg-inq-phone" name="phone" required autocomplete="tel"
                 style="width:100%;padding:12px 16px;border:1.5px solid #ddd;border-radius:8px;font-size:15px;outline:none;transition:border-color .2s;"
                 placeholder="+372 ...">
        </div>

        <div style="margin-bottom:16px;">
          <label for="mg-inq-email" style="display:block;font-size:12px;font-weight:600;color:#555;margin-bottom:6px;text-transform:uppercase;letter-spacing:.06em;">
            {{ __('magnoolia.form.email') }}
          </label>
          <input type="email" id="mg-inq-email" name="email" autocomplete="email"
                 style="width:100%;padding:12px 16px;border:1.5px solid #ddd;border-radius:8px;font-size:15px;outline:none;transition:border-color .2s;"
                 placeholder="email@example.com">
        </div>

        <div style="margin-bottom:20px;">
          <label for="mg-inq-message" style="display:block;font-size:12px;font-weight:600;color:#555;margin-bottom:6px;text-transform:uppercase;letter-spacing:.06em;">
            {{ __('magnoolia.form.message') }}
          </label>
          <textarea id="mg-inq-message" name="message" rows="3"
                    style="width:100%;padding:12px 16px;border:1.5px solid #ddd;border-radius:8px;font-size:15px;resize:vertical;outline:none;transition:border-color .2s;"
                    placeholder="{{ __('magnoolia.form.message_placeholder') }}"></textarea>
        </div>

        <div style="margin-bottom:20px;display:flex;align-items:flex-start;gap:10px;">
          <input type="checkbox" id="mg-inq-consent" name="consent" required
                 style="width:16px;height:16px;margin-top:2px;flex-shrink:0;accent-color:#c89443;">
          <label for="mg-inq-consent" style="font-size:12px;color:#666;line-height:1.5;">
            {!! __('magnoolia.form.consent_html') !!}
          </label>
        </div>

        <button type="submit"
                data-mg-analytics="magnoolia_form_submit"
                style="width:100%;padding:14px 24px;background:#c89443;color:#fff;border:none;border-radius:8px;font-size:15px;font-weight:700;letter-spacing:.04em;cursor:pointer;transition:background .2s;">
          {{ __('magnoolia.inquiry.submit_label') }}
        </button>

        <div id="mg-inq-error" style="display:none;margin-top:12px;padding:12px;background:#fef2f2;border:1px solid #fecaca;border-radius:8px;font-size:13px;color:#dc2626;"></div>
        <div id="mg-inq-success" style="display:none;margin-top:12px;padding:16px;background:#f0fdf4;border:1px solid #86efac;border-radius:8px;font-size:14px;color:#166534;text-align:center;">
          {{ __('magnoolia.inquiry.success_message') }}
        </div>
      </form>

      {{-- Direct contact fallback --}}
      <div style="margin-top:24px;padding-top:24px;border-top:1px solid #eee;text-align:center;">
        <div style="font-size:12px;color:#aaa;margin-bottom:10px;">{{ __('magnoolia.inquiry.or_direct') }}</div>
        <a href="tel:{{ $phone }}"
           data-mg-analytics="magnoolia_phone_click"
           style="display:inline-flex;align-items:center;gap:8px;font-size:16px;font-weight:700;color:#1d2430;text-decoration:none;margin-bottom:8px;">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 13a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 9.57a16 16 0 0 0 6.29 6.29l.94-.94a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
          {{ $phoneFormatted }}
        </a>
        <br>
        <a href="mailto:{{ $email }}"
           data-mg-analytics="magnoolia_email_click"
           style="font-size:13px;color:#c89443;text-decoration:none;">{{ $email }}</a>
      </div>
    </div>

    {{-- Diana Tali contact --}}
    <div style="padding:20px 32px;background:#f8f7f4;border-top:1px solid #ece9e1;flex-shrink:0;">
      <div style="display:flex;align-items:center;gap:16px;">
        {{-- Diana photo placeholder (real photo pending delivery) --}}
        <div style="width:52px;height:52px;border-radius:50%;background:#e5ddd0;flex-shrink:0;overflow:hidden;display:flex;align-items:center;justify-content:center;">
          @if(file_exists(public_path('assets/magnoolia/people/diana-tali.webp')))
            <img src="{{ asset('assets/magnoolia/people/diana-tali.webp') }}"
                 alt="Diana Tali"
                 width="52" height="52"
                 style="width:100%;height:100%;object-fit:cover;">
          @else
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#9c8b7e" stroke-width="1.5" aria-hidden="true"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
          @endif
        </div>
        <div>
          <div style="font-size:14px;font-weight:700;color:#1d2430;">Diana Tali</div>
          <div style="font-size:12px;color:#888;">{{ __('magnoolia.contact.sales_title') }}</div>
        </div>
      </div>
    </div>

  </div>
</div>

{{-- No-JS fallback link (hidden by default, shown when JS fails) --}}
<noscript>
  <style>#mg-inquiry-overlay{display:none!important}</style>
</noscript>

@push('scripts')
<script>
(function () {
  var overlay  = document.getElementById('mg-inquiry-overlay');
  var panel    = document.getElementById('mg-inquiry-panel');
  var closeBtn = document.getElementById('mg-inquiry-close');
  var form     = document.getElementById('mg-inquiry-form');
  var hint     = document.getElementById('mg-inquiry-unit-hint');
  if (!overlay) return;

  function open(trigger) {
    var key     = (trigger && trigger.dataset.unitKey)    || '';
    var slug    = (trigger && trigger.dataset.unitSlug)   || '';
    var address = (trigger && trigger.dataset.unitAddress)|| '';
    var stage   = (trigger && trigger.dataset.unitStage)  || '';
    var status  = (trigger && trigger.dataset.unitStatus) || '';
    var pub     = (trigger && trigger.dataset.unitPricePublic) !== 'false';
    var src     = (trigger && trigger.dataset.sourceComponent) || 'unknown';

    document.getElementById('mg-ctx-unit-key').value     = key;
    document.getElementById('mg-ctx-unit-slug').value    = slug;
    document.getElementById('mg-ctx-unit-address').value = address;
    document.getElementById('mg-ctx-stage').value        = stage;
    document.getElementById('mg-ctx-status').value       = status;
    document.getElementById('mg-ctx-price-public').value = pub ? '1' : '0';
    document.getElementById('mg-ctx-source').value       = src;
    document.getElementById('mg-ctx-page-url').value     = window.location.href;
    document.getElementById('mg-ctx-referrer').value     = document.referrer || '';

    var p = new URLSearchParams(window.location.search);
    document.getElementById('mg-ctx-utm-source').value   = p.get('utm_source')   || '';
    document.getElementById('mg-ctx-utm-medium').value   = p.get('utm_medium')   || '';
    document.getElementById('mg-ctx-utm-campaign').value = p.get('utm_campaign') || '';

    hint.textContent = address ? address : '';
    overlay.style.display = '';
    lockScroll();

    if (window.dataLayer) {
      window.dataLayer.push({ event: 'magnoolia_form_open', source_component: src, unit_key: key });
    }
    closeBtn.focus();
  }

  // Public opener — for CTAs that must stopPropagation (e.g. inside a clickable
  // table row/card) and therefore can't rely on the delegated document handler.
  window.mgInquiryOpen = open;

  // iOS-safe scroll lock (keeps the fixed overlay at the viewport top on a scrolled
  // page). Cooperates with the home-detail modal: if the body is already pinned
  // (drawer opened on top of that modal) we don't re-lock or restore — that modal owns it.
  var lockY = 0, didLock = false;
  function lockScroll() {
    if (document.body.style.position === 'fixed') { didLock = false; return; }
    didLock = true; lockY = window.scrollY || window.pageYOffset || 0;
    var b = document.body;
    b.style.position = 'fixed'; b.style.top = (-lockY) + 'px';
    b.style.left = '0'; b.style.right = '0'; b.style.width = '100%'; b.style.overflow = 'hidden';
  }
  function unlockScroll() {
    if (!didLock) return;
    didLock = false;
    var b = document.body;
    b.style.position = ''; b.style.top = ''; b.style.left = ''; b.style.right = ''; b.style.width = ''; b.style.overflow = '';
    window.scrollTo(0, lockY);
  }

  function close() {
    overlay.style.display = 'none';
    unlockScroll();
  }

  // Triggers
  document.addEventListener('click', function (e) {
    var trigger = e.target.closest('[data-mg-inquiry-open]');
    if (trigger) { e.preventDefault(); open(trigger); return; }
    // Site-wide: ANY button-style link to the contact page ("Küsi pakkumist" and
    // friends, on every page) opens this drawer instead of navigating. The href
    // stays as a no-JS fallback. Plain text/footer nav links (.mg-internal-link) and
    // the contact page's own in-page anchors are excluded.
    var cta = e.target.closest('a.zoomvilla-btn[href]');
    if (cta && !cta.classList.contains('mg-internal-link') &&
        /\/(kontakt|contact)(\?|#|$)/.test(cta.getAttribute('href') || '')) {
      e.preventDefault(); open(cta); return;
    }
    if (e.target === overlay) { close(); }
  });

  closeBtn.addEventListener('click', close);

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && overlay.style.display !== 'none') close();
  });

  // Prevent panel clicks from closing overlay
  panel.addEventListener('click', function (e) { e.stopPropagation(); });

  // Form submit via AJAX
  form.addEventListener('submit', function (e) {
    e.preventDefault();
    var btn   = form.querySelector('[type="submit"]');
    var err   = document.getElementById('mg-inq-error');
    var suc   = document.getElementById('mg-inq-success');
    err.style.display = 'none';
    suc.style.display = 'none';
    btn.disabled = true;
    btn.textContent = '...';

    fetch(form.action, {
      method: 'POST',
      body: new FormData(form),
      headers: { 'X-Requested-With': 'XMLHttpRequest' },
    })
    .then(function(r) {
      if (r.ok || r.status === 302) {
        suc.style.display = 'block';
        form.reset();
        if (window.dataLayer) {
          window.dataLayer.push({ event: 'magnoolia_form_submit',
            source_component: document.getElementById('mg-ctx-source').value,
            unit_key: document.getElementById('mg-ctx-unit-key').value });
        }
      } else {
        return r.text().then(function(t){ throw new Error(t); });
      }
    })
    .catch(function() {
      err.style.display = 'block';
      err.textContent = '{{ __('magnoolia.form.submit_error') }}';
    })
    .finally(function() {
      btn.disabled = false;
      btn.textContent = '{{ __('magnoolia.inquiry.submit_label') }}';
    });
  });

  // Analytics click tracking
  document.addEventListener('click', function(e) {
    var el = e.target.closest('[data-mg-analytics]');
    if (!el || !window.dataLayer) return;
    window.dataLayer.push({ event: el.dataset.mgAnalytics });
  });
})();
</script>
@endpush
