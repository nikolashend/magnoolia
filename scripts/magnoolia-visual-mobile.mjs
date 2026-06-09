/**
 * Magnoolia Phase 27 — Visual Mobile QA Screenshots
 * Usage: npm run magnoolia:visual:mobile
 * Requires: @playwright/test installed + dev server running at APP_URL
 */
import { chromium } from '@playwright/test';
import { mkdirSync, writeFileSync } from 'fs';
import { join, dirname } from 'path';
import { fileURLToPath } from 'url';

const __dirname = dirname(fileURLToPath(import.meta.url));
const ROOT = join(__dirname, '..');
const OUT_DIR = join(ROOT, 'docs', 'phase27-screenshots');

mkdirSync(OUT_DIR, { recursive: true });

const BASE_URL = process.env.APP_URL || 'http://localhost:8000';

const PAGES = [
  { slug: 'home',           path: '/' },
  { slug: 'kodud-ja-hinnad', path: '/kodud-ja-hinnad' },
  { slug: 'asendiplaan',    path: '/asendiplaan' },
  { slug: 'asukoht',        path: '/asukoht' },
  { slug: 'ehitusinfo',     path: '/ehitusinfo' },
  { slug: 'sisedisain',     path: '/sisedisain' },
  { slug: 'arhitektuur',    path: '/arhitektuur-ja-valisdisain' },
  { slug: 'galerii',        path: '/galerii' },
  { slug: 'finantseerimine', path: '/finantseerimine' },
  { slug: 'ostuprotsess',   path: '/ostuprotsess' },
  { slug: 'kkk',            path: '/kkk' },
  { slug: 'kontakt',        path: '/kontakt' },
];

const WIDTHS = [320, 375, 390, 430, 768, 1440];

const ASSERTIONS = [];

async function screenshotPage(page, url, slug, locale, width) {
  const fullUrl = `${BASE_URL}${url}`;
  try {
    const response = await page.goto(fullUrl, { waitUntil: 'networkidle', timeout: 30000 });
    const status = response?.status() ?? 0;

    if (status !== 200) {
      ASSERTIONS.push({ url, width, locale, status: 'FAIL', reason: `HTTP ${status}` });
      return;
    }

    await page.waitForTimeout(500);

    const filename = `phase27-${slug}-${locale}-${width}.png`;
    const outPath = join(OUT_DIR, filename);
    await page.screenshot({ path: outPath, fullPage: true });

    // Visual assertions
    const issues = [];

    // Check for horizontal overflow
    const hasOverflow = await page.evaluate(() => {
      return document.documentElement.scrollWidth > document.documentElement.clientWidth;
    });
    if (hasOverflow) issues.push('horizontal-overflow');

    // Check for hero / H1
    const hasH1 = await page.$('h1');
    if (!hasH1) issues.push('no-h1');

    // Check for footer
    const hasFooter = await page.$('.mg-footer, .site-footer, footer');
    if (!hasFooter) issues.push('no-footer');

    // Check for broken images (naturalWidth === 0)
    const brokenImages = await page.evaluate(() => {
      const imgs = Array.from(document.querySelectorAll('img'));
      return imgs.filter(img => img.complete && img.naturalWidth === 0 && img.src).map(img => img.src);
    });
    if (brokenImages.length > 0) issues.push(`broken-images:${brokenImages.length}`);

    const status_str = issues.length === 0 ? 'PASS' : 'WARN';
    ASSERTIONS.push({ url, width, locale, status: status_str, issues, file: filename });
    console.log(`  ${status_str === 'PASS' ? '✓' : '⚠'} ${filename}${issues.length ? ' — ' + issues.join(', ') : ''}`);
  } catch (err) {
    ASSERTIONS.push({ url, width, locale, status: 'FAIL', reason: err.message });
    console.error(`  ✗ FAIL ${slug}-${locale}-${width}: ${err.message}`);
  }
}

async function run() {
  console.log(`\nMagnoolia Phase 27 — Visual Mobile QA\n`);
  console.log(`Base URL: ${BASE_URL}`);
  console.log(`Output:   ${OUT_DIR}\n`);

  const browser = await chromium.launch({ headless: true });
  const context = await browser.newContext();

  for (const width of WIDTHS) {
    console.log(`\n── Width: ${width}px ──────────────────────`);
    const page = await context.newPage();
    await page.setViewportSize({ width, height: 812 });

    for (const pg of PAGES) {
      await screenshotPage(page, pg.path, pg.slug, 'et', width);
    }

    await page.close();
  }

  await browser.close();

  // Summary
  const passed = ASSERTIONS.filter(a => a.status === 'PASS').length;
  const warned = ASSERTIONS.filter(a => a.status === 'WARN').length;
  const failed = ASSERTIONS.filter(a => a.status === 'FAIL').length;
  const total = ASSERTIONS.length;

  console.log(`\n╔═══════════════════════════════════════════════════════════════╗`);
  console.log(`║  VISUAL QA SUMMARY                                            ║`);
  console.log(`║  Total:  ${total.toString().padEnd(4)} | PASS: ${passed.toString().padEnd(4)} | WARN: ${warned.toString().padEnd(4)} | FAIL: ${failed.toString().padEnd(4)}             ║`);
  console.log(`╚═══════════════════════════════════════════════════════════════╝\n`);

  // Write index report
  const lines = [
    '# Phase 27 Visual Screenshot Index',
    '',
    `Generated: ${new Date().toISOString()}`,
    `Base URL: ${BASE_URL}`,
    '',
    '## Results',
    '',
    `| File | Status | Issues |`,
    `|------|--------|--------|`,
  ];

  for (const a of ASSERTIONS) {
    const file = a.file || `${a.url} (failed)`;
    const issues = a.issues?.join(', ') || a.reason || '';
    lines.push(`| ${file} | ${a.status} | ${issues} |`);
  }

  lines.push('');
  lines.push(`## Summary`);
  lines.push(`- Total screenshots: ${total}`);
  lines.push(`- Passed: ${passed}`);
  lines.push(`- Warnings: ${warned}`);
  lines.push(`- Failed: ${failed}`);

  const reportPath = join(OUT_DIR, 'index.md');
  writeFileSync(reportPath, lines.join('\n'));
  console.log(`Report written to: ${reportPath}\n`);

  if (failed > 0) process.exit(1);
}

run().catch(err => {
  console.error('Visual QA failed:', err.message);
  process.exit(1);
});
