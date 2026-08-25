# MAGNOOLIA PHASE 35.0 REPORT

**P0 Google visibility + canonical domain recovery**
Date: 2026-08-24 · Scope: domain/canonical/indexability only — no redesign, no data or admin changes.

---

## Status

**`PENDING_PHASE35_0_SERVER_ACCESS_REQUIRED`**

Secondary: `PENDING_PHASE35_0_SEARCH_CONSOLE_ACCESS_REQUIRED` (no Google account access from this workstation).

### Update — 2026-08-24, after `MAGNOOLIA_INDEXABLE=true` was set on production

| Fault | State |
|---|---|
| **1. noindex on every page** | ✅ **RESOLVED.** Verified live: `https://magnoolia.ee/`, `magnoolia.estlanda.ee/` and `/kodud-ja-hinnad` all now return `index,follow,max-image-preview:large,…` |
| **2. canonical domain** | ✅ **RESOLVED IN CODE, ships with the next `git pull`.** The client confirmed `magnoolia.estlanda.ee` is the main site and `magnoolia.ee` a deliberate redirect to it. The app now adopts whichever public host serves the request, so this stays correct today *and* after a future reversal — with no config to maintain. |
| **3. `estlanda.ee/magnoolia/` duplicate** | ❌ **STILL OPEN.** Live 200, indexable, self-canonical, title "Kinnisvara Müük". Needs a 301. |

**Deployed and verified live on 2026-08-24:**

```
https://magnoolia.estlanda.ee/kodud-ja-hinnad → canonical https://magnoolia.estlanda.ee/kodud-ja-hinnad
https://magnoolia.ee/ (302 → estlanda)        → canonical https://magnoolia.estlanda.ee
https://magnoolia.estlanda.ee/robots.txt      → 200 from the route, Sitemap: …estlanda.ee/sitemap.xml
https://magnoolia.estlanda.ee/sitemap.xml     → 107 URLs, 107 on magnoolia.estlanda.ee
```

> **Deploy note.** Production runs *without* a cached config. `php artisan config:cache` during this deploy produced a 500; clearing the cache restored the site. The most likely cause is file ownership — `bootstrap/cache/config.php` written by a different user than the one the web server runs as. Config caching is a performance optimisation, not a requirement: everything above works without it. If it is wanted later, run it as the web user (`sudo -u www-data php artisan config:cache`) and verify the site immediately afterwards. **The deploy step for this project is therefore just `git pull`** (plus `php artisan view:clear` when Blade files changed).

PASS is still **not** claimed: the fix is not yet deployed (`git pull` outstanding), the old template page at `estlanda.ee/magnoolia/` remains an indexable duplicate, and Search Console is unverified.

⚠️ **Fixing fault 1 raised the urgency of fault 2.** While the site was `noindex`, the estlanda host was invisible to Google, so the wrong-way redirect cost nothing. Now `magnoolia.estlanda.ee` is fully **indexable** and serves the complete site while declaring `canonical: https://magnoolia.ee` — a URL that 302s straight back to estlanda. Google is being asked to honour a canonical that refuses to serve itself.

### Decision — 2026-08-24: `magnoolia.estlanda.ee` is the main site *for now*

The client confirmed the intended arrangement is the **inverse** of what this phase's brief assumed:

> **`magnoolia.estlanda.ee` = the live site. `magnoolia.ee` = a redirect to it. For now.**

That is a legitimate choice and it resolves the conflict above — but only once the application stops claiming otherwise.

### The canonical now follows the serving host, automatically

Rather than pin the canonical to a domain in `.env` (which would have to be edited again the day the redirect is reversed), the application now **adopts the host it is actually served on**.

The reasoning: only one of the two hosts ever reaches PHP — the other is answered by Apache with a redirect before the application runs. So the request host *is*, by definition, the canonical host. `ResolveCanonicalDomain` (middleware, ~30 lines) adopts it and rewrites `canonical_domain` / `canonical_base` / `production_domain` for that request.

**Consequence: flipping the redirect in the hosting panel is the entire migration.** No `.env` edit, no deploy, no `config:cache`, no code change. Canonical, hreflang, OG, schema `@id`s, robots.txt and all 107 sitemap URLs move with it.

Verified over real HTTP against a **config-cached** (production-like) build, by varying only the `Host:` header:

| Host the request arrives on | canonical emitted | robots.txt sitemap line |
|---|---|---|
| `magnoolia.estlanda.ee` *(today)* | `https://magnoolia.estlanda.ee/kodud-ja-hinnad` | `https://magnoolia.estlanda.ee/sitemap.xml` |
| `magnoolia.ee` *(after a future flip)* | `https://magnoolia.ee/kodud-ja-hinnad` | `https://magnoolia.ee/sitemap.xml` |
| `www.magnoolia.ee` | `https://magnoolia.ee/kodud-ja-hinnad` — www shares the apex canonical | `https://magnoolia.ee/sitemap.xml` |
| `evil.example.com` *(forged header)* | `https://magnoolia.ee/kodud-ja-hinnad` — **ignored** | `https://magnoolia.ee/sitemap.xml` |

