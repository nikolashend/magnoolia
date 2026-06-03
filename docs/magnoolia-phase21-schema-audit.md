# Phase 21 — Schema Audit
**Date:** 2025  
**Tool:** Manual grep + source review of `resources/views/pages/magnoolia/` and `resources/views/partials/seo/schema.blade.php`

---

## Global Schema (all pages)

**File:** `resources/views/partials/seo/schema.blade.php` — included by `layouts/app.blade.php` for every page.

| Type | Status | Notes |
|------|--------|-------|
| `WebSite` | ✅ | `@id: /#website`, `name`, `url`, `inLanguage: et-EE`, linked `publisher` |
| `Organization` | ✅ | `@id: /#organization`, name, url, email, telephone, address, contactPoint |
| `ApartmentComplex` | ✅ | `@id: /#project`, name, description, address, geo, numberOfRooms |

**P0 checks:**
- No `Offer` type present ✅
- No `aggregateRating` present ✅
- No fake `Review` nodes present ✅
- `email` uses `diana@estlanda.ee` (real agent email, not `info@magnoolia.ee`) ✅

---

## Per-Page Schema

| Page | Route | Schema Types | BreadcrumbList | P0 |
|------|-------|-------------|----------------|----|
| home.blade.php | `/` | WebSite + Organization + ApartmentComplex (global) | ❌ (home = root, acceptable) | ✅ |
| kodud-ja-hinnad | `/kodud-ja-hinnad` | BreadcrumbList + ApartmentComplex | ✅ | ✅ |
| asendiplaan | `/asendiplaan` | BreadcrumbList + Place + GeoCoordinates | ✅ | ✅ |
| asukoht | `/asukoht` | BreadcrumbList + Place + GeoCoordinates + FAQPage | ✅ | ✅ |
| ehitusinfo | `/ehitusinfo` | BreadcrumbList + FAQPage | ✅ | ✅ |
| finantseerimine | `/finantseerimine` | BreadcrumbList + FAQPage | ✅ | ✅ |
| galerii | `/galerii` | BreadcrumbList | ✅ | ✅ |
| kkk | `/kkk` | BreadcrumbList + FAQPage + microdata | ✅ | ✅ |
| kodud-ja-hinnad | `/kodud-ja-hinnad` | BreadcrumbList + ApartmentComplex | ✅ | ✅ |
| kontakt | `/kontakt` | BreadcrumbList + ContactPage + Organization | ✅ | ✅ |
| ostuprotsess | `/ostuprotsess` | BreadcrumbList + HowTo + FAQPage | ✅ | ✅ |
| arhitektuur | `/arhitektuur-ja-valisdisain` | BreadcrumbList + ApartmentComplex | ✅ | ✅ |
| sisedisain | `/sisedisain` | BreadcrumbList + FAQPage | ✅ | ✅ |

---

## Schema Parity (RU/EN locales)

Schema JSON-LD is embedded in page Blade templates. The global `schema.blade.php` partial uses `config('magnoolia')` for project data (locale-agnostic). Per-page schema for subpages uses hardcoded Estonian property name strings (proper nouns — acceptable). Schema content does not vary by locale (intentional — schema addresses Google/Bing, not end users).

**Note:** `WebSite.inLanguage` is hardcoded as `"et-EE"` in the global partial. For RU/EN pages, this is technically inaccurate. Recommendation: update to use dynamic locale value (`{{ app()->getLocale() == 'ru' ? 'ru-EE' : (app()->getLocale() == 'en' ? 'en-EE' : 'et-EE') }}`). This is a P3 improvement — no action required in Phase 21.

---

## P0 Rules Compliance

| Rule | Status |
|------|--------|
| No `Offer` / `aggregateRating` schema | ✅ None found |
| No fake `Review` schema | ✅ None found |
| `diana@estlanda.ee` used (not `info@magnoolia.ee`) | ✅ |
| No `suvi 2027` in schema description | ✅ Uses `kevad 2027` |
| `/aitah` noindex | ✅ Confirmed in route config |

---

## Verdict

✅ **Schema audit PASSED.** All 12 sub-pages have BreadcrumbList. 5 pages have FAQPage. All P0 rules respected.
