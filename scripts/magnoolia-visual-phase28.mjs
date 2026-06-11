/**
 * Magnoolia Phase 28 — Visual QA Screenshots
 * Usage: node scripts/magnoolia-visual-phase28.mjs
 * Requires: @playwright/test installed + dev server running at APP_URL
 */
import { chromium } from '@playwright/test';
import { mkdirSync, writeFileSync } from 'fs';
import { join, dirname } from 'path';
import { fileURLToPath } from 'url';

const __dirname = dirname(fileURLToPath(import.meta.url));
const ROOT = join(__dirname, '..');
const OUT_DIR = join(ROOT, 'docs', 'phase28-screenshots');

mkdirSync(OUT_DIR, { recursive: true });

const BASE_URL = process.env.APP_URL || 'http://localhost:8080';

// Phase 28 pages — ET locale uses no prefix (default), RU/EN use /ru/ and /en/
const PAGES = [
  { slug: 'home',               path: '/' },
  { slug: 'kodud-ja-hinnad',    path: '/kodud-ja-hinnad' },
  { slug: 'asendiplaan',        path: '/asendiplaan' },
  { slug: 'asukoht',            path: '/asukoht' },
  { slug: 'ehitusinfo',         path: '/ehitusinfo' },
  { slug: 'sisedisain',         path: '/sisedisain' },
  { slug: 'arhitektuur',        path: '/arhitektuur-ja-valisdisain' },
  { slug: 'galerii',            path: '/galerii' },
  { slug: 'finantseerimine',    path: '/finantseerimine' },
  { slug: 'ostuprotsess',       path: '/ostuprotsess' },
  { slug: 'kkk',                path: '/kkk' },
  { slug: 'kontakt',            path: '/kontakt' },
];

// Smoke locales — just key pages in RU and EN
const SMOKE_PAGES = [
  { slug: 'home-ru',            path: '/ru',                 locale: 'ru' },
  { slug: 'home-en',            path: '/en',                 locale: 'en' },
  { slug: 'kodud-ja-hinnad-ru', path: '/ru/kodud-ja-hinnad', locale: 'ru' },
  { slug: 'kodud-ja-hinnad-en', path: '/en/kodud-ja-hinnad', locale: 'en' },
  { slug: 'kontakt-ru',         path: '/ru/kontakt',         locale: 'ru' },
  { slug: 'kontakt-en',         path: '/en/kontakt',         locale: 'en' },
  { slug: 'asendiplaan-ru',     path: '/ru/asendiplaan',     locale: 'ru' },
  { slug: 'asendiplaan-en',     path: '/en/asendiplaan',     locale: 'en' },
];

const WIDTHS = [320, 375, 390, 430, 768, 1440];
const ASSERTIONS = [];

// Phase 28 specific quality checks
const FORBIDDEN_STRINGS = [
  '0 kodu',
  '0 homes',
  'price_cents',
  'OneDrive',
  'C:\\Users',
  'lorem',
  '[object Object]',
  'NaN',
  '+372 000',
  'info@magnoolia.ee',
];

// ET-page forbidden English strings
const ET_FORBIDDEN = [
  'Price to be confirmed',
  'Prices and availability',
  'Showing all',
  'Tip:',
  'Choose a home',
];

// RU-page forbidden ET strings
const RU_FORBIDDEN = [
  'Küsi pakkumist',
  'Näitan',
  'Valmib',
  'Broneeritud',
  'Müüdud',
  'Hind täpsustamisel',
];

async function screenshotPage(page, url, slug, locale, width) {
  const fullUrl = `${BASE_URL}${url}`;
  try {
    const response = await page.goto(fullUrl, { waitUntil: 'networkidle', timeout: 30000 });
    const status = response?.status() ?? 0;

    if (status !== 200) {
      ASSERTIONS.push({ url, width, locale, status: 'FAIL', reason: `HTTP ${status}` });
      return;
    }

    await page.waitForTimeout(800);

    const filename = `phase28-${slug}-${width}.png`;
    const outPath = join(OUT_DIR, filename);
    await page.screenshot({ path: outPath, fullPage: true });

    const issues = [];

    // Check for horizontal overflow
    const hasOverflow = await page.evaluate(() => {
      return document.documentElement.scrollWidth > document.documentElement.clientWidth + 5;
    });
    if (hasOverflow) issues.push('horizontal-overflow');

    // Check for H1
    const hasH1 = await page.$('h1');
    if (!hasH1) issues.push('no-h1');

    // Check for footer
    const hasFooter = await page.$('.mg-footer, .site-footer, footer');
    if (!hasFooter) issues.push('no-footer');

    // Check for broken images
    const brokenImages = await page.evaluate(() => {
      const imgs = Array.from(document.querySelectorAll('img'));
      return imgs.filter(img => img.complete && img.naturalWidth === 0 && img.src && !img.src.startsWith('data:')).map(img => img.src);
    });
    if (brokenImages.length > 0) issues.push(`broken-images:${brokenImages.length}`);

    // Phase 28 specific checks: visibility board on homepage
    if (slug === 'home' || slug === 'home-ru' || slug === 'home-en') {
      const hasAvailBoard = await page.$('#saadavus, [data-mg-availability-board], #mg-availability-board, .mg-availability-board');
      if (!hasAvailBoard) issues.push('no-availability-board');

      const has0Kodu = await page.evaluate(() => {
        const text = document.body.innerText;
        return /\b0 kodu\b|\b0 homes\b|\b0 домов\b/i.test(text);
      });
      if (has0Kodu) issues.push('0-kodu-bug');
    }

    // Check for forbidden content strings
    const pageText = await page.evaluate(() => document.body.innerText);
    for (const forbidden of FORBIDDEN_STRINGS) {
      if (pageText.includes(forbidden)) {
        issues.push(`forbidden:${forbidden.substring(0,20)}`);
      }
    }

    // ET locale-specific forbidden English strings
    if (locale === 'et') {
      for (const forbidden of ET_FORBIDDEN) {
        if (pageText.includes(forbidden)) {
          issues.push(`et-english:${forbidden.substring(0,15)}`);
        }
      }
    }

    // RU locale-specific forbidden ET strings
    if (locale === 'ru') {
      for (const forbidden of RU_FORBIDDEN) {
        if (pageText.includes(forbidden)) {
          issues.push(`ru-et:${forbidden.substring(0,15)}`);
        }
      }
    }

    const statusStr = issues.length === 0 ? 'PASS' : 'WARN';
    ASSERTIONS.push({ url, width, locale, status: statusStr, issues, file: filename });
    console.log(`  ${statusStr === 'PASS' ? '✓' : '⚠'} ${filename}${issues.length ? ' — ' + issues.join(', ') : ''}`);
  } catch (err) {
    ASSERTIONS.push({ url, width, locale, status: 'FAIL', reason: err.message.substring(0, 80) });
    console.error(`  ✗ FAIL ${slug}-${width}: ${err.message.substring(0, 80)}`);
  }
}

