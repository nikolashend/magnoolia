# Magnoolia Phase 27 — Baseline Before Changes

**Date:** 2026-06-09  
**Phase:** 27 — Mobile-First Premium Production Rescue

---

## Test Baseline

```
php artisan test --filter=Magnoolia
Tests: 407 passed (1792 assertions)
Duration: ~95s
```

All Phase 25 (330) and Phase 26 (77) tests pass.

---

## Publication Status

```
php artisan magnoolia:publication:status

Active publication:  NO
Snapshot file:       YES (storage/app/magnoolia/published/current.json)
Units in file:       19
Stage I in file:     7
Stage II in file:    12
Generated at:        2026-06-08T21:44:22+00:00
DB fallback used:    YES (snapshot file, no DB publication)
Draft DB state:      0 units (test environment)
```

**Status:** Serving from snapshot file. 19 units, correct stage split. Admin edits need re-publish.

---

## Asset Audit

```
php artisan magnoolia:assets:audit

Public assets found:   94
Manifest entries:      53
Missing public folders: none
OneDrive leakage:      0
Source path leakage:   0
Unsupported types:     0
```

### Oversized images (need WebP conversion):
| File | Size |
|------|------|
| gallery/exterior/Cam001.jpg | 4078 KB |
| gallery/exterior/Cam004.jpg | 6598 KB |
| gallery/exterior/Cam005.jpg | 6683 KB |
| gallery/exterior/Cam014.jpg | 4151 KB |
| gallery/exterior/magnoolia_cam07.jpg | 1922 KB |
| gallery/exterior/magnoolia_cam09.jpg | 2806 KB |
| gallery/interior/Interior-1.jpg | 9079 KB |
| gallery/interior/Interior-2.jpg | 8450 KB |
| gallery/interior/Interior-3.jpg | 6401 KB |
| gallery/interior/Interior-4.jpg | 988 KB |
| gallery/interior/Interior-5-1.jpg | 753 KB |
| gallery/interior/Interior-5-2.jpg | 832 KB |
| location/vaela-lasteaed-3.jpg | 518 KB |

### Missing manifest assets:
| Key | Path | Reason |
|-----|------|--------|
| diana_tali | assets/magnoolia/people/diana-tali.webp | Source not delivered |
| magnoolia_dark | assets/magnoolia/logos/magnoolia-dark.svg | Source not delivered |
| estlanda | assets/magnoolia/logos/estlanda.svg | Source not delivered |
| bigbank | assets/magnoolia/logos/bigbank.svg | PDF/AI needs manual conversion |
| aet_piel | assets/magnoolia/logos/aet-piel.png | Source not delivered |

---

## OneDrive Asset Discovery

Deep search of `C:\Users\nikol\` performed — **no matching OneDrive assets found**. The following are BLOCKED:
- `materials/asukoht/gallery/` — empty (images in parent dir, already used)
- `resources/source-assets/magnoolia/onedrive/` — not synced locally
- No `.pptx`, no `Diana Tali.jpg`, no logo files found in search paths

See: `docs/magnoolia-phase27-blocked-assets.md`

---

## Known Issues Entering Phase 27

1. **Gallery images 1–9 MB** — unoptimized JPGs served directly (no WebP)
2. **5 missing logo/person assets** — awaiting OneDrive sync
3. **noindex on staging** — need env-controlled indexing config
4. **Generic page metadata** on several pages
5. **Mobile CTA consistency** — needs audit
6. **Header CTA** — may still show raw `/kontakt` in some views

---

## Phase 27 P0 Priority Order

1. Fix metadata/indexing (P0.2) — env-controlled robots/canonical
2. Fix CTA consistency globally (P0.3)
3. Add "0 homes" defensive tests and guard (P0.1)
4. Image optimization pipeline
5. Mobile CSS improvements
6. Phase 27 tests
7. Final report
