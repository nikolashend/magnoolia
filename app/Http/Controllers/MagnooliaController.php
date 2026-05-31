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
     * Locale is set by SetLocale middleware from URL segment.
     * No need to handle it in the controller.
     */

    /**
     * GET /kodud-ja-hinnad
     */
    public function homes()
    {
        $page = config('magnoolia_pages.pages.homes', []);

        return view('pages.magnoolia.kodud-ja-hinnad', compact('page'));
    }

    /**
     * GET /asendiplaan
     */
    public function sitePlan()
    {
        $page = config('magnoolia_pages.pages.site_plan', []);

        return view('pages.magnoolia.asendiplaan', compact('page'));
    }

    /**
     * GET /asukoht
     */
    public function location()
    {
        $page = config('magnoolia_pages.pages.location', []);

        return view('pages.magnoolia.asukoht', compact('page'));
    }

    /**
     * GET /ehitusinfo
     */
    public function construction()
    {
        $page = config('magnoolia_pages.pages.construction', []);

        return view('pages.magnoolia.ehitusinfo', compact('page'));
    }

    /**
     * GET /kontakt
     * Accepts optional ?unit= query param to prefill selected unit in form.
     */
    public function contact(Request $request)
    {
        $page        = config('magnoolia_pages.pages.contact', []);
        $selectedUnit = $request->query('unit', '');

        // Validate against known unit IDs / addresses to prevent injection
        $validUnits = array_column(config('magnoolia.units', []), null, 'id');
        $unitData   = $validUnits[$selectedUnit] ?? null;

        return view('pages.magnoolia.kontakt', compact('page', 'selectedUnit', 'unitData'));
    }

    /** GET /sisedisain */
    public function interior()
    {
        $page = config('magnoolia_pages.pages.interior', []);
        return view('pages.magnoolia.sisedisain', compact('page'));
    }

    /** GET /arhitektuur-ja-valisdisain */
    public function architecture()
    {
        $page = config('magnoolia_pages.pages.architecture', []);
        return view('pages.magnoolia.arhitektuur-ja-valisdisain', compact('page'));
    }

    /** GET /galerii */
    public function gallery()
    {
        $page = config('magnoolia_pages.pages.gallery', []);
        return view('pages.magnoolia.galerii', compact('page'));
    }

    /** GET /ostuprotsess */
    public function purchase()
    {
        $page = config('magnoolia_pages.pages.purchase', []);
        return view('pages.magnoolia.ostuprotsess', compact('page'));
    }

    /** GET /finantseerimine */
    public function financing()
    {
        $page = config('magnoolia_pages.pages.financing', []);
        return view('pages.magnoolia.finantseerimine', compact('page'));
    }

    /** GET /kkk */
    public function faq()
    {
        $page = config('magnoolia_pages.pages.faq', []);
        return view('pages.magnoolia.kkk', compact('page'));
    }
}