Safety: only hosts listed in `magnoolia.seo.public_hosts` (`magnoolia.ee`, `magnoolia.estlanda.ee`) are adopted. Anything else — localhost, an IP, a preview domain, a forged `Host:` header — is ignored and the configured `MAGNOOLIA_CANONICAL_DOMAIN` is used, so this cannot be abused to rewrite the canonical/OG/sitemap URLs of a cached page. `MAGNOOLIA_CANONICAL_DOMAIN` remains the fallback for CLI rendering and non-public hosts.

> Known limitation, pre-existing and unrelated: `asset()` / `url()` still echo the request host into `<script>`/`<link>` `src` attributes, as stock Laravel does. It affects no SEO signal. Laravel's `TrustedHosts` middleware is the mitigation if it is ever wanted.

**Adding a third public host later** (say the site also answers on a new domain) is one line in `magnoolia.seo.public_hosts`.

**Correction to an earlier finding in this report.** The domain table below calls the 302s a defect and demands 301s. That was correct under the brief's premise (magnoolia.ee as the destination). Under the confirmed arrangement it is **inverted: 302 is the right status code**, because the redirect is explicitly temporary — a 301 would tell Google to permanently replace `magnoolia.ee` with the estlanda host and would make the later flip back to the brand domain much slower. **Leave the 302s as they are.** Everything in the domain table marked ❌ for pointing at estlanda should be read as ✅ under this arrangement.

**What is still worth doing, unchanged by the decision:**

| Item | Why it still applies |
|---|---|
| 301 `estlanda.ee/magnoolia/` → `https://magnoolia.estlanda.ee/` | Still a genuine duplicate competing on the brand name — just with a different target now. |
| Search Console | Verify the property for **`magnoolia.estlanda.ee`** (the indexable host), submit its sitemap. A property for `magnoolia.ee` shows only redirects. |
| `www.magnoolia.ee` → 2 hops | Cosmetic; could point straight at `magnoolia.estlanda.ee` to save a hop. Not urgent. |

**Known cost of this arrangement, stated plainly:** while `magnoolia.ee` is a redirect it accumulates no ranking signals — the brand domain will not appear in search results. When you later flip to `magnoolia.ee` as the main site, the work is: point its vhost at the app and reverse the redirect in the hosting panel, then add a Search Console property for it. **Nothing in the repository changes.** Two regression tests cover both directions (`test_canonical_adopts_whichever_public_host_serves_the_request`, `test_www_shares_the_canonical_of_its_apex`).

**Repo changes made for this decision:** `ResolveCanonicalDomain` middleware + `magnoolia.seo.public_hosts`; the static `public/robots.txt` was deleted (it hardcoded `Sitemap: https://magnoolia.ee/…` and shadowed the dynamic route, which follows the resolved canonical); the forbidden-host audit list is now derived from the resolved canonical instead of a fixed list; the Phase 35 tests assert the *invariant* — every emitted URL matches the serving host — rather than a literal domain; and the `.htaccess` host rules added earlier were reverted to stock, since host routing belongs to the hosting layer.

---

## Executive summary

The site is not "ranking badly" — **it is not in the index at all, by instruction.** Two independent faults:

1. **Every live page told Google to go away.** The production `.env` never got `MAGNOOLIA_INDEXABLE=true`, and the shipped default was "not indexable". Result: `<meta name="robots" content="noindex,nofollow">` on the homepage and every other public page. Confirmed live on 6 URLs. **Fixed 2026-08-24** — the env var was set on production, and the shipped default now also defaults to indexable so this cannot recur silently.

2. **The canonical domain points the wrong way.** `https://magnoolia.ee/` answers **302 → `https://magnoolia.estlanda.ee/`**. The application, meanwhile, correctly declares `https://magnoolia.ee` in canonical, hreflang, OG, schema and sitemap. So Googlebot is sent *away* from the domain that every page claims to be canonical — a self-contradicting signal.

3. **The only indexable Magnoolia page Google can currently see is the wrong one.** `https://estlanda.ee/magnoolia/` returns 200, is indexable (`max-image-preview:large`, no noindex), and canonicalises **to itself** with the title *"Kinnisvara Müük"*. That is the old template page, and it is competing unopposed.

The good news: everything the application itself emits was already correct. There is not a single hardcoded wrong-domain URL in the codebase — the sitemap's 107 URLs are 100% `magnoolia.ee`, robots.txt references the right sitemap, schema and OG are clean. **This was an environment and hosting problem, not a code problem** — so the fix is small and low-risk.

What changed in this phase (all deployable by `git pull`):

| Change | Effect |
|---|---|
| Indexing now defaults to **on** | A bare `git pull` makes production indexable. No `.env` edit needed, and a stale `MAGNOOLIA_NOINDEX=true` can no longer override it. |
| `mg_is_indexable()` helper | robots.txt and the robots meta tag can no longer disagree (they did). |
| `ResolveCanonicalDomain` middleware | The canonical base is adopted from the serving host, so reversing the hosting redirect needs no deploy, no `.env` edit and no code change. Non-public hosts (and forged `Host:` headers) fall back to config. |
| Static `public/robots.txt` deleted | `/robots.txt` is now served by the route, so its sitemap URL follows `MAGNOOLIA_CANONICAL_DOMAIN` instead of being frozen to one domain. It also can no longer contradict the robots meta tag. |
| Forbidden-host audit is config-derived | Whichever host is canonical is allowed; the others fail the suite. Works in both directions. |
| 18 new regression tests | Including one proving that switching the canonical domain moves every SEO signal. |

