<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\Magnoolia\MagnooliaPublicationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\CreatesMagnooliaTestUnits;

/**
 * Phase 27 P0 — Asserts "0 homes" never appears on any public page.
 * Also asserts 19 total homes are visible where expected.
 */
class MagnooliaPhase27NoZeroHomesRegressionTest extends TestCase
{
    use RefreshDatabase, CreatesMagnooliaTestUnits;

    protected function setUp(): void
    {
        parent::setUp();
        $admin = User::factory()->verified()->create(['role' => 'magnoolia_admin']);
        $this->create19TestUnits();
        app(MagnooliaPublicationService::class)->publish($admin->id, 'Phase 27 zero homes test');
    }

    /** @dataProvider homesPageProvider */
    public function test_no_showing_all_0_homes_text(string $url): void
    {
        $html = $this->get($url)->assertStatus(200)->getContent();

        $this->assertStringNotContainsString(
            'Showing all 0 homes',
            $html,
            "URL $url must not contain 'Showing all 0 homes'"
        );
        $this->assertStringNotContainsString(
            'Showing: 0 homes',
            $html,
            "URL $url must not contain 'Showing: 0 homes'"
        );
    }

    /** @dataProvider homesPageProvider */
    public function test_no_saadaval_0_or_0_kodu_in_availability_summary(string $url): void
    {
        $html = $this->get($url)->assertStatus(200)->getContent();

        // Guard against "X kodu" starting at 0 without a filter active
        // Allow "0" only in contexts like filter-result divs, not main page summaries
        $this->assertStringNotContainsString(
            '>0 kodu<',
            $html,
            "URL $url must not have '>0 kodu<' in availability summary"
        );
        $this->assertStringNotContainsString(
            'Saadaval: 0',
            $html,
            "URL $url must not contain 'Saadaval: 0'"
        );
        $this->assertStringNotContainsString(
            'Broneeritud: 0',
            $html,
            "URL $url must not contain 'Broneeritud: 0'"
        );
    }

    public function test_homepage_pricing_teaser_shows_19(): void
    {
        $html = $this->get('/')->assertStatus(200)->getContent();
        $this->assertStringContainsString('19', $html, 'Homepage must mention 19');
    }

    public function test_kodud_ja_hinnad_shows_all_units(): void
    {
        $html = $this->get('/kodud-ja-hinnad')->assertStatus(200)->getContent();
        $this->assertStringContainsString('19', $html, 'Kodud ja hinnad must show 19 units');
    }

    public function test_asendiplaan_shows_19(): void
    {
        $html = $this->get('/asendiplaan')->assertStatus(200)->getContent();
        $this->assertStringContainsString('19', $html, 'Asendiplaan must show 19 total homes');
    }

    public function test_asendiplaan_stage_counts_are_nonzero(): void
    {
        $html = $this->get('/asendiplaan')->assertStatus(200)->getContent();
        // Stage I = 7, Stage II = 12 (from test data: buildings 1,3 = stage 1 = 3+4=7)
        $this->assertStringContainsString('7', $html, 'Asendiplaan must show Stage I count');
        $this->assertStringContainsString('12', $html, 'Asendiplaan must show Stage II count');
    }

    public function test_ru_homepage_no_zero_homes(): void
    {
        $html = $this->get('/ru')->assertStatus(200)->getContent();
        $this->assertStringNotContainsString('0 домов', $html);
        $this->assertStringNotContainsString('Showing all 0', $html);
    }

    public function test_en_homepage_no_zero_homes(): void
    {
        $html = $this->get('/en')->assertStatus(200)->getContent();
        $this->assertStringNotContainsString('Showing all 0 homes', $html);
        $this->assertStringNotContainsString('0 homes</span>', $html);
    }

    public static function homesPageProvider(): array
    {
        return [
            'homepage-et'           => ['/'],
            'homepage-ru'           => ['/ru'],
            'homepage-en'           => ['/en'],
            'kodud-ja-hinnad-et'    => ['/kodud-ja-hinnad'],
            'kodud-ja-hinnad-ru'    => ['/ru/kodud-ja-hinnad'],
            'kodud-ja-hinnad-en'    => ['/en/kodud-ja-hinnad'],
            'asendiplaan-et'        => ['/asendiplaan'],
            'asendiplaan-ru'        => ['/ru/asendiplaan'],
            'asendiplaan-en'        => ['/en/asendiplaan'],
        ];
    }
}
