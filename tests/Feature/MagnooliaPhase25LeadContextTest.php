<?php

namespace Tests\Feature;

use App\Models\MagnooliaLead;
use App\Models\MagnooliaUnit;
use App\Models\User;
use App\Services\Magnoolia\MagnooliaPublicationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\CreatesMagnooliaTestUnits;

/**
 * Phase 25 — Lead context: unit_key, published_version, source_component stored.
 */
class MagnooliaPhase25LeadContextTest extends TestCase
{
    use RefreshDatabase, CreatesMagnooliaTestUnits;

    private User $admin;
    private MagnooliaPublicationService $pubService;
    private int $publishedVersion;

    public function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->verified()->create(['role' => 'magnoolia_admin']);
        $this->pubService = app(MagnooliaPublicationService::class);
        $this->create19TestUnits();
        $result = $this->pubService->publish($this->admin->id, 'Lead context test');
        $this->publishedVersion = $result['publication']->version;
    }

    private function validFormData(array $overrides = []): array
    {
        return array_merge([
            'name'             => 'Test Lead',
            'email'            => 'test@example.com',
            'phone'            => '+37255555555',
            'message'          => 'Interested in the unit',
            'selected_unit'    => 'Magnoolia tee 1/1',
            'source_component' => 'unit_page_hero',
            'consent'          => '1',
            'website'          => '',
        ], $overrides);
    }

    /** @test */
    public function contact_form_stores_unit_key_in_lead()
    {
        $this->post('/kontakt', $this->validFormData())->assertRedirect();

        $lead = MagnooliaLead::latest()->first();
        $this->assertNotNull($lead);
        $this->assertEquals('B1-S1', $lead->unit_key);
    }

    /** @test */
    public function contact_form_stores_published_version()
    {
        $this->post('/kontakt', $this->validFormData())->assertRedirect();

        $lead = MagnooliaLead::latest()->first();
        $this->assertNotNull($lead);
        $this->assertEquals($this->publishedVersion, $lead->published_version);
    }

    /** @test */
    public function contact_form_stores_source_component()
    {
        $this->post('/kontakt', $this->validFormData([
            'source_component' => 'asendiplaan_side_panel',
        ]))->assertRedirect();

        $lead = MagnooliaLead::latest()->first();
        $this->assertNotNull($lead);
        $this->assertEquals('asendiplaan_side_panel', $lead->source_component);
    }

    /** @test */
    public function contact_form_does_not_store_hidden_price()
    {
        // Stage II unit with hidden price
        $hiddenUnit = MagnooliaUnit::where('unit_key', 'B5-S1')->first();
        $hiddenUnit->update(['price_cents' => 48000000, 'price_public' => false]);
        $this->pubService->publish($this->admin->id, 'Hidden price lead test');

        $this->post('/kontakt', $this->validFormData([
            'selected_unit' => 'Magnoolia tee 5/1',
        ]))->assertRedirect();

        $lead = MagnooliaLead::latest()->first();
        $this->assertNotNull($lead);
        // price_public_at_submission should be false — meaning price is NOT public
        $this->assertFalse((bool) $lead->price_public_at_submission);
    }

    /** @test */
    public function unit_page_contact_link_includes_unit_address_in_query()
    {
        $response = $this->get('/kodud/b1-s1');
        $response->assertOk();
        $content = $response->getContent();
        // CTA must link to contact with unit param
        $this->assertStringContainsString('unit=', $content);
        $this->assertStringContainsString('source_component=unit_page_hero', $content);
    }

    /** @test */
    public function asendiplaan_side_panel_contact_link_has_source_component()
    {
        $response = $this->get('/asendiplaan');
        $response->assertOk();
        $content = $response->getContent();
        $this->assertStringContainsString('asendiplaan_side_panel', $content);
    }

    /** @test */
    public function contact_page_with_unit_param_returns_200()
    {
        $response = $this->get('/kontakt?unit=Magnoolia+tee+1%2F1&source_component=unit_page_hero');
        $response->assertOk();
    }
}
