import './bootstrap';
import Magnoolia from './magnoolia.js';

document.addEventListener('DOMContentLoaded', () => {
  Magnoolia.init();
});

// Expose for inline HTML usage (e.g. onclick="Magnoolia.trackCta(...)")
window.Magnoolia = Magnoolia;
