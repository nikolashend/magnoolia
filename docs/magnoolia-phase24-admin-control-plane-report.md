# Magnoolia Phase 24 — Admin Control Plane Report

**Date:** 2026-06-05  
**Status:** ✅ Complete — 269/269 tests passing

---

## Overview

Phase 24 introduced a full admin control plane for the Magnoolia project: a database-backed publication system with draft isolation, role-based access control, audit logging, and rollback capability. The public site now serves data exclusively from versioned, immutable publications rather than static config files.

---

## What Was Built

### Database Migrations (6)

| Migration | Purpose |
|---|---|
| `add_role_to_users_table` | Adds `role` column (`magnoolia_admin`, `magnoolia_editor`) |
| `create_magnoolia_units_table` | Draft units with all fields, lock versioning |
| `create_magnoolia_settings_table` | Campaign config, stage completions, sales contact |
| `create_magnoolia_publications_table` | Immutable versioned publications with public + private snapshots |
| `create_magnoolia_audit_logs_table` | Append-only audit log with before/after state |
| `add_phase24_context_to_magnoolia_leads_table` | Links leads to active publication at time of submission |

### Models

- **`MagnooliaUnit`** — draft unit record, `ALLOWED_STATUSES`, `change_reason` validation hook
- **`MagnooliaSetting`** — campaign and project settings
- **`MagnooliaPublication`** — immutable snapshot; `status` enum (`active`/`inactive`); `draft_checksum` deduplication
- **`MagnooliaAuditLog`** — append-only; `admin()` BelongsTo relationship to User

### Services

| Service | Responsibility |
|---|---|
| `MagnooliaValidationService` | Validates draft before publish: 19-unit count, building distribution (1:3, 3:4, 5:3, 7:3, 9:3, 11:3), asset file existence, price sanity |
| `MagnooliaPublicationService` | Atomically publishes draft → versioned publication; deduplicates identical publishes via checksum; rollback copies a previous snapshot |
| `MagnooliaPublicDataRepository` | Reads `current.json` snapshot for public pages; falls back to draft DB on first publish |
| `MagnooliaAuditService` | Logs actions with before/after state, admin user, IP |

### HTTP Layer

**Middleware:**
- `EnsureMagnooliaAdmin` — requires `magnoolia_admin` or `magnoolia_editor` role
- `EnsureMagnooliaPublishAdmin` — requires `magnoolia_admin` role only
- `MagnooliaLoginThrottle` — 5 attempts per IP per minute on admin login

**Admin Routes** (`/admin/magnoolia/`):

| Method | Path | Middleware | Action |
|---|---|---|---|
| GET | `/units` | admin | Unit list |
| PUT | `/units/{key}` | admin | Update unit (requires `change_reason` for critical fields) |
| GET | `/preview` | admin | Draft preview (noindex) |
| GET | `/publish` | publish-admin | Publish form |
| POST | `/publish` | publish-admin | Execute publish |
| GET | `/rollback/{id}` | publish-admin | Rollback form |
| POST | `/rollback/{id}` | publish-admin | Execute rollback |
| GET | `/publications` | publish-admin | Publication history |
| GET | `/audit` | publish-admin | Audit log (admin-only) |

**Controller:** `MagnooliaAdminController`
- `updateUnit` validates all fields; critical field changes (`status`, `price_cents`) require non-empty `change_reason`
- `publish` checks `confirm_warnings` before proceeding if validation warnings exist
- Audit list eager-loads `admin` relationship

### View Composer

`MagnooliaPublicDataComposer` — registered for all public Magnoolia views; injects `$mgPublic` with `units`, `stages`, `campaign`, `commercial`, `project` from the active publication snapshot.

### Public View Migration

All public Blade templates migrated from `config('magnoolia.units', [])` to `$mgPublic['units']`:
- `pages/magnoolia/asendiplaan.blade.php` — added null-safe `plan_type` access (`?? null`)
- `pages/magnoolia/kodud-ja-hinnad.blade.php`
- `sections/magnoolia/asendiplaan.blade.php`
- `sections/magnoolia/hinnad.blade.php`
- `partials/unit-modal.blade.php`

---

## Test Suite

### Phase 24 Tests: 55/55 ✅

