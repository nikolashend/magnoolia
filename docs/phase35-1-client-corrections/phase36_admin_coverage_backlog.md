# Phase 35.1 — Admin editability map & Phase 36 backlog

**The question this answers:** for every block Indrek asked to change, can the client change it himself today, and if not, what does Phase 36 need to build?

## How the admin works today

The Magnoolia admin has these screens (from `php artisan route:list`):

`/admin/magnoolia` · `units` · `campaign` · `content` · `media` · `leads` · `preview` · `validate` · `publish` · `publications` · `changes` · `audit` · `site-map` · `help` · CSV import/export

Publishing flow: **edit draft → Preview Draft → Validate → Publish Website Changes → (rollback via Publications)**. Nothing reaches the public site until publish.

### The decisive constraint

The **Content editor (`/admin/magnoolia/content`) is a fixed whitelist of 34 blocks**, defined in `app/Console/Commands/MagnooliaSeedContentCommand.php::BLOCKS`. Only these are editable:

| Covered | Blocks |
|---|---|
| Homepage | `hero.h1`, `hero.subheadline`, `hero.cta_primary` |
| Per page (kodud, asendiplaan, asukoht, ehitusinfo, sisedisain, galerii, ostuprotsess, finantseerimine, kkk, kontakt) | `page_h1`, `lead`, and usually `note` |
| Footer | `footer.tagline`, `footer.desc` |

Everything else on every page — feature cards, plan comparison specs, FAQ answers, section titles, gallery ordering, extras/lisateenused, developer projects, header images — lives in **lang files or Blade templates and is not reachable from the admin**.

That single fact explains most of Indrek's frustrations: he can change a page's heading and intro, but not the content underneath it.

---

## Per-item editability

| # | Block Indrek asked to change | Editable now | Admin path | Why not / note |
|---|---|---|---|---|
| 2 | Homepage hero H1 | **YES** | Content → Homepage — hero headline (H1) | `hero.h1` is whitelisted. Change applied in code today is the new default; client can override. |
| 2 | Homepage hero subheadline | **YES** | Content → Homepage — hero subheadline | `hero.subheadline` whitelisted. |
| 2 | "Ridaelamu mugavus. Eramaja kogemus." | **NO** | — | `magnoolia.section.why_title` in lang file, not whitelisted. |
| 3 | Homepage main image | **NO** | — | Image is referenced by filename in Blade/lang, not by a media record. |
| 4 | Feature photo titles (09 → Autovarjualune) | **NO** | — | Card array lives in `lang/*/magnoolia.php`. |
| 4 | Feature photo images (04/06/08) | **NO** | — | Same array; filenames hardcoded. |
| 5 | Campaign / Eripakkumine text | **YES — now truthful** | Campaign | Was broken: two of three campaign renderings ignored the admin screen entirely. Phase 35.1 made the admin the single authority. Deadline/CTA fields exist in the DB but are not surfaced publicly yet. |
| 6 | Asukoht map / buttons | **NO** | — | Markup in `asukoht.blade.php`. |
| 7 | Asukoht architect plan image | **NO** | — | Not a media record. |
| 8 | Gallery order | **PARTIAL** | Media | Media manager exists; whether it exposes sort order for the public gallery is unverified. Public gallery reads the publication payload. |
| 9 | Eripakkumine on Hinnad page | **YES** | Campaign | Same source as item 5 since Phase 35.1; switching the campaign off now removes the red banner too. |
| 10 | Lisateenused / extras | **NO** | — | `config/magnoolia_interiors.php`. |
| 11 | Table labels ("Netopind kokku") | **NO** | — | Lang keys. |
| 11 | Hooviala values | **YES** | Units → per-home fields | `private_yard_area` is a unit column. |
| 12 | Plan A/B facts, Valikuabi, KKK | **NO** | — | Lang files + Blade. This is why the wrong 143,2 m² survived so long. |
| 13 | Arhitektuur renders | **NO** | — | Hardcoded list. |
| 14 | Exterior element photos/text | **NO** | — | Hardcoded. |
| 15 | Sisedisain CTA recipient | **NO** | — | Recipient is `config('magnoolia.project.contact_email')` = diana@estlanda.ee, editable only in code. The admin *Settings* table already stores `sales_contact_email`; wiring the mailer to it would make this editable with almost no work. |
| 16 | Self-link button | **NO** | — | Blade markup. |
| 17 | Prestige section | **NO** | — | Blade markup. |
| 18 | Accordion default state | **NO** | — | Blade/CSS behaviour. |
| 19 | Sisedisain black-background photos | **NO** | — | `config/magnoolia_interiors.php` image paths. |
| 20 | Arendaja projects (Kakumäe) | **NO** | — | Hardcoded project cards. |
| 21 | Per-page header images | **NO** | — | Hardcoded per page. |

