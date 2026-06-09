<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\Magnoolia\MagnooliaPublicationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\CreatesMagnooliaTestUnits;

/**
 * Phase 27 — Footer and contact: no placeholder data, real Diana Tali contact.
 */
class MagnooliaPhase27FooterContactIntegrityTest extends TestCase
{
    use RefreshDatabase, CreatesMagnooliaTestUnits;

    private array $pages;

    protected function setUp(): void
    {
        parent::setUp();
        $admin = User::factory()->verified()->create(['role' => 'magnoolia_admin']);
        $this->create19TestUnits();
        app(MagnooliaPublicationService::class)->publish($admin->id, 'Phase 27 footer test');

        $this->pages = ['/', '/kodud-ja-hinnad', '/asendiplaan', '/asukoht', '/kontakt'];
    }

    public function test_no_placeholder_phone_on_any_page(): void
    {
        foreach ($this->pages as $url) {
            $html = $this->get($url)->assertStatus(200)->getContent();
            $this->assertStringNotContainsString('+372 000 0000', $html, "Page $url: no placeholder phone");
        }
    }

    public function test_no_info_at_magnoolia_email_on_any_page(): void
    {
        foreach ($this->pages as $url) {
            $html = $this->get($url)->assertStatus(200)->getContent();
            $this->assertStringNotContainsString('info@magnoolia.ee', $html, "Page $url: no placeholder email");
        }
    }

    public function test_no_lorem_ipsum_on_any_page(): void
    {
        foreach ($this->pages as $url) {
            $html = $this->get($url)->assertStatus(200)->getContent();
            $this->assertStringNotContainsString('Lorem ipsum', $html, "Page $url: no Lorem ipsum");
        }
    }

    public function test_pages_have_diana_phone(): void
    {
        foreach ($this->pages as $url) {
            $html = $this->get($url)->assertStatus(200)->getContent();
            $this->assertTrue(
                str_contains($html, '58164078') || str_contains($html, '58 16 40 78'),
                "Page $url must contain Diana's real phone number"
            );
        }
    }

    public function test_pages_have_diana_email(): void
    {
        foreach ($this->pages as $url) {
            $html = $this->get($url)->assertStatus(200)->getContent();
            $this->assertStringContainsString('diana@estlanda.ee', $html, "Page $url must have Diana's email");
        }
    }

    public function test_mobile_nav_has_diana_phone(): void
    {
        $html = $this->get('/')->assertStatus(200)->getContent();
        $this->assertTrue(
            str_contains($html, '58164078') || str_contains($html, '58 16 40 78'),
            'Mobile nav must contain Diana real phone number'
        );
    }

    public function test_contact_page_no_placeholder_contact(): void
    {
        $html = $this->get('/kontakt')->assertStatus(200)->getContent();
        $this->assertStringNotContainsString('+372 000 0000', $html);
        $this->assertStringNotContainsString('info@magnoolia.ee', $html);
    }
}
