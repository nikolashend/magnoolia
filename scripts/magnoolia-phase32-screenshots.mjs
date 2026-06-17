import { chromium } from '@playwright/test';

const BASE = process.env.MG_BASE || 'http://127.0.0.1:8799';
const OUT = 'docs/phase32-screenshots';

const desktop = [
  ['home', '/'],
  ['kodud-ja-hinnad', '/kodud-ja-hinnad'],
  ['asendiplaan', '/asendiplaan'],
  ['asendiplaan-home-tee-3-2', '/asendiplaan?row=tee-3&home=tee-3-2#mg-masterplan'],
  ['ehitusinfo', '/ehitusinfo'],
  ['sisedisain', '/sisedisain'],
  ['asukoht', '/asukoht'],
  ['kontakt', '/kontakt'],
  ['kkk', '/kkk'],
  ['en-asendiplaan', '/en/asendiplaan'],
  ['ru-kodud-ja-hinnad', '/ru/kodud-ja-hinnad'],
];

const mobile = [
  ['m-home', '/'],
  ['m-kodud-ja-hinnad', '/kodud-ja-hinnad'],
  ['m-asendiplaan', '/asendiplaan'],
  ['m-asendiplaan-detail', '/asendiplaan?row=tee-3&home=tee-3-2#mg-masterplan'],
  ['m-ehitusinfo', '/ehitusinfo'],
  ['m-kontakt', '/kontakt'],
];

async function shoot(page, name, full = true) {
  await page.screenshot({ path: `${OUT}/${name}.png`, fullPage: full });
  console.log('  saved', name);
}

const b = await chromium.launch({ headless: true });

// ---- desktop 1440 ----
const dctx = await b.newContext({ viewport: { width: 1440, height: 900 } });
const dp = await dctx.newPage();
const errors = {};
for (const [name, path] of desktop) {
  const errs = [];
  dp.on('pageerror', e => errs.push(String(e)));
  await dp.goto(BASE + path, { waitUntil: 'networkidle' }).catch(() => {});
  await dp.waitForTimeout(900);
  await shoot(dp, name);
  errors[path] = errs.slice(0, 3);
  dp.removeAllListeners('pageerror');
}

// special: asendiplaan alternate view (switch to 2nd view pill) + floor lightbox
await dp.goto(BASE + '/asendiplaan?row=tee-3&home=tee-3-2#mg-masterplan', { waitUntil: 'networkidle' }).catch(() => {});
await dp.waitForTimeout(900);
await dp.evaluate(() => { const p = document.querySelector('[data-mp-view="1"]'); if (p) p.click(); });
await dp.waitForTimeout(700);
await shoot(dp, 'special-asendiplaan-view2', false);

// floor plan lightbox open
await dp.evaluate(() => { const z = document.getElementById('mg-d-floor-zoom'); if (z) z.click(); });
await dp.waitForTimeout(600);
await shoot(dp, 'special-floor-lightbox', false);

// inquiry drawer with selected home context (click "Küsi pakkumist" on a home detail)
await dp.goto(BASE + '/asendiplaan?row=tee-1&home=tee-1-1#mg-masterplan', { waitUntil: 'networkidle' }).catch(() => {});
await dp.waitForTimeout(900);
await dp.evaluate(() => { const c = document.getElementById('mg-d-cta'); if (c) c.click(); });
await dp.waitForTimeout(700);
await shoot(dp, 'special-inquiry-drawer', false);

await dctx.close();

// ---- mobile 390 ----
const mctx = await b.newContext({ viewport: { width: 390, height: 844 }, isMobile: true });
const mp = await mctx.newPage();
for (const [name, path] of mobile) {
  await mp.goto(BASE + path, { waitUntil: 'networkidle' }).catch(() => {});
  await mp.waitForTimeout(900);
  await shoot(mp, name);
}
// mobile ehitusinfo with a proof accordion open
await mp.goto(BASE + '/ehitusinfo', { waitUntil: 'networkidle' }).catch(() => {});
await mp.waitForTimeout(800);
await mp.evaluate(() => {
  const acc = document.querySelector('[data-mg-finish-toggle],.mg-finish__toggle,details summary, .mg-acc__head, button[aria-expanded]');
  if (acc) acc.click();
});
await mp.waitForTimeout(500);
await shoot(mp, 'm-ehitusinfo-accordion-open');
await mctx.close();

await b.close();
console.log('PAGE ERRORS:', JSON.stringify(errors, null, 1));
console.log('DONE');
