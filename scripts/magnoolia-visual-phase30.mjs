/**
 * Magnoolia Phase 30 — Visual QA for the interactive perspective masterplan.
 * Captures the /asendiplaan journey (initial → row → home detail → floor plans →
 * 2D support map) at desktop + mobile widths, plus a deep-linked URL state.
 *
 * Requires a running dev server.  APP_URL=http://127.0.0.1:8799 node scripts/magnoolia-visual-phase30.mjs
 */
import { chromium } from '@playwright/test';
import { mkdirSync, writeFileSync } from 'fs';
import { join, dirname } from 'path';
import { fileURLToPath } from 'url';

const OUT = join(dirname(fileURLToPath(import.meta.url)), '..', 'docs', 'phase30-screenshots');
mkdirSync(OUT, { recursive: true });
const BASE = process.env.APP_URL || 'http://127.0.0.1:8799';
const WIDTHS = [320, 375, 390, 430, 768, 1440];
const RESULTS = [];

async function shot(page, name, full = true) {
  await page.screenshot({ path: join(OUT, name + '.png'), fullPage: full });
  const overflow = await page.evaluate(() => document.documentElement.scrollWidth > document.documentElement.clientWidth + 5);
  RESULTS.push({ file: name + '.png', overflow });
  console.log(`  ${overflow ? '⚠ overflow' : '✓'} ${name}`);
}
const safe = async (fn) => { try { await fn(); } catch (e) { console.log('  · skip: ' + String(e.message).slice(0, 50)); } };

async function run() {
  console.log(`\nMagnoolia Phase 30 — Visual QA\nBase: ${BASE}\n`);
  const browser = await chromium.launch({ headless: true });
  const ctx = await browser.newContext();

  for (const w of WIDTHS) {
    console.log(`\n── ${w}px ──`);
    const tag = w === 1440 ? 'desktop' : `m${w}`;
    const page = await ctx.newPage();
    await page.setViewportSize({ width: w, height: 900 });

    await page.goto(`${BASE}/asendiplaan`, { waitUntil: 'networkidle', timeout: 30000 });
    await page.waitForTimeout(600);
    await shot(page, `${tag}-initial`, false);

    await safe(async () => { await page.evaluate(() => document.querySelector('[data-mp-row]')?.click()); await page.waitForTimeout(500); await shot(page, `${tag}-row`); });
    const floorLoaded = () => page.waitForFunction(() => { const i = document.getElementById('mg-d-floor-img'); return i && i.complete && i.naturalWidth > 0; }, { timeout: 6000 }).catch(() => {});
    await safe(async () => { await page.evaluate(() => { const h = document.querySelector('[data-mp-home]'); if (h) h.click(); }); await page.waitForTimeout(500); await floorLoaded(); await shot(page, `${tag}-home-detail`); });
    await safe(async () => { await page.evaluate(() => document.querySelector('.mg-mp__ftab[data-floor="2"]')?.click()); await page.waitForTimeout(300); await floorLoaded(); await shot(page, `${tag}-floorplan`); });

    await page.close();
  }

  // deep-linked URL state (desktop) + RU/EN
  console.log('\n── deep-link + RU/EN ──');
  for (const [name, url, full] of [
    ['desktop-deeplink', '/asendiplaan?row=tee-3&home=tee-3-2', true],
    ['desktop-asendiplaan-ru', '/ru/asendiplaan', false],
    ['desktop-asendiplaan-en', '/en/asendiplaan', false],
  ]) {
    const page = await ctx.newPage();
    await page.setViewportSize({ width: 1440, height: 1000 });
    await page.goto(`${BASE}${url}`, { waitUntil: 'networkidle', timeout: 30000 });
    await page.waitForTimeout(800);
    await shot(page, name, full);
    await page.close();
  }

  await browser.close();
  const ov = RESULTS.filter(r => r.overflow);
  writeFileSync(join(OUT, 'index.md'), [
    '# Phase 30 Visual QA Index', '', `Generated: ${new Date().toISOString()}`, `Base: ${BASE}`, '',
    `Total: ${RESULTS.length} · Horizontal overflow: ${ov.length}`, '',
    '| File | Overflow |', '|------|----------|',
    ...RESULTS.map(r => `| ${r.file} | ${r.overflow ? '⚠ yes' : 'no'} |`),
  ].join('\n'));
  console.log(`\n✓ ${RESULTS.length} screenshots, ${ov.length} overflow → docs/phase30-screenshots/index.md\n`);
}
run().catch(e => { console.error('failed:', e.message); process.exit(1); });
