<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ApartmentController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\MagnooliaController;
use App\Http\Controllers\Admin\Magnoolia\MagnooliaAdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Locale switcher
Route::get('/locale/{locale}', [LocaleController::class, 'switch'])
    ->name('locale.switch')
    ->where('locale', 'en|et|ru');

// Home — locale-prefix routing: / (ET default), /ru, /en
Route::get('/', [HomeController::class, 'index'])->name('home')->defaults('locale', 'et');
Route::get('/ru', [HomeController::class, 'index'])->name('home.ru')->defaults('locale', 'ru');
Route::get('/en', [HomeController::class, 'index'])->name('home.en')->defaults('locale', 'en');

// ── Magnoolia standalone pages (Phase 14 + Phase 25) ────────────────────────────────
Route::get('/kodud-ja-hinnad',  [MagnooliaController::class, 'homes'])        ->name('magnoolia.homes');
Route::get('/kodud/{slug}',     [MagnooliaController::class, 'unitDetail'])   ->name('magnoolia.unit');
Route::get('/vordle',           [MagnooliaController::class, 'compare'])      ->name('magnoolia.compare');
Route::get('/asendiplaan',      [MagnooliaController::class, 'sitePlan'])      ->name('magnoolia.site-plan');
Route::get('/asukoht',          [MagnooliaController::class, 'location'])      ->name('magnoolia.location');
Route::get('/ehitusinfo',       [MagnooliaController::class, 'construction'])  ->name('magnoolia.construction');
Route::get('/kontakt',          [MagnooliaController::class, 'contact'])       ->name('magnoolia.contact');
Route::post('/kontakt',         [MagnooliaController::class, 'contactSend'])   ->name('magnoolia.contact.send');
Route::get('/sisedisain',       [MagnooliaController::class, 'interior'])      ->name('magnoolia.sisedisain');
Route::get('/arhitektuur-ja-valisdisain', [MagnooliaController::class, 'architecture'])->name('magnoolia.arhitektuur');
Route::get('/galerii',          [MagnooliaController::class, 'gallery'])       ->name('magnoolia.galerii');
Route::get('/ostuprotsess',     [MagnooliaController::class, 'purchase'])      ->name('magnoolia.ostuprotsess');
Route::get('/finantseerimine',  [MagnooliaController::class, 'financing'])     ->name('magnoolia.finantseerimine');
Route::get('/kkk',              [MagnooliaController::class, 'faq'])           ->name('magnoolia.kkk');
Route::get('/aitah',            [MagnooliaController::class, 'thankyou'])      ->name('magnoolia.thankyou');
Route::get('/privaatsus',       [MagnooliaController::class, 'privacy'])       ->name('magnoolia.privacy');
Route::get('/tingimused',       [MagnooliaController::class, 'terms'])         ->name('magnoolia.terms');
Route::get('/arendajast',       [MagnooliaController::class, 'developer'])     ->name('magnoolia.developer');

// ── Phase 34.2: SEO / Google Ads commercial landing pages (ET, indexable) ────
$mgLandingsEt = [
    'ridaelamud-harjumaa', 'ridamajad-harjumaa', 'uusarendus-kiili', 'uusarendus-harjumaa',
    'uus-kodu-tallinna-lahedal', 'maja-muuk-harjumaa', 'a-energiaklassi-ridaelamud',
    'perekodu-tallinna-lahedal', 'ridaelamu-oma-hooviga', 'ridaelamu-vaela-kula',
];
foreach ($mgLandingsEt as $_slug) {
    Route::get('/' . $_slug, [MagnooliaController::class, 'landing'])
        ->defaults('lpview', $_slug)
        ->name('magnoolia.lp.' . $_slug);
}

// Phase 34.3: location hub pages (ET, indexable) — /asukoht/{hub}
$mgLocationHubs = ['vaela-kula', 'kiili-vald', 'tallinna-lahedal'];
foreach ($mgLocationHubs as $_h) {
    Route::get('/asukoht/' . $_h, [MagnooliaController::class, 'landing'])
        ->defaults('lpview', 'asukoht.' . $_h)
        ->name('magnoolia.hub.' . $_h);
}

// EN / RU buyer-intent landing pages (standalone, single-locale)
$mgLandingsIntl = [
    'en/new-townhouses-near-tallinn'  => 'en.new-townhouses-near-tallinn',
    'en/terraced-houses-harju-county' => 'en.terraced-houses-harju-county',
    'ru/taunhaus-rjadom-s-tallinnom'  => 'ru.taunhaus-rjadom-s-tallinnom',
    'ru/novyj-dom-v-harjumaa'         => 'ru.novyj-dom-v-harjumaa',
];
foreach ($mgLandingsIntl as $_path => $_view) {
    $_loc = explode('/', $_path)[0];
    Route::get('/' . $_path, [MagnooliaController::class, 'landing'])
        ->defaults('lpview', $_view)
        ->name($_loc . '.magnoolia.lp.' . substr($_view, strlen($_loc) + 1));
}

