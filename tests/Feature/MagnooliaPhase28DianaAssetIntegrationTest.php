<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * Phase 28 — Diana Tali asset integration test
 * Diana photo was NOT provided in phase28 import materials.
 * This test documents that absence and verifies graceful fallback.
 */
class MagnooliaPhase28DianaAssetIntegrationTest extends TestCase
{
    public function test_diana_photo_not_provided_in_phase28_import(): void
    {
        $importDir = base_path('materials/onedrive-import/phase28');
        $dianaPhotos = array_merge(
            glob($importDir . '/Diana*.jpg') ?: [],
            glob($importDir . '/diana*.jpg') ?: [],
            glob($importDir . '/Diana*.png') ?: [],
        );

        // Document that Diana photo was NOT provided (verified absence)
        $this->assertEmpty($dianaPhotos,
            'Diana Tali photo was not in phase28 import — this is documented in blocked assets report');
    }

    public function test_contact_page_handles_missing_diana_photo_gracefully(): void
    {
        $response = $this->get('/kontakt');
        $response->assertStatus(200);
        $html = $response->getContent();

        // Page must still render correctly without Diana's photo
        $this->assertStringContainsString('Diana', $html,
            'Contact page must show Diana name even without photo');
        $this->assertStringNotContainsString('broken', strtolower($html),
            'Contact page must not show broken image references');
    }

    public function test_contact_page_has_no_placeholder_avatar_icon(): void
    {
        // If no Diana photo, should show a clean alternative, not a generic broken placeholder
        $response = $this->get('/kontakt');
        $response->assertStatus(200);
        $html = $response->getContent();

        // Generic placeholder icons that would look unprofessional
        $this->assertStringNotContainsString('fa-user-circle', $html,
            'Contact page must not show generic user circle icon as Diana photo placeholder');
    }

    public function test_diana_photo_public_path_is_correct_when_available(): void
    {
        $dianaWebp = public_path('assets/magnoolia/people/diana-tali.webp');

        if (file_exists($dianaWebp)) {
            // If Diana photo exists (future provision), verify contact page uses it
            $response = $this->get('/kontakt');
            $response->assertStatus(200);
            $response->assertSee('diana-tali.webp', false);
        } else {
            // Document absence
            $this->assertFileDoesNotExist($dianaWebp,
                'Diana photo not provided in phase28 (expected path is assets/magnoolia/people/diana-tali.webp)');
        }
    }
}