> **Host routing belongs to the hosting layer.** Earlier drafts of this phase shipped a PHP `EnforceCanonicalHost` middleware and then a set of `.htaccess` host rules to force `magnoolia.ee`. Both were removed once the arrangement was confirmed: which host serves the site is decided in the hosting panel, and the application's only job is to advertise whichever host it is told is canonical. `public/.htaccess` is back to stock Laravel.

---

## Domain status table

> **Read this table against the decision above.** It was written to the brief's premise that `magnoolia.ee` must serve the site. Under the confirmed arrangement (`magnoolia.estlanda.ee` is the main site) the rows marked ❌ *for redirecting to estlanda* are the intended behaviour, and the 302 status code is correct because the arrangement is temporary. The one row that remains a genuine defect either way is `estlanda.ee/magnoolia/`.

Measured with `curl -I -L` on 2026-08-23/24. Full output: `phase35-0-google-visibility-screenshots/robots-sitemap-proof.txt` and `live-capture-notes.txt`.

| Source URL | Status | Final URL | Hops | Decision |
|---|---|---|---|---|
| `https://magnoolia.ee/` | **302** | `https://magnoolia.estlanda.ee/` | 1 | ❌ **BACKWARDS.** Must serve the site with 200. |
| `https://www.magnoolia.ee/` | 302 → 302 | `https://magnoolia.estlanda.ee/` | 2 | ❌ Ends on the wrong host. Chain also longer than necessary. |
| `http://magnoolia.ee/` | 302 → 302 | `https://magnoolia.estlanda.ee/` | 2 | ❌ HTTPS upgrade is fine; destination is not. |
| `http://www.magnoolia.ee/` | 302 → 302 → 302 | `https://magnoolia.estlanda.ee/` | 3 | ❌ Three hops, wrong destination. |
| `https://magnoolia.estlanda.ee/` | **200** | (itself) | 0 | ❌ This is where the real site lives today. Must become a 301 to `magnoolia.ee`. |
| `https://estlanda.ee/magnoolia/` | **200** | (itself) | 0 | ❌ Old template page, **indexable**, self-canonical, title "Kinnisvara Müük". Must be 301'd or removed. |

Additional path-level checks (same pattern, so the fault is host-wide, not page-specific):

| Source | Result |
|---|---|
| `https://magnoolia.ee/kodud-ja-hinnad` | 302 → `magnoolia.estlanda.ee/kodud-ja-hinnad` (200) |
| `https://www.magnoolia.ee/kontakt` | 302 → 302 → `magnoolia.estlanda.ee/kontakt` (200) |
| `https://magnoolia.ee/robots.txt` | 302 → `magnoolia.estlanda.ee/robots.txt` (200) |
| `https://magnoolia.ee/sitemap.xml` | 302 → `magnoolia.estlanda.ee/sitemap.xml` (200) |

**Redirect-type finding:** every redirect in the chain is **302 (temporary)**. For a permanent canonical move these must be **301**, otherwise Google keeps both URLs as candidates and does not transfer signals. The 302s are emitted by Apache (`Server: Apache`, `Content-Type: text/html; charset=iso-8859-1`) — i.e. the hosting/vhost layer, **not** this repository. No `.htaccess` in the repo produces them.

No `502`/`500` was observed anywhere. No redirect to `/m` exists.

---

## Production env verification

⚠️ **I have no shell on production**, so these are not read from the production `.env`. They are *inferred from observed live behaviour*, which is stated explicitly per value.

| Variable | Required | Observed / inferred on production | Evidence |
|---|---|---|---|
| `APP_URL` | `https://magnoolia.ee` | **Unknown.** Not directly observable. Canonical output is correct, but that comes from `MAGNOOLIA_CANONICAL_DOMAIN`, not `APP_URL`. | — |
| `APP_ENV` | `production` | **Unknown**, but consistent with production (no debug output). | 404 page is the styled "Page Not Found", not a Laravel debug screen |
| `APP_DEBUG` | `false` | **Satisfied.** No stack trace, no Whoops page, no `Illuminate\` frames on an error URL. | `GET /zzz-does-not-exist-35` → 404, 98 KB styled page, `<title>Page Not Found</title>` |
| `MAGNOOLIA_INDEXABLE` | `true` | **NOW SET to `true`** (2026-08-24). Was unset/false — that was fault #1. | Live pages render `index,follow,max-image-preview:large,…` |
| `MAGNOOLIA_NOINDEX` | `false` | **Unknown; irrelevant after this phase.** Either it is `true`, or it is unset and the old default `true` applied. | Same as above |
| `MAGNOOLIA_CANONICAL_DOMAIN` | `https://magnoolia.estlanda.ee` (per the 2026-08-24 decision) | **MISMATCHED** — currently resolves to `https://magnoolia.ee`, which is the redirect, not the live host. This is the one outstanding change. | canonical/OG/schema/sitemap all emit `https://magnoolia.ee` while the site is served from `magnoolia.estlanda.ee` |

**Why no `.env` edit is required any more.** `config/magnoolia.php` now ships `'indexable' => env('MAGNOOLIA_INDEXABLE', true)`, and `mg_is_indexable()` ORs the two switches, so `indexable` wins. Even if the production `.env` still contains `MAGNOOLIA_NOINDEX=true`, a bare `git pull` produces `index,follow`.

