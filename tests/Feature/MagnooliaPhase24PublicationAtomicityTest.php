<?php

namespace Tests\Feature;

use App\Models\MagnooliaPublication;
use App\Models\MagnooliaUnit;
use App\Models\User;
use App\Services\Magnoolia\MagnooliaPublicationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\CreatesMagnooliaTestUnits;

class MagnooliaPhase24PublicationAtomicityTest extends TestCase
{
    use RefreshDatabase, CreatesMagnooliaTestUnits;

    protected User $admin;
    protected MagnooliaPublicationService $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->verified()->create(['role' => 'magnoolia_admin']);
        $this->service = app(MagnooliaPublicationService::class);

        // Seed 19 canonical units (required by validation service)
        $this->create19TestUnits();
    }

    /**
     * @test
     * Publication creates immutable snapshot
     */
    public function publication_creates_immutable_version()
    {
        $before = MagnooliaPublication::count();

        $result = $this->service->publish($this->admin->id, 'Test publication');

        $after = MagnooliaPublication::count();

        $this->assertTrue($result['ok']);
        $this->assertGreaterThan($before, $after);
        $this->assertNotNull($result['publication']->id);
        $this->assertEquals('active', $result['publication']->status);
    }

    /**
     * @test
     * Previous publication becomes inactive when new one published
     */
    public function previous_active_publication_becomes_inactive()
    {
        // Create v1
        $v1Result = $this->service->publish($this->admin->id, 'Version 1');
        $this->assertTrue($v1Result['ok']);
        $v1 = $v1Result['publication'];

        // Change a unit to force different checksum
        MagnooliaUnit::where('unit_key', 'B1-S1')->update(['price_cents' => 600000]);

        // Create v2
        $v2Result = $this->service->publish($this->admin->id, 'Version 2');
        $this->assertTrue($v2Result['ok']);

        $v1->refresh();
        $this->assertEquals('inactive', $v1->status);
    }

    /**
     * @test
     * Publication includes checksum for integrity
     */
    public function publication_has_valid_checksum()
    {
        $result = $this->service->publish($this->admin->id, 'Checksum test');

        $this->assertTrue($result['ok']);
        $this->assertNotNull($result['publication']->draft_checksum);
        $this->assertEquals(64, strlen($result['publication']->draft_checksum)); // SHA-256 hex = 64 chars
    }

    /**
     * @test
     * Duplicate publish is rejected (same checksum)
     */
    public function duplicate_publish_is_rejected()
    {
        $v1Result = $this->service->publish($this->admin->id, 'Version 1');
        $this->assertTrue($v1Result['ok']);

        // Attempt to publish same data without changes
        $v2Result = $this->service->publish($this->admin->id, 'Version 2 (same data)');
        $this->assertFalse($v2Result['ok']);
        $this->assertTrue($v2Result['duplicate'] ?? false);
    }

    /**
     * @test
     * Publication returns validation results
     */
    public function publication_includes_validation_results()
    {
        $result = $this->service->publish($this->admin->id, 'Validation test');

        $this->assertArrayHasKey('validation', $result);
        $this->assertArrayHasKey('blockers', $result['validation']);
        $this->assertArrayHasKey('warnings', $result['validation']);
    }

    /**
     * @test
     * Published version is accessible on all locales
     */
    public function publication_available_on_all_locales()
    {
        $this->service->publish($this->admin->id, 'i18n test');

        // Check ET
        $responseET = $this->get('/kodud-ja-hinnad');
        $responseET->assertSuccessful();

        // Check RU
        $responseRU = $this->get('/ru/kodud-ja-hinnad');
        $responseRU->assertSuccessful();

        // Check EN
        $responseEN = $this->get('/en/kodud-ja-hinnad');
        $responseEN->assertSuccessful();
    }

    /**
     * @test
     * Publication history is maintained (versions accumulate)
     */
    public function publication_history_preserved()
    {
        // Publish v1
        $r1 = $this->service->publish($this->admin->id, 'v1');
        $this->assertTrue($r1['ok']);

        // Change data for v2
        MagnooliaUnit::where('unit_key', 'B1-S1')->update(['price_cents' => 600000]);
        $r2 = $this->service->publish($this->admin->id, 'v2');
        $this->assertTrue($r2['ok']);

        // Change data for v3
        MagnooliaUnit::where('unit_key', 'B1-S1')->update(['price_cents' => 700000]);
        $r3 = $this->service->publish($this->admin->id, 'v3');
        $this->assertTrue($r3['ok']);

        // All three versions must exist
        $this->assertEquals(3, MagnooliaPublication::count());
    }

    /**
     * @test
     * Publication records author (published_by)
     */
    public function publication_author_recorded()
    {
        $result = $this->service->publish($this->admin->id, 'Authored publication');

        $this->assertTrue($result['ok']);
        $this->assertEquals($this->admin->id, $result['publication']->published_by);
    }

    /**
     * @test
     * Published public payload hides Stage II prices where price_public=false
     */
    public function publication_hides_stage2_prices_when_not_public()
    {
        // B5-S1 is a Stage 2 unit (stage = $building <= 3 ? 1 : 2)
        // Set it to price_public = false
        MagnooliaUnit::where('unit_key', 'B5-S1')->update([
            'price_cents' => 750000,
            'price_public' => false, // Hidden!
        ]);

        $result = $this->service->publish($this->admin->id, 'Hidden price test');
        $this->assertTrue($result['ok']);

        $publication = $result['publication'];
        $publicPayload = json_decode($publication->public_payload_json, true);

        // Find the hidden unit in public payload
        $hiddenUnit = collect($publicPayload['units'])->firstWhere('unit_key', 'B5-S1');
        $this->assertNotNull($hiddenUnit);

        // Price should be null in public payload
        $this->assertNull($hiddenUnit['price']);
    }
}