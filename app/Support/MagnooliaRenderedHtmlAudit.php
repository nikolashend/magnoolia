<?php

declare(strict_types=1);

namespace App\Support;

use DOMDocument;
use DOMXPath;

final class MagnooliaRenderedHtmlAudit
{
    /**
     * Public URLs that must be validated in rendered HTML.
     *
     * @return array<int, string>
     */
    public static function urls(): array
    {
        return [
            '/', '/kodud-ja-hinnad', '/asendiplaan', '/asukoht', '/ehitusinfo', '/kontakt', '/galerii', '/sisedisain', '/arhitektuur-ja-valisdisain', '/ostuprotsess', '/finantseerimine', '/kkk', '/aitah',
            '/ru', '/ru/kodud-ja-hinnad', '/ru/asendiplaan', '/ru/asukoht', '/ru/ehitusinfo', '/ru/kontakt', '/ru/galerii', '/ru/sisedisain', '/ru/arhitektuur-ja-valisdisain', '/ru/ostuprotsess', '/ru/finantseerimine', '/ru/kkk', '/ru/aitah',
            '/en', '/en/kodud-ja-hinnad', '/en/asendiplaan', '/en/asukoht', '/en/ehitusinfo', '/en/kontakt', '/en/galerii', '/en/sisedisain', '/en/arhitektuur-ja-valisdisain', '/en/ostuprotsess', '/en/finantseerimine', '/en/kkk', '/en/aitah',
        ];
    }

    /**
     * Analyse rendered HTML for blocked strings and broken anchors.
     *
     * @return array<string, mixed>
     */
    public static function analyze(string $url, string $html): array
    {
        $locale = self::localeFromUrl($url);
        $forbidden = self::forbiddenStrings($locale);
        $forbiddenFound = [];
        foreach ($forbidden as $needle) {
            if ($needle !== '' && stripos($html, $needle) !== false) {
                $forbiddenFound[] = $needle;
            }
        }

        [$duplicateIds, $brokenAnchors] = self::domChecks($html);

        $templateResidue = [];
        foreach (['happy-client', 'avater', 'avatar', 'icon-star', 'fake client', 'View not found', 'Internal Server Error', 'Stack trace'] as $needle) {
            if (stripos($html, $needle) !== false) {
                $templateResidue[] = $needle;
            }
        }

        return [
            'url' => $url,
            'locale' => $locale,
            'forbidden_found' => $forbiddenFound,
            'duplicate_ids' => $duplicateIds,
            'broken_anchors' => $brokenAnchors,
            'template_residue' => $templateResidue,
            'title' => self::extractMeta($html, 'title'),
            'description' => self::extractMeta($html, 'description'),
            'canonical' => self::extractLink($html, 'canonical'),
            'og_locale' => self::extractMetaProperty($html, 'og:locale'),
            'schema_in_language' => self::extractSchemaInLanguage($html),
        ];
    }

    /**
     * @return array<int, string>
     */
    public static function forbiddenStrings(string $locale): array
    {
        return [
            'magnoolia.adme.ee',
        ];
    }

    /**
     * @return array{0: array<int, string>, 1: array<int, string>}
     */
    private static function domChecks(string $html): array
    {
        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML('<?xml encoding="utf-8" ?>'.$html);
        libxml_clear_errors();

        $xpath = new DOMXPath($dom);
        $ids = [];
        foreach ($xpath->query('//*[@id]') as $node) {
            $id = trim((string) $node->attributes?->getNamedItem('id')?->nodeValue);
            if ($id !== '') {
                $ids[] = $id;
            }
        }

        $duplicateIds = array_values(array_unique(array_filter(array_keys(array_count_values($ids)), static fn (string $id) => in_array($id, $ids, true) && count(array_keys($ids, $id, true)) > 1)));

        $knownIds = array_fill_keys($ids, true);
        $brokenAnchors = [];
        foreach ($xpath->query('//a[@href]') as $node) {
            $href = trim((string) $node->attributes?->getNamedItem('href')?->nodeValue);
            if ($href === '' || $href === '#' || strcasecmp($href, 'javascript:void(0)') === 0) {
                $brokenAnchors[] = $href === '' ? '(empty)' : $href;
                continue;
            }
            if ($href[0] === '#') {
                $target = substr($href, 1);
                if ($target === '' || ! isset($knownIds[$target])) {
                    $brokenAnchors[] = $href;
                }
            }
        }

        return [array_values(array_unique($duplicateIds)), array_values(array_unique($brokenAnchors))];
    }

    private static function localeFromUrl(string $url): string
    {
        return match (true) {
            str_starts_with($url, '/ru') => 'ru',
            str_starts_with($url, '/en') => 'en',
            default => 'et',
        };
    }

    private static function extractMeta(string $html, string $name): ?string
    {
        if (preg_match('/<meta[^>]+name="'.preg_quote($name, '/').'"[^>]+content="([^"]*)"/i', $html, $m)) {
            return html_entity_decode($m[1], ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }

        if ($name === 'title' && preg_match('/<title>(.*?)<\/title>/is', $html, $m)) {
            return html_entity_decode(trim($m[1]), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }

        return null;
    }

    private static function extractMetaProperty(string $html, string $property): ?string
    {
        if (preg_match('/<meta[^>]+property="'.preg_quote($property, '/').'"[^>]+content="([^"]*)"/i', $html, $m)) {
            return html_entity_decode($m[1], ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }

        return null;
    }

    private static function extractLink(string $html, string $rel): ?string
    {
        if (preg_match('/<link[^>]+rel="'.preg_quote($rel, '/').'"[^>]+href="([^"]*)"/i', $html, $m)) {
            return html_entity_decode($m[1], ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }

        return null;
    }

    private static function extractSchemaInLanguage(string $html): ?string
    {
        if (preg_match('/"inLanguage"\s*:\s*"([^"]+)"/i', $html, $m)) {
            return $m[1];
        }

        return null;
    }
}
