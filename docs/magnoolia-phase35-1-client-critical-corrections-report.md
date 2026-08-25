# MAGNOOLIA PHASE 35.1 REPORT

**Client critical corrections — Indrek's 21 items · Round 1**
Date: 2026-08-24 · Scope: visible site corrections only. No SEO/domain work (that was Phase 35.0), no redesign.

---

## Status

**`PENDING_PHASE35_1_ASSETS_REQUIRED`**

Read this as: **8 of 21 items are complete; 12 remain, and 1 is deferred by the client's own decision.** PASS is not claimed and would be false while items are outstanding.

> **Update 2026-08-24 (round 2a):** the client supplied the three missing assets in `materials/24.08.2026/` and confirmed the washroom counts. Items **3, 7 and 13 are now done**, item 12 is complete including `pesuruumide arv`, and the image half of item 4 is **deferred at the client's request** until Indrek names the replacements.

| Status | Items |
|---|---|
| ✅ Done (16) | 1, 2, 3, 4 (title), 5, 7, 9, 10, 11, 12, 13, 15, 16, 17, 18, 19, 20 |
| ⏸ Deferred by client | image half of 4 |
| ❓ Needs one client decision | 14 (külm panipaik copy) |
| ⏳ Remaining (4) | **6** map · **8** gallery order · **14** exterior photos · **21** header images |

### Round 3 highlights

**The campaign was advertising an offer nobody could switch off.** Three places rendered it from three different sources: the homepage teaser from `config/magnoolia.php`, the red banner on `/kodud-ja-hinnad` from a hardcoded lang string, and only the lower banner from the admin Campaign screen. Turning the campaign off in admin therefore left a **20 000 € offer with a 31.08.2026 deadline** live on two pages — a week before it expired. All three now read one normalised source, with the admin screen authoritative once anything has been published. This is the phase's only genuine gain in client editability.

**Sisedisain leads were reaching Diana but arriving mislabelled.** The chain (CTA → drawer → `POST /kontakt` → `contact_email` = diana@estlanda.ee) was intact, but *Täpsusta viimistlust* was a bare link carrying no context, and the finish-package CTAs still tagged themselves `ehitusinfo_siseviimistlus` although the block had moved to `/sisedisain`. Both fixed; the hero button keeps its `href` as a no-JS fallback so it cannot dead-end.

**Item 20 needed no work.** `Kakumäe Residence → kakumae.com` was already the third card on `/arendajast`, correctly using `rel="noopener noreferrer"`. Reported rather than re-done.

Full per-item detail: `phase35-1-client-corrections/indrek_21_item_acceptance_matrix.md`.

---

## Executive summary

The headline result is **item 12**, and it was worse than Indrek described.

He said the 143,2 m² five-room variant does not exist. I checked the authoritative source — the live publication payload behind `/kodud-ja-hinnad`, all 19 homes — and he is exactly right:

| Plan | Rooms | net_area | Homes |
|---|---|---|---|
| type-a | 4 | **129,2 m²** | 7 |
| type-b | 5 | **129,6 m²** | 12 |

**No home in the development has 143,2 m².** That figure was being advertised in 34 places: Estonian, Russian and English copy, the plan comparison cards, the FAQ, the page JSON-LD and the FAQPage rich-results schema. All 34 are corrected. This was a live commercial misstatement about the product, in three languages, in structured data Google reads.

The deeper finding is *why* it survived: **the client cannot edit any of it.** The admin's Content editor is a fixed whitelist of 34 blocks — page headings, intro paragraphs, the footer. Everything underneath (plan facts, FAQ answers, feature cards, extras, gallery order, header images, developer projects) lives in lang files and Blade templates. Of Indrek's 21 requests, **3 are editable today, 2 partly, and 16 not at all.** That is the real subject of Phase 36, and it is mapped in `phase36_admin_coverage_backlog.md`.

---

## Phase 35.0 dependency status