> **Consequence to accept:** any *other* deployment of this repo (staging `magnoolia.adme.ee`, a preview copy) is now indexable unless it sets `MAGNOOLIA_INDEXABLE=false`. That trade is deliberate: a live commercial site silently deindexed is a far worse failure than a staging copy briefly indexed. If `magnoolia.adme.ee` is still running, set `MAGNOOLIA_INDEXABLE=false` there.

---

## Canonical proof

Live homepage (`https://magnoolia.ee/` → served from the estlanda host):

```html
<link rel="canonical" href="https://magnoolia.ee">
```

Live, per page — note canonical is correct on **every** page even though the serving host is wrong:

| Requested | Served from | `<link rel=canonical>` |
|---|---|---|
| `https://magnoolia.ee/` | `magnoolia.estlanda.ee/` | `https://magnoolia.ee/` |
| `https://magnoolia.ee/kodud-ja-hinnad` | `magnoolia.estlanda.ee/kodud-ja-hinnad` | `https://magnoolia.ee/kodud-ja-hinnad` |
| `https://magnoolia.ee/asukoht` | `magnoolia.estlanda.ee/asukoht` | `https://magnoolia.ee/asukoht` |
| `https://magnoolia.ee/kontakt` | `magnoolia.estlanda.ee/kontakt` | `https://magnoolia.ee/kontakt` |

Local verification of the post-fix build — **36 pages crawled, zero wrong canonicals**:

```
pages checked: 36
non-200/301  : none
wrong canon  : none
```

Canonical is built from `magnoolia.canonical_domain` + the request *path* only, so query strings (`?utm_source=…`) can never fork the canonical.

No canonical anywhere points at `magnoolia.estlanda.ee`, `estlanda.ee/magnoolia`, `localhost` or `magnoolia.adme.ee`. Verified by grep over `resources/`, `config/`, `routes/`, `app/`, `public/*.txt`: **zero hardcoded occurrences.**

---

## Robots proof

Live `robots.txt` (correct, and always was — it is a static `public/robots.txt`):

```
User-agent: *
Allow: /
Disallow: /admin
Disallow: /admin/

Sitemap: https://magnoolia.ee/sitemap.xml
```

- Public pages: **not blocked** ✅
- `/admin`: disallowed ✅ and additionally auth-protected — `GET /admin/magnoolia` → redirect to `/admin/login` ✅
- Sitemap reference: canonical domain ✅
- No `X-Robots-Tag` header on any response ✅ (so the meta tag was the sole blocker)

**Contradiction found and fixed.** `public/robots.txt` (static, says *crawl me*) shadows the dynamic `/robots.txt` route, which — with the old config — would have said `Disallow: /`. robots.txt and the meta tag were therefore giving Google opposite instructions. Both now derive from `mg_is_indexable()`, and a test asserts they agree.

---

## Sitemap proof

Served by the **dynamic route** (`routes/web.php` → `resources/views/sitemap.blade.php`). Confirmed **no static `public/sitemap.xml` exists** to shadow it — and a test now enforces its absence (the previous test asserted the opposite, which would have re-introduced the Phase-34 bug).

```
loc entries : 107
hosts       : 107 magnoolia.ee
admin URLs  : 0
asendiplaan : 0
adme.ee     : 0
estlanda    : 0
```

- 107 URLs, **100 % on `https://magnoolia.ee`** ✅
- No admin URLs ✅ · no staging URLs ✅ · no old-domain URLs ✅ · no duplicates ✅
- All core pages present ✅
- All 17 SEO landing pages present (13 ET + 2 EN + 2 RU) ✅
- All **19 unit pages** present, in 3 locales (`/kodud/{slug}`, `/ru/kodud/{slug}`, `/en/homes/{slug}`) ✅
- `/asendiplaan` correctly **absent**: it is a permanent 301 into `/kodud-ja-hinnad#mg-masterplan`, and a sitemap must not advertise redirecting URLs ✅

---

## Hreflang proof

Live homepage:

```html
<link rel="alternate" hreflang="et" href="https://magnoolia.ee">
<link rel="alternate" hreflang="ru" href="https://magnoolia.ee/ru">
<link rel="alternate" hreflang="en" href="https://magnoolia.ee/en">
<link rel="alternate" hreflang="x-default" href="https://magnoolia.ee">
```

- All hreflang URLs on the canonical domain ✅
- `x-default` present ✅
- Sitemap carries matching `xhtml:link rel="alternate"` for every multi-locale URL ✅
- Single-locale SEO landings correctly emit **no** cross-locale alternates (they have no equivalents) ✅

---

## Schema / OG proof

Open Graph (live homepage):

```html
<meta property="og:url"   content="https://magnoolia.ee">
<meta property="og:image" content="https://magnoolia.ee/assets/images/magnoolia/Cam001.0000.jpg">
<meta property="og:type"  content="website">
<meta property="og:site_name" content="Magnoolia Kodud">
<meta property="og:locale" content="et_EE">
```

JSON-LD node identifiers (live homepage) — every one on the canonical domain:

