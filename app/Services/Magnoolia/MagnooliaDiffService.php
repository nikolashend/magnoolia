<?php

namespace App\Services\Magnoolia;

use App\Models\MagnooliaContentBlock;
use App\Models\MagnooliaPublication;
use App\Models\MagnooliaSetting;
use App\Models\MagnooliaUnit;
use Illuminate\Support\Carbon;

/**
 * Phase 33.1 — "Changes since last publish".
 *
 * Compares the current editable DRAFT (DB rows) against the active publication's
 * private snapshot, so the client sees exactly what will go live before publishing.
 */
class MagnooliaDiffService
{
    private const UNIT_FIELDS = [
        'status' => 'Status',
        'price_public' => 'Public price visible',
        'price_cents' => 'Internal price',
        'is_visible' => 'Visible on site',
        'completion_key' => 'Completion',
        'net_area' => 'Net area',
        'private_yard_area' => 'Private yard',
        'floorplan_floor_1' => 'Floor plan (1st)',
        'floorplan_floor_2' => 'Floor plan (2nd)',
    ];

    private const SETTING_FIELDS = [
        'campaign_active' => 'Campaign active',
        'campaign_discount_type' => 'Campaign discount type',
        'campaign_discount_cents' => 'Campaign discount (cents)',
        'campaign_deadline' => 'Campaign deadline',
        'campaign_note_et' => 'Campaign text (ET)',
        'campaign_cta_label' => 'Campaign CTA',
    ];

    public function diff(): array
    {
        $active = MagnooliaPublication::query()->where('status', 'active')->orderByDesc('version')->first();
        $snapshot = $active?->private_snapshot ?? [];

        $pubUnits = collect($snapshot['units'] ?? [])->keyBy('unit_key');
        $pubBlocks = collect($snapshot['content_blocks'] ?? [])->keyBy('key');
        $pubSettings = $snapshot['settings'] ?? [];

        // No active publication yet → the first publish will push everything.
        if (!$active) {
            return [
                'first_publish' => true,
                'units' => [], 'content' => [], 'settings' => [],
                'has_changes' => MagnooliaUnit::query()->exists(),
                'active_version' => null,
            ];
        }

        $unitChanges = [];
        foreach (MagnooliaUnit::query()->orderBy('sort_order')->get() as $u) {
            $pub = $pubUnits->get($u->unit_key);
            $rows = [];
            foreach (self::UNIT_FIELDS as $field => $label) {
                $from = is_array($pub) ? ($pub[$field] ?? null) : null;
                if ($this->norm($from, $field) !== $this->norm($u->{$field}, $field)) {
                    $rows[] = ['label' => $label, 'from' => $this->display($from, $field), 'to' => $this->display($u->{$field}, $field)];
                }
            }
            if ($rows) {
                $unitChanges[] = ['address' => $u->address, 'unit_key' => $u->unit_key, 'is_new' => !$pub, 'rows' => $rows];
            }
        }

        $contentChanges = [];
        foreach (MagnooliaContentBlock::query()->orderBy('page')->orderBy('sort_order')->get() as $c) {
            $pub = $pubBlocks->get($c->key);
            $rows = [];
            foreach (['et' => 'ET', 'ru' => 'RU', 'en' => 'EN', 'is_active' => 'Active'] as $field => $label) {
                $from = is_array($pub) ? ($pub[$field] ?? null) : null;
                if ($this->norm($from, $field) !== $this->norm($c->{$field}, $field)) {
                    $rows[] = ['label' => $label, 'from' => $this->display($from, $field), 'to' => $this->display($c->{$field}, $field)];
                }
            }
            if ($rows) {
                $contentChanges[] = ['label' => $c->label, 'key' => $c->key, 'is_new' => !$pub, 'rows' => $rows];
            }
        }

        $settingChanges = [];
        if ($s = MagnooliaSetting::query()->latest('id')->first()) {
            foreach (self::SETTING_FIELDS as $field => $label) {
                $from = $pubSettings[$field] ?? null;
                if ($this->norm($from, $field) !== $this->norm($s->{$field}, $field)) {
                    $settingChanges[] = ['label' => $label, 'from' => $this->display($from, $field), 'to' => $this->display($s->{$field}, $field)];
                }
            }
        }

        return [
            'first_publish' => false,
            'units' => $unitChanges,
            'content' => $contentChanges,
            'settings' => $settingChanges,
            'has_changes' => (bool) (count($unitChanges) + count($contentChanges) + count($settingChanges)),
            'active_version' => $active->version,
        ];
    }

    private function norm($v, string $field): string
    {
        if (str_contains($field, 'deadline')) {
            return $v ? Carbon::parse($v)->toDateString() : '∅';
        }
        if (is_bool($v)) {
            return $v ? '1' : '0';
        }
        if ($v === null || $v === '') {
            return '∅';
        }
        // numeric-ish fields: compare loosely
        if (in_array($field, ['net_area', 'private_yard_area', 'price_cents', 'campaign_discount_cents'], true)) {
            return (string) (0 + (float) $v);
        }
        return trim((string) $v);
    }

    private function display($v, string $field): string
    {
        if (str_contains($field, 'deadline')) {
            return $v ? Carbon::parse($v)->format('d.m.Y') : '—';
        }
        if (is_bool($v)) {
            return $v ? 'yes' : 'no';
        }
        if ($v === null || $v === '') {
            return '—';
        }
        if (in_array($field, ['price_cents', 'campaign_discount_cents'], true)) {
            return number_format(((float) $v) / 100, 0, '.', ' ') . ' €';
        }
        return \Illuminate\Support\Str::limit((string) $v, 80);
    }
}
