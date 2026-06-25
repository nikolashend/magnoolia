<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Phase 33.3 — public no-regression: the admin UX changes must not change the
 * public site. Pages render 200 in ET/RU/EN and never leak internal data.
 */
class MagnooliaPhase333NoRegressionTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_pages_render_200_in_all_locales(): void
    {
        $paths = ['/', '/kodud-ja-hinnad', '/asendiplaan', '/asukoht', '/galerii', '/kontakt'];
        foreach ($paths as $p) {
            $this->get($p)->assertOk();
            $this->get('/ru' . ($p === '/' ? '' : $p))->assertOk();
            $this->get('/en' . ($p === '/' ? '' : $p))->assertOk();
        }
    }

    public function test_public_pages_do_not_leak_internal_data(): void
    {
        foreach (['/', '/kodud-ja-hinnad', '/galerii'] as $p) {
            $html = $this->get($p)->assertOk()->getContent();
            $this->assertStringNotContainsString('price_cents', $html);
            $this->assertStringNotContainsString('/source/', $html);
            $this->assertStringNotContainsString('.pptx', $html);
            $this->assertStringNotContainsString('1drv.ms', $html);
            // No unresolved lang keys leaking as bare text.
            $this->assertDoesNotMatchRegularExpression('/magnoolia\.(page|nav|hero|footer|section|pricing)\.[a-z0-9_]+/', $html);
        }
    }

    public function test_sitemap_excludes_admin(): void
    {
        $xml = $this->get('/sitemap.xml')->assertOk()->getContent();
        $this->assertStringNotContainsString('/admin', $xml);
    }
}