| Test Class | Tests | Coverage |
|---|---|---|
| `MagnooliaPhase24AdminAuthenticationTest` | 9 | Guest/unverified/regular user blocked; editor vs admin route access; login throttle |
| `MagnooliaPhase24DraftIsolationTest` | 8 | Draft changes don't leak to public; preview noindex; editor can't publish; HTTP publish; all locales; critical field requires reason |
| `MagnooliaPhase24HiddenPriceTest` | 7 | Hidden prices absent from payload and public pages; visible price correct (cents÷100); private snapshot preserves raw cents; toggle takes effect on next publish |
| `MagnooliaPhase24PublicationAtomicityTest` | 9 | Immutable versions; previous becomes inactive; checksum; duplicate rejected; validation included; all 3 locales; history preserved; author recorded; stage-2 price hiding |
| `MagnooliaPhase24RollbackTest` | 11 | New version created; previous deactivated; original not mutated; audit trail; reason recorded; non-admin blocked; form accessible; author recorded; draft units restored; append-only chain; audit log entry |
| `MagnooliaPhase24AuditLogsTest` | 11 | Create/read logs; before/after state; publication and rollback entries (`publication_created`, `publication_rolled_back`); user/action display; IP tracking; editor blocked from audit UI; filtering; no delete endpoint; service layer logging |

### Regression: 212 Phase 23 tests ✅

Full suite: **269/269 passed**.

---

## Key Design Decisions

**Price field convention:** DB stores `price_cents` (integer cents). Public payload exposes `price` = `price_cents / 100` (integer euros). Private snapshot retains raw `price_cents`.

**ET locale routing:** Estonian (default locale) has no URL prefix — route is `/kodud-ja-hinnad`. Russian and English have prefixes: `/ru/kodud-ja-hinnad`, `/en/kodud-ja-hinnad`.

**Duplicate publish guard:** `draft_checksum` (SHA-256 of private snapshot JSON) prevents publishing identical data twice.

**Warning confirmation:** Publish endpoint requires `confirm_warnings=1` when `validateDraft()` returns warnings (e.g. missing settings row). Blockers always prevent publish regardless.

**Audit security:** Audit log route is inside `magnoolia.publish-admin` middleware group — editors cannot view it.

---

## Files Changed / Created

```
app/
  Console/Commands/MagnooliaPublishCommand.php
  Http/Controllers/Admin/Magnoolia/MagnooliaAdminController.php
  Http/Middleware/EnsureMagnooliaAdmin.php
  Http/Middleware/EnsureMagnooliaPublishAdmin.php
  Http/Middleware/MagnooliaLoginThrottle.php
  Models/MagnooliaAuditLog.php
  Models/MagnooliaPublication.php
  Models/MagnooliaSetting.php
  Models/MagnooliaUnit.php
  Services/Magnoolia/MagnooliaAuditService.php
  Services/Magnoolia/MagnooliaPublicDataRepository.php
  Services/Magnoolia/MagnooliaPublicationService.php
  Services/Magnoolia/MagnooliaValidationService.php
  View/Composers/MagnooliaPublicDataComposer.php
bootstrap/app.php                          (middleware registration)
config/magnoolia_phase24.php
database/migrations/2026_06_05_120001-6_*
resources/views/admin/magnoolia/          (audit, preview, publish, rollback, units, publications)
resources/views/pages/magnoolia/asendiplaan.blade.php   (plan_type null-safe)
resources/views/pages/magnoolia/kodud-ja-hinnad.blade.php
resources/views/sections/magnoolia/asendiplaan.blade.php
resources/views/sections/magnoolia/hinnad.blade.php
resources/views/partials/unit-modal.blade.php
routes/web.php                             (admin route group)
tests/
  Traits/CreatesMagnooliaTestUnits.php
  Feature/MagnooliaPhase24AdminAuthenticationTest.php
  Feature/MagnooliaPhase24AuditLogsTest.php
  Feature/MagnooliaPhase24DraftIsolationTest.php
  Feature/MagnooliaPhase24HiddenPriceTest.php
  Feature/MagnooliaPhase24PublicationAtomicityTest.php
  Feature/MagnooliaPhase24RollbackTest.php
```
