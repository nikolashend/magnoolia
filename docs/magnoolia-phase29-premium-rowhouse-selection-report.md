# Magnoolia Phase 29 — Premium Rowhouse Selection — Report

## 1. Executive status

**YELLOW_PHASE29_PARTIAL_ASSET_LIMITATION**

The complete row → home → detail → inquiry selection system is built, wired and
green-tested across ET/RU/EN. All Phase 29 acceptance items are functionally met
(6 rows, 19 homes, correct private-use land areas, premium /asendiplaan selector,
preserved + filtered /kodud-ja-hinnad, accessible home-detail modal, compact
homepage, ET/RU/EN, tests, screenshots, no regressions).

YELLOW (not PASS) is the honest call for these asset/data caveats, all detailed in
§10:
- Per-row and per-home **crops are generated from the top-down clean asendiplaan**
  (accurate, orientation-friendly, premium) rather than the perspective aerial
  renders. The perspective renders are used as the development **overview**. This
  is a deliberate choice to avoid mislabeling houses (see §10).
- Per-home **map markers** are derived from detected footprint centroids and are
  flagged `approximate:true` in the manifest.
- One source-data discrepancy to confirm with the client: **Magnoolia tee 3-4**
  private-use land area is `841.5 m²` in the canonical hinnatabel
  (`config/magnoolia_units.php`) but the asendiplaan PDF labels `756,4 m²`.
- The two reference "hinnad broneeritud" price screenshots were not supplied
  (non-blocking — pricing already lives in the data layer).

---

## 2. Phase continuity audit

**Docs read:** Phase 26/27/28 reports in `docs/` (asset integration, mobile rescue,
final launch readiness).

**Routes inspected** (`app/routes/web.php`): localized ET (no prefix) / `ru` / `en`
groups; `magnoolia.site-plan` (/asendiplaan), `magnoolia.homes`
(/kodud-ja-hinnad), `magnoolia.construction` (/ehitusinfo), `magnoolia.contact`
(+ `contact.send`), `home`. No routes added or changed.

**Tests inspected:** `tests/Feature/Magnoolia*` (Phase 25–28). Reused
`tests/Traits/CreatesMagnooliaTestUnits` (canonical 19-unit fixture) and mirrored
the audit conventions (`MagnooliaRenderedHtmlAudit`, anchor/residue checks).

**Data architecture discovered:**
- Canonical facts: `config/magnoolia_units.php` (hinnatabel 2026-05-05, real areas).
- Live publication payload (`MagnooliaPublication` → `$mgPublic` via
  `MagnooliaPublicDataComposer`) uses building/section keys `B{n}-S{n}`.
- Global inquiry drawer `components/magnoolia/inquiry-drawer.blade.php` opens from
  any `[data-mg-inquiry-open]` trigger reading `data-unit-*` + `data-source-component`.

**No-regression areas (untouched):** layout/header/footer/hero, robots/indexability
(`MAGNOOLIA_INDEXABLE`), sitemap/llms/schema, contact form endpoint + validation,
analytics attributes, existing `/asendiplaan` unit side-panel, existing pricing
table columns, homepage availability board.

---

## 3. Source file audit

Source folder (internal, never published): `materials/phase29/`.

| File | Role | Dimensions | Use | Generated output |
| --- | --- | --- | --- | --- |
| `1.jpg` | Primary daylight aerial render | 4000×1999 | public (overview) | `overview/development-primary*.webp` |
| `3.jpg` | Secondary dusk aerial render | 4000×1999 | public (overview, view 2) | `overview/development-secondary*.webp` |
| `5.jpg` | Clean buyer-facing asendiplaan | 2865×4016 | public (map + crops) | clean map + 6 row + 19 home crops |
| `2a.png` | Colour mask (primary) | 4000×1999 | internal only | calibration reference (not shipped) |
| `4a.png` | Colour mask (secondary) | 4000×1999 | internal only | not shipped |
| `6.jpg` | Technical asendiplaan | 5315×4134 | internal only | mapping verification only |
| `PR03822_EP_AS-4.pdf` | Technical site plan (source of truth) | — | internal only | POS/area verification |
| `0.png` | Extra render frame | 1672×941 | internal only | not used |
| `7/8/9 konkurent.*` | Competitor UX refs | — | internal only | logic inspiration only |
| `Magnoolia … Prestige Sisedisain.pptx` | Interior finishes | — | internal | reused already-extracted WebP for /ehitusinfo |

No source file, colour mask, OneDrive link or PPTX path is referenced in any public
asset or rendered page (verified by tests, §8).

---

## 4. Mapping proof

