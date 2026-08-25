<?php

namespace App\Providers;

use App\Services\Magnoolia\MagnooliaPublicDataRepository;
use Illuminate\Support\ServiceProvider;

/**
 * Phase 36, Module A — make published text overrides apply to every read.
 *
 * THE PROBLEM THIS SOLVES
 * -----------------------
 * Overrides used to reach the page only through `mg_text()`. Templates mostly call
 * `__('magnoolia.…')`, so of the 85 texts registered as "editable" only 34 actually
 * were — the rest would have shown an edit box in admin that changed nothing on the
 * site. Rewriting 51 call sites would have fixed today's list and left the next key
 * broken again.
 *
 * Instead the published overrides are layered onto Laravel's translator, so both
 * `__()` and `mg_text()` see them, and any key added to config/magnoolia_editable.php
 * works without touching a template.
 *
 * WHY THE load() CALL MATTERS
 * ---------------------------
 * `addLines()` marks the group as loaded. Calling it before the lang file is read
 * would leave the file unread and every untouched key empty — the whole site's text
 * would disappear. Loading the file first makes addLines merge into it instead.
 *
 * Failures are swallowed on purpose: no publication, no database, or a broken
 * payload must degrade to the plain lang files, never to a blank site.
 */
class MagnooliaContentOverrideProvider extends ServiceProvider
{
    /** Locales the site publishes. */
    private const LOCALES = ['et', 'ru', 'en'];

    public function boot(): void
    {
        // Migrations and installs run before the tables exist; nothing to overlay.
        if ($this->app->runningInConsole() && ! $this->app->runningUnitTests()) {
            return;
        }

        try {
            $content = $this->app->make(MagnooliaPublicDataRepository::class)
                ->getPublicPayload()['content'] ?? [];
        } catch (\Throwable $e) {
            return;
        }

        if (! is_array($content) || $content === []) {
            return;
        }

        $translator = $this->app->make('translator');

        foreach (self::LOCALES as $locale) {
            $lines = $this->linesFor($content, $locale);

            if ($lines === []) {
                continue;
            }

            // Read the lang file first — see the class docblock.
            $translator->load('*', 'magnoolia', $locale);
            $translator->addLines($lines, $locale);
        }
    }

    /**
     * Overrides for one locale, prefixed for the translator.
     *
     * An empty value is skipped rather than published: blanking a field in admin
     * must fall back to the existing text, not wipe it off the page. The
     * Estonian-fallback rule for untranslated locales lives in mg_text().
     *
     * @param  array<string, mixed>  $content
     * @return array<string, string>
     */
    private function linesFor(array $content, string $locale): array
    {
        $lines = [];

        foreach ($content[$locale] ?? [] as $key => $value) {
            if (is_string($value) && $value !== '') {
                $lines['magnoolia.' . $key] = $value;
            }
        }

        return $lines;
    }
}
