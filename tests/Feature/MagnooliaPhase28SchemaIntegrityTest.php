<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * Phase 28 — Schema.org integrity test
 * Verifies JSON-LD schema is present and valid on key pages.
 */
class MagnooliaPhase28SchemaIntegrityTest extends TestCase
{
    private array $pages = ['/', '/sisedisain', '/ehitusinfo', '/kontakt'];

    public function test_schema_present_on_key_pages(): void
    {
        foreach ($this->pages as $url) {
            $response = $this->get($url);
            $response->assertStatus(200);
            $html = $response->getContent();

            $this->assertStringContainsString('application/ld+json', $html,
                "Page {$url} must have JSON-LD schema");
        }
    }

    public function test_schema_does_not_have_aggregate_rating(): void
    {
        foreach ($this->pages as $url) {
            $response = $this->get($url);
            $response->assertStatus(200);
            $html = $response->getContent();

            $this->assertStringNotContainsString('AggregateRating', $html,
                "Page {$url} must not have AggregateRating schema (no fake reviews)");
        }
    }

    public function test_schema_does_not_have_fake_offer_price(): void
    {
        // Offer schema with specific prices is not published until price list is confirmed
        $response = $this->get('/');
        $response->assertStatus(200);
        $html = $response->getContent();

        // Allow Offer schema but not with specific price amounts in JSON-LD
        if (preg_match_all('/application\/ld\+json[^>]*>(.*?)<\/script>/si', $html, $matches)) {
            foreach ($matches[1] as $jsonContent) {
                $decoded = json_decode($jsonContent, true);
                if ($decoded) {
                    $jsonString = json_encode($decoded);
                    $this->assertStringNotContainsString('"priceSpecification"', $jsonString,
                        'JSON-LD must not contain priceSpecification with specific prices');
                }
            }
        }
    }

    public function test_faqpage_schema_on_relevant_pages(): void
    {
        $faqPages = ['/sisedisain', '/ehitusinfo', '/kkk'];

        foreach ($faqPages as $url) {
            $response = $this->get($url);
            $response->assertStatus(200);
            $html = $response->getContent();

            $this->assertStringContainsString('FAQPage', $html,
                "Page {$url} must have FAQPage schema");
        }
    }

    public function test_breadcrumb_schema_on_inner_pages(): void
    {
        $innerPages = ['/sisedisain', '/ehitusinfo', '/kontakt'];

        foreach ($innerPages as $url) {
            $response = $this->get($url);
            $response->assertStatus(200);
            $html = $response->getContent();

            $this->assertStringContainsString('BreadcrumbList', $html,
                "Page {$url} must have BreadcrumbList schema");
        }
    }
}
