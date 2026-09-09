<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Short paths that were returning 404 to Google, and the consent link that
 * pointed at one of them.
 *
 * /arhitektuur, /arendaja and /privaatsuspoliitika were all live or linked at
 * some point. The first two were plain 404s in Search Console; the third was
 * worse than an SEO problem — it sat next to the data-processing consent on the
 * enquiry form, so a visitor agreed to a policy the site would not show them.
 */
class MagnooliaLegacyPathRedirectsTest extends TestCase
{
    use RefreshDatabase;

    /** @return array<string, array{string, string}> */
    public static function aliases(): array
    {
        return [
            'arhitektuur et'         => ['/arhitektuur', '/arhitektuur-ja-valisdisain'],
            'arhitektuur ru'         => ['/ru/arhitektuur', '/ru/arhitektuur-ja-valisdisain'],
            'arhitektuur en'         => ['/en/arhitektuur', '/en/arhitektuur-ja-valisdisain'],
            'arendaja et'            => ['/arendaja', '/arendajast'],
            'arendaja ru'            => ['/ru/arendaja', '/ru/arendajast'],
            'arendaja en'            => ['/en/arendaja', '/en/arendajast'],
            'privaatsuspoliitika et' => ['/privaatsuspoliitika', '/privaatsus'],
            'privaatsuspoliitika ru' => ['/ru/privaatsuspoliitika', '/ru/privaatsus'],
            'privaatsuspoliitika en' => ['/en/privaatsuspoliitika', '/en/privaatsus'],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('aliases')]
    public function test_the_legacy_path_redirects_permanently(string $from, string $to): void
    {
        // 301, not 302: a temporary redirect hands over no ranking signal, which is
        // the whole point of fixing these.
        $this->get($from)->assertStatus(301)->assertRedirect($to);
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('aliases')]
    public function test_the_destination_is_a_real_page(string $from, string $to): void
    {
        $this->get($to)->assertOk();
    }

    public function test_no_alias_is_listed_in_the_sitemap(): void
    {
        // A sitemap must list canonical pages, never redirects.
        $xml = $this->get('/sitemap.xml')->assertOk()->getContent();

        foreach (array_column(self::aliases(), 0) as $alias) {
            $this->assertStringNotContainsString(
                $alias . '</loc>',
                $xml,
                "The sitemap lists the redirecting {$alias}."
            );
        }
    }

    public function test_the_consent_link_points_at_the_privacy_page_in_each_language(): void
    {
        foreach (['/kontakt' => '/privaatsus', '/ru/kontakt' => '/ru/privaatsus', '/en/kontakt' => '/en/privaatsus'] as $page => $expected) {
            $html = $this->get($page)->assertOk()->getContent();

            $this->assertStringNotContainsString('privaatsuspoliitika', $html,
                "{$page} still links to the path that does not exist.");
            $this->assertStringContainsString('href="' . $expected . '"', $html,
                "{$page} does not link to its own language's privacy page.");
        }
    }

    public function test_arendajast_is_in_the_sitemap(): void
    {
        // The page was live in all three languages but missing from the sitemap.
        $xml = $this->get('/sitemap.xml')->assertOk()->getContent();

        foreach (['/arendajast', '/ru/arendajast', '/en/arendajast'] as $path) {
            $this->assertStringContainsString($path . '</loc>', $xml);
        }
    }
}