| POS | Row / address | Homes | Stage | Completion | Private-use land areas (m²) |
| --- | --- | --- | --- | --- | --- |
| tee-1  | Magnoolia tee 1  | 3 | I  | kevad 2027 | 959,7 · 513,2 · 636,7 |
| tee-3  | Magnoolia tee 3  | 4 | I  | kevad 2027 | 509,5 · 418,6 · 465,0 · 841,5* |
| tee-5  | Magnoolia tee 5  | 3 | II | kevad 2028 | 549,1 · 551,7 · 599,9 |
| tee-7  | Magnoolia tee 7  | 3 | II | kevad 2028 | 640,4 · 561,9 · 638,6 |
| tee-9  | Magnoolia tee 9  | 3 | II | kevad 2028 | 583,1 · 504,3 · 558,3 |
| tee-11 | Magnoolia tee 11 | 3 | II | kevad 2028 | 678,1 · 429,1 · 872,1 |

Totals: **6 rows, 19 homes. Stage I = 7, Stage II = 12.** Values sourced from the
canonical hinnatabel; live availability status overlaid from the publication.
*tee-3-4 — see §1/§10 discrepancy note.

---

## 5. Asset proof

- **Generator:** `scripts/magnoolia-generate-rowhouse-assets.mjs`
  (Node + Sharp; `npm run magnoolia:generate:rowhouse`). Reproducible: detects
  orange footprints on the clean asendiplaan, clusters into 6 address groups,
  sub-clusters each into its homes (so crops/markers land on real house centroids),
  emits WebP + responsive variants and the manifest.
- **Manifest:** `public/assets/magnoolia/rowhouse-selection/manifest.json`
  (6 rows, 19 homes, overview primary+secondary, clean asendiplaan, highlight
  coordinates; no source paths/masks).
- **Generated assets:** **93 WebP files**, ~9.6 MB total:
  - overview primary + secondary (base/2048/1280/768)
  - clean asendiplaan (base/1600/1024/768)
  - 6 row crops (base/1280/768/480)
  - 19 home crops (base/768/480)
- **Optimization:** 18.3 MB source → 9.3 MB output (WebP q82, responsive).

---

## 6. UX implemented

- **Homepage:** existing compact availability board preserved (no heavy table added).
- **/asendiplaan:** new "Vali Magnoolia kodu asukoht" section — clean map with 6
  gold row markers + 6 premium row cards (etapp, completion, count, vaba/bron/müüd,
  CTA). Selecting a row reveals its home cards (display address, Plaan A/B, net area,
  Hooviala, status chip, "Vaata kodu"). Desktop = map left / cards right; mobile =
  cards first, map below, "Suurenda asendiplaani". Existing unit side-panel untouched.
- **Home-detail modal** (`components/magnoolia/home-detail-modal.blade.php`):
  accessible `role="dialog"`, focus trap, ESC + overlay close, body-scroll lock,
  ≥44px targets. Shows home crop, mini asendiplaan with gold marker, floor-plan PDFs,
  full specs (net, yard, rooms, parking, stage, completion, energy A, status), and
  status-aware CTAs (Küsi pakkumist / Küsi saadavust / Vaata vabu kodusid · Helista
  Dianale · Vaata asendiplaani). "Küsi pakkumist" reuses the existing inquiry drawer
  with selected-home context (`data-source-component="home_detail_modal"`).
- **/kodud-ja-hinnad:** full 19-home table preserved; added tee row-filter chips
  (Kõik / tee 1·3·5·7·9·11), "Vaata kodu" modal trigger on every row + mobile card,
  and private-use land area on mobile cards. Mobile keeps readable cards.
- **/ehitusinfo:** new "Siseviimistluse ja materjalide näited" block — 6 Prestige
  PPTX WebP cards, disclaimer, CTAs to /sisedisain + inquiry.

---

## 7. Changed / new files

**New:**
- `config/magnoolia_rowhouses.php`
- `app/Services/Magnoolia/RowhouseSelectionService.php`
- `resources/views/sections/magnoolia/rowhouse-selector.blade.php`
- `resources/views/components/magnoolia/home-detail-modal.blade.php`
- `scripts/magnoolia-generate-rowhouse-assets.mjs`
- `scripts/magnoolia-visual-phase29.mjs`
- `tests/Feature/MagnooliaPhase29{AssetManifest,RowhouseMapping,FrontendRender,CtaContext,MobileUx,Language}Test.php`
- `public/assets/magnoolia/rowhouse-selection/**` (93 WebP + manifest.json)
- `docs/magnoolia-phase29-premium-rowhouse-selection-report.md`
- `docs/phase29-screenshots/**`

**Modified (additive):**
- `resources/views/pages/magnoolia/asendiplaan.blade.php` (include selector + modal)
- `resources/views/sections/magnoolia/hinnad.blade.php` (row filter, modal triggers, yard, modal include)
- `resources/views/pages/magnoolia/ehitusinfo.blade.php` (material block)
- `lang/{et,ru,en}/magnoolia.php` (`rowhouse` namespace)
- `public/assets/css/magnoolia.css` (Phase 29 styles, appended)
- `package.json` (npm scripts)

