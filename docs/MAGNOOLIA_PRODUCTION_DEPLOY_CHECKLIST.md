# Magnoolia Kodud — Production Deployment Checklist

Purpose: an ordered, actionable go-live checklist for moving the Magnoolia Kodud site from staging (`magnoolia.adme.ee`) to production at `estlanda.ee/magnoolia` (canonical `magnoolia.ee`), with SEO indexing switched on and all commercial data published.

---

## 0. Pre-flight — decisions to confirm with the client first

- [ ] Confirm the **final public URL** the site is served from: `estlanda.ee/magnoolia` (path-based) **or** a dedicated host `magnoolia.ee`.
- [ ] Confirm the **canonical domain strategy**. The codebase canonicalises to `magnoolia.seo.canonical_base` (default `https://magnoolia.ee`). Decide whether canonical = `https://magnoolia.ee` (recommended, brand domain) or the `estlanda.ee/magnoolia` path.
- [ ] Confirm whoever controls DNS for `magnoolia.ee` and TLS certificates is ready.
- [ ] Confirm Diana Tali (sales) is ready to receive live enquiry emails at `diana@estlanda.ee`.
- [ ] Confirm the confirmed Stage I `hinnatabel` (price list) is loaded into the active Magnoolia version (Stage II prices remain "täpsustatakse").

---

## 1. Environment configuration (`.env` on production)

Set the following. Key names verified against `config/magnoolia.php`:

| Variable | Production value | Notes |
|---|---|---|
| `APP_ENV` | `production` | |
| `APP_DEBUG` | `false` | Never expose stack traces publicly |
| `APP_URL` | final public base URL | Used as canonical fallback |
| `MAGNOOLIA_CANONICAL_DOMAIN` | `https://magnoolia.ee` | Drives `magnoolia.seo.canonical_base` + `canonical_domain`. Set only if it differs from the default |
| `MAGNOOLIA_PUBLIC_DOMAIN` | `https://magnoolia.ee` | `seo.production_domain` source; keep consistent with canonical |
| `MAGNOOLIA_INDEXABLE` | `true` | Switches robots meta to `index,follow` |
| `MAGNOOLIA_NOINDEX` | `false` | Legacy switch — set to match (`false` = indexable) |
| `MAGNOOLIA_ENV` | `production` | `seo.env` |
| `MAGNOOLIA_STAGING_DOMAIN` | `https://magnoolia.adme.ee` | Leave as-is (used for redirect/reference only) |

- [ ] `APP_ENV=production`
- [ ] `APP_DEBUG=false`
- [ ] `APP_URL` set to the final public base URL
- [ ] `MAGNOOLIA_CANONICAL_DOMAIN=https://magnoolia.ee` (if different from default)
- [ ] `MAGNOOLIA_PUBLIC_DOMAIN` set consistently with the canonical
- [ ] **Switch to indexable**: `MAGNOOLIA_INDEXABLE=true` **and** `MAGNOOLIA_NOINDEX=false` (both, to cover the legacy path)
- [ ] `MAGNOOLIA_ENV=production`
- [ ] Verify no leftover staging overrides remain in the production `.env`

---

## 2. Sitemap gotcha — MUST verify (dynamic route, not a static file)

The `/sitemap.xml` route is **dynamic** (rendered from `resources/views/sitemap.blade.php`) and now lists 107 URLs including all 14 new landing pages. A stale **static** `public/sitemap.xml` was removed on staging. If a static file exists on production it will **shadow the route** and silently omit the new landings.

- [ ] Confirm **no** `public/sitemap.xml` file exists on the production server (only the dynamic route may serve `/sitemap.xml`).
- [ ] After deploy, fetch `/sitemap.xml` and confirm it returns the **107 URLs** and that URLs use the final canonical domain.
- [ ] Confirm the 14 Phase 34.2 landings appear (e.g. `/ridaelamud-harjumaa`, `/uusarendus-kiili`, `/en/new-townhouses-near-tallinn`, `/ru/taunhaus-rjadom-s-tallinnom`).

---

## 3. Redirects & domain hygiene

- [ ] If `magnoolia.adme.ee` must retire, add a **301 redirect** from `magnoolia.adme.ee/*` to the final production URL (avoid duplicate-content / competing indexation).
- [ ] Confirm the internal 301: `/asendiplaan` → `/kodud-ja-hinnad#mg-masterplan` still resolves on production.
- [ ] Confirm HTTPS is enforced (HTTP → HTTPS redirect) and there are no mixed-content warnings.
- [ ] Decide and enforce a single host form (www vs non-www) with a 301 to the canonical.

---

## 4. Security

- [ ] **Rotate the admin password** (do not carry over the staging credential).
- [ ] Confirm `APP_DEBUG=false` (repeat check — critical).
- [ ] Confirm any admin / publishing routes are protected on production.

---

## 5. Build & deploy the code

Run in this order on the production release:

- [ ] Pull/deploy the latest release code.
- [ ] `composer install --no-dev --optimize-autoloader`
- [ ] Run database **migrations** if any are pending: `php artisan migrate --force`
- [ ] `npm ci && npm run build` (compile production assets)
- [ ] `php artisan config:clear` — **do NOT run `config:cache` on this production.**
      It produced a 500 on 2026-08-24 and clearing the cache restored the site; the
      likely cause is `bootstrap/cache/config.php` being written by a different user
      than the one the web server runs as. Config caching is a performance
      optimisation, not a requirement. If it is wanted later, run it as the web user
      (`sudo -u www-data php artisan config:cache`) and check the site immediately.
