import { chromium } from '@playwright/test';

const BASE = process.env.MG_BASE || 'http://127.0.0.1:8799';
const OUT = 'docs/phase33-screenshots';
const EMAIL = process.env.MG_ADMIN_EMAIL || 'admin@magnoolia.ee';
const PASS = process.env.MG_ADMIN_PASS || 'Magnoolia2027!';

const adminPages = [
  ['dashboard', '/admin/magnoolia'],
  ['units', '/admin/magnoolia/units'],
  ['unit-edit', '/admin/magnoolia/units/tee-3-2/edit'],
  ['validate', '/admin/magnoolia/validate'],
  ['preview', '/admin/magnoolia/preview'],
  ['publish', '/admin/magnoolia/publish'],
  ['publications', '/admin/magnoolia/publications'],
  ['audit', '/admin/magnoolia/audit'],
  ['campaign', '/admin/magnoolia/campaign'],
];

const b = await chromium.launch({ headless: true });
const ctx = await b.newContext({ viewport: { width: 1440, height: 900 } });
const p = await ctx.newPage();

// ---- Filament login (authenticates the web guard used by /admin/magnoolia) ----
await p.goto(BASE + '/admin/login', { waitUntil: 'networkidle' });
await p.waitForTimeout(600);
// Filament login fields
await p.fill('input[type="email"], #data\\.email', EMAIL).catch(() => {});
await p.fill('input[type="password"], #data\\.password', PASS).catch(() => {});
await p.click('button[type="submit"]').catch(() => {});
await p.waitForTimeout(2500);
console.log('after login url:', p.url());

for (const [name, path] of adminPages) {
  const errs = [];
  p.on('pageerror', e => errs.push(String(e)));
  const resp = await p.goto(BASE + path, { waitUntil: 'networkidle' }).catch(() => null);
  await p.waitForTimeout(700);
  await p.screenshot({ path: `${OUT}/admin-${name}.png`, fullPage: true });
  console.log(`admin-${name}: http=${resp ? resp.status() : '?'} errs=${errs.length}`);
  p.removeAllListeners('pageerror');
}

// ---- public after publish ----
const pubCtx = await b.newContext({ viewport: { width: 1440, height: 900 } });
const pp = await pubCtx.newPage();
for (const [name, path] of [['public-kodud', '/kodud-ja-hinnad'], ['public-asendiplaan', '/asendiplaan?row=tee-3&home=tee-3-2#mg-masterplan'], ['public-en-kodud', '/en/kodud-ja-hinnad']]) {
  await pp.goto(BASE + path, { waitUntil: 'networkidle' }).catch(() => {});
  await pp.waitForTimeout(800);
  await pp.screenshot({ path: `${OUT}/${name}.png`, fullPage: true });
  console.log(`${name}: saved`);
}

// mobile public
const mctx = await b.newContext({ viewport: { width: 390, height: 844 }, isMobile: true });
const mp = await mctx.newPage();
for (const [name, path] of [['m-public-kodud', '/kodud-ja-hinnad'], ['m-public-asendiplaan', '/asendiplaan']]) {
  await mp.goto(BASE + path, { waitUntil: 'domcontentloaded' }).catch(() => {});
  await mp.waitForTimeout(800);
  await mp.screenshot({ path: `${OUT}/${name}.png` });
  console.log(`${name}: saved`);
}

await b.close();
console.log('DONE');
