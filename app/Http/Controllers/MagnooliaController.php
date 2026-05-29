<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * MagnooliaController — Phase 14
 * Serves standalone SEO landing pages for each intent cluster.
 * Home page remains in HomeController.
 */
class MagnooliaController extends Controller
{
    /**
     * Resolve locale from route default or session.
     */
    protected function locale(string $locale = 'et'): string
    {
        $supported = ['et', 'ru', 'en'];
        $locale = in_array($locale, $supported) ? $locale : 'et';
        app()->setLocale($locale);
        return $locale;
    }

    /**
     * GET /kodud-ja-hinnad
     */
    public function homes(string $locale = 'et')
    {
        $this->locale($locale);
        $page = config('magnoolia_pages.pages.homes', []);

        return view('pages.magnoolia.kodud-ja-hinnad', compact('page'));
    }

    /**
     * GET /asendiplaan
     */
    public function sitePlan(string $locale = 'et')
    {
        $this->locale($locale);
        $page = config('magnoolia_pages.pages.site_plan', []);

        return view('pages.magnoolia.asendiplaan', compact('page'));
    }

    /**
     * GET /asukoht
     */
    public function location(string $locale = 'et')
    {
        $this->locale($locale);
        $page = config('magnoolia_pages.pages.location', []);

        return view('pages.magnoolia.asukoht', compact('page'));
    }

    /**
     * GET /ehitusinfo
     */
    public function construction(string $locale = 'et')
    {
        $this->locale($locale);
        $page = config('magnoolia_pages.pages.construction', []);

        return view('pages.magnoolia.ehitusinfo', compact('page'));
    }

    /**
     * GET /kontakt
     * Accepts optional ?unit= query param to prefill selected unit in form.
     */
    public function contact(Request $request, string $locale = 'et')
    {
        $this->locale($locale);
        $page        = config('magnoolia_pages.pages.contact', []);
        $selectedUnit = $request->query('unit', '');

        // Validate against known unit IDs / addresses to prevent injection
        $validUnits = array_column(config('magnoolia.units', []), null, 'id');
        $unitData   = $validUnits[$selectedUnit] ?? null;

        return view('pages.magnoolia.kontakt', compact('page', 'selectedUnit', 'unitData'));
    }
}
