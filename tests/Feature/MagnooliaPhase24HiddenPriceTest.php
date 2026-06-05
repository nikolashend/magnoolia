<?php

namespace Tests\Feature;

use App\Models\MagnooliaUnit;
use App\Models\User;
use App\Services\Magnoolia\MagnooliaPublicationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\CreatesMagnooliaTestUnits;

class MagnooliaPhase24HiddenPriceTest extends TestCase
{
    use RefreshDatabase, CreatesMagnooliaTestUnits;

    protected User $admin;
    protected MagnooliaPublicationService $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->verified()->create(['role' => 'magnoolia_admin']);
        $this->service = app(MagnooliaPublicationService::class);
        $this->create19TestUnits();
    }

    /** @test */
    public function hidden_prices_absent_from_published_payload()
    {
        MagnooliaUnit::where('unit_key', 'B5-S1')->update(['price_cents' => 500000, 'price_public' => false]);
        $result = $this->service->publish($this->admin->id, 'Hidden price test');
        $this->assertTrue($result['ok']);
        $publicPayload = json_decode($result['publication']->public_payload_json, true);
        $unit = collect($publicPayload['units'])->firstWhere('unit_key', 'B5-S1');
        $this->assertNotNull($unit);
        $this->assertNull($unit['price']);
    }

    /** @test */
    public function visible_price_appears_in_published_payload()
    {
        MagnooliaUnit::where('unit_key', 'B1-S1')->update(['price_cents' => 550000, 'price_public' => true]);
        $result = $this->service->publish($this->admin->id, 'Visible price test');
        $this->assertTrue($result['ok']);
        $publicPayload = json_decode($result['publication']->public_payload_json, true);
        $unit = collect($publicPayload['units'])->firstWhere('unit_key', 'B1-S1');
        $this->assertNotNull($unit);
        $this->assertEquals(5500, $unit['price']);
    }

    /** @test */
    public function admin_can_see_hidden_price_unit_in_panel()
    {
        MagnooliaUnit::where('unit_key', 'B5-S1')->update(['price_public' => false]);
        $response = $this->actingAs($this->admin)->get('/admin/magnoolia/units');
        $response->assertSuccessful();
        $this->assertStringContainsString('B5-S1', $response->getContent());
    }

    /** @test */
    public function hidden_prices_absent_from_public_pages()
    {
        MagnooliaUnit::where('unit_key', 'B5-S1')->update(['price_cents' => 800000, 'price_public' => false]);
        $r = $this->service->publish($this->admin->id, 'Hidden price public test');
        $this->assertTrue($r['ok']);
        $response = $this->get('/kodud-ja-hinnad');
        $response->assertSuccessful();
        $this->assertStringNotContainsString('800000', $response->getContent());
    }

    /** @test */
    public function hidden_price_respected_on_all_locales()
    {
        MagnooliaUnit::where('unit_key', 'B5-S1')->update(['price_cents' => 999999, 'price_public' => false]);
        $r = $this->service->publish($this->admin->id, 'i18n hidden price test');
        $this->assertTrue($r['ok']);
        $this->get('/kodud-ja-hinnad')->assertSuccessful();
        $this->assertStringNotContainsString('999999', $this->get('/kodud-ja-hinnad')->getContent(), 'Price leaked on locale: et');
        $this->get('/ru/kodud-ja-hinnad')->assertSuccessful();
        $this->assertStringNotContainsString('999999', $this->get('/ru/kodud-ja-hinnad')->getContent(), 'Price leaked on locale: ru');
        $this->get('/en/kodud-ja-hinnad')->assertSuccessful();
        $this->assertStringNotContainsString('999999', $this->get('/en/kodud-ja-hinnad')->getContent(), 'Price leaked on locale: en');
    }

    /** @test */
    public function private_snapshot_preserves_hidden_prices()
    {
        MagnooliaUnit::where('unit_key', 'B5-S1')->update(['price_cents' => 650000, 'price_public' => false]);
        $result = $this->service->publish($this->admin->id, 'Snapshot price test');
        $this->assertTrue($result['ok']);
        $privateSnapshot = json_decode($result['publication']->private_snapshot_json, true);
        $unit = collect($privateSnapshot['units'])->firstWhere('unit_key', 'B5-S1');
        $this->assertNotNull($unit);
        $this->assertEquals(650000, $unit['price_cents']);
    }

    /** @test */
    public function toggling_price_visibility_takes_effect_on_next_publish()
    {
        MagnooliaUnit::where('unit_key', 'B1-S1')->update(['price_cents' => 700000, 'price_public' => true]);
        $r1 = $this->service->publish($this->admin->id, 'Toggle v1');
        $this->assertTrue($r1['ok']);
        $p1 = json_decode($r1['publication']->public_payload_json, true);
        $u1 = collect($p1['units'])->firstWhere('unit_key', 'B1-S1');
        $this->assertEquals(7000, $u1['price']);
        MagnooliaUnit::where('unit_key', 'B1-S1')->update(['price_cents' => 710000, 'price_public' => false]);
        $r2 = $this->service->publish($this->admin->id, 'Toggle v2');
        $this->assertTrue($r2['ok']);
        $p2 = json_decode($r2['publication']->public_payload_json, true);
        $u2 = collect($p2['units'])->firstWhere('unit_key', 'B1-S1');
        $this->assertNull($u2['price']);
    }
}