```
"@id": "https://magnoolia.ee/#organization"
"@id": "https://magnoolia.ee/#website"
"@id": "https://magnoolia.ee/#project"
"@id": "https://magnoolia.ee/#place"
"@id": "https://magnoolia.ee/#breadcrumb"
"@id": "https://magnoolia.ee/#faq"
"@id": "https://magnoolia.ee/#hero-image"
"url": "https://magnoolia.ee"
```

Scan for schema/OG URLs **not** on `magnoolia.ee`: **none**. ✅

---

## Noindex proof

**Before (live production, 2026-08-23):**

```
https://magnoolia.ee/                 → meta robots: noindex,nofollow
https://magnoolia.ee/kodud-ja-hinnad  → meta robots: noindex,nofollow
https://magnoolia.ee/asukoht          → meta robots: noindex,nofollow
https://magnoolia.ee/kontakt          → meta robots: noindex,nofollow
```

**After — live production, 2026-08-24, once `MAGNOOLIA_INDEXABLE=true` was set:**

```
https://magnoolia.ee/                          → index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1
https://magnoolia.estlanda.ee/                 → index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1
https://magnoolia.estlanda.ee/kodud-ja-hinnad  → index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1
```

**After the fix (local build, shipped defaults, no env overrides) — 36 pages:**

```
noindex pages: none
```

Rendered value is now:

```html
<meta name="robots" content="index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1">
```

### Production deploy simulation (the decisive test)

Run against a build whose `.env` **still contains the stale `MAGNOOLIA_NOINDEX=true`** — i.e. the exact state production is believed to be in — with a real `php artisan config:cache` — the harshest case, since a cached config is the one that could freeze the stale value (this is how the test was run, not how production should be deployed; see the deploy note above):

```
.env:  MAGNOOLIA_CANONICAL_DOMAIN=https://magnoolia.ee
.env:  MAGNOOLIA_NOINDEX=true          ← stale value deliberately left in place
$ php artisan config:cache             → Configuration cached successfully

robots meta : index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1
canonical   : https://magnoolia.ee
robots.txt  : User-agent: *
              Allow: /
              Disallow: /admin
              Disallow: /admin/
              Sitemap: https://magnoolia.ee/sitemap.xml
```

The stale `MAGNOOLIA_NOINDEX=true` no longer has any effect. **A bare `git pull` is sufficient to lift the noindex — no production `.env` edit required** (the test above cached the config only to prove the stale value cannot survive even that).

Intentional, documented exceptions that remain `noindex`:

| Page | Reason |
|---|---|
| `/aitah`, `/ru/aitah`, `/en/aitah` | Thank-you page after form submit — must not be indexed. Asserted by test. |
| `/admin/*` incl. `/admin/magnoolia/preview` | Admin area; hardcoded `noindex,nofollow` + auth + robots.txt disallow. |

No `X-Robots-Tag: noindex` header anywhere. ✅

---

## Keyword coverage proof

Measured on the rendered HTML of 36 pages (title + visible body text), matching **Estonian stems** rather than exact strings — Estonian inflects, so "ridaelamukodud Vaela külas" legitimately serves *Vaela ridaelamud*.

| Client direction | Pages | Primary home |
|---|---|---|
| Magnoolia kodud | 29 | `/` , site-wide |
| Magnoolia ridaelamud | 27 | `/ridaelamud-harjumaa` |
| Magnoolia ridamajad | 26 | `/ridamajad-harjumaa` |
| Vaela uusarendus | 26 | `/uusarendus-kiili`, `/asukoht/vaela-kula` |
| Vaela ridaelamud | 27 | `/ridaelamu-vaela-kula` |
| Vaela ridamajad | 26 | `/ridamajad-harjumaa`, `/asukoht/vaela-kula` |
| Kiili uusarendus | 26 | `/uusarendus-kiili` |
| Kiili ridaelamud | 27 | `/uusarendus-kiili`, `/asukoht/kiili-vald` |
| Kiili ridamajad | 26 | `/asukoht/kiili-vald` |
| **Kiili premium kodud** | **1** | `/uusarendus-kiili` — **gap found and closed this phase** |
| A-energiaklassi kodud | 26 | `/a-energiaklassi-ridaelamud` |

**Gap closed.** "premium" appeared on **zero** public pages. One FAQ entry was added to `/uusarendus-kiili`:

> **Mis teeb Magnooliast premium kodud Kiili vallas?**
> Kodud on A-energiaklassis: maasoojuspump, soojustagastusega ventilatsioon ja põrandaküte hoiavad küttekulud kontrolli all. Siseviimistluse standardpaketi on kujundanud sisearhitekt Aet Piel ning iga kodu juurde kuulub privaatne hooviala, terrass, rõdu ja oma parkimiskohad.

Every claim in it is already asserted elsewhere on the site (A-energiaklass, maasoojuspump, ventilation, underfloor heating, Aet Piel interior package, private yard/terrace/balcony/parking) — **nothing invented**. Because that page's `$faqs` array is the single source for both the visible FAQ and the `FAQPage` JSON-LD, the phrase lands in visible copy **and** structured data at once.

How the directions are served overall (no stuffing, no `meta keywords`, no visible keyword list):
- **Titles/meta** — each landing has a distinct, intent-matched `<title>` and description.
- **H1/H2** — one H1 per page, verified on all 36 pages.
- **Internal links** — the footer "Populaarsed otsingud / Popular searches / Популярные запросы" chip row links the ET landings site-wide (locale-aware), which is why coverage is broad.
- **Schema** — `FAQPage` + `WebPage` per landing.
- **Sitemap** — all 17 landings listed.

