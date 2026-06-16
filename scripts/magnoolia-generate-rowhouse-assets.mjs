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
  primary:   join(SRC, '1.jpg'),  // perspective render (Phase 30 primary selector, calibrated hotspots)
  secondary: join(SRC, '3.jpg'),  // alternate-angle daylight view
  dusk:      join(SRC, '0.png'),  // dusk/evening view (Phase 30.1)
  asendiplaan: join(SRC, '5.jpg'),
  perspectiveMask: join(SRC, '2a.png'), // internal-only, for hotspot calibration
};

// Floor plans by plan type (fallback). type-a = 4-room Plan A, type-b = 5-room Plan B.
const FLOORPLANS = {
  'type-a': { floor1: join(SRC, 'plan a_1korrus.png'), floor2: join(SRC, 'plan a_2korrus.png') },
  'type-b': { floor1: join(SRC, 'plan b_1korrus.png'), floor2: join(SRC, 'plan b_2korrus.png') },
};

// Per-building floor plan sheets (Phase 30.1) — authoritative, complete for all 6
// buildings. materials/plans/m{N}/M{N}_{floor}korrus_page-0001.jpg
const PLANS_SRC = join(ROOT, '..', 'materials', 'plans');
const BUILDING_PLANS = {};
for (const n of [1, 3, 5, 7, 9, 11]) {
  BUILDING_PLANS[n] = {
    floor1: join(PLANS_SRC, `M${n}_1korrus_page-0001.jpg`),
    floor2: join(PLANS_SRC, `M${n}_2korrus_page-0001.jpg`),
  };
}

// Per-UNIT floor plan source for (building, section, floor). Naming (Phase 30.1):
//  - tee 3 (4 units):  M3_{floor}korrus_{section}.png
//  - 3-unit, 1st floor: section 1 -> _1, 2 -> _1_2, 3 -> _1_3
//  - 3-unit, 2nd floor: M{n}_2korrus_{section}.png
function unitPlanFile(building, section, floor) {
  let name;
  if (building === 3) {
    name = `M3_${floor}korrus_${section}.png`;
  } else if (floor === 1) {
    const suffix = section === 1 ? '1' : `1_${section}`;
    name = `M${building}_1korrus_${suffix}.png`;
  } else {
    name = `M${building}_2korrus_${section}.png`;
  }
  return join(PLANS_SRC, name);
}

const ORDER = [11, 9, 7, 5, 3, 1];      // diagonal top-left -> bottom-right (asendiplaan)
const TEE_LR = [1, 3, 5, 7, 9, 11];     // perspective render screen order: foreground/entrance -> background
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

