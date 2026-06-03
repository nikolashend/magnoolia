# Phase 21 — Mobile QA Checklist
**Date:** 2025  
**Viewport targets:** 320px, 375px (iPhone SE), 390px (iPhone 14), 414px (Plus), 768px (iPad), 1024px (iPad Pro)

---

## Viewport & Meta

| Check | Status | Notes |
|-------|--------|-------|
| `<meta name="viewport" content="width=device-width, initial-scale=1">` | ✅ | In `layouts/app.blade.php` |
| No horizontal scroll at 320px | ✅ | Bootstrap grid is fluid |
| Touch targets ≥ 44×44px | ✅ | CTA buttons use `mg-cta` with `padding: 12px 28px` min |

---

## Section-by-Section Mobile Checks

### Hero / Slider
- Full-width at all breakpoints ✅
- CTA button stacks below headline on < 576px ✅
- Slide images use `object-fit: cover` ✅

### Facts Strip
- Counter tiles wrap to 2×2 grid on mobile ✅
- No overflow from wide numbers ✅

### About Magnoolia
- Image hides on < 768px (col-lg-6 structure) ✅
- Text remains readable at 320px ✅

### Benefits
- 3-column becomes 1-column at < 768px ✅

### Gallery Strip
- Owl carousel shows 1 item at < 576px, 2 at < 992px, per `data-owl-options` ✅

### Hinnad (Pricing)
- Unit cards stack at mobile ✅
- Price CTA button full-width on mobile ✅

### Asendiplaan (Site Plan)
- Interactive map SVG has `max-width: 100%` ✅
- Tooltip interaction works on touch (click-triggered, not hover) — **needs manual verification** ⚠️

### Floor Plans
- Plan selector tabs become scrollable on < 576px ✅
- Floor plan images scale with `max-width: 100%` ✅
- Lightbox (fullscreen enlarge) uses correct ARIA labels in all locales ✅ (fixed Phase 21)

### Video Gallery
- Owl carousel shows 1 item on mobile ✅

### FAQ Accordion
- Full width at all breakpoints ✅
- Arrow icon accessible (rotates on open) ✅

### AI Answer / Answer Unit
- Text content wraps correctly ✅

### Contact Section
- Form inputs are 100% width on mobile ✅
- Consent checkbox is tappable (sufficient padding) ✅

---

## Header / Navigation
- Mobile hamburger menu at < 992px ✅
- Sticky header — height compensates for content below ✅
- CTA button in header collapses on mobile ✅

## Footer
- 3-column footer stacks to 1-column on mobile ✅
- WhatsApp float button doesn't obscure footer links ✅ (fixed in Phase 17)

## Mobile CTA Strip
- Fixed bottom CTA visible on scroll ✅
- Dismissible after first scroll down ✅

---

## Performance (Mobile-Relevant)
- LCP image preloaded (`<link rel="preload" as="image">`) ✅ (Phase 20)
- Hero image uses modern format (WebP/JPEG XL recommended — P2 improvement)
- No render-blocking JS above fold ✅ (scripts deferred)
- Reduced-motion CSS for preloader ✅ (Phase 20)

---

## Accessibility (Mobile)
- Enlarged floor plan aria-labels now locale-aware ✅ (Phase 21 fix)
- All icon buttons have `aria-label` or `aria-hidden="true"` on decorative icons ✅

---

## Items Requiring Manual Verification

| Item | Why |
|------|-----|
| Asendiplaan touch tooltips | SVG hover interaction on touch devices may need tap-toggle |
| Owl carousel swipe on iOS Safari | CSS `touch-action` must allow horizontal swipe |
| Unit modal scroll on small height viewports | Modal content should be scrollable, not clipped |
| Form autocomplete on mobile keyboard | `autocomplete` attributes on all inputs |

---

## Verdict

✅ **Mobile QA baseline PASSED** (automated/code review). 4 items flagged for manual device testing on next sprint.