// ── Locale prefix groups: /ru/... and /en/... ────────────────────────────────
foreach (['ru', 'en'] as $_loc) {
    Route::prefix($_loc)
        ->group(function () use ($_loc) {
            Route::get('/', [HomeController::class, 'index'])
                ->name($_loc . '.home');
            Route::get('/kodud-ja-hinnad',  [MagnooliaController::class, 'homes'])
                ->name($_loc . '.magnoolia.homes');
            $unitPath    = $_loc === 'en' ? '/homes/{slug}' : '/kodud/{slug}';
            $comparePath = $_loc === 'en' ? '/compare' : '/sravnit';
            Route::get($unitPath,    [MagnooliaController::class, 'unitDetail'])
                ->name($_loc . '.magnoolia.unit');
            Route::get($comparePath, [MagnooliaController::class, 'compare'])
                ->name($_loc . '.magnoolia.compare');
            Route::get('/asendiplaan',      [MagnooliaController::class, 'sitePlan'])
                ->name($_loc . '.magnoolia.site-plan');
            Route::get('/asukoht',          [MagnooliaController::class, 'location'])
                ->name($_loc . '.magnoolia.location');
            Route::get('/ehitusinfo',       [MagnooliaController::class, 'construction'])
                ->name($_loc . '.magnoolia.construction');
            Route::get('/kontakt',          [MagnooliaController::class, 'contact'])
                ->name($_loc . '.magnoolia.contact');
            Route::post('/kontakt',         [MagnooliaController::class, 'contactSend'])
                ->name($_loc . '.magnoolia.contact.send');
            Route::get('/sisedisain',       [MagnooliaController::class, 'interior'])
                ->name($_loc . '.magnoolia.sisedisain');
            Route::get('/arhitektuur-ja-valisdisain', [MagnooliaController::class, 'architecture'])
                ->name($_loc . '.magnoolia.arhitektuur');
            Route::get('/galerii',          [MagnooliaController::class, 'gallery'])
                ->name($_loc . '.magnoolia.galerii');
            Route::get('/ostuprotsess',     [MagnooliaController::class, 'purchase'])
                ->name($_loc . '.magnoolia.ostuprotsess');
            Route::get('/finantseerimine',  [MagnooliaController::class, 'financing'])
                ->name($_loc . '.magnoolia.finantseerimine');
            Route::get('/kkk',              [MagnooliaController::class, 'faq'])
                ->name($_loc . '.magnoolia.kkk');
            Route::get('/aitah',            [MagnooliaController::class, 'thankyou'])
                ->name($_loc . '.magnoolia.thankyou');
            Route::get('/privaatsus',       [MagnooliaController::class, 'privacy'])
                ->name($_loc . '.magnoolia.privacy');
            Route::get('/tingimused',       [MagnooliaController::class, 'terms'])
                ->name($_loc . '.magnoolia.terms');
            Route::get('/arendajast',       [MagnooliaController::class, 'developer'])
                ->name($_loc . '.magnoolia.developer');
        });
}

// ── Sitemap ──────────────────────────────────────────────────────────────
Route::get('/robots.txt', function () {
    $noindex = config('magnoolia.seo.noindex', true) || request()->is('aitah') || request()->is('ru/aitah') || request()->is('en/aitah');

    $content = $noindex
        ? "User-agent: *\nDisallow: /\n\nSitemap: https://magnoolia.ee/sitemap.xml\n"
        : "User-agent: *\nAllow: /\nDisallow: /admin\nDisallow: /admin/\n\nSitemap: https://magnoolia.ee/sitemap.xml\n";

    return response($content, 200)->header('Content-Type', 'text/plain; charset=UTF-8');
})->name('robots');

Route::get('/sitemap.xml', function () {
    return response()->view('sitemap')->header('Content-Type', 'application/xml');
})->name('sitemap');

// About
Route::get('/about', [AboutController::class, 'index'])->name('about');

// Apartments
Route::prefix('apartments')->name('apartments.')->group(function () {
    Route::get('/', [ApartmentController::class, 'index'])->name('index');
    Route::get('/{slug}', [ApartmentController::class, 'show'])->name('show');
    Route::post('/inquiry', [ApartmentController::class, 'inquiry'])->name('inquiry');
});

// Services
Route::prefix('services')->name('services.')->group(function () {
    Route::get('/', [ServiceController::class, 'index'])->name('index');
    Route::get('/{slug}', [ServiceController::class, 'show'])->name('show');
});

// Blog
Route::prefix('blog')->name('blog.')->group(function () {
    Route::get('/', [BlogController::class, 'index'])->name('index');
    Route::get('/{slug}', [BlogController::class, 'show'])->name('show');
});

// Gallery
Route::get('/gallery', [PageController::class, 'gallery'])->name('gallery.index');

// FAQ
Route::get('/faq', [PageController::class, 'faq'])->name('faq');

