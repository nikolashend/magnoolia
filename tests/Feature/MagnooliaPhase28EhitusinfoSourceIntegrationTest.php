<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * Phase 28 — Ehitusinfo source integration test
 * Verifies that the Ehitusinfo page now has material specifications from Excel data.
 */
class MagnooliaPhase28EhitusinfoSourceIntegrationTest extends TestCase
{
    public function test_ehitusinfo_has_materials_section(): void
    {
        $response = $this->get('/ehitusinfo');
        $response->assertStatus(200);
        $html = $response->getContent();

        // Phase 28 added material specifications section with id="materjalid"
        $this->assertStringContainsString('materjalid', $html,
            'Ehitusinfo must have "materjalid" section id');
    }

    public function test_ehitusinfo_has_tile_spec(): void
    {
        $response = $this->get('/ehitusinfo');
        $response->assertStatus(200);
        $html = $response->getContent();

        // Tile sizes from Plaadid maht.xlsx
        $this->assertStringContainsString('60×120', $html,
            'Ehitusinfo must show wall tile size 60×120 cm from Excel data');
        $this->assertStringContainsString('60×60', $html,
            'Ehitusinfo must show floor tile size 60×60 cm from Excel data');
    }

    public function test_ehitusinfo_has_cross_link_to_sisedisain(): void
    {
        $response = $this->get('/ehitusinfo');
        $response->assertStatus(200);
        $html = $response->getContent();

        $this->assertStringContainsString('sisedisain', $html,
            'Ehitusinfo must link to Sisedisain page');
    }

    public function test_ehitusinfo_ru_has_russian_material_labels(): void
    {
        $response = $this->get('/ru/ehitusinfo');
        $response->assertStatus(200);
        $html = $response->getContent();

        $this->assertStringContainsString('Материал', $html,
            'RU Ehitusinfo must show Russian material section heading');
    }

    public function test_ehitusinfo_has_buyer_note_disclaimer(): void
    {
        $response = $this->get('/ehitusinfo');
        $response->assertStatus(200);
        $html = $response->getContent();

        // Must have the required technical/finish disclaimer
        $this->assertStringContainsString('täpsustatakse', $html,
            'Ehitusinfo must have "täpsustatakse" disclaimer about finalization in sales offer');
    }

    public function test_excel_extraction_report_exists(): void
    {
        $this->assertFileExists(base_path('docs/magnoolia-phase28-excel-content-report.md'),
            'Excel extraction report must exist after Phase 28 processing');
    }
}
