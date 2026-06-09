<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\Magnoolia\MagnooliaPublicationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\CreatesMagnooliaTestUnits;

/**
 * Phase 27 — Language purity: no ET headings on RU pages, no hardcoded strings.
 */
class MagnooliaPhase27LanguagePurityTest extends TestCase
{
    use RefreshDatabase, CreatesMagnooliaTestUnits;

    protected function setUp(): void
    {
        parent::setUp();
        $admin = User::factory()->verified()->create(['role' => 'magnoolia_admin']);
        $this->create19TestUnits();
        app(MagnooliaPublicationService::class)->publish($admin->id, 'Phase 27 language purity test');
    }

    public function test_ru_pages_do_not_have_et_cta_text(): void
    {
        $ruPages = ['/ru', '/ru/kodud-ja-hinnad', '/ru/asendiplaan'];
        $etOnlyHeadings = ['Küsi pakkumist', 'Vaata kodusid'];

        foreach ($ruPages as $url) {
            $html = $this->get($url)->assertStatus(200)->getContent();
            foreach ($etOnlyHeadings as $heading) {
                $this->assertStringNotContainsString(
                    $heading,
                    $html,
                    "RU page $url must not contain ET-only heading: $heading"
                );
            }
        }
    }

    public function test_en_pages_return_200(): void
    {
        $enPages = [
            '/en', '/en/kodud-ja-hinnad', '/en/asendiplaan',
            '/en/asukoht', '/en/galerii', '/en/kontakt',
        ];
        foreach ($enPages as $url) {
            $this->get($url)->assertStatus(200, "EN page $url must return 200");
        }
    }

    public function test_ru_pages_contain_russian_text(): void
    {
        $html = $this->get('/ru')->assertStatus(200)->getContent();
        // Russian pages must have at least one Cyrillic character in the body
        $this->assertMatchesRegularExpression('/[\x{0400}-\x{04FF}]/u', $html, 'RU homepage must contain Cyrillic text');
    }

    public function test_et_homepage_does_not_contain_unexpected_russian(): void
    {
        $html = $this->get('/')->assertStatus(200)->getContent();
        // ET pages shouldn't have Cyrillic headings (minor CTA strings like brand names are OK in transliteration,
        // but full Cyrillic sentences in headings indicate language leakage)
        $cyrillicCount = preg_match_all('/[\x{0400}-\x{04FF}]{5,}/u', $html, $matches);
        $this->assertLessThan(
            5,
            $cyrillicCount,
            'ET homepage must not contain significant Cyrillic text (max 4 isolated occurrences)'
        );
    }

    public function test_en_pages_do_not_contain_et_only_phrases(): void
    {
        $enPages = ['/en', '/en/kodud-ja-hinnad'];
        $etPhrases = ['Küsi pakkumist', 'Vaata kodusid'];

        foreach ($enPages as $url) {
            $html = $this->get($url)->assertStatus(200)->getContent();
            foreach ($etPhrases as $phrase) {
                $this->assertStringNotContainsString(
                    $phrase,
                    $html,
                    "EN page $url must not contain ET phrase: $phrase"
                );
            }
        }
    }

    public function test_lang_html_attribute_set_correctly(): void
    {
        $langMap = [
            '/'    => 'et',
            '/ru'  => 'ru',
            '/en'  => 'en',
        ];

        foreach ($langMap as $url => $expectedLang) {
            $html = $this->get($url)->assertStatus(200)->getContent();
            $this->assertTrue(
                str_contains($html, 'lang="' . $expectedLang . '"') || str_contains($html, "lang='" . $expectedLang . "'"),
                "Page $url must have <html lang=\"$expectedLang\">"
            );
        }
    }
}
