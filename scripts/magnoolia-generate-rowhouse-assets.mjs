/**
 * Magnoolia Phase 29 — Rowhouse selection asset generator.
 *
 * Reproducible. Reads the Phase-29 source renders + clean asendiplaan from
 *   materials/phase29/   (repo root, NOT public)
 * and produces public, buyer-safe WebP assets + a manifest into
 *   public/assets/magnoolia/rowhouse-selection/
 *
 * What it does:
 *  1. Clean asendiplaan (5.jpg)        -> asendiplaan/magnoolia-asendiplaan-clean*.webp
 *  2. Primary aerial render (1.jpg)    -> overview/development-primary*.webp
 *  3. Secondary dusk render (3.jpg)    -> overview/development-secondary*.webp
 *  4. Detects orange building footprints on the asendiplaan, clusters them into
 *     the 6 address groups (tee 1/3/5/7/9/11) and subdivides each into homes:
 *       - 6 row crops   -> rows/magnoolia-tee-N-row*.webp
 *       - 19 home crops -> homes/magnoolia-tee-N-n*.webp
 *     and records normalised highlight coordinates (row + home) in the manifest
 *     so the UI can draw a muted-gold marker on the clean map. Coordinates are
 *     reproducible map geometry, NOT the colour mask (masks are never shipped).
 *
 * Notes / honesty:
 *  - Row boxes/markers are derived directly from detected footprints (accurate).
 *  - Per-home markers come from equal subdivision of each row box along its long
 *    axis; they are APPROXIMATE and flagged 'approximate:true' in the manifest.
 *  - Crops are top-down asendiplaan snippets (premium, orientation-friendly).
 *    The perspective renders are used as the overall development overview.
 *
 * Run: npm run magnoolia:generate:rowhouse   (or: node scripts/magnoolia-generate-rowhouse-assets.mjs)
 */
import sharp from 'sharp';
import { mkdir, writeFile, stat } from 'fs/promises';
import { join, dirname } from 'path';
import { fileURLToPath } from 'url';

const __dirname = dirname(fileURLToPath(import.meta.url));
const ROOT = join(__dirname, '..');                 // = app/
const SRC = join(ROOT, '..', 'materials', 'phase29'); // repo-root materials/phase29
const OUT = join(ROOT, 'public/assets/magnoolia/rowhouse-selection');
const PUBLIC_BASE = 'assets/magnoolia/rowhouse-selection';

const SOURCES = {
  primary:   join(SRC, '1.jpg'),
  secondary: join(SRC, '3.jpg'),
  asendiplaan: join(SRC, '5.jpg'),
};

const ORDER = [11, 9, 7, 5, 3, 1];      // diagonal top-left -> bottom-right
const COUNT = { 1: 3, 3: 4, 5: 3, 7: 3, 9: 3, 11: 3 };
const Q = 82;

let srcBytes = 0, outBytes = 0;
const trackOut = (info) => { outBytes += info.size; return info; };

async function emit(srcPath, relPath, widths, { crop = null } = {}) {
  const abs = join(OUT, relPath);
  await mkdir(dirname(abs), { recursive: true });
  const variants = {};

  // full-size base
  trackOut(await (crop ? sharp(srcPath).extract(crop) : sharp(srcPath)).webp({ quality: Q }).toFile(abs));
  variants.base = `${PUBLIC_BASE}/${relPath}`;

  for (const w of widths) {
    const wRel = relPath.replace(/\.webp$/, `-${w}.webp`);
    const wAbs = join(OUT, wRel);
    trackOut(await (crop ? sharp(srcPath).extract(crop) : sharp(srcPath))
      .resize(w, null, { withoutEnlargement: true, fit: 'inside' })
      .webp({ quality: Q }).toFile(wAbs));
    variants[String(w)] = `${PUBLIC_BASE}/${wRel}`;
  }
  return variants;
}

