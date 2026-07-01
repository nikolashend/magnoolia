import { chromium } from '@playwright/test';

const SLUG = '/perekodu-tallinna-lahedal';
const TARGET = 'http://127.0.0.1:8799' + SLUG;
const out = { jsErrors: [], issues: [] };

const browser = await chromium.launch({ headless: true });
const ctx = await browser.newContext({ viewport: { width: 1280, height: 900 } });
const page = await ctx.newPage();

page.on('console', m => { if (m.type() === 'error') out.jsErrors.push('console: ' + m.text()); });
page.on('pageerror', e => out.jsErrors.push('pageerror: ' + e.message));

const resp = await page.goto(TARGET, { waitUntil: 'networkidle' });
out.status = resp.status();
out.slug = new URL(page.url()).pathname;

// h1
const h1s = await page.$$eval('h1', els => els.map(e => e.textContent.trim()));
out.h1Count = h1s.length;
out.h1Text = h1s[0] || '';

// title
const title = await page.title();
out._title = title;
out.titleSet = !!title && !/^(untitled|document|home|magnoolia)$/i.test(title.trim());

// meta description
const metaDesc = await page.$eval('meta[name="description"]', e => e.getAttribute('content')).catch(() => null);
out._metaDesc = metaDesc;
out.metaDescLen = metaDesc ? metaDesc.length : 0;

// canonical
const canonical = await page.$eval('link[rel="canonical"]', e => e.getAttribute('href')).catch(() => null);
out._canonical = canonical;
out.canonicalSelf = !!canonical && new URL(canonical).pathname === SLUG;

// hreflang
const hreflangs = await page.$$eval('link[rel="alternate"][hreflang]', els => els.map(e => ({ lang: e.getAttribute('hreflang'), href: e.getAttribute('href') })));
out._hreflangs = hreflangs;
const badAlt = hreflangs.filter(h => !['et','x-default'].includes((h.lang||'').toLowerCase()));
out.hreflangOk = badAlt.length === 0;
out._badAlt = badAlt;

// JSON-LD
const ldRaw = await page.$$eval('script[type="application/ld+json"]', els => els.map(e => e.textContent));
let jsonLdValid = true;
const types = [];
for (const raw of ldRaw) {
  try {
    const j = JSON.parse(raw);
    const arr = Array.isArray(j) ? j : (j['@graph'] || [j]);
    for (const node of arr) { if (node && node['@type']) types.push(Array.isArray(node['@type']) ? node['@type'].join(',') : node['@type']); }
  } catch (e) { jsonLdValid = false; out.issues.push('Invalid JSON-LD: ' + e.message); }
}
out._ldTypes = types;
out._ldCount = ldRaw.length;
const hasWebPage = types.some(t => /WebPage/i.test(t));
const hasFAQ = types.some(t => /FAQPage/i.test(t));
if (!hasWebPage) out.issues.push('Missing WebPage JSON-LD');
if (!hasFAQ) out.issues.push('Missing FAQPage JSON-LD');
out.jsonLdValid = jsonLdValid && hasWebPage && hasFAQ;

// FAQ questions
let faqQuestions = [];
for (const raw of ldRaw) {
  try {
    const j = JSON.parse(raw);
    const arr = Array.isArray(j) ? j : (j['@graph'] || [j]);
    for (const node of arr) {
      const t = node['@type'];
      const isFaq = Array.isArray(t) ? t.includes('FAQPage') : t === 'FAQPage';
      if (isFaq && node.mainEntity) {
        faqQuestions = (Array.isArray(node.mainEntity) ? node.mainEntity : [node.mainEntity]).map(q => (q.name || '').trim());
      }
    }
  } catch {}
}
out._faqQuestions = faqQuestions;

const bodyText = await page.evaluate(() => document.body.innerText);
const norm = s => s.replace(/\s+/g, ' ').trim().toLowerCase();
const bodyNorm = norm(bodyText);
const missingFaq = faqQuestions.filter(q => q && !bodyNorm.includes(norm(q)));
out.faqMatchesVisible = faqQuestions.length > 0 && missingFaq.length === 0;
out._missingFaq = missingFaq;

// CTA above fold (1280x900)
const ctas = await page.$$eval('.zoomvilla-btn', els => els.map(e => { const r = e.getBoundingClientRect(); return { top: r.top, text: e.textContent.trim().slice(0,40), visible: r.width > 0 && r.height > 0 }; }));
out._ctas = ctas;
out.ctaAboveFold = ctas.some(c => c.visible && c.top < 700 && c.top >= 0);

// internal links
const hrefs = await page.$$eval('a[href]', els => els.map(e => e.getAttribute('href')));
out._hrefs = [...new Set(hrefs)];
const hasHomes = hrefs.some(h => /(^|\/)(kodud|kodu|majad|villa|homes)\b/i.test(h) && new URL(h, TARGET).pathname !== SLUG);
const hasContact = hrefs.some(h => /kontakt|contact/i.test(h));
out.internalLinksOk = hasHomes && hasContact;
out._hasHomes = hasHomes; out._hasContact = hasContact;

// overflow at 320
await page.setViewportSize({ width: 320, height: 800 });
await page.waitForTimeout(300);
const ov = await page.evaluate(() => ({ sw: document.documentElement.scrollWidth, cw: document.documentElement.clientWidth }));
out._ov = ov;
out.overflow320 = ov.sw > ov.cw;

// language consistency
const enWords = /\b(the|and|welcome|contact us|read more|about us|price from|available now|book a viewing|learn more|our homes)\b/i;
const ruChars = /[а-яА-ЯёЁ]/;
const langAttr = await page.$eval('html', e => e.getAttribute('lang')).catch(() => null);
out._langAttr = langAttr;
const enHit = bodyText.match(enWords);
const ruHit = ruChars.test(bodyText);
out.langConsistent = !enHit && !ruHit && (!langAttr || /^et/i.test(langAttr));
if (enHit) out.issues.push('English copy detected: ' + enHit[0]);
if (ruHit) out.issues.push('Russian copy detected');
if (langAttr && !/^et/i.test(langAttr)) out.issues.push('html lang not et: ' + langAttr);

// verdict
out.verdict = (out.status === 200 && out.h1Count === 1 && out.jsonLdValid && out.ctaAboveFold && !out.overflow320 && out.jsErrors.length === 0 && out.internalLinksOk && out.langConsistent) ? 'PASS' : 'FAIL';

if (out.status !== 200) out.issues.push('status ' + out.status);
if (out.h1Count !== 1) out.issues.push('h1Count=' + out.h1Count);
if (!out.canonicalSelf) out.issues.push('canonical not self: ' + out._canonical);
if (!out.hreflangOk) out.issues.push('bad hreflang alternates: ' + JSON.stringify(badAlt));
if (!out.titleSet) out.issues.push('title default/empty: ' + title);
if (!out.faqMatchesVisible) out.issues.push('FAQ JSON-LD mismatch, missing: ' + JSON.stringify(missingFaq));
if (!out.ctaAboveFold) out.issues.push('no CTA above fold');
if (out.overflow320) out.issues.push('horizontal overflow at 320px: sw=' + ov.sw + ' cw=' + ov.cw);
if (!out.internalLinksOk) out.issues.push('internal links missing homes=' + hasHomes + ' contact=' + hasContact);
if (out.jsErrors.length) out.issues.push(out.jsErrors.length + ' JS errors');

console.log(JSON.stringify(out, null, 2));
await browser.close();