// Detect 6 row hotspots on the perspective render via its segmentation mask.
// Returns [{tee, marker:[x,y], hull:[[x,y]...]}] in normalised render coords.
async function detectPerspectiveRows() {
  const PROC_W = 1000;
  const { data, info } = await sharp(SOURCES.perspectiveMask).resize(PROC_W, null).raw().toBuffer({ resolveWithObject: true });
  const W = info.width, H = info.height, C = info.channels;
  const at = (x, y) => { const i = (y * W + x) * C; return [data[i], data[i + 1], data[i + 2]]; };
  const bg = at(2, 2);
  const isBuilding = ([r, g, b]) => {
    if (Math.abs(r - bg[0]) + Math.abs(g - bg[1]) + Math.abs(b - bg[2]) < 70) return false;
    const mx = Math.max(r, g, b), mn = Math.min(r, g, b);
    if (mx < 70 || mx - mn < 60) return false;
    if (g > 150 && r < 150 && b < 110) return false;          // field greens
    if (r > 180 && g < 110 && b > 120 && b < 200) return false; // road/tree pink
    return true;
  };
  const pts = [];
  for (let y = 0; y < H; y++) for (let x = 0; x < W; x++) if (isBuilding(at(x, y))) pts.push([x / W, y / H]);

  let cent = Array.from({ length: 6 }, (_, k) => [0.12 + 0.78 * k / 5, 0.55 - 0.30 * k / 5]);
  const nearest = (x, y) => { let bi = 0, bd = 1e9; for (let k = 0; k < 6; k++) { const dx = x - cent[k][0], dy = y - cent[k][1], d = dx * dx + dy * dy; if (d < bd) { bd = d; bi = k; } } return bi; };
  for (let it = 0; it < 60; it++) { const s = cent.map(() => [0, 0, 0]); for (const [x, y] of pts) { const k = nearest(x, y); s[k][0] += x; s[k][1] += y; s[k][2]++; } cent = s.map((v, k) => v[2] ? [v[0] / v[2], v[1] / v[2]] : cent[k]); }
  const order = cent.map((_, k) => k).sort((a, b) => cent[a][0] - cent[b][0]);

  const conv = (P) => {
    const p = P.slice().sort((a, b) => a[0] - b[0] || a[1] - b[1]); if (p.length < 3) return p;
    const cr = (o, a, b) => (a[0] - o[0]) * (b[1] - o[1]) - (a[1] - o[1]) * (b[0] - o[0]);
    const lo = [], up = [];
    for (const pt of p) { while (lo.length >= 2 && cr(lo[lo.length - 2], lo[lo.length - 1], pt) <= 0) lo.pop(); lo.push(pt); }
    for (let i = p.length - 1; i >= 0; i--) { const pt = p[i]; while (up.length >= 2 && cr(up[up.length - 2], up[up.length - 1], pt) <= 0) up.pop(); up.push(pt); }
    lo.pop(); up.pop(); return lo.concat(up);
  };
  return order.map((ci, gi) => {
    const cx = cent[ci][0], cy = cent[ci][1];
    const gp = pts.filter(([x, y]) => nearest(x, y) === ci).map(([x, y]) => [cx + (x - cx) * 1.08, cy + (y - cy) * 1.08]);
    return { tee: TEE_LR[gi], marker: [+cx.toFixed(4), +cy.toFixed(4)], hull: conv(gp).map(([x, y]) => [+x.toFixed(4), +y.toFixed(4)]) };
  });
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

  let overviewDusk = null;
  try {
    overviewDusk = await emit(SOURCES.dusk, 'overview/development-dusk.webp', [1280, 768]);
  } catch (e) { console.warn('  dusk render skipped:', e.message); }

  console.log('• floor plans (by plan type)');
  const floorplans = {};
  for (const [type, fp] of Object.entries(FLOORPLANS)) {
    const tag = type === 'type-a' ? 'a' : 'b';
    floorplans[type] = {
      floor_1: await emit(fp.floor1, `floorplans/plan-${tag}-1korrus.webp`, [1200, 768]),
      floor_2: await emit(fp.floor2, `floorplans/plan-${tag}-2korrus.webp`, [1200, 768]),
    };
  }

  console.log('• per-building floor plans');
  const floorplansByBuilding = {};
  for (const [n, fp] of Object.entries(BUILDING_PLANS)) {
    try {
      floorplansByBuilding[n] = {
        floor_1: await emit(fp.floor1, `floorplans/building-${n}-1korrus.webp`, [1600, 1024, 768]),
        floor_2: await emit(fp.floor2, `floorplans/building-${n}-2korrus.webp`, [1600, 1024, 768]),
      };
    } catch (e) { console.warn(`  building ${n} plans skipped:`, e.message); }
  }

  console.log('• perspective row hotspots');
  const persRows = await detectPerspectiveRows();
  const persByTee = {};
  for (const r of persRows) persByTee[r.tee] = { marker: r.marker, hull: r.hull };

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

      // Per-unit floor plans (authoritative; fall back to per-building in the app).
      const mkPlan = async (src, rel) => { try { return await emit(src, rel, [1024, 768]); } catch { return null; } };
      const uf1 = await mkPlan(unitPlanFile(g.tee, h.unit, 1), `floorplans/unit-tee-${g.tee}-${h.unit}-1korrus.webp`);
      const uf2 = await mkPlan(unitPlanFile(g.tee, h.unit, 2), `floorplans/unit-tee-${g.tee}-${h.unit}-2korrus.webp`);
      const unitFloorplans = (uf1 || uf2) ? { floor_1: uf1, floor_2: uf2 } : null;

      homes.push({
        unit_key: unitKey,
        image: homeImg,
        floorplans: unitFloorplans,
        map_highlight: { x: +h.cx.toFixed(4), y: +h.cy.toFixed(4), box: h.box.map(v => +v.toFixed(4)), approximate: true },
      });
    }

    rows.push({
      building: g.tee,
      pos: `tee-${g.tee}`,
      image: rowImg,
      map_highlight: { x: +g.cx.toFixed(4), y: +g.cy.toFixed(4), box: g.box.map(v => +v.toFixed(4)), approximate: false },
      perspective: persByTee[g.tee] || null, // {marker:[x,y], hull:[[x,y]...]} on the render
      homes,
    });
  }
  // canonical display order 1,3,5,7,9,11
  rows.sort((a, b) => a.building - b.building);

  const manifest = {
    meta: {
      phase: 30,
      generated_by: 'scripts/magnoolia-generate-rowhouse-assets.mjs',
      note: 'Perspective render (1.jpg) is the primary selector; row hotspot hulls are approximate (mask-derived). 2D asendiplaan markers confirm exact location. Per-home markers approximate. Colour masks, technical plan and source renders are internal-only, never published.',
    },
    // Phase 30 primary selector: the perspective render reuses the overview-primary
    // WebP variants; per-row hotspots live on each rows[] entry under `perspective`.
    // Phase 30.1: `views` is the ordered switcher set. Only the primary view is
    // hotspot-calibrated (matches the mask); alternate views are premium visuals
    // and rely on the row cards for selection (no faked polygons).
    perspective: {
      image: overviewPrimary,
      views: [
        { key: 'primary',   label: 'view_primary',   image: overviewPrimary,   hotspots: true },
        ...(hasSecondary ? [{ key: 'secondary', label: 'view_secondary', image: overviewSecondary, hotspots: false }] : []),
        ...(overviewDusk ? [{ key: 'dusk', label: 'view_dusk', image: overviewDusk, hotspots: false }] : []),
      ],
    },
    floorplans, // by plan type (fallback): { 'type-a': {floor_1,floor_2}, 'type-b': {...} }
    floorplans_by_building: floorplansByBuilding, // authoritative per-building sheets keyed by building number
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
