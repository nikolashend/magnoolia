# Magnoolia Phase 10 — Current State (Do-Not-Break)

## Public homepage section order
1. Hero (`partials/home/slider`)
2. Facts (`sections/approved/facts-source`)
3. About (`sections/approved/about-magnoolia-source`)
4. Benefits (`sections/approved/benefits-source`)
5. Gallery strip (`sections/approved/gallery-strip-source`)
6. Hinnad (`sections/magnoolia/hinnad`)
7. Asendiplaan (`sections/magnoolia/asendiplaan`)
8. Floor plans (`sections/approved/floor-plan-source`)
9. Video/gallery (`sections/approved/video-gallery-source`)
10. FAQ accordion (`sections/approved/accordion-source`)
11. AI answer block (`sections/magnoolia/ai-answer`)
12. Contact (`sections/magnoolia/contact`)
13. Footer (`partials/footer`)

## Public layout includes
- `resources/views/layouts/app.blade.php`
- `resources/views/partials/header.blade.php`
- `resources/views/partials/footer.blade.php`
- `resources/views/partials/mobile-cta.blade.php`
- `resources/views/partials/mobile-menu.blade.php`
- `resources/views/partials/sidebar.blade.php`
- `resources/views/partials/unit-modal.blade.php`
- `resources/views/partials/seo/meta.blade.php`
- `resources/views/partials/seo/schema.blade.php`

## Demo-only / internal files
- `resources/views/sections/approved/contact-team-source.blade.php` (demo template block; not included on homepage)
- `resources/views/pages/styleguide.blade.php` (internal page; route guarded by `app()->isLocal()` or preview token)

## Known client-data blockers (must not be invented)
- Final hinnatabel (prices)
- Kasutusõiguse ala / private yard m² per unit
- Final Diana photo permission
- Jaanika / JP Design confirmation
- Final production domain/canonical
- Final EXR/mask/hotspot mapping from Yellow Studio

## Forbidden strings (public)
- Louis Coolidge
- Anthony Roy
- Brenda Salinas
- Marketing Team
- 370X507
- info@magnoolia.ee
- +372 000 0000
- contact.submit
- suvi 2027 / summer 2027
- lahedal / kuela / taepsustamisel
- Paikesepaneelide / Porandakuute / rodud
- Lorem / placeholder (template placeholders)

## Safe next data-update path
1. Update `config/magnoolia.php` unit-level factual data (prices, areas, statuses)
2. Keep all fallbacks as `Täpsustamisel` until approved
3. Add hotspot coordinates into `config/magnoolia_map.php` only after Yellow Studio mapping
4. Keep `MG_SHOW_DEV_HOTSPOTS=false` on public environment
5. Re-run cache + forbidden-string grep before deploy
