import { chromium } from 'playwright';

const BASE = 'http://127.0.0.1:8799';
const PATH = '/maja-muuk-harjumaa';
const URL = BASE + PATH;

const out = {};
const jsErrors = [];

const browser = await chromium.launch({ headless: true });
const ctx = await browser.newContext({ viewport: { width: 1280, height: 900 } });
const page = await ctx.newPage();

page.on('console', m => { if (m.type() === 'error') jsErrors.push('console: ' + m.text()); });
page.on('pageerror', e => jsErrors.push('pageerror: ' + e.message));

const resp = await page.goto(URL, { waitUntil: 'networkidle' });
out.status = resp.status();

// h1
const h1s = await page.$$eval('h1', els => els.map(e => e.textContent.trim()));
out.h1Count = h1s.length;
out.h1Text = h1s[0] || '';

// title / meta desc
out.title = await page.title();
out.metaDesc = await page.$eval('meta[name="description"]', e => e.content).catch(() => null);
out.metaDescLen = out.metaDesc ? out.metaDesc.length : 0;

// canonical
out.canonical = await page.$eval('link[rel="canonical"]', e => e.getAttribute('href')).catch(() => null);

// hreflang
out.hreflang = await page.$$eval('link[rel="alternate"]', els => els.map(e => ({ lang: e.getAttribute('hreflang'), href: e.getAttribute('href') })));

// html lang
out.htmlLang = await page.$eval('html', e => e.getAttribute('lang')).catch(() => null);

// ld+json
const ldRaw = await page.$$eval('script[type="application/ld+json"]', els => els.map(e => e.textContent));
out.ldParsed = [];
out.jsonLdValid = true;
for (const r of ldRaw) {
  try {
    out.ldParsed.push(JSON.parse(r));
  } catch (e) {
    out.jsonLdValid = false;
    out.ldParsed.push({ __parseError: e.message, raw: r.slice(0, 200) });
  }
}
// collect types
const types = [];
const collectTypes = (o) => {
  if (Array.isArray(o)) return o.forEach(collectTypes);
  if (o && typeof o === 'object') {
    if (o['@type']) types.push(o['@type']);
    if (o['@graph']) collectTypes(o['@graph']);
  }
};
out.ldParsed.forEach(collectTypes);
out.ldTypes = types;

// FAQ from JSON-LD
const faqLd = [];
const findFaq = (o) => {
  if (Array.isArray(o)) return o.forEach(findFaq);
  if (o && typeof o === 'object') {
    if (o['@type'] === 'FAQPage' && Array.isArray(o.mainEntity)) {
      o.mainEntity.forEach(q => faqLd.push({
        q: (q.name || '').trim(),
        a: (q.acceptedAnswer && q.acceptedAnswer.text || '').replace(/<[^>]+>/g, '').trim()
      }));
    }
    if (o['@graph']) findFaq(o['@graph']);
  }
};
out.ldParsed.forEach(findFaq);
out.faqLd = faqLd;

// visible FAQ cards - try common selectors
out.faqVisible = await page.evaluate(() => {
  const results = [];
  // heuristics: look for FAQ section
  const cands = document.querySelectorAll('[class*="faq"], [id*="faq"]');
  const texts = new Set();
  cands.forEach(c => {
    // find question-like elements
    c.querySelectorAll('h2,h3,h4,h5,summary,dt,strong,button,.question,[class*="question"]').forEach(q => {
      const t = q.textContent.trim();
      if (t && t.length < 300) texts.add(t);
    });
  });
  return [...texts];
});

// CTA above fold
out.ctas = await page.$$eval('.zoomvilla-btn', els => els.map(e => {
  const r = e.getBoundingClientRect();
  return { top: Math.round(r.top), text: e.textContent.trim().slice(0, 60), visible: r.width > 0 && r.height > 0 };
}));
out.ctaAboveFold = out.ctas.some(c => c.visible && c.top < 700 && c.top >= 0);

// internal links
out.links = await page.$$eval('a[href]', els => els.map(e => e.getAttribute('href')));

// language content sample
out.bodySample = await page.evaluate(() => document.body.innerText.replace(/\s+/g, ' ').trim().slice(0, 4000));

// overflow at 320
await page.setViewportSize({ width: 320, height: 800 });
await page.waitForTimeout(300);
const ov = await page.evaluate(() => ({ sw: document.documentElement.scrollWidth, cw: document.documentElement.clientWidth }));
out.overflow320 = ov.sw > ov.cw;
out.overflowDims = ov;

out.jsErrors = jsErrors;

console.log(JSON.stringify(out, null, 2));
await browser.close();