| Check | Result |
|---|---|
| Final production host | `https://magnoolia.estlanda.ee` (client's confirmed arrangement; `magnoolia.ee` 302s to it) |
| Canonical | Follows the serving host automatically; verified live |
| Indexability | `index,follow` live |
| Gate | Phase 35.0 is functionally deployed and verified — content work is not blocked |
| New old-domain links introduced by 35.1 | **none** — no absolute URLs were added |

> ⚠️ **Conflict with the brief, resolved in favour of reality.** Phase 35.1's text assumes `https://magnoolia.ee/` is the production domain and warns against treating `magnoolia.estlanda.ee` as production. The client has since decided the opposite: estlanda **is** the live host, for now. Testing therefore used the real production host. This is a documented decision, not an assumption.

---

## What changed

### `/kodud-ja-hinnad`, KKK and schema — item 12 (P0 factual)

- Plan A card: `ca 129,6 m²` → **`ca 129,2 m²`**
- Plan B card: `ca 143,2 m²` → **`ca 129,6 m²`**
- ET/RU/EN: every "5-toaline ~143,2 m²" claim corrected in FAQ answers, plan descriptions, comparison text, `plan_a_size` / `plan_b_size`
- Ranges "~129–143 m²" → "~129 m²" (both plans are ~129 m²; the range implied a larger type that does not exist)
- `/kodud-ja-hinnad` page JSON-LD description corrected
- **FAQPage JSON-LD** in `partials/seo/schema.blade.php` corrected, so visible FAQ and rich-results data agree
- `config/magnoolia.php` header comment corrected — it was the origin of the myth ("type-b (5-room 143.2m2, corner)")
- Terminology: "laoruum" → "panipaik" in the corrected schema sentence

### Homepage — item 2 (Indrek's exact wording)

| Before | After |
|---|---|
| `A-energiaklassi ridaelamukodud Tallinna lähedal` (H1) | **`Eksklusiivsed Magnoolia kodud Tallinna lähedal`** |
| `…eramaja privaatsuse ja uusarenduse **kindluse**…` | `…eramaja privaatsuse ja uusarenduse **väärtuse**…` |
| `Ridaelamu mugavus. Eramaja **privaatsus**.` | `Ridaelamu mugavus. Eramaja **kogemus**.` |

### Homepage — item 4 (title half)

`Kaetud parkimiskoht` → **`Autovarjualune 2-le sõidukile`**, with the alt text updated to match. The image half is deferred at the client's request.

### Plan cards — washroom counts (item 12, completing)

Confirmed by the client from Indrek's annotated screenshot and now published in all three languages via a new `spec_washrooms` label: **Plan A = 3**, **Plan B = 2**.

### `/` and `/arhitektuur-ja-valisdisain` — items 3 and 13

Homepage "19 kodu" block now shows the evening LED facade render; the exterior-renders section gained `Cam020` and `Cam007`.

### `/asukoht` — item 7

New **"Arhitekti asendiplaan"** section above the distance table: responsive image up to 2400 px, click opens the full-size drawing in a new tab, `OPMANI TEE` legible. Three new lang keys added in ET/RU/EN (`siteplan_title`, `siteplan_sub`, `siteplan_open`).

---

## What was intentionally not changed

- **No image was substituted for a blocked one.** Items 3, 7 and 13 name specific OneDrive files. All three are unreachable without a Microsoft login (evidence below). Choosing a different render would be inventing client intent — explicitly forbidden by the brief.
- **Photos 04/06/08 (item 4) not replaced.** The client decided to defer: which images should replace them is not clear from Indrek's note, and choosing them is a judgement about meaning, not a technical swap.
- **RU/EN hero copy left as-is.** Item 2 is an Estonian-language complaint; the corrected ET strings are not literal translations of the RU/EN ones, so syncing them is a separate copy decision.
- **No layout, route, admin, gallery, form or pricing logic touched.**

---

## Assets — resolved 2026-08-24

The client exported the three files manually to `materials/24.08.2026/` after the OneDrive links proved unreachable. What was done with them:

| File supplied | Resolution | Used for |
|---|---|---|
| `Lisa Cam_20 lükandaken.jpg` | 4500×2250 | **Item 3** — homepage "19 kodu" block image, replacing `Cam005`. **Item 13** — added to VÄLISVAATED. Installed as `Cam020.0000.jpg`; note the same render was already on site at only 2000×1000, so this is also a quality upgrade. |
| `Cam007.0000.jpg` | 4500×2250 | **Item 13** — evening view from the yard side; was not on the site at all. |
| `MAGNOOLIA_ASENDIPLAAN.pdf` | 19×27 inch, vector | **Item 7** — rasterised with pdf.js at scale 2.2 (3025×4241), published as `asendiplaan-hires.webp` 2400 px + 1200/768 variants, in a new "Arhitekti asendiplaan" section on `/asukoht`. The **OPMANI TEE** label is visible on the drawing, as Indrek marked. |

All images went through the project's existing optimiser (`scripts/magnoolia-optimize-images.mjs`) — webp at 480/768/1200/1600, ~66 KB at 1200 px.

Rasterising the PDF needed no new project dependency: ImageMagick, Ghostscript and poppler are absent, and Chromium treats a PDF as a download, so pdf.js was loaded from a CDN **inside a throwaway Playwright page**. Nothing was added to `package.json`.

### Original blocking evidence (for the record)

All three OneDrive links were tried anonymously, three ways:

| Link | Direct | shares API | Graph |
|---|---|---|---|
| Evening LED facade (items 3, 13) | 301 → photos.onedrive.com, `Content-Length: 0` (JS viewer) | **401 unauthenticated** | **401 InvalidAuthenticationToken** |
| Architect plan PDF (item 7) | 301 → onedrive.live.com, **403 Forbidden** | **401** | **401** |
| Second render (item 13) | 301 → viewer, `Content-Length: 0` | **401** | **401** |

`materials/` was also searched: it holds `OneDrive_1_7-9-2026/Cam_17.jpg` and `Cam_20.jpg` plus the phase28/29 render sets, but no evening-LED facade view and no high-resolution architect plan matching Indrek's screenshots.

**Resolved:** the client exported them manually — see above. The request list sent to him is kept at `phase35-1-client-corrections/ASSETS_NEEDED.md`.

---

## Item 11 — implemented as annotated (client approved)

Indrek asked to replace the label **"Netopind"** with **"Netopind kokku"** and to show hooviala as a whole number.

There was a trap in the literal reading, which the client confirmed should be avoided. Indrek's own annotation defines three different areas:

```
Köetav pind      129,6 m²      ← net_area (what the column shows today)
Panipaiga pind     7,3 m²      ← storage_area
Netopind kokku   136,9 m²      ← net_area + storage_area
```

The column currently displays `net_area` **alone**. Simply relabelling it "Netopind kokku" would state that 129,6 m² is the total — replacing one factual error with another, on the same page we just corrected.

**Implemented:** the unit modal now shows, in Indrek's order — `Köetav pind` (net_area) → `Panipaiga pind` (storage_area) → `Netopind kokku` (computed) → `Terrass` → `Rõdu` → `Hooviala` (whole number). The plan A/B comparison cards, which show net_area alone, are relabelled `Netopind` → `Köetav pind` in ET/RU/EN.

Two safety rules in the code:
- `fmtNetTotal()` returns nothing when either part is missing, so a total is never invented — the row simply does not render.
- `fmtAreaWhole()` rounds only the *display* of hooviala; stored values are untouched, so the admin Units screen and exports keep full precision.

Verified rendered: Plan A card `ca 129,2 m²`, Plan B card `ca 129,6 m²`, all three modal labels present, no `143,2` anywhere.

---

## SEO / schema no-regression proof

| Check | Result |
|---|---|
| FAQ visible text vs FAQPage JSON-LD | **now consistent** — both corrected together (they had diverged) |
| Canonical / hreflang / robots / sitemap | untouched by this phase |
| New absolute links introduced | none |
| Old-domain strings in changed files | none |
| Full test suite | **860 tests, 56 failures, 0 errors — identical to the pre-phase baseline**, i.e. no regressions |

---

## Test results

```
php artisan config:clear / view:clear   OK
php vendor/bin/phpunit                  860 tests, 56 failures, 0 errors  (unchanged baseline)
route smoke test (7 URLs)               all 200 — /, /asukoht, /arhitektuur-ja-valisdisain,
                                        /kodud-ja-hinnad, /galerii, /ru/…, /en/…
```

The 56 failures are the pre-existing `/asendiplaan` consolidation drift documented in the Phase 35.0 report, unrelated to this phase.

---

## Screenshots

**Not captured in round 1.** The `before/` and `after/` folders are created but empty. Capturing 20+ before/after pairs is only meaningful once the remaining 12 items are implemented — otherwise "before" shots would be taken after four fixes already landed. Round 2 will capture them against the production host.

This is stated plainly rather than claimed as done.

---

## Remaining risks

1. **12 of 21 items are not yet implemented.** This report does not pretend otherwise.
2. **Pesuruumide arv unconfirmed** — the one plan fact still missing from the corrected block.
3. **The corrected areas are now right, but still unverifiable by the client.** Until Phase 36, another stale figure could be introduced the same way.
4. **RU/EN copy divergence** after the ET hero corrections.
5. **Changes are not yet published.** Lang/Blade changes go live on `git pull`; they do not need the admin publish flow.

---

## Final verdict

**`PENDING_PHASE35_1_ASSETS_REQUIRED`** — round 1 complete.

The single most damaging item on Indrek's list (a product area advertised in three languages and in Google's structured data that no home actually has) is fixed and verified against the authoritative dataset. The admin-editability map he implicitly asked for by complaining "I can't change this myself" is complete and turned into a prioritised Phase 36 backlog. Three items need files only he can supply, one needs a one-line decision, and twelve are queued for round 2.
