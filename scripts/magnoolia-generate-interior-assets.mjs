/**
 * Magnoolia Phase 31 — Interior-finish (siseviimistlus) asset generator.
 *
 * Converts the Phase-31 source JPGs (materials/phase31) into optimised WebP:
 *  - 2 interior renders (large, for the editorial block) + responsive variants
 *  - 5 "proof sheets" (the material slides) shown inside detail accordions/lightbox
 *
 * Run: npm run magnoolia:generate:interior
 */
import sharp from 'sharp';
import { mkdir, writeFile, stat } from 'fs/promises';
import { join, dirname } from 'path';
import { fileURLToPath } from 'url';

const __dirname = dirname(fileURLToPath(import.meta.url));
const ROOT = join(__dirname, '..');
const SRC = join(ROOT, '..', 'materials', 'phase31');
const OUT = join(ROOT, 'public/assets/magnoolia/siseviimistlus');

// source -> { out, widths, quality }
const JOBS = [
  { src: 'valmis tuba.jpg',            out: 'interior-living-day.webp',      widths: [1600, 768], q: 83 },
  { src: 'valmis tuba2.jpg',           out: 'interior-living-evening.webp',  widths: [1600, 768], q: 83 },
  { src: 'pistikud display.jpg',       out: 'electrical-overview.webp',      widths: [1400, 768], q: 80 },
  { src: 'sanitaartehnika.jpg',        out: 'sanitary-overview.webp',        widths: [1400, 768], q: 80 },
  { src: 'seina voi porandaplaat.jpg', out: 'tiles-overview.webp',           widths: [1400, 768], q: 80 },
  { src: 'sisedisain.jpg',             out: 'finish-overview.webp',          widths: [1400, 768], q: 80 },
  { src: 'valikud lisatasu eest.jpg',  out: 'paid-options-overview.webp',    widths: [1400, 768], q: 80 },
];

async function main() {
  await mkdir(OUT, { recursive: true });
  const report = [];
  for (const j of JOBS) {
    const srcPath = join(SRC, j.src);
    let srcKb = 0;
    try { srcKb = Math.round((await stat(srcPath)).size / 1024); }
    catch { console.warn(`  ! missing source: ${j.src}`); continue; }

    // full-size base
    const baseInfo = await sharp(srcPath).webp({ quality: j.q }).toFile(join(OUT, j.out));
    const variants = [{ w: 'base', kb: Math.round(baseInfo.size / 1024), file: j.out }];
    for (const w of j.widths) {
      const wOut = j.out.replace(/\.webp$/, `-${w}.webp`);
      const info = await sharp(srcPath).resize(w, null, { withoutEnlargement: true, fit: 'inside' }).webp({ quality: j.q }).toFile(join(OUT, wOut));
      variants.push({ w, kb: Math.round(info.size / 1024), file: wOut });
    }
    const minKb = Math.min(...variants.map(v => v.kb));
    report.push({ src: j.src, srcKb, out: j.out, variants, minKb });
    console.log(`  ✓ ${j.src} (${srcKb}KB) -> ${j.out} (${variants.map(v => v.w + ':' + v.kb + 'KB').join(', ')})`);
  }

  const totalSrc = report.reduce((a, r) => a + r.srcKb, 0);
  const totalMin = report.reduce((a, r) => a + r.minKb, 0);
  const lines = [
    '# Phase 31 — Interior asset optimization', '',
    `Generated: ${new Date().toISOString()}`, '',
    '| Source (JPG) | KB | WebP | variants (KB) |', '|---|---|---|---|',
    ...report.map(r => `| ${r.src} | ${r.srcKb} | ${r.out} | ${r.variants.map(v => v.w + ':' + v.kb).join(', ')} |`),
    '', `Total source: ${totalSrc} KB · total smallest WebP per asset: ${totalMin} KB`,
  ];
  await writeFile(join(OUT, 'optimization.md'), lines.join('\n'));
  console.log(`\n✓ ${report.length} assets · source ${totalSrc}KB -> ${totalMin}KB (min variants). Report: optimization.md`);
}
main().catch(e => { console.error('FATAL', e); process.exit(1); });
