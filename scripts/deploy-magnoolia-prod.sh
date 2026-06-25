#!/usr/bin/env bash
#
# Magnoolia — production admin/data deploy (Phase 33.3).
#
# Run this ON THE PRODUCTION SERVER from the Laravel app root (magnoolia/app).
# It populates the data the client-facing admin depends on and verifies the
# result. It is idempotent: re-running does not duplicate homes, media, or text.
#
# It does NOT publish automatically (publishing changes the live site). After it
# reports READY, confirm the status distribution, then run the publish line shown
# at the end.
#
set -euo pipefail

echo "==> Clearing caches"
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear || true

echo "==> Running migrations"
php artisan migrate --force

echo "==> Seeding canonical data (idempotent)"
php artisan magnoolia:seed-units                 # 19 homes, approved 14/4/1 baseline
php artisan magnoolia:seed-content --force       # 34 Page-Texts blocks across 12 pages (ET/RU/EN)
php artisan magnoolia:seed-gallery               # import public gallery renders into Media Library

echo "==> Verifying readiness"
php artisan magnoolia:verify-readiness || {
  echo "Readiness check failed — review the output above before publishing." >&2
  exit 1
}

cat <<'NOTE'

------------------------------------------------------------------
Data is populated. NOTHING is published yet — the public site is
unchanged until you publish.

1) Log in to /admin/magnoolia and confirm the status distribution
   (approved baseline: 14 Vaba / 4 Broneeritud / 1 Müüdud).
2) When ready, publish so the gallery + page texts go live:

     php artisan magnoolia:publish --note="Production handover seed"

3) Re-run the verify to confirm an active publication exists:

     php artisan magnoolia:verify-readiness

Then capture the production screenshots listed in the Phase 33.3 report.
------------------------------------------------------------------
NOTE
