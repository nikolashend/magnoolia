<?php

namespace App\Services\Magnoolia;

use App\Models\MagnooliaSetting;
use App\Models\MagnooliaUnit;

class MagnooliaValidationService
{
    public function validateDraft(): array
    {
        $units = MagnooliaUnit::query()->orderBy('sort_order')->get();
        $settings = MagnooliaSetting::query()->latest('id')->first();

        $errors = [
            'blockers' => [],
            'warnings' => [],
            'info' => [],
        ];

        if ($units->count() !== 19) {
            $errors['blockers'][] = 'Units count must be exactly 19.';
        }

        if ($units->pluck('unit_key')->filter()->count() !== $units->pluck('unit_key')->filter()->unique()->count()) {
            $errors['blockers'][] = 'Duplicate unit_key found.';
        }

        if ($units->pluck('slug')->filter()->count() !== $units->pluck('slug')->filter()->unique()->count()) {
            $errors['blockers'][] = 'Duplicate slug found.';
        }

        if ($units->pluck('address')->filter()->count() !== $units->pluck('address')->filter()->unique()->count()) {
            $errors['blockers'][] = 'Duplicate address found.';
        }

        $distribution = $units->groupBy('building_number')->map->count()->toArray();
        $expected = [1 => 3, 3 => 4, 5 => 3, 7 => 3, 9 => 3, 11 => 3];
        if ($distribution !== $expected) {
            $errors['blockers'][] = 'Building distribution mismatch: expected 3/4/3/3/3/3.';
        }

        foreach ($units as $unit) {
            if (!in_array($unit->status, MagnooliaUnit::ALLOWED_STATUSES, true)) {
                $errors['blockers'][] = "Invalid status for {$unit->unit_key}.";
            }

            if ($unit->net_area <= 0) {
                $errors['blockers'][] = "Net area must be > 0 for {$unit->unit_key}.";
            }

            foreach (['terrace_area', 'balcony_area', 'storage_area', 'private_yard_area'] as $field) {
                $value = $unit->{$field};
                if ($value !== null && $value < 0) {
                    $errors['blockers'][] = "{$field} must be >= 0 for {$unit->unit_key}.";
                }
            }

            if ($unit->price_public && ($unit->price_cents === null || $unit->price_cents <= 0)) {
                $errors['blockers'][] = "Public price invalid for {$unit->unit_key}.";
            }

            foreach (['floorplan_floor_1', 'floorplan_floor_2'] as $assetField) {
                $path = (string) $unit->{$assetField};
                // Missing floor plan is a WARNING (the public site shows an honest
                // placeholder / per-building fallback) — not a publish blocker (Phase 33).
                if ($path === '') {
                    $errors['warnings'][] = "Missing {$assetField} for {$unit->unit_key} (public shows placeholder).";
                    continue;
                }
                // Path-safety issues remain hard BLOCKERS (security).
                if (str_contains($path, '..')) {
                    $errors['blockers'][] = "Unsafe {$assetField} path for {$unit->unit_key}.";
                    continue;
                }
                $normalized = ltrim($path, '/');
                if (!str_starts_with($normalized, 'assets/magnoolia/')) {
                    $errors['blockers'][] = "{$assetField} outside allowed asset directory for {$unit->unit_key}.";
                    continue;
                }
                $fullPath = public_path($normalized);
                if (!is_file($fullPath)) {
                    $errors['warnings'][] = "Asset file for {$assetField} not found for {$unit->unit_key} (public shows placeholder).";
                }
            }
        }

        if ($settings) {
            if ($settings->campaign_active) {
                if (blank($settings->campaign_note_et)) {
                    $errors['blockers'][] = 'Active campaign needs public text (Estonian).';
                }
                if (!$settings->campaign_deadline) {
                    $errors['blockers'][] = 'Campaign deadline is required when campaign is active.';
                }
                // A positive discount is required only for a "fixed amount" campaign.
                if (($settings->campaign_discount_type ?? 'text') === 'fixed' && ($settings->campaign_discount_cents ?? 0) <= 0) {
                    $errors['blockers'][] = 'Fixed-amount campaign must have a discount greater than 0 €.';
                }
                if ($settings->campaign_deadline && $settings->campaign_deadline->isPast()) {
                    $errors['blockers'][] = 'Campaign deadline is in the past.';
                }
                if (blank($settings->campaign_cta_label)) {
                    $errors['warnings'][] = 'Active campaign has no CTA label.';
                }
            }
        } else {
            $errors['warnings'][] = 'Magnoolia settings row is missing; defaults will be used.';
        }

        if ($units->where('stage', 2)->where('price_public', true)->isNotEmpty()) {
            $errors['warnings'][] = 'Stage II units have public prices enabled.';
        }

        // Page-Texts CMS: active blocks must have ET (required); RU/EN missing = warning.
        if (\Illuminate\Support\Facades\Schema::hasTable('magnoolia_content_blocks')) {
            foreach (\App\Models\MagnooliaContentBlock::query()->where('is_active', true)->get() as $cb) {
                if (blank($cb->et)) {
                    $errors['blockers'][] = "Page text \"{$cb->label}\" has no Estonian (ET) value.";
                }
                if (blank($cb->ru)) {
                    $errors['warnings'][] = "Page text \"{$cb->label}\" is missing Russian (RU).";
                }
                if (blank($cb->en)) {
                    $errors['warnings'][] = "Page text \"{$cb->label}\" is missing English (EN).";
                }
            }
        }

        return $errors;
    }
}