async function run() {
  console.log(`\nMagnoolia Phase 28 — Visual QA Screenshots\n`);
  console.log(`Base URL: ${BASE_URL}`);
  console.log(`Output:   ${OUT_DIR}\n`);

  const browser = await chromium.launch({ headless: true });
  const context = await browser.newContext();

  // ET pages at all widths
  for (const width of WIDTHS) {
    console.log(`\n── ET Width: ${width}px ──────────────────────`);
    const page = await context.newPage();
    await page.setViewportSize({ width, height: 812 });

    for (const pg of PAGES) {
      await screenshotPage(page, pg.path, pg.slug, 'et', width);
    }

    await page.close();
  }

  // Smoke screenshots for RU and EN (homepage + 2 key pages)
  console.log(`\n── Smoke: RU/EN at 375px and 1440px ──────────────────────`);
  for (const width of [375, 1440]) {
    const page = await context.newPage();
    await page.setViewportSize({ width, height: 812 });
    for (const pg of SMOKE_PAGES) {
      await screenshotPage(page, pg.path, pg.slug, pg.locale, width);
    }
    await page.close();
  }

  await browser.close();

  const passed = ASSERTIONS.filter(a => a.status === 'PASS').length;
  const warned = ASSERTIONS.filter(a => a.status === 'WARN').length;
  const failed = ASSERTIONS.filter(a => a.status === 'FAIL').length;
  const total = ASSERTIONS.length;

  console.log(`\n╔═══════════════════════════════════════════════════════════════════╗`);
  console.log(`║  PHASE 28 VISUAL QA SUMMARY                                       ║`);
  console.log(`║  Total:  ${total.toString().padEnd(4)} | PASS: ${passed.toString().padEnd(4)} | WARN: ${warned.toString().padEnd(4)} | FAIL: ${failed.toString().padEnd(4)}               ║`);
  console.log(`╚═══════════════════════════════════════════════════════════════════╝\n`);

  // Write Phase 28 screenshots index
  const lines = [
    '# Phase 28 Visual Screenshot Index',
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
  lines.push('');
  lines.push('## Phase 28 Visual QA Checklist');
  lines.push('');
  lines.push('| Check | Status |');
  lines.push('|-------|--------|');
  const homeAssertions = ASSERTIONS.filter(a => a.file?.includes('-home-') || a.file?.includes('-home-ru-') || a.file?.includes('-home-en-'));
  const availBoardMissing = homeAssertions.some(a => a.issues?.includes('no-availability-board'));
  const zeroKoduBug = ASSERTIONS.some(a => a.issues?.includes('0-kodu-bug'));
  const hasBrokenImages = ASSERTIONS.some(a => a.issues?.some(i => i.startsWith('broken-images')));
  const hasHorizOverflow = ASSERTIONS.some(a => a.issues?.includes('horizontal-overflow'));
  const hasEtEnglish = ASSERTIONS.some(a => a.issues?.some(i => i.startsWith('et-english')));
  const hasRuEt = ASSERTIONS.some(a => a.issues?.some(i => i.startsWith('ru-et')));
  lines.push(`| Homepage availability board visible | ${availBoardMissing ? '⚠ WARN' : '✓ PASS'} |`);
  lines.push(`| No "0 kodu" counter | ${zeroKoduBug ? '✗ FAIL' : '✓ PASS'} |`);
  lines.push(`| No broken images | ${hasBrokenImages ? '⚠ WARN' : '✓ PASS'} |`);
  lines.push(`| No horizontal overflow (mobile) | ${hasHorizOverflow ? '⚠ WARN' : '✓ PASS'} |`);
  lines.push(`| ET pages no English text | ${hasEtEnglish ? '✗ FAIL' : '✓ PASS'} |`);
  lines.push(`| RU pages no ET text | ${hasRuEt ? '✗ FAIL' : '✓ PASS'} |`);

  const reportPath = join(OUT_DIR, 'index.md');
  writeFileSync(reportPath, lines.join('\n'));
  console.log(`Report written to: ${reportPath}\n`);
}

run().catch(err => {
  console.error('Visual QA failed:', err.message);
  process.exit(1);
});