**Client typo check:** the misspelling **"Vaele"** appears **nowhere** in `resources/`, `lang/`, `config/`, `routes/`, `app/`, `docs/`, `llms.txt` or `ai.txt`. Only the correct **"Vaela"** is used. ✅

---

## Key page verification

36 URLs crawled headlessly against the post-fix build (ET core, all 17 SEO landings, RU/EN locales):

| Check | Result |
|---|---|
| HTTP 200 | 36/36 ✅ |
| Correct canonical (`https://magnoolia.ee…`) | 36/36 ✅ |
| `noindex` on a public page | 0 ✅ |
| Exactly one `<h1>` | 36/36 ✅ |
| Title + meta description present | 36/36 ✅ |
| Unresolved translation keys in visible text | 0 ✅ |
| Horizontal overflow at 390 px | 0 px on all ✅ |
| Browser console errors / page errors | 0 ✅ |
| Broken CSS/JS | none observed ✅ |

`/asendiplaan` behaves as designed (verified locally, all three locales):

```
/asendiplaan     → 301 → /kodud-ja-hinnad#mg-masterplan
/ru/asendiplaan  → 301 → /ru/kodud-ja-hinnad#mg-masterplan
/en/asendiplaan  → 301 → /en/kodud-ja-hinnad#mg-masterplan
```

A permanent 301 to the consolidated page, excluded from the sitemap — correct for §9's "canonical equivalent".

---

## Search Console status

**BLOCKED — no Google account access from this workstation.** Nothing was submitted; nothing is claimed.

Manual steps for whoever owns the Google account (Indrek / ADME). **Do these only after the runbook below is applied**, otherwise verification will attach to a domain that redirects away.

1. **Create the property.** Prefer a **Domain property** (`magnoolia.ee`) — it covers http/https, www/non-www and every subdomain in one place. Verification is a **DNS TXT record** at the registrar; the URL-prefix alternative needs an HTML file or meta tag.
   - If a URL-prefix property is used instead, create **all four**: `https://magnoolia.ee`, `https://www.magnoolia.ee`, `http://…` variants — otherwise the redirect data is invisible.
2. **Submit the sitemap:** Sitemaps → add `https://magnoolia.ee/sitemap.xml` → expect **107 discovered URLs**.
3. **Request indexing** (URL Inspection → *Request indexing*), in this order:
   - `https://magnoolia.ee/`
   - `https://magnoolia.ee/kodud-ja-hinnad`
   - `https://magnoolia.ee/asukoht`
   - `https://magnoolia.ee/kontakt`
   - `https://magnoolia.ee/kkk`
   - Landings: `/ridaelamud-harjumaa`, `/ridamajad-harjumaa`, `/uusarendus-kiili`, `/uusarendus-harjumaa`, `/a-energiaklassi-ridaelamud`, `/ridaelamu-vaela-kula`
   - 2–3 unit pages, e.g. `https://magnoolia.ee/kodud/b1-s1`
   - `/asendiplaan` is intentionally **not** submitted (it 301s).
4. **Retire the duplicate.** If `estlanda.ee` is a separate Search Console property, use *Removals* on `https://estlanda.ee/magnoolia/` for a fast temporary hide, then make the 301 permanent (below). Removals alone are temporary (~6 months) — the 301 is the real fix.
5. **Check after ~7 days:** Pages report should show *Indexed*, not *Excluded by 'noindex' tag* or *Page with redirect*. Also confirm no "Duplicate, Google chose a different canonical" against the estlanda host.

---

## Screenshots

`docs/phase35-0-google-visibility-screenshots/`

**Live production, as it is today** (each capture starts at the requested `magnoolia.ee` URL and records where the browser actually landed — see `live-capture-notes.txt`):

| File | Shows |
|---|---|
| `magnoolia-ee-home-desktop.png` | Requested `https://magnoolia.ee/` → landed `https://magnoolia.estlanda.ee/`, robots `noindex,nofollow` |
| `magnoolia-ee-home-mobile.png` | Same at 390 px |
| `magnoolia-ee-kodud-ja-hinnad.png` | → `magnoolia.estlanda.ee/kodud-ja-hinnad`, `noindex,nofollow` |
| `magnoolia-ee-asukoht.png` | → `magnoolia.estlanda.ee/asukoht`, `noindex,nofollow` |
| `magnoolia-ee-contact.png` | → `magnoolia.estlanda.ee/kontakt`, `noindex,nofollow` |
| `duplicate-estlanda-ee-magnoolia.png` | The competing old page: `estlanda.ee/magnoolia/`, **indexable**, title "Kinnisvara Müük" |
| `live-capture-notes.txt` | Requested URL → final URL, status, robots, canonical, title for each |
| `robots-sitemap-proof.txt` | Live robots.txt, redirect chain, sitemap head, URL/host counts |

**Post-fix build** (same code, correct settings — what production will look like after the runbook):

| File | Shows |
|---|---|
| `local-postfix-home-desktop.png` | Homepage, `index,follow` |
| `local-postfix-home-mobile.png` | Mobile, no horizontal overflow |
| `local-postfix-kodud-ja-hinnad.png` | 19 homes, prices, masterplan section |
| `local-postfix-asukoht.png` | Location page |
| `local-postfix-contact.png` | Contact page |