// ----- detect + cluster footprints on the asendiplaan -----
async function detect() {
  const PROC_W = 1000;
  const { data, info } = await sharp(SOURCES.asendiplaan).resize(PROC_W, null).raw().toBuffer({ resolveWithObject: true });
  const W = info.width, H = info.height, C = info.channels;
  const iso = (r, g, b) => r >= 205 && g >= 180 && g <= 228 && b >= 125 && b <= 198 && (r - b) >= 45 && (r - g) >= 12 && (g - b) >= 18;
  const pts = [];
  for (let y = 0; y < H; y++) for (let x = 0; x < W; x++) {
    const i = (y * W + x) * C;
    if (iso(data[i], data[i + 1], data[i + 2])) pts.push([x / W, y / H]);
  }
  let cent = ORDER.map((_, k) => [0.15 + 0.65 * k / 5, 0.10 + 0.78 * k / 5]);
  const nearest = (x, y) => { let bi = 0, bd = 1e9; for (let k = 0; k < 6; k++) { const dx = x - cent[k][0], dy = y - cent[k][1], d = dx * dx + dy * dy; if (d < bd) { bd = d; bi = k; } } return bi; };
  for (let it = 0; it < 50; it++) {
    const s = cent.map(() => [0, 0, 0]);
    for (const [x, y] of pts) { const k = nearest(x, y); s[k][0] += x; s[k][1] += y; s[k][2]++; }
    cent = s.map((v, k) => v[2] ? [v[0] / v[2], v[1] / v[2]] : cent[k]);
  }
  const order = cent.map((_, k) => k).sort((a, b) => (cent[a][0] + cent[a][1]) - (cent[b][0] + cent[b][1]));
  const groups = order.map((ci, gi) => {
    const tee = ORDER[gi];
    const gp = pts.filter(([x, y]) => nearest(x, y) === ci);
    const xs = gp.map(p => p[0]), ys = gp.map(p => p[1]);
    const box = [Math.min(...xs), Math.min(...ys), Math.max(...xs), Math.max(...ys)];
    const horiz = (box[2] - box[0]) >= (box[3] - box[1]);
    const n = COUNT[tee];

    // Sub-cluster the row's footprint pixels into N individual homes so each
    // crop/marker lands on a REAL house centroid (the rows run diagonally and
    // houses are unevenly spaced, so equal slicing of the bbox is wrong).
    let sc = Array.from({ length: n }, (_, u) => {
      const t = n === 1 ? 0.5 : u / (n - 1);
      return horiz ? [box[0] + (box[2] - box[0]) * t, (box[1] + box[3]) / 2]
                   : [(box[0] + box[2]) / 2, box[1] + (box[3] - box[1]) * t];
    });
    const sNearest = (x, y) => { let bi = 0, bd = 1e9; for (let k = 0; k < n; k++) { const dx = x - sc[k][0], dy = y - sc[k][1], d = dx * dx + dy * dy; if (d < bd) { bd = d; bi = k; } } return bi; };
    for (let it = 0; it < 40; it++) {
      const s = sc.map(() => [0, 0, 0]);
      for (const [x, y] of gp) { const k = sNearest(x, y); s[k][0] += x; s[k][1] += y; s[k][2]++; }
      sc = s.map((v, k) => v[2] ? [v[0] / v[2], v[1] / v[2]] : sc[k]);
    }
    // bbox per sub-cluster
    const sub = sc.map(() => ({ x0: 1, y0: 1, x1: 0, y1: 0, cx: 0, cy: 0, area: 0 }));
    for (const [x, y] of gp) {
      const k = sNearest(x, y); const b = sub[k];
      if (x < b.x0) b.x0 = x; if (x > b.x1) b.x1 = x; if (y < b.y0) b.y0 = y; if (y > b.y1) b.y1 = y;
      b.cx += x; b.cy += y; b.area++;
    }
    sub.forEach(b => { if (b.area) { b.cx /= b.area; b.cy /= b.area; } });
    // order along principal axis to match plan numbering (1..n)
    sub.sort((a, b) => horiz ? (a.cx - b.cx) : (a.cy - b.cy));
    const homes = sub.map((b, u) => ({
      unit: u + 1, cx: b.cx, cy: b.cy, box: [b.x0, b.y0, b.x1, b.y1],
    }));

    return { tee, cx: cent[ci][0], cy: cent[ci][1], box, horiz, homes };
  });
  return groups;
}

// normalized box -> padded pixel crop on the asendiplaan
async function cropFor(box, padX, padY, asendiMeta) {
  const W = asendiMeta.width, H = asendiMeta.height;
  let [x0, y0, x1, y1] = box;
  const w = x1 - x0, h = y1 - y0;
  x0 -= w * padX; x1 += w * padX; y0 -= h * padY; y1 += h * padY;
  x0 = Math.max(0, x0); y0 = Math.max(0, y0); x1 = Math.min(1, x1); y1 = Math.min(1, y1);
  return {
    left: Math.round(x0 * W), top: Math.round(y0 * H),
    width: Math.max(8, Math.round((x1 - x0) * W)), height: Math.max(8, Math.round((y1 - y0) * H)),
  };
}

