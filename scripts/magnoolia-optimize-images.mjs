/**
 * Magnoolia Image Optimizer — Phase 27
 * Converts large JPGs to WebP at multiple widths.
 * Run: node scripts/magnoolia-optimize-images.mjs
 */
import sharp from 'sharp';
import { readdir, stat, mkdir } from 'fs/promises';
import { join, extname, basename, dirname } from 'path';
import { fileURLToPath } from 'url';

const __dirname = dirname(fileURLToPath(import.meta.url));
const ROOT = join(__dirname, '..');

const DIRS = [
  'public/assets/magnoolia/gallery/exterior',
  'public/assets/magnoolia/gallery/interior',
  'public/assets/magnoolia/location',
  'public/assets/magnoolia/sisedisain',
  'public/assets/images/magnoolia',
];

// Widths to generate
const WIDTHS = {
  gallery:  [480, 768, 1200],
  location: [480, 768, 1200],
  hero:     [480, 768, 1200, 1600],
  default:  [480, 768, 1200],
};

// Quality settings
const QUALITY = {
  gallery:  { webp: 78 },
  location: { webp: 80 },
  hero:     { webp: 82 },
  default:  { webp: 80 },
};

const SIZE_THRESHOLD_KB = 300;

async function getFiles(dir) {
  try {
    const entries = await readdir(dir);
    return entries.filter(f => /\.(jpg|jpeg|png)$/i.test(f)).map(f => join(dir, f));
  } catch {
    return [];
  }
}

async function optimizeImage(src, category) {
  const stats = await stat(src);
  const sizeKb = stats.size / 1024;

  if (sizeKb < SIZE_THRESHOLD_KB) {
    console.log(`  SKIP (${Math.round(sizeKb)}KB, below threshold): ${basename(src)}`);
    return { skipped: true, src, sizeKb };
  }

  const widths = WIDTHS[category] || WIDTHS.default;
  const quality = QUALITY[category] || QUALITY.default;
  const srcDir = dirname(src);
  const name = basename(src, extname(src));

  const results = [];

  for (const w of widths) {
    const outPath = join(srcDir, `${name}-${w}w.webp`);

    try {
      const info = await sharp(src)
        .resize(w, null, { withoutEnlargement: true, fit: 'inside' })
        .webp({ quality: quality.webp })
        .toFile(outPath);

      results.push({
        width: w,
        out: outPath,
        outKb: Math.round(info.size / 1024),
      });
    } catch (err) {
      console.error(`  ERROR at ${w}w: ${err.message}`);
    }
  }

  // Generate default WebP (original size)
  const defaultOut = join(srcDir, `${name}.webp`);
  if (!results.find(r => r.out === defaultOut)) {
    try {
      const info = await sharp(src)
        .webp({ quality: quality.webp })
        .toFile(defaultOut);
      results.push({ width: 'default', out: defaultOut, outKb: Math.round(info.size / 1024) });
    } catch (err) {
      console.error(`  ERROR (default webp): ${err.message}`);
    }
  }

  return { skipped: false, src, sizeKb: Math.round(sizeKb), results };
}

async function main() {
  const report = {
    processed: [],
    skipped: [],
    errors: [],
    totalOriginalKb: 0,
    totalOptimizedKb: 0,
  };

  for (const rel of DIRS) {
    const dir = join(ROOT, rel);
    const files = await getFiles(dir);

    if (files.length === 0) {
      console.log(`  No images in: ${rel}`);
      continue;
    }

    console.log(`\n📁 ${rel} (${files.length} images)`);

    const category = rel.includes('gallery') ? 'gallery'
                   : rel.includes('location') ? 'location'
                   : rel.includes('images/magnoolia') ? 'hero'
                   : 'default';

    for (const src of files) {
      // Skip already-converted WebP files
      if (src.endsWith('.webp')) continue;

      console.log(`  → ${basename(src)}`);
      try {
        const result = await optimizeImage(src, category);
        if (result.skipped) {
          report.skipped.push(result);
        } else {
          const minOut = Math.min(...result.results.map(r => r.outKb));
          report.processed.push({ ...result, minOut });
          report.totalOriginalKb += result.sizeKb;
          report.totalOptimizedKb += result.results.reduce((acc, r) => acc + r.outKb, 0);

          const savings = Math.round((1 - minOut / result.sizeKb) * 100);
          console.log(`     ✓ ${result.sizeKb}KB → ${minOut}KB minimum (~${savings}% smaller)`);
          result.results.forEach(r => {
            console.log(`       ${r.width}w: ${r.outKb}KB`);
          });
        }
      } catch (err) {
        console.error(`  ✗ FAILED: ${err.message}`);
        report.errors.push({ src, error: err.message });
      }
    }
  }

  console.log('\n' + '='.repeat(60));
  console.log('IMAGE OPTIMIZATION COMPLETE');
  console.log(`Processed: ${report.processed.length} images`);
  console.log(`Skipped (small): ${report.skipped.length} images`);
  console.log(`Errors: ${report.errors.length}`);
  console.log(`Total original: ${Math.round(report.totalOriginalKb / 1024 * 10) / 10} MB`);
  console.log(`Total optimized variants: ${Math.round(report.totalOptimizedKb / 1024 * 10) / 10} MB`);
  if (report.processed.length > 0) {
    const avgSavings = Math.round((1 - report.totalOptimizedKb / (report.totalOriginalKb * WIDTHS.default.length)) * 100);
    console.log(`Average size reduction: ~${Math.max(0, avgSavings)}%`);
  }
  console.log('='.repeat(60));

  // Write report to docs
  const reportLines = [
    '# Magnoolia Phase 27 — Image Optimization Report',
    '',
    `**Date:** ${new Date().toISOString().split('T')[0]}`,
    '',
    `## Summary`,
    `- Processed: ${report.processed.length} images`,
    `- Skipped (below ${SIZE_THRESHOLD_KB}KB threshold): ${report.skipped.length}`,
    `- Errors: ${report.errors.length}`,
    `- Total original size: ${Math.round(report.totalOriginalKb / 1024 * 10) / 10} MB`,
    '',
    '## Processed Images',
    '',
    ...report.processed.map(r => {
      const lines = [`### ${basename(r.src)}`];
      lines.push(`- Original: ${r.sizeKb}KB`);
      r.results.forEach(v => lines.push(`- ${v.width}w WebP: ${v.outKb}KB`));
      return lines.join('\n');
    }),
    '',
    '## Skipped (Below Threshold)',
    ...report.skipped.map(r => `- ${basename(r.src)}: ${Math.round(r.sizeKb)}KB`),
    '',
    ...(report.errors.length > 0 ? [
      '## Errors',
      ...report.errors.map(e => `- ${basename(e.src)}: ${e.error}`),
    ] : []),
  ];

  const { writeFile } = await import('fs/promises');
  const reportPath = join(ROOT, 'docs/magnoolia-phase27-image-optimization-report.md');
  await writeFile(reportPath, reportLines.join('\n'));
  console.log(`\nReport written to: docs/magnoolia-phase27-image-optimization-report.md`);
}

main().catch(err => {
  console.error('Fatal error:', err);
  process.exit(1);
});
