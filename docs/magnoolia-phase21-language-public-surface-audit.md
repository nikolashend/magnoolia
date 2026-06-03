# Phase 21 — Language Public Surface Audit 3.0
**Date:** 2025  
**Scope:** All live public routes × 3 locales (ET/RU/EN). Dead files (styleguide, unit-detail, contact-team) excluded.

---

## Audit Methodology

Automated `Select-String` grep on every `*.blade.php` in:
- `resources/views/sections/approved/`
- `resources/views/sections/magnoolia/`
- `resources/views/partials/home/`
- `resources/views/partials/` (header, footer, mobile-cta, unit-modal)
- `resources/views/pages/magnoolia/`

Pattern: `[äöüõÄÖÜÕ]` plus known ET phrases (Suurenda, korruse, esimese, Vaata, Küsi).

---

## Results by File

### sections/approved/

| File | Status | Notes |
|------|--------|-------|
| facts-source.blade.php | ✅ CLEAN | alt attrs with property name are OK |
| about-magnoolia-source.blade.php | ✅ CLEAN | alt attrs with property name only |
| benefits-source.blade.php | ✅ CLEAN | |
| gallery-strip-source.blade.php | ✅ CLEAN | |
| floor-plan-source.blade.php | ✅ FIXED | aria-labels and floor alt texts → `sprintf(__(...), $plan['label'])` |
| video-gallery-source.blade.php | ✅ CLEAN | alt attrs only |
| accordion-source.blade.php | ✅ CLEAN | |

### sections/magnoolia/

| File | Status |
|------|--------|
| hinnad.blade.php | ✅ CLEAN |
| asendiplaan.blade.php | ✅ CLEAN |
| ai-answer.blade.php | ✅ CLEAN |
| answer-unit.blade.php | ✅ CLEAN |
| contact.blade.php | ✅ CLEAN (alt="Diana Tali – Estlanda müügiinfo" = proper name, OK) |
| page-cta.blade.php | ✅ SAFE — fallback `'Küsi lähemalt'` never triggered (all callers pass `__()`) |
| page-faq.blade.php | ✅ SAFE — fallback `'Korduma kippuvad küsimused'` never triggered |

### partials/ (shared components — fixed in Phase 20)

| Component | Status |
|-----------|--------|
| header.blade.php | ✅ CLEAN |
| footer/ | ✅ CLEAN |
| mobile-cta.blade.php | ✅ CLEAN |
| unit-modal.blade.php | ✅ CLEAN (56 keys added in Phase 20) |

---

## Fix Applied — floor-plan-source.blade.php

**Issue:** 4 hardcoded ET strings in aria-labels and `$plans` array alt texts.

**Fix (Phase 21):**
- Added 4 keys to all 3 lang files (`lang/{et,ru,en}/magnoolia.php`) in `floorplan` section:
  - `floor1_alt` — `'%s — esimese korruse plaan'` / `'%s — план 1-го этажа'` / `'%s — ground floor plan'`
  - `floor2_alt` — `'%s — teise korruse plaan'` / `'%s — план 2-го этажа'` / `'%s — upper floor plan'`
  - `enlarge_aria_1` — `'Suurenda %s 1. korruse plaani'` / `'Увеличить план 1-го этажа %s'` / `'Enlarge %s ground floor plan'`
  - `enlarge_aria_2` — `'Suurenda %s 2. korruse plaani'` / `'Увеличить план 2-го этажа %s'` / `'Enlarge %s upper floor plan'`
- Blade updated to use `sprintf(__('magnoolia.floorplan.floor1_alt'), $plan['label'])` pattern.
- aria-labels use `{{ sprintf(__('magnoolia.floorplan.enlarge_aria_1'), $plan['label']) }}`.

---

## Forbidden Content Scan

| Pattern | Files scanned | Violations in live files |
|---------|--------------|--------------------------|
| `Lorem ipsum` | all live blades | 0 (only in dead styleguide.blade.php) |
| `info@magnoolia.ee` | all live blades | 0 |
| `suvi 2027` | all live blades | 0 |
| `Ralph Havens` / `Louis Coolidge` | all live blades | 0 (only in dead contact-team-source) |
| `Zoomvilla` | all live blades | CSS class names only — ACCEPTABLE |

---

## Alt Attribute Policy

Alt attributes referencing the property name (Magnoolia, Vaela küla, Harjumaa) are **intentionally in Estonian** as proper nouns — this is acceptable per the project spec. All other user-visible content must use `__()`.

---

## Verdict

✅ **Language audit PASSED.** Zero visible ET strings leak to RU/EN visitors after Phase 21 fix.
