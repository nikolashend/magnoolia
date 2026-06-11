# Magnoolia Phase 28 — Baseline Before Changes

**Date:** 2026-06-11  
**Phase:** 28 — Final Premium Launch Readiness  
**Baseline captured at:** Start of Phase 28 execution  

---

## Test Baseline

| Metric | Value |
|--------|-------|
| Tests passed (Phase 27 result) | **558** |
| Test failures | 0 |
| Assertions | 2803 |
| Risky tests | 1 |
| Duration | ~155 seconds |

**Command:** `php artisan test --filter=Magnoolia`

---

## Publication Status

```
Active publication: NO
Snapshot file: storage/app/magnoolia/published/current.json
Snapshot exists: YES
Version in file: 1
Generated at: 2026-06-11T13:06:23+00:00
Units in file: 19
Stage I in file: 7
Stage II in file: 12
DB fallback used: YES (serving from snapshot, no DB publication)
```

**19 homes confirmed, Stage I = 7, Stage II = 12** ✓

---

## Phase 27 Completion State

- Status: `YELLOW_ASSET_LIMITED_BUT_CODE_READY`
- Image optimization: 32 images processed, 136.6 MB → 32.1 MB
- Visual QA: 72 screenshots, 0 FAIL, 30 WARN
- Mobile menu: Fixed and functional
- Header CTA: Using `data-mg-inquiry-open`
- Footer: Has `site-footer` class
- SEO: MAGNOOLIA_INDEXABLE flag implemented

---

## Known Issues Before Phase 28

| Issue | Severity | Status |
|-------|----------|--------|
| Real Magnoolia logo not in header | P1 | OPEN |
| Estlanda logo not in footer | P2 | OPEN |
| ET `pricing.*` keys missing (buyer_note, count_all_pre, etc.) | P1 | OPEN |
| Homepage missing compact availability board | P1 | OPEN |
| asendiplaan.blade.php hardcoded "kevad 2027" (ET only, not localized for RU/EN) | P1 | OPEN |
| pricing-teaser "I etapp"/"II etapp" not localized for RU/EN | P2 | OPEN |
| Sisedisain not using PPTX source images | P2 | OPEN |
| Ehitusinfo missing material spec table from Excel | P2 | OPEN |
| Diana photo not provided in phase28 materials | BLOCKED | DOCUMENTED |
| No Aet Piel logo/photo in phase28 materials | BLOCKED | DOCUMENTED |
| No Ehitusinfo.xlsx (only Hals.xlsx, Plaadid maht.xlsx) | BLOCKED | DOCUMENTED |

---

## Phase 28 Asset Inventory (discovered at baseline)

### Found in materials/phase28:
| File | Size | Status |
|------|------|--------|
| Taustata.png | 2059 KB | Magnoolia logo without background |
| Taustaga.png | 226 KB | Magnoolia logo with background |
| Estlanda-1-taustata.png | 46 KB | Estlanda horizontal logo (v1) |
| Estlanda-1.0-taustata.png | 53 KB | Estlanda wide logo |
| Estlanda-2-taustata.png | 42 KB | Estlanda horizontal (v2) |
| Estlanda-2.0-taustata.png | 48 KB | Estlanda wide logo (v2) |
| Estlanda-3-taustata.png | 46 KB | Estlanda horizontal (v3) |
| Estlanda-3.0-taustata.png | 53 KB | Estlanda wide logo (v3) |
| Magnoolia kodud Prestige.pptx | 26,272 KB | General PPTX |
| Magnoolia  kodud Prestige Sisedisain.pptx | 35,170 KB | Sisedisain PPTX |
| Hals.xlsx | 9.7 KB | Sanitary fittings data |
| Plaadid maht.xlsx | 9.2 KB | Tile specifications |
| Copy of Mag. tee ker plaadid.xlsx | 9.4 KB | Tile specifications with prices |
| BB Kodulaen.pdf | 8,146 KB | Bigbank loan PDF |
| bigbank_logo_rgb.pdf | 292 KB | Bigbank logo PDF |
| Magnoolia UUED bännerid_3400x1750mm_näidis1.jpg | 2,217 KB | Banner image |

### NOT found in phase28 import:
| Missing File | Impact |
|-------------|--------|
| Diana Tali.jpg / diana*.jpg | Contact page still uses text fallback for contact person |
| aet piel foto.png | Sisedisain Aet Piel card uses text fallback |
| Aet Piel LOGO.png | Sisedisain Aet Piel card uses text fallback |
| www.aetpiel.com.docx | No website confirmation needed for Aet Piel |
| Ehitusinfo.xlsx | Using available tile/fittings Excel files instead |

---

## Check Passed Before Phase 28 Work

- [x] 558 existing tests pass
- [x] 19 homes in snapshot
- [x] Stage I = 7 homes
- [x] Stage II = 12 homes
- [x] No price_cents leakage (confirmed by existing tests)
- [x] CTA drawer attributes present
- [x] Header/mobile menu/footer exist
- [x] Image optimization artifacts exist (32 WebP images from Phase 27)

**Baseline: CONFIRMED. Proceed with Phase 28 work.**
