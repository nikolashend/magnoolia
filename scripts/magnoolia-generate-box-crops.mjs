// Phase 35 — generate per-HOME 3D box crops from the MAIN perspective render
// (materials/phase29/3.jpg) using the hand-traced polygons in
// config/magnoolia_hotspots.php → perspective_boxes.secondary (exported to
// /tmp/boxes.json by the caller). Each crop is framed around the box's bounding
// box (4:3, padded) with a soft gold outline of the box, and written to
// public/assets/magnoolia/rowhouse-selection/homes/magnoolia-<key>{,-480,-768}.webp
// (overwriting the old 2D plan snippets — same filenames, so wiring is unchanged).
import sharp from 'sharp';
import fs from 'fs';
import { execSync } from 'child_process';
import { fileURLToPath } from 'url';
import { dirname, join } from 'path';

const __dirname = dirname(fileURLToPath(import.meta.url));
const ROOT = join(__dirname, '..');
const SRC = join(ROOT, '..', 'materials', 'phase29', '3.jpg');
const OUT = join(ROOT, 'public/assets/magnoolia/rowhouse-selection/homes');
const MANIFEST = join(ROOT, 'public/assets/magnoolia/rowhouse-selection/manifest.json');

// Read the hand-traced boxes straight from config/magnoolia_hotspots.php (the main
// view = 'secondary'), so this is a one-command tool: edit the config, run this.
const VIEW = process.argv[2] || 'secondary';
// `php -n` skips php.ini (avoids a duplicate-extension startup warning polluting
// stdout); the config is a plain array return, so no extensions are needed.
const raw = execSync(
  `php -n -r "echo json_encode((include 'config/magnoolia_hotspots.php')['perspective_boxes']['${VIEW}'] ?? []);"`,
  { cwd: ROOT }
).toString().trim();
const BOXES = JSON.parse(raw.slice(raw.indexOf('{')) || '{}');
const Q = 82;
const PAD = 1.7;          // crop = bbox * PAD (context around the box)
const ASPECT = 4 / 3;     // card aspect

const meta = await sharp(SRC).metadata();
const W = meta.width, H = meta.height;

function frame(poly) {
  const xs = poly.map(p => p[0] * W), ys = poly.map(p => p[1] * H);
  let x0 = Math.min(...xs), x1 = Math.max(...xs), y0 = Math.min(...ys), y1 = Math.max(...ys);
  const cx = (x0 + x1) / 2, cy = (y0 + y1) / 2;
  let cw = (x1 - x0) * PAD, ch = (y1 - y0) * PAD;
  // enforce 4:3
  if (cw / ch < ASPECT) cw = ch * ASPECT; else ch = cw / ASPECT;
  // clamp to image
  cw = Math.min(cw, W); ch = Math.min(ch, H);
  let left = Math.round(Math.min(Math.max(cx - cw / 2, 0), W - cw));
  let top = Math.round(Math.min(Math.max(cy - ch / 2, 0), H - ch));
  return { left, top, width: Math.round(cw), height: Math.round(ch) };
}

function outlineSvg(poly, f) {
  const pts = poly.map(p => `${(p[0] * W - f.left).toFixed(1)},${(p[1] * H - f.top).toFixed(1)}`).join(' ');
  const sw = Math.max(2, Math.round(f.width / 220));
  return Buffer.from(
    `<svg width="${f.width}" height="${f.height}" xmlns="http://www.w3.org/2000/svg">` +
    `<polygon points="${pts}" fill="rgba(200,148,67,0.12)" stroke="#e0b052" stroke-width="${sw}" ` +
    `stroke-linejoin="round" stroke-linecap="round"/></svg>`
  );
}

let n = 0;
for (const [key, box] of Object.entries(BOXES)) {
  const poly = box.polygon;
  if (!poly || poly.length < 3) { console.warn('skip', key, '(no polygon)'); continue; }
  const f = frame(poly);
  const base = await sharp(SRC).extract(f).composite([{ input: outlineSvg(poly, f) }]).png().toBuffer();
  const stem = join(OUT, 'magnoolia-' + key);
  await sharp(base).webp({ quality: Q }).toFile(stem + '.webp');
  await sharp(base).resize(768).webp({ quality: Q }).toFile(stem + '-768.webp');
  await sharp(base).resize(480).webp({ quality: Q }).toFile(stem + '-480.webp');
  n++;
  console.log('✓', key, `crop ${f.width}x${f.height} @ ${f.left},${f.top}`);
}
// Bump the manifest mtime so the browser cache-buster (?v=filemtime) refreshes
// the regenerated crops (filenames are stable).
try { const m = fs.readFileSync(MANIFEST); fs.writeFileSync(MANIFEST, m); } catch (e) { /* ignore */ }
console.log(`\nDone: ${n} box crops written to homes/ (manifest cache-bust bumped).`);
console.log('Tip: run "php artisan cache:clear" if config/asset cache is on.');
