// Phase 34 — hostile public audit: 200s, leaks, i18n, SEO metadata, JSON-LD,
// overflow, and screenshots across ET/RU/EN. Outputs a JSON report to stdout.
import { chromium } from '@playwright/test';

const BASE = process.env.MG_BASE || 'http://127.0.0.1:8799';
const SHOTS = 'docs/phase34-final-launch-screenshots';

// [slug, key, screenshot?]
const PAGES = [
  ['', 'home', true],
  ['kodud-ja-hinnad', 'kodud', true],
  ['asendiplaan', 'asendiplaan', true],
  ['asukoht', 'asukoht', false],
  ['ehitusinfo', 'ehitusinfo', true],
  ['sisedisain', 'sisedisain', false],
  ['arhitektuur-ja-valisdisain', 'arhitektuur', false],
  ['galerii', 'galerii', true],
  ['ostuprotsess', 'ostuprotsess', false],
  ['finantseerimine', 'finantseerimine', false],
  ['kkk', 'kkk', false],
  ['kontakt', 'kontakt', true],
];
const LOCALES = ['et', 'ru', 'en'];
const LEAKS = ['price_cents', '/source/', '.pptx', '1drv.ms', 'onedrive', 'C:\\\\', 'storage/app'];
const KEYRE = /magnoolia\.(page|nav|hero|footer|section|pricing|faq|forms|contact|statuses|stages|benefits|facts|floorplan)\.[a-z0-9_]+/;
const WIDTHS = [320, 375, 390, 430, 768, 1024, 1440];

function urlFor(locale, slug) {
  const p = slug ? '/' + slug : '';
  if (locale === 'et') return BASE + (p || '/');
  return BASE + '/' + locale + (p || '');
}

const report = { pages: [], summary: {} };
const b = await chromium.launch({ headless: true });

for (const locale of LOCALES) {
  for (const [slug, key, shot] of PAGES) {
    const url = urlFor(locale, slug);
    const ctx = await b.newContext({ viewport: { width: 1440, height: 900 } });
    const p = await ctx.newPage();
    const jsErrors = [];
    p.on('console', m => { if (m.type() === 'error') jsErrors.push(m.text()); });
    const row = { locale, key, url, status: 0, leaks: [], unresolvedKeys: [], jsErrors: 0, overflow: [] };
    try {
      const resp = await p.goto(url, { waitUntil: 'networkidle', timeout: 25000 });
      row.status = resp.status();
      const html = await p.content();
      row.leaks = LEAKS.filter(s => html.includes(s));
      const km = html.match(KEYRE);
      row.unresolvedKeys = km ? [km[0]] : [];
      // SEO metadata
      row.meta = await p.evaluate(() => {
        const q = s => document.querySelector(s);
        return {
          title: (document.title || '').trim(),
          desc: q('meta[name="description"]')?.content || '',
          canonical: q('link[rel="canonical"]')?.href || '',
          hreflang: [...document.querySelectorAll('link[rel="alternate"][hreflang]')].map(l => l.getAttribute('hreflang')),
          ogTitle: q('meta[property="og:title"]')?.content || '',
          ogImage: q('meta[property="og:image"]')?.content || '',
          h1: [...document.querySelectorAll('h1')].map(h => h.textContent.trim()).filter(Boolean),
          jsonld: [...document.querySelectorAll('script[type="application/ld+json"]')].map(s => s.textContent),
          imgsNoAlt: [...document.querySelectorAll('img')].filter(i => !i.getAttribute('alt') && !i.hasAttribute('aria-hidden')).length,
        };
      });
      // JSON-LD validity + types
      row.schemaTypes = [];
      row.schemaInvalid = 0;
      for (const block of row.meta.jsonld) {
        try {
          const j = JSON.parse(block);
          const collect = o => {
            if (Array.isArray(o)) return o.forEach(collect);
            if (o && typeof o === 'object') {
              if (o['@type']) row.schemaTypes.push(Array.isArray(o['@type']) ? o['@type'].join('|') : o['@type']);
              if (o['@graph']) collect(o['@graph']);
            }
          };
          collect(j);
        } catch { row.schemaInvalid++; }
      }
      row.jsErrors = jsErrors.length;
      // overflow across widths
      for (const w of WIDTHS) {
        await p.setViewportSize({ width: w, height: 900 });
        await p.waitForTimeout(120);
        const of = await p.evaluate(() => document.documentElement.scrollWidth > document.documentElement.clientWidth + 2);
        if (of) row.overflow.push(w);
      }
      // screenshots (et only): desktop 1440 + mobile 390
      if (shot && locale === 'et') {
        await p.setViewportSize({ width: 1440, height: 900 });
        await p.waitForTimeout(200);
        await p.screenshot({ path: `${SHOTS}/desktop-${key}.png`, fullPage: false });
        await p.setViewportSize({ width: 390, height: 844 });
        await p.waitForTimeout(200);
        await p.screenshot({ path: `${SHOTS}/mobile-${key}.png`, fullPage: false });
      }
    } catch (e) {
      row.error = e.message;
    }
    report.pages.push(row);
    await ctx.close();
  }
}
await b.close();

// summary
const bad = report.pages.filter(r => r.status !== 200);
const leaky = report.pages.filter(r => (r.leaks || []).length || (r.unresolvedKeys || []).length);
const overflowed = report.pages.filter(r => (r.overflow || []).length);
const jserr = report.pages.filter(r => r.jsErrors > 0);
const noCanonical = report.pages.filter(r => !r.meta?.canonical);
const badH1 = report.pages.filter(r => (r.meta?.h1?.length || 0) !== 1);
const schemaBad = report.pages.filter(r => r.schemaInvalid > 0);
report.summary = {
  total: report.pages.length,
  non200: bad.map(r => `${r.locale}:${r.key}=${r.status}`),
  leaks: leaky.map(r => `${r.locale}:${r.key} ${[...r.leaks, ...r.unresolvedKeys].join(',')}`),
  overflow: overflowed.map(r => `${r.locale}:${r.key} @${r.overflow.join('/')}`),
  jsErrors: jserr.map(r => `${r.locale}:${r.key}=${r.jsErrors}`),
  noCanonical: noCanonical.map(r => `${r.locale}:${r.key}`),
  badH1count: badH1.map(r => `${r.locale}:${r.key}=${r.meta?.h1?.length}`),
  schemaInvalid: schemaBad.map(r => `${r.locale}:${r.key}`),
};
console.log(JSON.stringify(report.summary, null, 2));
// dump per-page SEO for ET
console.log('\n=== ET SEO ===');
for (const r of report.pages.filter(p => p.locale === 'et')) {
  console.log(`${r.key}: title="${(r.meta?.title||'').slice(0,60)}" canon=${r.meta?.canonical?'Y':'N'} hreflang=${(r.meta?.hreflang||[]).join('/')} h1=${r.meta?.h1?.length} schema=[${[...new Set(r.schemaTypes||[])].join(',')}] noAlt=${r.meta?.imgsNoAlt}`);
}