- [ ] `php artisan route:cache`
- [ ] `php artisan view:clear` (or `view:cache`)
- [ ] Config changes take effect on this production without any cache step, because
      the config is not cached.

---

## 6. Publish Magnoolia content (units, prices, availability)

Without an active published version, the 19 homes table and prices will not render. Commands verified from the codebase:

- [ ] `php artisan magnoolia:sync-prices` — sync the confirmed Stage I price list into the units.
- [ ] `php artisan magnoolia:publish --note="Production go-live"` — publish the active version so units/prices/availability render.
- [ ] Verify `/kodud-ja-hinnad` shows all **19 homes**, correct statuses (Vaba / Broneeritud / Müüdud / Täpsustamisel), Stage I prices visible, Stage II shown as täpsustamisel, and the masterplan section present.
- [ ] Spot-check unit pages at `/kodud/{slug}`, `/ru/kodud/{slug}`, `/en/homes/{slug}`.

---

## 7. Forms & lead delivery

- [ ] Submit the contact/enquiry form and confirm the email is delivered to **`diana@estlanda.ee`**.
- [ ] Test the inquiry drawer opened from a landing-page CTA (`data-mg-inquiry-open`).
- [ ] Confirm the phone link (`+372 58 16 40 78`) and email link (`diana@estlanda.ee`) are correct site-wide.
- [ ] Confirm the from-address / reply-to and mail transport are production-grade (not a staging mailer / mailtrap).

---

## 8. SEO verification on the live domain

- [ ] Robots meta on live pages is now `index,follow` (not `noindex`) — verify on home + a landing page.
- [ ] `/robots.txt` reflects production (allows crawling; references the sitemap).
- [ ] `<link rel="canonical">` on each page points to the **final canonical domain** (`magnoolia.ee`).
- [ ] `hreflang` tags (ET / EN / RU) resolve to the correct localized URLs on the final domain.
- [ ] Open Graph image and metadata render correctly (`assets/images/magnoolia/Cam001.0000.jpg`).
- [ ] `llms.txt` is reachable and lists the commercial pages.
- [ ] Footer "Populaarsed otsingud / Popular searches / Популярные запросы" chip row links resolve on the live domain.
- [ ] Each landing links correctly to `/kodud-ja-hinnad` and `/kontakt` plus its support pages.

---

## 9. Analytics (GA4 / GTM)

The dataLayer bridge is already implemented in `resources/views/layouts/app.blade.php`; CTAs carry `data-mg-analytics` / `data-event`. The **GTM container ID is not yet configured** — events currently only push to `window.dataLayer` and fail silently.

- [ ] Obtain the client's **GA4 / GTM container ID** and configure it in the layout.
- [ ] Verify `window.dataLayer` receives events (e.g. `magnoolia_cta_click`, `contact_form_start`, `contact_form_submit`, `magnoolia_phone_click`, `magnoolia_email_click`).
- [ ] Confirm **primary conversions** fire: `form_submit`, `phone_click`, `email_click`.
- [ ] Spot-check secondary events: `plan_download`, `price_table_view`, `unit_card_click`, `financing_click`, `gallery_open`.
- [ ] Confirm analytics does not break the page when the GTM ID is absent (must remain silent).

---

## 10. Search Console

- [ ] Verify ownership of the production property (`magnoolia.ee` and/or the `estlanda.ee/magnoolia` path) in Google Search Console.
- [ ] **Submit `/sitemap.xml`** and confirm it is read (107 URLs discovered).
- [ ] Request indexing for the home page and a few priority landings.
- [ ] Check the Coverage/Pages report a few days post-launch for `noindex` or canonical warnings.

---

## 11. Final QA & handover

- [ ] Cross-browser / mobile smoke test of home, `/kodud-ja-hinnad`, `/kontakt`, and a sample of the 14 landings (ET/EN/RU).
- [ ] Confirm cautious near-Tallinn wording is intact ("ligikaudu 20 min, sõltuvalt liiklusest") — no invented distances.
- [ ] Confirm no placeholder/invented data (prices, phone, metrics) is showing.
- [ ] **Capture production screenshots** (home, prices/availability, one landing per locale, contact) for the record.
- [ ] Final **client handover**: share the live URL, confirm indexing timeline, Search Console access, analytics setup status, and how to publish future price/availability updates (`magnoolia:sync-prices` + `magnoolia:publish`).

---

### Quick reference — key config keys

| Concern | Where |
|---|---|
| Canonical base | `magnoolia.seo.canonical_base` → `MAGNOOLIA_CANONICAL_DOMAIN` (`https://magnoolia.ee`) |
| Indexing on/off | `MAGNOOLIA_INDEXABLE=true` + `MAGNOOLIA_NOINDEX=false` |
| Public/production domain | `MAGNOOLIA_PUBLIC_DOMAIN` |
| Sales contact | `diana@estlanda.ee`, `+372 58 16 40 78` |
| Publish content | `php artisan magnoolia:sync-prices` → `php artisan magnoolia:publish` |
