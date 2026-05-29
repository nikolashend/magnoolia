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

// ── Magnoolia standalone pages (Phase 14) ────────────────────────────────
Route::get('/kodud-ja-hinnad',  [MagnooliaController::class, 'homes'])        ->name('magnoolia.homes');
Route::get('/asendiplaan',      [MagnooliaController::class, 'sitePlan'])      ->name('magnoolia.site-plan');
Route::get('/asukoht',          [MagnooliaController::class, 'location'])      ->name('magnoolia.location');
Route::get('/ehitusinfo',       [MagnooliaController::class, 'construction'])  ->name('magnoolia.construction');
Route::get('/kontakt',          [MagnooliaController::class, 'contact'])       ->name('magnoolia.contact');

// ── Sitemap ──────────────────────────────────────────────────────────────
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
Route::get('/register', fn() => redirect('/admin/login'))->name('register');
