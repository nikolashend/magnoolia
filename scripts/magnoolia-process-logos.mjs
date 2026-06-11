/**
 * Phase 28 — Logo and banner asset processing
 * Converts PNG logos to optimized WebP + keeps PNG fallback
 */
import sharp from 'sharp';
import { mkdirSync, copyFileSync, existsSync, statSync } from 'fs';
import { join, dirname } from 'path';
import { fileURLToPath } from 'url';

const __dirname = dirname(fileURLToPath(import.meta.url));
const ROOT = join(__dirname, '..');
const IMPORT = join(ROOT, 'materials', 'onedrive-import', 'phase28');
const LOGOS_OUT = join(ROOT, 'public', 'assets', 'magnoolia', 'logos');
const BANNERS_OUT = join(ROOT, 'public', 'assets', 'magnoolia', 'banners');

mkdirSync(LOGOS_OUT, { recursive: true });
mkdirSync(BANNERS_OUT, { recursive: true });

const REPORT = [];

async function processLogo(src, destBase, widths = []) {
  if (!existsSync(src)) {
    REPORT.push({ src, status: 'MISSING' });
    return;
  }
  const sizeKB = Math.round(statSync(src).size / 1024);
  console.log(`Processing: ${src} (${sizeKB} KB)`);

  // Always copy original PNG
  const pngDest = `${destBase}.png`;
  copyFileSync(src, pngDest);

  // Generate WebP
  try {
    const meta = await sharp(src).metadata();
    console.log(`  Size: ${meta.width}x${meta.height}`);

    // Full-size WebP
    await sharp(src).webp({ quality: 90, lossless: false }).toFile(`${destBase}.webp`);
    console.log(`  ✓ ${destBase}.webp`);
    REPORT.push({ src, dest: `${destBase}.webp`, status: 'OK', w: meta.width, h: meta.height, sizeKB });

    // Responsive widths if specified
    for (const w of widths) {
      if (meta.width > w) {
        await sharp(src).resize(w, null, { withoutEnlargement: true }).webp({ quality: 88 }).toFile(`${destBase}-${w}w.webp`);
        console.log(`  ✓ ${destBase}-${w}w.webp`);
      }
    }
  } catch (err) {
    console.error(`  ERROR: ${err.message}`);
    REPORT.push({ src, status: 'ERROR', reason: err.message });
  }
}

async function run() {
  console.log('\nPhase 28 — Logo & Banner Processing\n');

  // Magnoolia logo: Taustata.png (no background) = primary
  await processLogo(
    join(IMPORT, 'Taustata.png'),
    join(LOGOS_OUT, 'magnoolia-dark'),
    [320, 480, 640]
  );

  // Magnoolia logo: Taustaga.png (with background) = light version
  await processLogo(
    join(IMPORT, 'Taustaga.png'),
    join(LOGOS_OUT, 'magnoolia-light'),
    [320, 480]
  );

  // Estlanda logos — pick the cleanest version (taustata = no background)
  // Try versions 1.0, 2.0, 3.0 — use 1.0 as primary (smallest file = usually cleanest)
  const estlandaVersions = [
    'Estlanda-1-taustata.png',
    'Estlanda-1.0-taustata.png',
    'Estlanda-2-taustata.png',
    'Estlanda-2.0-taustata.png',
    'Estlanda-3-taustata.png',
    'Estlanda-3.0-taustata.png',
  ];
  // Use first found as primary
  for (let i = 0; i < estlandaVersions.length; i++) {
    const src = join(IMPORT, estlandaVersions[i]);
    const suffix = estlandaVersions[i].replace('.png', '').replace(/ /g, '-').toLowerCase();
    if (existsSync(src)) {
      await processLogo(src, join(LOGOS_OUT, `estlanda-${i + 1}`), [240, 320]);
    }
  }
  // Also copy best Estlanda as primary
  const primaryEstlanda = join(IMPORT, 'Estlanda-1-taustata.png');
  if (existsSync(primaryEstlanda)) {
    await processLogo(primaryEstlanda, join(LOGOS_OUT, 'estlanda'), [240, 320]);
  }

  // Banner image (large, process with reduced quality/size)
  const banner = join(IMPORT, 'Magnoolia UUED bännerid_3400x1750mm_näidis1.jpg');
  const bannerAlt = join(IMPORT, 'Magnoolia UUED b\u00e4nnerid_3400x1750mm_n\u00e4idis1.jpg');
  const bannerSrc = existsSync(banner) ? banner : existsSync(bannerAlt) ? bannerAlt : null;
  
  if (bannerSrc) {
    console.log(`Processing banner: ${bannerSrc}`);
    try {
      const meta = await sharp(bannerSrc).metadata();
      console.log(`  Banner size: ${meta.width}x${meta.height}`);
      await sharp(bannerSrc).resize(1600, null, { withoutEnlargement: true }).webp({ quality: 82 }).toFile(join(BANNERS_OUT, 'magnoolia-banner-1600w.webp'));
      await sharp(bannerSrc).resize(1200, null, { withoutEnlargement: true }).webp({ quality: 80 }).toFile(join(BANNERS_OUT, 'magnoolia-banner-1200w.webp'));
      await sharp(bannerSrc).resize(768, null, { withoutEnlargement: true }).webp({ quality: 78 }).toFile(join(BANNERS_OUT, 'magnoolia-banner-768w.webp'));
      console.log('  ✓ Banner processed at 1600/1200/768w');
      REPORT.push({ src: bannerSrc, status: 'OK', note: 'banner processed at 3 widths' });
    } catch (e) {
      console.error(`  Banner error: ${e.message}`);
    }
  }

  console.log('\n=== SUMMARY ===');
  REPORT.forEach(r => console.log(`  ${r.status.padEnd(8)} ${r.src?.split(/[\\/]/).pop()}`));
  console.log('\nDone.\n');
}

run().catch(err => {
  console.error('Error:', err.message);
  process.exit(1);
});