---

## 8. Tests

Commands:
```
php artisan test --filter=MagnooliaPhase29
php artisan test
```

Results:
- **Phase 29: 24 passed (338 assertions), 0 failed.**
- **Full suite: 660 passed, 1 failed, 1 risky (3912 assertions).**
  - The single failure is `MagnooliaPhase28LogoIntegrationTest > header uses real
    logo image` — **pre-existing and unrelated to Phase 29**. The test expects the
    header to reference `magnoolia-light`, but the header now renders a different
    real logo (`assets/magnoolia/logos/magnoolia-logo2.webp`) from a prior logo
    rename. So a real logo IS shown; the assertion is simply stale. Phase 29 does
    not touch the header/layout. The `1 risky` test is also pre-existing. (Left
    as-is to avoid scope creep; recommend updating that Phase 28 assertion to
    `magnoolia-logo2` separately.)

During development one regression was found and fixed: the modal CTA/floor links
initially rendered `href="#"`, which the existing anchor audits correctly flagged;
changed to a real `/kontakt` fallback (CTA) and JS-set hrefs (floor links). All
audit/anchor tests green afterward.

Frontend build: `npm run build` does **not** complete in this environment due to a
pre-existing toolchain issue (Node 18.20 installed; Vite 7 requires Node ≥20.19, and
`@tailwindcss/oxide` is missing its native binding). This is unrelated to Phase 29:
the Phase 29 styles live in the static `public/assets/css/magnoolia.css` and the
selector/modal JS is inline Blade, so no Vite bundling is required for the feature to
work (verified by live rendering + Playwright screenshots). See §11.

---

## 9. Screenshots

Output: `docs/phase29-screenshots/` (`npm run magnoolia:visual:phase29`, widths
320/375/390/430/768/1440). Per width: homepage, /asendiplaan default · selected row ·
home-detail modal, /kodud-ja-hinnad full · filtered · modal, /ehitusinfo material
block; plus RU/EN /asendiplaan at desktop. Index: `docs/phase29-screenshots/index.md`.

---

## 10. Limitations (honest)

1. **Crop source.** Per-row/per-home crops are top-down clean-asendiplaan snippets
   (accurate, labelled, premium) rather than perspective aerial-render crops. The
   perspective renders (1.jpg/3.jpg) are used as the development overview. Reason:
   reliably matching perspective render regions to exact unit numbers was not safe,
   and the master-context crop principle explicitly permits clean
   map-based highlights with the limitation reported. No houses are faked or removed.
2. **Per-home markers** are footprint-centroid estimates (`approximate:true` in the
   manifest); row markers are accurate.
3. **tee-3-4 land area discrepancy:** hinnatabel `841,5 m²` vs asendiplaan PDF
   `756,4 m²` — needs client confirmation. Code uses the hinnatabel value.
4. **Secondary aerial view (3.jpg)** is wired as the overview "view 2"; per-row
   "Vaade 1 / Vaade 2" toggle is not surfaced because row crops come from the map.
5. **Reference price screenshots** ("hinnad broneeritud") were not supplied
   (non-blocking).
6. **Floor plans** are the existing per-building PDFs (shared within a building).
7. **Live availability** in this environment reflects placeholder seed data
   (all "vaba"); the system overlays whatever the publication holds, so production
   availability will display correctly once published.

---

## 11. No-regression confirmation

- No Phase 25–28 features removed; all additions are additive.
- Header / footer / hero / global layout not redesigned.
- robots/indexability (`MAGNOOLIA_INDEXABLE`), sitemap, llms.txt, schema unchanged.
- Contact form endpoint + validation untouched; modal reuses the existing inquiry
  drawer contract.
- CTA analytics attributes preserved (`data-mg-analytics`, `data-mg-inquiry-open`,
  `data-source-component`); new analytics events added, none removed.
- No OneDrive links, source paths, colour masks or PPTX paths exposed (tested).
- No `price_cents` in any rendered page or view-model (tested).
- No fake prices / reviews / awards added.
- Full test suite shows no new failures (only the pre-existing logo-asset test).

> Note on `npm run build`: it currently fails in this environment for pre-existing
> reasons (Node 18.20 < Vite 7's required Node ≥20.19; `@tailwindcss/oxide` native
> binding missing — fix with a Node upgrade + `npm i`). Phase 29 does **not** depend
> on the Vite build: its styles are in the static `public/assets/css/magnoolia.css`
> and its JS is inline Blade. Functionality verified via live rendering and the
> Playwright screenshots.