// Team
Route::get('/team', [PageController::class, 'team'])->name('team');

// Search
Route::get('/search', [PageController::class, 'search'])->name('search');

// Contact
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

// Newsletter
Route::post('/newsletter', [PageController::class, 'newsletterSubscribe'])->name('newsletter.subscribe');

// Styleguide (internal dev tool — guard with env check)
Route::get('/styleguide', function () {
    abort_unless(app()->isLocal() || request()->get('preview') === 'mg2025', 403);
    return view('pages.styleguide');
})->name('styleguide');

// Auth (redirect to Filament admin login)
Route::get('/login', fn() => redirect('/admin/login'))->name('login');
Route::get('/register', fn() => abort(404))->name('register');

// ── Phase 24: Magnoolia Admin Control Plane ───────────────────────────────
Route::prefix('admin/magnoolia')
    ->name('admin.magnoolia.')
    ->middleware(['auth', 'verified', 'magnoolia.admin', 'throttle:60,1'])
    ->group(function () {
        Route::get('/', [MagnooliaAdminController::class, 'dashboard'])->name('dashboard');

        // Phase 33.3 — "Edit Website" page map: start from a page, jump to the editor.
        Route::get('/site-map', [MagnooliaAdminController::class, 'siteMap'])->name('sitemap');

        Route::get('/units', [MagnooliaAdminController::class, 'units'])->name('units.index');
        Route::get('/units/{unit}/edit', [MagnooliaAdminController::class, 'editUnit'])->name('units.edit');
        Route::put('/units/{unit}', [MagnooliaAdminController::class, 'updateUnit'])->name('units.update');
        Route::patch('/units/{unit}/quick', [MagnooliaAdminController::class, 'quickUpdate'])->name('units.quick');
        Route::post('/units/bulk', [MagnooliaAdminController::class, 'bulkUpdate'])->name('units.bulk');

        // Page-Texts CMS (Phase 33.1)
        Route::get('/content', [MagnooliaAdminController::class, 'content'])->name('content.index');
        Route::patch('/content/{block}', [MagnooliaAdminController::class, 'contentUpdate'])->name('content.update');

        // Media Library (Phase 33.1)
        Route::get('/media', [\App\Http\Controllers\Admin\Magnoolia\MagnooliaMediaController::class, 'index'])->name('media.index');
        Route::post('/media', [\App\Http\Controllers\Admin\Magnoolia\MagnooliaMediaController::class, 'store'])->name('media.store');
        Route::patch('/media/{item}', [\App\Http\Controllers\Admin\Magnoolia\MagnooliaMediaController::class, 'update'])->name('media.update');
        Route::delete('/media/{item}', [\App\Http\Controllers\Admin\Magnoolia\MagnooliaMediaController::class, 'destroy'])->name('media.destroy');

        Route::get('/validate', [MagnooliaAdminController::class, 'validateDraft'])->name('validate');
        Route::get('/preview', [MagnooliaAdminController::class, 'preview'])->name('preview');

        Route::get('/export/csv', [MagnooliaAdminController::class, 'exportCsv'])->name('export.csv');
        Route::post('/import/csv/preview', [MagnooliaAdminController::class, 'importCsvPreview'])->name('import.csv.preview');
        Route::post('/import/csv/apply', [MagnooliaAdminController::class, 'importCsvApply'])->name('import.csv.apply');

        Route::get('/help', [MagnooliaAdminController::class, 'help'])->name('help');

        // Leads / Inquiries (Phase 33.1)
        Route::get('/leads', [MagnooliaAdminController::class, 'leads'])->name('leads.index');
        Route::get('/leads/export', [MagnooliaAdminController::class, 'leadsExport'])->name('leads.export');
        Route::patch('/leads/{lead}/status', [MagnooliaAdminController::class, 'leadStatus'])->name('leads.status');

        Route::get('/changes', [MagnooliaAdminController::class, 'changes'])->name('changes');

        Route::get('/publications', [MagnooliaAdminController::class, 'publications'])->name('publications.index');

        // Advanced — full system admin (ADME) only.
        Route::middleware(['magnoolia.system-admin'])->group(function () {
            Route::get('/audit', [MagnooliaAdminController::class, 'audit'])->name('audit');
        });

        Route::middleware(['magnoolia.publish-admin'])->group(function () {
            Route::get('/campaign', [MagnooliaAdminController::class, 'campaign'])->name('campaign');
            Route::post('/campaign', [MagnooliaAdminController::class, 'updateCampaign'])->name('campaign.update');

            Route::get('/publish', [MagnooliaAdminController::class, 'publishForm'])->name('publish.form');
            Route::post('/publish', [MagnooliaAdminController::class, 'publish'])->name('publish');

            Route::get('/publications/{id}/rollback', [MagnooliaAdminController::class, 'rollbackForm'])->name('publications.rollback.form');
            Route::post('/publications/{id}/rollback', [MagnooliaAdminController::class, 'rollback'])->name('publications.rollback');
        });
    });
