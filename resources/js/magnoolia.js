/**
 * magnoolia.js — Project-specific JS
 * Phase 1: Minimal foundation only.
 * Phase 2+ will add: scroll animations, nav mobile toggle, CTA tracking hooks.
 */

const Magnoolia = {

  /**
   * Init — called on DOMContentLoaded
   */
  init() {
    this.initHeaderScroll();
    this.initMobileNav();
    this.logPhase();
  },

  /**
   * Add scrolled class to header for subtle shadow on scroll
   */
  initHeaderScroll() {
    const header = document.querySelector('.site-header');
    if (!header) return;

    const onScroll = () => {
      header.classList.toggle('site-header--scrolled', window.scrollY > 10);
    };

    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll(); // run once on load
  },

  /**
   * Mobile nav toggle placeholder
   * Phase 2 will implement the full mobile menu
   */
  initMobileNav() {
    const toggle = document.querySelector('[data-nav-toggle]');
    const nav    = document.querySelector('[data-nav-menu]');
    if (!toggle || !nav) return;

    toggle.addEventListener('click', () => {
      const isOpen = toggle.getAttribute('aria-expanded') === 'true';
      toggle.setAttribute('aria-expanded', String(!isOpen));
      nav.hidden = isOpen;
    });
  },

  /**
   * Phase tracking — remove in production
   */
  logPhase() {
    if (document.documentElement.dataset.env !== 'production') {
      console.info('[Magnoolia] Phase 1 foundation loaded.');
    }
  },

  /**
   * CTA event hook — placeholder for Phase 8 tracking
   * Usage: Magnoolia.trackCta('hero_primary', { label: 'Küsi pakkumist' })
   */
  trackCta(id, data = {}) {
    // Phase 8: replace with real GA4 / Google Ads event push
    if (document.documentElement.dataset.env !== 'production') {
      console.debug('[Magnoolia CTA]', id, data);
    }
  },

};

export default Magnoolia;