// Centre a crop on (cx,cy) covering the house box plus context, clamped to image.
async function cropCentered(cx, cy, box, meta, { scale = 1.7, minHalf = 0.06 } = {}) {
  const W = meta.width, H = meta.height;
  const halfW = Math.max((box[2] - box[0]) / 2 * scale, minHalf);
  const halfH = Math.max((box[3] - box[1]) / 2 * scale, minHalf);
  let x0 = cx - halfW, x1 = cx + halfW, y0 = cy - halfH, y1 = cy + halfH;
  x0 = Math.max(0, x0); y0 = Math.max(0, y0); x1 = Math.min(1, x1); y1 = Math.min(1, y1);
  return {
    left: Math.round(x0 * W), top: Math.round(y0 * H),
    width: Math.max(8, Math.round((x1 - x0) * W)), height: Math.max(8, Math.round((y1 - y0) * H)),
  };
}

async function main() {
  await mkdir(OUT, { recursive: true });
  for (const p of Object.values(SOURCES)) srcBytes += (await stat(p)).size;

  const asendiMeta = await sharp(SOURCES.asendiplaan).metadata();

  console.log('• clean asendiplaan');
  const asendiplaan = await emit(SOURCES.asendiplaan, 'asendiplaan/magnoolia-asendiplaan-clean.webp', [1600, 1024, 768]);

  console.log('• overview renders');
  const overviewPrimary = await emit(SOURCES.primary, 'overview/development-primary.webp', [2048, 1280, 768]);
  let overviewSecondary = null, hasSecondary = false;
  try {
    overviewSecondary = await emit(SOURCES.secondary, 'overview/development-secondary.webp', [2048, 1280, 768]);
    hasSecondary = true;
  } catch (e) { console.warn('  secondary render skipped:', e.message); }

  console.log('• detecting footprints + crops');
  const groups = await detect();
  const rows = [];
  for (const g of groups) {
    const rowCrop = await cropFor(g.box, 0.55, 0.85, asendiMeta);
    const rowRel = `rows/magnoolia-tee-${g.tee}-row.webp`;
    const rowImg = await emit(SOURCES.asendiplaan, rowRel, [1280, 768, 480], { crop: rowCrop });

    const homes = [];
    for (const h of g.homes) {
      const unitKey = `tee-${g.tee}-${h.unit}`;
      // Centre the crop on the detected house centroid with a consistent context
      // window (premium, focused but not too tight, regardless of house size).
      const homeCrop = await cropCentered(h.cx, h.cy, h.box, asendiMeta);
      const homeRel = `homes/magnoolia-tee-${g.tee}-${h.unit}.webp`;
      const homeImg = await emit(SOURCES.asendiplaan, homeRel, [768, 480], { crop: homeCrop });
      homes.push({
        unit_key: unitKey,
        image: homeImg,
        map_highlight: { x: +h.cx.toFixed(4), y: +h.cy.toFixed(4), box: h.box.map(v => +v.toFixed(4)), approximate: true },
      });
    }

    rows.push({
      building: g.tee,
      pos: `tee-${g.tee}`,
      image: rowImg,
      map_highlight: { x: +g.cx.toFixed(4), y: +g.cy.toFixed(4), box: g.box.map(v => +v.toFixed(4)), approximate: false },
      homes,
    });
  }
  // canonical display order 1,3,5,7,9,11
  rows.sort((a, b) => a.building - b.building);

  const manifest = {
    meta: {
      phase: 29,
      generated_by: 'scripts/magnoolia-generate-rowhouse-assets.mjs',
      note: 'Row markers derived from detected footprints; per-home markers are approximate (equal subdivision). Colour masks and the technical plan are internal-only and never published.',
    },
    overview: { primary: overviewPrimary, secondary: overviewSecondary, has_secondary_view: hasSecondary },
    asendiplaan: { clean: asendiplaan, enlarge_pdf: 'assets/magnoolia/asendiplaan/VEEBI _ASENDIPLAAN.pdf' },
    rows,
    counts: { rows: rows.length, homes: rows.reduce((n, r) => n + r.homes.length, 0) },
    optimization: {
      source_bytes: srcBytes,
      output_bytes: outBytes,
      source_mb: +(srcBytes / 1048576).toFixed(1),
      output_mb: +(outBytes / 1048576).toFixed(1),
    },
  };

  await writeFile(join(OUT, 'manifest.json'), JSON.stringify(manifest, null, 2));
  console.log(`\n✓ rows=${manifest.counts.rows} homes=${manifest.counts.homes}`);
  console.log(`✓ source ${manifest.optimization.source_mb} MB -> output ${manifest.optimization.output_mb} MB`);
  console.log(`✓ manifest: ${join(OUT, 'manifest.json')}`);
}

main().catch(e => { console.error('FATAL', e); process.exit(1); });
