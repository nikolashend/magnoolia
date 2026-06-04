# Magnoolia Phase 23 — Real Client Data Integration Report

## Scope
- Implemented canonical real-data integration for 19 units from client Excel and PDF materials.
- Enforced Stage II hidden-price policy across table, modal payload, and UI rendering.
- Integrated official floorplan and asendiplaan PDF assets.
- Removed public Jaanika/JP references and added Aet Piel + Bigbank blocks on required pages.
- Added dedicated Phase 23 regression tests and reran full Magnoolia test gate.

## Data Sources
- `materials/1 Koduka tekst Magnoolia  05.05.26 (2).docx`
- `materials/_Magnoolia hinnatabel 05.05.26.xlsx`
- `materials/VEEBI _ASENDIPLAAN.pdf`
- `materials/M1_1korrus.pdf` … `materials/M11_2korrus.pdf`

## Canonical Model
- New canonical units source: `config/magnoolia_units.php` (generated from Excel)
- Connected in `config/magnoolia.php` via `require __DIR__ . '/magnoolia_units.php'`
- Total units: **19**
- Building split: **1:3, 3:4, 5:3, 7:3, 9:3, 11:3**
- Stage visibility:
  - Stage I: `price_public = true`
  - Stage II: `price_public = false`
- Added central commercial blocks:
  - `campaign`
  - `commercial.included`
  - `commercial.extras`
  - `commercial.excluded`

## Asset Integration
- Floorplans copied to: `public/assets/magnoolia/floorplans/`
  - `M1_1korrus.pdf`, `M1_2korrus.pdf`, … `M11_1korrus.pdf`, `M11_2korrus.pdf`
- Asendiplaan PDF copied to:
  - `public/assets/magnoolia/asendiplaan/asendiplaan.pdf`
- Modal now resolves per-unit floorplan PDF links from canonical unit fields.

## UI / Content Changes
- `resources/views/sections/magnoolia/hinnad.blade.php`
  - Price rendering now gated by `price_public`
  - Stage II prices render as “price to be confirmed”
  - Added campaign stripe and extras/excluded commercial block rendering
- `resources/views/partials/unit-modal.blade.php`
  - `mgUnitsData` sanitized server-side so hidden-price units export `price = null`
  - JS rendering checks `unit.price_public`
  - Floorplan links wired to canonical per-building PDFs
- `resources/views/pages/magnoolia/asendiplaan.blade.php`
  - Added official asendiplaan PDF download CTA
- `resources/views/sections/magnoolia/contact.blade.php`
  - Removed public Jaanika block and legacy references
- `resources/views/pages/magnoolia/sisedisain.blade.php`
  - Added Aet Piel interior design contact panel
- `resources/views/pages/magnoolia/finantseerimine.blade.php`
  - Added Bigbank financing partner block

## DOCX-derived Content Added
- From `materials/1 Koduka tekst Magnoolia  05.05.26 (2).docx` → `SISEDISAIN`
  - Added Aet Piel interior-design contact panel in `resources/views/pages/magnoolia/sisedisain.blade.php`
  - Implemented public copy: `Sisedisaini kontakt`, `Aet Piel`, and short helper text for interior-design enquiries
- From `materials/1 Koduka tekst Magnoolia  05.05.26 (2).docx` → `Finantseerimine`
  - Added Bigbank financing partner block in `resources/views/pages/magnoolia/finantseerimine.blade.php`
  - Implemented public copy: `Finantseerimispartner`, `Bigbank`, and CTA block pointing to Bigbank
- From `materials/1 Koduka tekst Magnoolia  05.05.26 (2).docx` → `Hinnad ja plaanid`
  - Added campaign copy to `config/magnoolia.php` and rendered it in `resources/views/sections/magnoolia/hinnad.blade.php`
  - Implemented public copy: `KAMPAANIA` and `Esimestele ostjatele hinnasoodustus 20 000 eurot hinnakirjas näidatud summast.`
- From `materials/1 Koduka tekst Magnoolia  05.05.26 (2).docx` → `Hoonesektsiooni hind sisaldab` / `Müügihind ei sisalda` / optional extras
  - Added config-driven included / excluded / extras blocks in `config/magnoolia.php`
  - Rendered on `resources/views/sections/magnoolia/hinnad.blade.php`
  - Public ET labels for these blocks added in `lang/et/magnoolia.php` and translated for RU/EN parity

## Mapping Layer
- `config/magnoolia_map.php`
  - Hotspot registry now auto-populated 1:1 from canonical units
  - Coordinates remain null until final approved hotspot coordinate package

## Localization
- Added pricing labels for new commercial UI blocks in:
  - `lang/et/magnoolia.php`
  - `lang/ru/magnoolia.php`
  - `lang/en/magnoolia.php`
  - Keys: `pricing.extras_title`, `pricing.excluded_prefix`

## Tests
### New Phase 23 tests
- `tests/Feature/MagnooliaPhase23RealDataIntegrationTest.php`
  - Validates canonical count + building distribution
  - Validates Stage II `price_public = false`
  - Validates hidden-price logic exists in pricing/modal templates
  - Validates no public Jaanika/JP references in target public templates
  - Validates floorplan asset files exist

### Regression gate results
- Command: `php artisan test --filter=Magnoolia`
- Result: **212 passed, 0 failed**

## Notes
- One source-level numeric drift observed during extraction cross-check (`Magnoolia tee 1/2` private yard area appeared as 513.3 in asendiplaan text extraction vs 513.2 in Excel). Canonical value follows Excel (pricing table source of truth).

## Status
- Phase 23 implementation: **COMPLETE**
- P0/P1 acceptance gate status: **PASS**