No Search Console screenshot exists — access blocked, see above.

---

## Tests / commands run

| Command | Result |
|---|---|
| `php artisan route:list` | 156 routes. `sitemap.xml` and `robots.txt` are **routes**, not files. `/asendiplaan` in 3 locales → `MagnooliaController@sitePlan`. Admin routes behind auth. |
| `php artisan config:clear` | OK |
| `php artisan cache:clear` | OK |
| `php artisan view:clear` | OK |
| `php artisan config:cache` | OK |
| `php artisan magnoolia:verify-readiness` | **READY** — Units 19 ✅ · Media 33 ✅ · Gallery 31 ✅ · Content blocks 34 ✅ · Active publication **v5** ✅ · Status 14/4/1 ✅ |
| `php artisan test --filter=Magnoolia` | **56 failed, 795 passed** (5 505 assertions) |
| `php vendor/bin/phpunit` (full suite) | 860 tests · **56 failures, 0 errors** (was **116 failures + 2 errors** before this phase) |
| `php vendor/bin/phpunit --filter MagnooliaPhase35CanonicalDomainTest` | **18/18 pass** |
| `curl -I` × 10 URLs | See domain status table |

> ⚠️ **Gotcha worth knowing:** running `php artisan test` **after** `php artisan config:cache` produces ~500 bogus failures — the cached config overrides the testing environment (wrong DB driver). Always `php artisan config:clear` before running tests locally. **And not on production either** — see the deploy note at the top: `config:cache` 500'd this production and is not part of its deploy.

### New tests (`MagnooliaPhase35CanonicalDomainTest`, 18 cases)

Indexability: shipped defaults are indexable · a stale legacy `noindex` **cannot** deindex production · staging can still opt out with both switches · `/aitah` stays noindex · 6 public pages carry no noindex.
robots.txt: allows crawling, blocks `/admin`, references the canonical sitemap · follows the same switch as the meta tag.
Canonical: 4 key pages canonicalise to `magnoolia.ee` · no legacy host string in public HTML on 5 pages.
Sitemap: only canonical-domain URLs · no admin, no redirecting URLs · core pages + landings present.

Canonical host resolution: the canonical follows whichever public host serves the request (both directions covered) · www shares the apex canonical · canonical is https even on an http request · sitemap and robots.txt follow the serving host · an unknown/forged host cannot poison any SEO signal · setting the canonical domain explicitly still overrides everything.

Which host *serves* the site is decided in the hosting panel, not in PHP; that part is verified with `curl -I` in the acceptance checklist below.

### Pre-existing test failures (NOT caused by this phase)

The suite was already red when this phase started: **116 failures + 2 errors**. It is now **56 failures, 0 errors**, because fixes made for this phase also repaired the shared page lists.

Remaining 56 have one dominant root cause: an earlier phase consolidated `/asendiplaan` into `/kodud-ja-hinnad#mg-masterplan`, but ~40 assertions still expect that URL to render 200, and `MagnooliaPhase25AsendiplaanDiscoveryTest` (11 cases) tests a page that no longer exists standalone. The rest are content-drift assertions (Aet Piel block, "täpsustatakse" disclaimer, RU/ET heading purity, availability-board analytics attribute).

These are **not** Google-visibility issues and were deliberately left alone rather than expanding this P0 into a test-suite refactor. Recommend a separate **Phase 35.1 — test suite realignment**.

---

## THE RUNBOOK — what must happen on production

Everything below is deployable with `git pull`; only **step 1** needs the hosting panel and only **step 4** is optional.

> ⚠️ **Steps 1 and 3 below are SUPERSEDED by the 2026-08-24 decision** — they describe making `magnoolia.ee` the serving host, which is explicitly not the current plan. Keep them for the day you flip to the brand domain. **What to do now is the one `.env` line in the decision section, plus steps 4 and 5.**

### Step 1 — Hosting: make `magnoolia.ee` serve the application *(only you/ADME can do this)* — DEFERRED

This is the one thing not in the repository. In the hosting panel / Apache vhost for `magnoolia.ee`:

- **Remove** the redirect `magnoolia.ee → magnoolia.estlanda.ee`.
- **Point** `magnoolia.ee` (and `www.magnoolia.ee`) at the same document root as `magnoolia.estlanda.ee` — i.e. the Laravel `public/` directory.
- Ensure the TLS certificate covers `magnoolia.ee` **and** `www.magnoolia.ee`.
- Keep `http → https` as a **301** (currently 302).

Verify before continuing:

```bash
curl -I https://magnoolia.ee/          # expect: HTTP/1.1 200 OK   (no Location header)
```

**Do not proceed to step 3 until this returns 200.**

### Step 2 — Deploy the code

```bash
cd /path/to/magnoolia/app
git pull
php artisan config:clear      # NOT config:cache — see the deploy note above
php artisan view:clear
# no migrations or seeds are needed for this phase
```

After this, production serves `index,follow` — the noindex is gone. Verify:

```bash
curl -s https://magnoolia.ee/ | grep -o '<meta name="robots"[^>]*>'
# expect: <meta name="robots" content="index,follow,max-image-preview:large,...">
```

