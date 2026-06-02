# Phase 20 — Production Readiness Audit — COMPLETE

**Date:** 2025  
**Scope:** Language purity 2.0, domain alignment, contact form hardening, SEO/AEO, tracking, performance baseline, forbidden content audit

---

## 1. Files Changed

### Config / Environment
- `.env` — Fixed duplicate MAIL entries (localhost nulls overriding mailtrap), added `MAGNOOLIA_CANONICAL_DOMAIN=https://magnoolia.ee`, `MAGNOOLIA_NOINDEX=true`
- `config/magnoolia.php` — Fixed wrong comment (tee-1=4,tee-3=3 → tee-1=3,tee-3=4)

### Lang Files (all 3: et / ru / en)
- `lang/et/magnoolia.php` — Added `forms.unit_none`, `footer.col_developer`, `footer.legal_nav_label`, `nav.main_nav_aria`, `nav.logo_back_aria`, `nav.header_cta`, `nav.mobile_menu`, `modal.*` (56 keys)
- `lang/ru/magnoolia.php` — Same keys, RU translations
- `lang/en/magnoolia.php` — Same keys, EN translations

### Views / Layout
- `resources/views/layouts/app.blade.php` — Added `@stack('head')` (critical: fixes /aitah noindex), LCP preload for hero image, reduced motion CSS, dataLayer tracking bridge
- `resources/views/components/site-footer.blade.php` — All 6 hardcoded ET strings replaced with `__()` calls
- `resources/views/components/site-header.blade.php` — All 4 hardcoded ET strings (aria-labels, CTA label) replaced with `__()` calls
- `resources/views/partials/unit-modal.blade.php` — Injected `window.mgI18n = @json(__('magnoolia.modal'))`, replaced all ~40 hardcoded ET strings in HTML + JS
- `resources/views/partials/mobile-cta.blade.php` — Replaced 3 hardcoded ET strings

### Public
- `public/sitemap.xml` — Created (36 URLs, 13 pages × 3 locales, hreflang alternates)

---

## 2. Data Truth Audit

**Unit structure verified correct:**
| Building | Units | Stage |
|----------|-------|-------|
| Magnoolia tee 1 | tee-1-1, tee-1-2, tee-1-3 | I |
| Magnoolia tee 3 | tee-3-1, tee-3-2, tee-3-3, tee-3-4 | I |
| Magnoolia tee 5 | tee-5-1, tee-5-2, tee-5-3 | II |
| Magnoolia tee 7 | tee-7-1, tee-7-2, tee-7-3 | II |
| Magnoolia tee 9 | tee-9-1, tee-9-2, tee-9-3 | II |
| Magnoolia tee 11 | tee-11-1, tee-11-2, tee-11-3 | II |
| **Total** | **19 homes** | I=7, II=12 |

Config comment corrected: was "tee-1 (4 homes) + tee-3 (3 homes)" → now "tee-1 (3 homes) + tee-3 (4 homes)"

---

## 3. Domain / SEO Audit

- `MAGNOOLIA_CANONICAL_DOMAIN=https://magnoolia.ee` → all canonicals now correct
- `MAGNOOLIA_NOINDEX=true` → staging is noindex; set to `false` on production deploy
- `/aitah` → double-protected: `@push('head')` noindex + MAGNOOLIA_NOINDEX=true (layout now has `@stack('head')`)
- `public/sitemap.xml` created — 36 URLs with xhtml:link hreflang alternates
- `public/robots.txt` already had `Sitemap: https://magnoolia.ee/sitemap.xml` — now points to real file

---

## 4. Language Purity 2.0

**Shared components (shown on all locales) — all fixed:**
- `site-footer.blade.php` — copyright, column headings, link labels → `__()` ✅
- `site-header.blade.php` — nav aria-label, logo back-link, CTA label, mobile toggle → `__()` ✅
- `unit-modal.blade.php` — ALL JS/HTML strings (status labels, spec labels, features, disclaimers, CTA, prefill message) → `window.mgI18n` via `@json(__('magnoolia.modal'))` ✅
- `mobile-cta.blade.php` — sticky bar labels → `__()` ✅

**ET-only page blades (used for ET locale only — acceptable):**
- `asukoht.blade.php`, `arhitektuur.blade.php`, `galerii.blade.php`, etc. — contain ET strings in JSON-LD schema and alt attributes. These pages are only rendered for ET locale route (`/asukoht` etc.), not for `/ru/asukoht` or `/en/asukoht`. ✅

**Non-live files (contain fake content but are never rendered):**
- `contact-team-source.blade.php` — fake names (Ralph Havens, Louis Coolidge) — NOT included anywhere ✅
- `styleguide.blade.php` — Lorem ipsum — NOT included anywhere ✅
- `unit-detail-source.blade.php` — ET text — NOT included anywhere ✅

---

## 5. Contact Form

- Honeypot field: `website` ✅
- Rate limiter: 3 submissions / 600 seconds ✅
- DB logging: `magnoolia_leads` table (MagnooliaLead model) ✅
- Email subject: `"Magnoolia päring — {$unitLabel} — {$locale}"` ✅
- `unit_none` fallback: `__('magnoolia.forms.unit_none')` → ET='Üldine päring', RU='Общая заявка', EN='General enquiry' ✅
- Mail config: Mailtrap sandbox (sandbox.smtp.mailtrap.io:2525) — duplicate .env entries removed ✅
- Redirect: → `/aitah` (localized) ✅

---

## 6. Tracking (dataLayer)

Added JS bridge in `layouts/app.blade.php`:
- `[data-event]` click tracking → pushes `{event, unit_id, unit, locale, cta, page_url}`
- `contact_form_start` on first form focus
- `contact_form_submit` on form submit

---

## 7. Performance Baseline

- LCP image preload added for `/` (homepage): `Cam001.0000.jpg` with `fetchpriority="high"`
- `@media (prefers-reduced-motion: reduce)` CSS block added globally

---

## 8. Forbidden Content

**P0 Rules — all verified:**
- ✅ No fake prices (no Offer schema, no price digits in schema.blade.php)
- ✅ No `info@magnoolia.ee` (using `diana@estlanda.ee` from config)
- ✅ No "suvi 2027" (using "kevad 2027")
- ✅ No Lorem ipsum in live pages
- ✅ No fake names in live pages
- ✅ `aggregateRating` not present in schema
- ✅ `/aitah` is noindex (via `@push('head')` + layout `@stack('head')`)

---

## 9. Production Checklist Deltas

When switching to production:
1. Set `MAGNOOLIA_NOINDEX=false` in production `.env`
2. Set `APP_URL=https://magnoolia.ee`
3. Set `MAGNOOLIA_CANONICAL_DOMAIN=https://magnoolia.ee`
4. Point MAIL config to production mail provider
5. Run `php artisan config:cache` and `php artisan route:cache`
6. Verify sitemap is accessible at `https://magnoolia.ee/sitemap.xml`
7. Submit sitemap to Google Search Console
