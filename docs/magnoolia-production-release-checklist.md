# Magnoolia.ee — Production Release Checklist

> **Target domain:** magnoolia.ee  
> **Stack:** Laravel 12 · PHP 8.2 · MySQL · XAMPP (dev) → Linux VPS (prod)  
> **Last updated:** 2026-06-01

---

## PRE-DEPLOY — Local / Staging

### Code & Environment
- [ ] `php artisan route:list` — no unexpected routes, all 39 magnoolia routes present (ET + RU + EN × 13 + thank-you × 3 + POST × 3)
- [ ] `php -l app/Http/Controllers/MagnooliaController.php` — no syntax errors
- [ ] `php -l routes/web.php` — no syntax errors
- [ ] `php -l config/magnoolia.php` — no syntax errors
- [ ] `php -l lang/et/magnoolia.php` — no syntax errors
- [ ] `php artisan view:cache` — no compilation errors
- [ ] `php artisan config:cache` — clean
- [ ] `php artisan route:cache` — clean

### Environment Variables (.env → .env.production)
- [ ] `APP_ENV=production`
- [ ] `APP_DEBUG=false`
- [ ] `APP_URL=https://magnoolia.ee`
- [ ] `DB_*` — production database credentials
- [ ] `MAIL_MAILER=smtp` — configured to real SMTP (not `log`)
- [ ] `MAIL_FROM_ADDRESS` — set to a verified sender address
- [ ] `SESSION_DRIVER=database` or `redis` (not `file` for multi-process)
- [ ] `CACHE_DRIVER=redis` or `database`
- [ ] `QUEUE_CONNECTION=sync` (or `redis` if queues used)
- [ ] No `info@magnoolia.ee` anywhere in codebase (grep before deploy)

### Contact & Lead Logging
- [ ] `config/magnoolia.php` → `project.contact_email` = correct diana@... address
- [ ] Honeypot field present in contact form (`name="website"`)
- [ ] Rate limit active: 3 requests / 10 min / IP
- [ ] DB table `magnoolia_leads` created: `php artisan migrate:status | grep leads`
- [ ] Test form end-to-end: submit → redirect to /aitah → lead in DB → email received
- [ ] Test honeypot: fill `website` field → silent discard, no email, no DB row

### Content & Language
- [ ] ET homepage — no English text visible (visual QA in browser)
- [ ] RU homepage (/ru) — all text in Russian
- [ ] EN homepage (/en) — all text in English
- [ ] `video-gallery-source.blade.php` — uses `__()` keys, not hardcoded ET text ✅
- [ ] All 13 ET inner pages load without errors
- [ ] `/aitah` loads, shows ET thank-you message
- [ ] `/ru/aitah` loads, shows RU thank-you message

### SEO & Meta
- [ ] Each page has unique `<title>` (no "Laravel" default)
- [ ] Each page has `<meta name="description">` with non-empty content
- [ ] `<link rel="canonical">` present and correct
- [ ] `/robots.txt` — allows crawling of main pages, disallows /aitah, /ru/aitah, /en/aitah
- [ ] `/sitemap.xml` accessible, contains all 13 ET public pages + RU + EN equivalents
- [ ] OG tags present: `og:title`, `og:description`, `og:url`, `og:image`
- [ ] `/llms.txt` — accessible and up to date
- [ ] No `<meta name="robots" content="noindex">` on public pages (only on /aitah)

### Schema / Structured Data
- [ ] Homepage: `LocalBusiness` or `RealEstateAgent` JSON-LD with `name`, `url`, `address`
- [ ] `/kkk` page: `FAQPage` JSON-LD validates at schema.org/validator
- [ ] `/ostuprotsess` page: `HowTo` JSON-LD present (if implemented)
- [ ] No `Offer` schema with fake prices
- [ ] No `Review`/`AggregateRating` with fake data

### Images
- [ ] All `<img>` tags in `sections/approved/` have `loading="lazy" decoding="async"` ✅
- [ ] Hero images (above-the-fold) use `loading="eager"` NOT lazy
- [ ] All images have non-empty descriptive `alt` attributes (no `alt=""` on content images)
- [ ] Images under `public/assets/images/magnoolia/` all exist (no 404s)

### Performance (quick)
- [ ] No unused `console.log` in production JS
- [ ] CSS and JS assets are properly versioned (`mix()` or `vite()`)
- [ ] Response time < 2s for homepage on localhost (baseline)

---

## DNS CUTOVER STEPS

1. **Backup current site** (if any) — download full files + DB dump
2. **Deploy to server** — `git pull` or rsync
3. **Run migrations** on prod: `php artisan migrate --force`
4. **Set permissions**: `chmod -R 755 storage bootstrap/cache`
5. **Generate app key** if new environment: `php artisan key:generate`
6. **Point DNS** — A record for `magnoolia.ee` → server IP, TTL 300
7. **SSL certificate** — issue Let's Encrypt cert, redirect HTTP → HTTPS
8. **Set APP_URL** to `https://magnoolia.ee` and run `php artisan config:cache`
9. **Test live**: `/`, `/kodud-ja-hinnad`, `/kontakt`, form submission, `/aitah`, `/ru/`, `/en/`
10. **Verify email delivery**: check diana@... inbox for test lead

---

## POST-DEPLOY MONITORING

- [ ] Check `storage/logs/laravel.log` — no errors after first hour
- [ ] Check `magnoolia_leads` table — entries arriving after real visitors
- [ ] Verify Google Search Console ownership tag in `<head>` (if provided)
- [ ] Submit sitemap to Google Search Console: `https://magnoolia.ee/sitemap.xml`
- [ ] Test on mobile (Chrome DevTools or real device)
- [ ] Check page load in incognito (no dev cache)

---

## QA COMMANDS (run before each deploy)

```bash
# Syntax checks
php -l app/Http/Controllers/MagnooliaController.php
php -l routes/web.php
php -l config/magnoolia.php
php -l lang/et/magnoolia.php
php -l lang/ru/magnoolia.php
php -l lang/en/magnoolia.php

# Route list (spot-check)
php artisan route:list | grep magnoolia

# Cache clear + warm
php artisan view:clear
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:cache
php artisan route:cache

# Migration status
php artisan migrate:status | grep leads

# Check for forbidden strings
grep -r "info@magnoolia.ee" resources/ app/ config/ lang/
grep -r "Lorem ipsum" resources/
grep -r "suvi 2027" resources/ lang/
```

---

## P0 RULES (must never violate)

- ❌ No fake prices or price ranges in schema or visible text
- ❌ No `Offer` schema, no `AggregateRating` with invented data
- ❌ No `info@magnoolia.ee` anywhere
- ❌ No "suvi 2027" (use "kevad 2027" / "2027")
- ❌ No Lorem ipsum or placeholder agent names
- ❌ No global CSS rewrites that break existing components
- ❌ No breaking existing routes or changing route names without updating `lroute()` calls
