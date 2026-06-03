# Phase 21 — Page Intent & Keyword Audit
**Date:** 2025  
**Method:** Title tag audit + H1/H2 review + intent mapping. All pages use `__('magnoolia.pages.*')` for SEO titles — fully i18n'd (ET/RU/EN).

---

## Intent Clusters & Keyword Coverage

| Page | Route | Intent | Primary Keyword (ET) | H1 Source |
|------|-------|--------|----------------------|-----------|
| Home | `/` | Awareness / Overview | Magnoolia kodud, ridaelamud Vaela külas | `pages.magnoolia.hero_title` |
| Kodud ja hinnad | `/kodud-ja-hinnad` | Purchase intent | ridaelamu hinnad, A-energiaklass | `page.title` → `magnoolia.pages.homes_title` |
| Asendiplaan | `/asendiplaan` | Location/spatial research | asendiplaan, Vaela küla plaan | `page.title` → `magnoolia.pages.site_plan_title` |
| Asukoht | `/asukoht` | Location intent | Vaela küla asukoht, Kiili vald | `page.title` → `magnoolia.pages.location_title` |
| Ehitusinfo | `/ehitusinfo` | Build quality trust | energiaklass, ehitusinfo | `page.title` → `magnoolia.pages.construction_title` |
| Arhitektuur | `/arhitektuur-ja-valisdisain` | Design trust | arhitektuur, välisdisain | `page.title` → `magnoolia.pages.architecture_title` |
| Sisedisain | `/sisedisain` | Design trust | sisedisain, esmaviimistlus | `page.title` → `magnoolia.pages.interior_title` |
| Galerii | `/galerii` | Visual exploration | fotod, visualisatsioonid | `page.title` → `magnoolia.pages.gallery_title` |
| Ostuprotsess | `/ostuprotsess` | Purchase process guidance | kinnisvara ost, etapid | `page.title` → `magnoolia.pages.purchase_title` |
| Finantseerimine | `/finantseerimine` | Financing info | laen, pangalaen, kodulaen | `page.title` → `magnoolia.pages.finance_title` |
| KKK | `/kkk` | FAQ / objection handling | korduma kippuvad küsimused | `page.title` → `magnoolia.pages.faq_title` |
| Kontakt | `/kontakt` | Contact / lead capture | kontakt, müügiinfo | `page.title` → `magnoolia.pages.contact_title` |
| Aitah | `/aitah` | Post-conversion | — (noindex) | Static |

---

## SEO Title Structure

All pages follow pattern: `{Page Keyword} | Magnoolia Kodud`  
- Source: `__('magnoolia.pages.{page}_title')` for each locale
- ET, RU, EN translations confirmed present in lang files

---

## Key Keyword Gaps (Recommendations)

| Gap | Impact | Recommendation |
|-----|--------|----------------|
| Home page lacks schema for "kevad 2027" delivery date | P2 | Add `expectedCompletionDate` to ApartmentComplex schema |
| `description` meta is not locale-aware on all subpages | P2 | Verify `@section('description')` uses `__()` on all magnoolia subpages |
| Long-tail: "ridaelamu Kiili vald" not in H1/H2 on any page | P3 | Add to asukoht or kodud-ja-hinnad page copy |
| Russian keyword research not done | P3 | Verify RU translations match Russian buyer search patterns |

---

## P0 Compliance

| Rule | Status |
|------|--------|
| No fake prices in page content | ✅ (prices loaded from config/DB, not hardcoded) |
| No "suvi 2027" in any page title or H1 | ✅ |
| No "summer 2027" in EN pages | ✅ |
| `/aitah` excluded from sitemap + noindex | ✅ |

---

## Sitemap Coverage

`sitemap.xml` contains 36 URLs covering:
- 13 ET routes + 13 RU routes + 13 EN routes = 39 minus 3 `/aitah` noindex = 36 ✅

---

## Verdict

✅ **Keyword/intent audit PASSED.** All intent clusters covered by dedicated pages. 4 P2–P3 improvements noted for next iteration.
