<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use App\Models\MagnooliaLead;
use App\Services\Magnoolia\MagnooliaPublicDataRepository;
use App\Services\Magnoolia\MagnooliaUnitDiscoveryService;

/**
 * MagnooliaController — Phase 14
 * Serves standalone SEO landing pages for each intent cluster.
 * Home page remains in HomeController.
 */
class MagnooliaController extends Controller
{
    public function __construct(
        private readonly MagnooliaPublicDataRepository $publicDataRepository,
        private readonly MagnooliaUnitDiscoveryService $discovery,
    ) {
    }

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
        $validUnits = array_column($this->publicDataRepository->getUnits(), null, 'id');
        $unitData   = $validUnits[$selectedUnit] ?? null;

        return view('pages.magnoolia.kontakt', compact('page', 'selectedUnit', 'unitData'));
    }

    /**
     * POST /kontakt  (also /ru/kontakt, /en/kontakt)
     * Processes the inquiry form and redirects back with flash.
     */
    public function contactSend(Request $request)
    {
        // Honeypot — bots fill this hidden field, humans don't
        if ($request->filled('website')) {
            return redirect()->to(lroute('magnoolia.contact') . '#kontaktivorm')
                ->with('contact_success', true)
                ->with('contact_name', $request->input('name', ''));
        }

        // Rate limit: max 3 submissions per 10 minutes per IP
        $rateLimitKey = 'contact|' . $request->ip();
        if (RateLimiter::tooManyAttempts($rateLimitKey, 3)) {
            $seconds = RateLimiter::availableIn($rateLimitKey);
            return redirect()->back()
                ->withErrors(['email' => __('magnoolia.contact.rate_limit_message', [], app()->getLocale()) ?: 'Liiga palju päringuid. Proovi ' . ceil($seconds / 60) . ' minuti pärast uuesti.'])
                ->withInput();
        }
        RateLimiter::hit($rateLimitKey, 600); // 10-minute window

        $validated = $request->validate([
            'name'          => 'required|string|min:2|max:120',
            'email'         => 'required|email|max:190',
            'phone'         => 'nullable|string|max:50',
            'message'       => 'nullable|string|max:2000',
            'selected_unit' => 'nullable|string|max:100',
            'consent'       => 'accepted',
        ]);

        $toEmail    = config('magnoolia.project.contact_email', 'diana@estlanda.ee');
        $unitLabel  = $validated['selected_unit'] ?: __('magnoolia.forms.unit_none');
        $locale     = app()->getLocale();
        $sourceUrl  = $request->headers->get('referer', $request->url());
        $referrer   = $request->session()->previousUrl() ?? $request->headers->get('referer');
        $ip         = $request->ip();
        $userAgent  = substr($request->userAgent() ?? '', 0, 500);
        $utmSource  = $request->query('utm_source');
        $utmMedium  = $request->query('utm_medium');
        $utmCampaign= $request->query('utm_campaign');
        $utmContent = $request->query('utm_content');

        $publicPayload = $this->publicDataRepository->getPublicPayload();
        $publishedVersion = (int) ($publicPayload['meta']['version'] ?? 0);
        $publishedUnits = collect($publicPayload['units'] ?? []);
        $selectedUnitRow = $publishedUnits->first(function (array $unit) use ($validated) {
            $selected = (string) ($validated['selected_unit'] ?? '');
            return ($unit['address'] ?? '') === $selected || ($unit['id'] ?? '') === $selected || ($unit['unit_key'] ?? '') === $selected;
        });

        $sourcePage = parse_url($sourceUrl ?? '', PHP_URL_PATH) ?: $request->path();
        $sourceComponent = $request->input('source_component', 'contact_form');

        // Build email body
        $body = "Uus päring Magnoolia kodulehelt — {$unitLabel}\n"
            . str_repeat('-', 50) . "\n"
            . "Nimi:      {$validated['name']}\n"
            . "E-post:    {$validated['email']}\n"
            . "Telefon:   " . ($validated['phone'] ?? '—') . "\n"
            . "Kodu:      " . ($validated['selected_unit'] ?? '—') . "\n\n"
            . "Sõnum:\n" . ($validated['message'] ?? '—') . "\n\n"
            . str_repeat('-', 50) . "\n"
            . "Keel:      {$locale}\n"
            . "Published version: {$publishedVersion}\n"
            . "Status (published): " . (($selectedUnitRow['status'] ?? null) ?: '—') . "\n"
            . "Public price state: " . ((($selectedUnitRow['price_public'] ?? false) ? 'public' : 'hidden')) . "\n"
            . "Lehekülg:  {$sourceUrl}\n"
            . "Referrer:  " . ($referrer ?? '—') . "\n"
            . "IP:        {$ip}\n"
            . "Aeg:       " . now()->setTimezone('Europe/Tallinn')->format('d.m.Y H:i:s T') . "\n";
        if ($utmSource) {
            $body .= "UTM:       source={$utmSource} medium={$utmMedium} campaign={$utmCampaign} content={$utmContent}\n";
        }

        // 1) Send mail
        $mailStatus = 'sent';
        try {
            Mail::raw($body, function ($message) use ($toEmail, $validated, $unitLabel) {
                $message->to($toEmail)
                        ->replyTo($validated['email'], $validated['name'])
                        ->subject("Magnoolia päring — {$unitLabel} — {$locale}");
            });
        } catch (\Exception $e) {
            $mailStatus = 'failed';
            Log::error('Magnoolia contact form mail failed: ' . $e->getMessage(), ['email' => $validated['email']]);
        }

        // 2) Log lead to DB (fail silently so mail failure doesn't break UX)
        try {
            MagnooliaLead::create([
                'name'          => $validated['name'],
                'email'         => $validated['email'],
                'phone'         => $validated['phone'] ?? null,
                'selected_unit' => $validated['selected_unit'] ?? null,
                'unit_key'      => $selectedUnitRow['unit_key'] ?? null,
                'unit_address'  => $selectedUnitRow['address'] ?? ($validated['selected_unit'] ?? null),
                'published_version' => $publishedVersion,
                'status_at_submission' => $selectedUnitRow['status'] ?? null,
                'price_public_at_submission' => isset($selectedUnitRow['price_public']) ? (bool) $selectedUnitRow['price_public'] : null,
                'source_page'   => is_string($sourcePage) ? mb_substr($sourcePage, 0, 255) : null,
                'source_component' => mb_substr((string) $sourceComponent, 0, 120),
                'message'       => $validated['message'] ?? null,
                'locale'        => $locale,
                'source_url'    => $sourceUrl,
                'referrer'      => $referrer,
                'utm_source'    => $utmSource,
                'utm_medium'    => $utmMedium,
                'utm_campaign'  => $utmCampaign,
                'utm_content'   => $utmContent,
                'ip_address'    => $ip,
                'user_agent'    => $userAgent,
                'mail_status'   => $mailStatus,
            ]);
        } catch (\Exception $e) {
            Log::error('Magnoolia lead DB log failed: ' . $e->getMessage());
        }

        // Redirect to locale thank-you page
        return redirect()->to(lroute('magnoolia.thankyou'))
            ->with('contact_name', $validated['name']);
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
        return view('pages.magnoolia.arhitektuur', compact('page'));
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

    /** GET /aitah  (ET) | /ru/spasibo (RU) | /en/thank-you (EN) */
    public function thankyou()
    {
        $name = session('contact_name');
        return view('pages.magnoolia.aitah', compact('name'));
    }

    /** Phase 34 — privacy notice (GDPR). Locale via SetLocale from URL prefix. */
    public function privacy()
    {
        return view('pages.magnoolia.legal', ['doc' => 'privacy']);
    }

    /** Phase 34 — terms / usage notice. */
    public function terms()
    {
        return view('pages.magnoolia.legal', ['doc' => 'terms']);
    }

    /**
     * GET /kodud/{slug}  (ET)
     * GET /ru/kodud/{slug}  (RU)
     * GET /en/homes/{slug}  (EN)
     * Individual unit detail page.
     */
    public function unitDetail(string $slug)
    {
        $unit = $this->discovery->findBySlug($slug);

        if ($unit === null) {
            abort(404);
        }

        $similar  = $this->discovery->similar($unit, 3);
        $adjacent = $this->discovery->adjacent($unit);
        $locale   = app()->getLocale();

        $payload  = $this->publicDataRepository->getPublicPayload();
        $publishedVersion = $payload['meta']['version'] ?? null;

        $hreflang = [
            'et' => MagnooliaUnitDiscoveryService::unitPageUrl($unit, 'et'),
            'ru' => MagnooliaUnitDiscoveryService::unitPageUrl($unit, 'ru'),
            'en' => MagnooliaUnitDiscoveryService::unitPageUrl($unit, 'en'),
        ];

        return view('pages.magnoolia.unit-detail', compact(
            'unit', 'similar', 'adjacent', 'locale', 'publishedVersion', 'hreflang',
        ));
    }

    /**
     * GET /vordle  (ET)
     * GET /ru/sravnit  (RU)
     * GET /en/compare  (EN)
     * Unit comparison page.
     */
    public function compare(Request $request)
    {
        $slugsParam = $request->query('units', '');
        $slugs = array_filter(array_map('trim', explode(',', (string) $slugsParam)));
        $compareUnits = $this->discovery->comparePayload(array_slice($slugs, 0, 3));

        $allUnits = $this->discovery->allUnits();

        return view('pages.magnoolia.compare', compact('compareUnits', 'allUnits'));
    }
}
