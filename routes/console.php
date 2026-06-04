<?php

use App\Support\MagnooliaRenderedHtmlAudit;
use Illuminate\Http\Request;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Contracts\Http\Kernel as HttpKernel;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('magnoolia:audit-rendered-html', function () {
    $kernel = app(HttpKernel::class);
    $results = [];
    $failures = 0;
    $forbidden = 0;
    $languageLeaks = 0;
    $templateResidue = 0;
    $brokenAnchors = 0;
    $duplicateIds = 0;

    foreach (MagnooliaRenderedHtmlAudit::urls() as $url) {
        $request = Request::create($url, 'GET');
        $response = $kernel->handle($request);
        $html = (string) $response->getContent();
        $audit = MagnooliaRenderedHtmlAudit::analyze($url, $html);

        $forbidden += count($audit['forbidden_found']);
        $templateResidue += count($audit['template_residue']);
        $brokenAnchors += count($audit['broken_anchors']);
        $duplicateIds += count($audit['duplicate_ids']);

        $locale = $audit['locale'];
        $languageLeak = count($audit['forbidden_found']) > 0 || count($audit['broken_anchors']) > 0 || count($audit['duplicate_ids']) > 0 || count($audit['template_residue']) > 0;
        if ($languageLeak) {
            $languageLeaks++;
        }

        $results[] = [
            $url,
            $response->getStatusCode(),
            count($audit['forbidden_found']),
            count($audit['duplicate_ids']),
            count($audit['broken_anchors']),
            $locale,
        ];

        if ($response->getStatusCode() !== 200 || $languageLeak) {
            $failures++;
        }
    }

    $this->line('Rendered URLs checked: ' . count($results) . '/' . count($results));
    $this->line('Forbidden rendered strings: ' . $forbidden);
    $this->line('Language leakage: ' . $languageLeaks);
    $this->line('Template residue: ' . $templateResidue);
    $this->line('Broken internal anchors: ' . $brokenAnchors);
    $this->line('Duplicate IDs: ' . $duplicateIds);

    if ($failures > 0 || $forbidden > 0 || $languageLeaks > 0 || $templateResidue > 0 || $brokenAnchors > 0 || $duplicateIds > 0) {
        return self::FAILURE;
    }

    return self::SUCCESS;
})->purpose('Audit rendered HTML for all public Magnoolia URLs');