**Score after Phase 35.1: 4 of 21 fully editable** (hero H1, hero subheadline, campaign on both pages, unit numeric fields), 1 partial (gallery order), 16 not editable.

> The campaign moved from "editable but ignored by two of three pages" to genuinely editable. That is the only editability *gain* this phase — everything else Indrek asked for still needs a developer, which is the whole point of Phase 36.

---

## Phase 36 backlog (recommended build order)

Ordered by client pain × commercial risk, not by technical convenience.

| Priority | Module | Why | Risk if not built |
|---|---|---|---|
| **P0** | **Plan type / Variant A–B editor** | The 143,2 m² error sat live because nobody could correct it without a developer. | Wrong areas in a sales-critical block; legal/trust exposure. |
| **P0** | **FAQ / KKK editor** (with JSON-LD kept in sync) | FAQ repeats the same facts; it drifted from reality in the same way. | Contradictory facts across page and rich results. |
| **P0** | **Campaign / Eripakkumine editor hardening** | Client explicitly plans to change offers himself, on two pages. | Stale offers, or offers shown while marked inactive. |
| P1 | **Lisateenused / extras structured editor** | Sales block with prices/options that changes with suppliers. | Outdated paid-extras list. |
| P1 | **Homepage feature-card editor** (image + title + text) | Item 4 is exactly this. | Every card change needs a deploy. |
| P1 | **Per-page header image editor** | Item 21. | Visual staleness; no client control. |
| P1 | **Gallery drag-sort editor** | Item 8; ordering is a marketing decision. | New photos always land last. |
| P2 | **Sisedisain section image editor** | Item 19; client asked directly how to replace them. | Developer needed for a routine swap. |
| P2 | **Architecture renders editor** | Item 13. | New renders require a deploy. |
| P2 | **Developer projects editor** | Item 20; project list grows over time. | Each new project needs a deploy. |
| P2 | **Location map / pins editor** | Item 6. | Map content frozen. |
| **P1** | **Lead recipient / routing config** | Item 15. `sales_contact_email` already exists in Settings but the mailer ignores it — small change, high value. | Leads silently going to the wrong address if Diana changes. |
| P3 | **Generic label / translation override CMS** | Items 11, 12 labels. | Every wording tweak is a code change. |

### The structural recommendation

Rather than building 13 bespoke editors, Phase 36 should consider **widening the existing Content-block whitelist into a generic keyed-text override** (the `mg_text()` mechanism already exists and already flows through draft → publish). That converts most "NO" rows above into "YES" with one mechanism, and leaves only genuinely structured data (images, ordering, repeatable cards) needing purpose-built UI.

Suggested split:
1. **Generic text override** (extend `mg_text()` coverage + admin UI to browse/edit any whitelisted key) → solves items 2, 4-titles, 11, 12, 14-text, 20-text.
2. **Media assignment** (bind a named site slot to a media record) → solves items 3, 4-images, 7, 13, 19, 21.
3. **Ordering** (sort_order on gallery/media) → solves item 8.
4. **Structured editors** only where the data is genuinely relational → plan types, extras, campaign, developer projects.