### Step 3 — Turn on the legacy-host 301 (after step 1 returns 200)

Uncomment the marked block in `public/.htaccess`:

```apache
RewriteCond %{HTTP_HOST} ^(www\.)?magnoolia\.estlanda\.ee$ [NC]
RewriteRule ^ https://magnoolia.ee%{REQUEST_URI} [L,R=301]
```

No `.env` change, no cache rebuild — Apache picks `.htaccess` up immediately.

> **Why this ships commented:** while `magnoolia.ee` still 302s to `magnoolia.estlanda.ee`, a 301 in the other direction puts visitors in a loop between the two hosts and takes the site down. Step 1 first, always.

Verify:

```bash
curl -I https://magnoolia.estlanda.ee/kodud-ja-hinnad
# expect: 301 → https://magnoolia.ee/kodud-ja-hinnad
curl -I https://www.magnoolia.ee/
# expect: 301 → https://magnoolia.ee/
```

### Step 4 — Retire `estlanda.ee/magnoolia/` *(different site, different hosting)* — STILL REQUIRED

It is a live, indexable duplicate. **Back it up first**, then 301 it to the current main site:

```apache
# in the estlanda.ee document root .htaccess
RedirectMatch 301 ^/magnoolia(/.*)?$ https://magnoolia.estlanda.ee$1
```

A 301 is strongly preferred over deletion — it transfers whatever authority the old page accumulated.

### Step 5 — Search Console

Follow the "Search Console status" section above.

### Post-deploy acceptance checklist

**Under the current arrangement (estlanda is the main site):**

```bash
curl -I https://magnoolia.estlanda.ee/                  # 200, no Location
curl -I https://magnoolia.ee/                           # 302 → magnoolia.estlanda.ee  (temporary, correct)
curl -I https://estlanda.ee/magnoolia/                  # 301 → https://magnoolia.estlanda.ee/
curl -s https://magnoolia.estlanda.ee/ | grep -c noindex                 # 0
curl -s https://magnoolia.estlanda.ee/ | grep -o 'rel="canonical"[^>]*'  # → magnoolia.estlanda.ee
curl -s https://magnoolia.estlanda.ee/robots.txt        # Allow: / + Disallow: /admin + estlanda sitemap URL
curl -s https://magnoolia.estlanda.ee/sitemap.xml | grep -c "<loc>"      # 107, all estlanda
```

**For the later flip to the brand domain**, use the original list above with `magnoolia.ee` as the 200 host.

When the current list passes and Search Console is verified for `magnoolia.estlanda.ee`, this phase can be closed as `PASS_PHASE35_0_GOOGLE_VISIBILITY_RECOVERY_READY`.

---

## Remaining risks

1. **The single unavoidable manual step.** Step 1 is hosting-level. Nothing in this repository can override an Apache redirect that runs before PHP.
2. **Ordering risk.** Uncommenting the legacy-host 301 (step 3) before step 1 returns 200 causes a redirect loop. Mitigated by shipping it commented, with the reason and the verifying `curl` commands written directly above the block in `public/.htaccess`.
3. **Staging becomes indexable.** If `magnoolia.adme.ee` still serves this code, it will now be indexable. Set `MAGNOOLIA_INDEXABLE=false` there, or take it offline.
4. **Recovery is not instant.** Even after everything is correct, re-indexing takes days to weeks. `noindex` removal must be *recrawled* before pages return. Requesting indexing (step 5.3) accelerates the priority URLs only.
5. **Accumulated 302 history.** Google has been served temporary redirects; converting them to 301 makes the consolidation permanent, but expect a period where both hosts still appear in the index.
6. **`estlanda.ee/magnoolia/` is outside this project.** It may be on separate hosting with a separate owner. Until it is 301'd, it competes for the brand name.
7. **56 pre-existing test failures** remain unrelated to visibility (see above). They do not block the recovery but do reduce the suite's value as a safety net.
8. **Production env values are inferred, not read.** `APP_URL` and `APP_ENV` could not be verified without shell access. Worth confirming during step 2.

---

## Final verdict

**`PENDING_PHASE35_0_SERVER_ACCESS_REQUIRED`**

Fault 1 (noindex) is **resolved on production** as of 2026-08-24. Fault 2 (canonical domain) is not, and is now the more urgent of the two — see the update at the top.

Everything that could be fixed in code **is** fixed, tested and staged for `git pull`:

- indexing is on by shipped default and can no longer be silently disabled by a stale env var;
- robots.txt and the robots meta tag can no longer contradict each other;
- canonical, hreflang, OG, schema and the 107-URL sitemap were already 100 % `magnoolia.ee` and are now guarded by tests;
- www→apex 301 ships active in `.htaccess`; the legacy-host 301 sits one uncomment away, loop-safe;
- the one missing client keyword direction is covered honestly;
- the suite went from 116 failures + 2 errors to 56 failures + 0 errors, with 18 new regression tests.

PASS is withheld because the task forbids it while `magnoolia.ee` does not return 200, old hosts remain indexable duplicates, and Search Console status is unverified. What remains is **runbook step 1 (hosting panel)**, then step 3 (uncomment eight lines in `.htaccess`), step 4 (301 the estlanda duplicate) and step 5 (Search Console). Step 2 is a plain `git pull`.
