/**
 * Magnoolia Phase 29 — Visual QA for the rowhouse selection journey.
 * Captures: homepage availability board, /asendiplaan (default · selected row ·
 * home-detail modal), /kodud-ja-hinnad (full · filtered · mobile cards · modal),
 * and the /ehitusinfo material-proof block — at desktop + mobile widths.
 *
 * Requires a running dev server. Usage:
 *   APP_URL=http://127.0.0.1:8799 node scripts/magnoolia-visual-phase29.mjs
 */
import { chromium } from '@playwright/test';
import { mkdirSync, writeFileSync } from 'fs';
import { join, dirname } from 'path';
import { fileURLToPath } from 'url';

const __dirname = dirname(fileURLToPath(import.meta.url));
const OUT_DIR = join(__dirname, '..', 'docs', 'phase29-screenshots');
mkdirSync(OUT_DIR, { recursive: true });

const BASE = process.env.APP_URL || 'http://127.0.0.1:8799';
const WIDTHS = [320, 375, 390, 430, 768, 1440];
const RESULTS = [];

async function shot(page, name) {
  const file = `${name}.png`;
  await page.screenshot({ path: join(OUT_DIR, file), fullPage: true });
  const overflow = await page.evaluate(() => document.documentElement.scrollWidth > document.documentElement.clientWidth + 5);
  RESULTS.push({ file, overflow });
  console.log(`  ${overflow ? '⚠ overflow' : '✓'} ${file}`);
}

async function safe(fn) {
  try { await fn(); } catch (e) { console.log(`  · step skipped: ${String(e.message).slice(0, 60)}`); }
}

async function go(page, path) {
  const r = await page.goto(`${BASE}${path}`, { waitUntil: 'networkidle', timeout: 30000 });
  await page.waitForTimeout(500);
  return r?.status() ?? 0;
}

async function run() {
  console.log(`\nMagnoolia Phase 29 — Visual QA\nBase: ${BASE}\nOut:  ${OUT_DIR}\n`);
  const browser = await chromium.launch({ headless: true });
  const ctx = await browser.newContext();

  for (const w of WIDTHS) {
    console.log(`\n── ${w}px ──`);
    const page = await ctx.newPage();
    await page.setViewportSize({ width: w, height: 900 });
    const tag = w === 1440 ? 'desktop' : `m${w}`;

    // Homepage availability board
    if (await go(page, '/') === 200) await shot(page, `${tag}-home`);

    // /asendiplaan — default · selected row · home-detail modal
    if (await go(page, '/asendiplaan') === 200) {
      await shot(page, `${tag}-asendiplaan-default`);
      await safe(async () => { await page.evaluate(() => { document.querySelectorAll('[data-mg-row]')[1]?.click(); }); await page.waitForTimeout(400); await shot(page, `${tag}-asendiplaan-row`); });
      await safe(async () => {
        await page.evaluate(() => { const t = document.querySelector('[data-mg-home-open]'); if (t && window.mgOpenHome) window.mgOpenHome(t.getAttribute('data-mg-home-open')); });
        await page.waitForTimeout(500); await shot(page, `${tag}-home-detail`);
        await page.evaluate(() => document.getElementById('mg-hd-close')?.click());
      });
    }

    // /kodud-ja-hinnad — full · filtered · modal
    if (await go(page, '/kodud-ja-hinnad') === 200) {
      await shot(page, `${tag}-kodud-full`);
      await safe(async () => { await page.evaluate(() => window.mgFilter && window.mgFilter('tee-3')); await page.waitForTimeout(300); await shot(page, `${tag}-kodud-filtered`); await page.evaluate(() => window.mgFilter && window.mgFilter('all')); });
      await safe(async () => {
        await page.evaluate(() => { const t = document.querySelector('[data-mg-home-open]'); if (t && window.mgOpenHome) window.mgOpenHome(t.getAttribute('data-mg-home-open')); });
        await page.waitForTimeout(500); await shot(page, `${tag}-kodud-modal`);
        await page.evaluate(() => document.getElementById('mg-hd-close')?.click());
      });
    }

    // /ehitusinfo material block
    if (await go(page, '/ehitusinfo') === 200) {
      const block = page.locator('#viimistluse-naited');
      if (await block.count()) await block.scrollIntoViewIfNeeded();
      await page.waitForTimeout(300);
      await shot(page, `${tag}-ehitusinfo-materials`);
    }

    await page.close();
  }

  // RU/EN smoke at desktop
  console.log(`\n── RU/EN smoke (1440px) ──`);
  const page = await ctx.newPage();
  await page.setViewportSize({ width: 1440, height: 900 });
  for (const [loc, path] of [['ru', '/ru/asendiplaan'], ['en', '/en/asendiplaan']]) {
    if (await go(page, path) === 200) await shot(page, `desktop-asendiplaan-${loc}`);
  }
  await page.close();
  await browser.close();

  const overflow = RESULTS.filter(r => r.overflow);
  const lines = [
    '# Phase 29 Visual QA Index', '', `Generated: ${new Date().toISOString()}`, `Base: ${BASE}`, '',
    `Total screenshots: ${RESULTS.length} · Horizontal overflow: ${overflow.length}`, '',
    '| File | Overflow |', '|------|----------|',
    ...RESULTS.map(r => `| ${r.file} | ${r.overflow ? '⚠ yes' : 'no'} |`),
  ];
  writeFileSync(join(OUT_DIR, 'index.md'), lines.join('\n'));
  console.log(`\n✓ ${RESULTS.length} screenshots, ${overflow.length} overflow. Index: docs/phase29-screenshots/index.md\n`);
}

run().catch(e => { console.error('Visual QA failed:', e.message); process.exit(1); });
