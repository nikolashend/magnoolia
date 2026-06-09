# Magnoolia Phase 27 — Blocked Assets

**Date:** 2026-06-09

The requested files exist in the user's OneDrive screenshots but are not available to the local Cursor runtime. Please sync/download the folder or place a ZIP under `materials/onedrive-import/`.

## Blocked files

| Expected file | Category | Status |
|---------------|----------|--------|
| `Diana Tali.jpg` | Contact photo | NOT FOUND in any local path |
| `Magnoolia kodud Prestige.pptx` | Sisedisain PPTX | NOT FOUND |
| `Ehitusinfo.xlsx` | Construction info | NOT FOUND |
| `Plaadid maht.xlsx` | Sisedisain tiles | NOT FOUND |
| `Aet Piel LOGO.png` | Partner logo | NOT FOUND |
| `aet piel foto.png` | Partner photo | NOT FOUND |
| `bigbank_logo_rgb.pdf` | Bank logo | NOT FOUND |
| `Estlanda.ai / Estlanda.pdf` | Developer logo | NOT FOUND |
| `Magnoolia*.png` (dark/light variants) | Project logo | NOT FOUND |

## Searched paths

- `C:\Users\nikol\Documents\projects\magnoolia\app\materials\`
- `C:\Users\nikol\Documents\projects\magnoolia\app\resources\source-assets\`
- `C:\Users\nikol\Documents\` (depth 4, no results)
- `C:\Users\nikol\Downloads\` (depth 1, no results)
- No OneDrive folder found at `C:\Users\nikol\OneDrive\`

## Resolution required

1. Sync OneDrive folder `_Estlanda/26. Mangoolia 1, 3, 5, 7, 9, 11/8. Koduleht/` locally
2. OR place files in `materials/onedrive-import/`
3. OR copy directly to `public/assets/magnoolia/people/diana-tali.webp` etc.

## Code implications

All placeholder/text-only fallbacks remain in place until real assets are delivered. No broken image icons will be shown — all asset displays use `@if(file_exists(...))` guards.
