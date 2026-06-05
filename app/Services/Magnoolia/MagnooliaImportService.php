<?php

namespace App\Services\Magnoolia;

use App\Models\MagnooliaUnit;

class MagnooliaImportService
{
    public function previewCsv(string $csvContent): array
    {
        $lines = preg_split('/\r\n|\r|\n/', trim($csvContent));
        if (!$lines || count($lines) < 2) {
            return ['errors' => ['CSV is empty.'], 'rows' => []];
        }

        $header = str_getcsv(array_shift($lines));
        $required = [
            'unit_key', 'address', 'building_number', 'section_number', 'stage', 'status',
            'is_visible', 'price', 'price_public', 'rooms', 'net_area', 'terrace_area',
            'balcony_area', 'storage_area', 'private_yard_area', 'parking_spaces', 'completion_key',
        ];

        foreach ($required as $field) {
            if (!in_array($field, $header, true)) {
                return ['errors' => ["Missing required column: {$field}"], 'rows' => []];
            }
        }

        $errors = [];
        $rows = [];
        foreach ($lines as $lineNumber => $line) {
            if (trim($line) === '') {
                continue;
            }
            $values = str_getcsv($line);
            $assoc = array_combine($header, $values);
            if (!is_array($assoc)) {
                $errors[] = "Row " . ($lineNumber + 2) . ': invalid CSV columns.';
                continue;
            }

            $unit = MagnooliaUnit::query()->where('unit_key', $assoc['unit_key'])->first();
            if (!$unit) {
                $errors[] = "Row " . ($lineNumber + 2) . ": unknown unit_key {$assoc['unit_key']}.";
                continue;
            }

            $rows[] = [
                'line' => $lineNumber + 2,
                'unit_key' => $assoc['unit_key'],
                'current' => $unit->toArray(),
                'incoming' => $assoc,
            ];
        }

        return ['errors' => $errors, 'rows' => $rows];
    }

    public function applyPreviewRows(array $rows, int $adminUserId): int
    {
        $updated = 0;
        foreach ($rows as $row) {
            $incoming = $row['incoming'];
            $unit = MagnooliaUnit::query()->where('unit_key', $incoming['unit_key'])->first();
            if (!$unit) {
                continue;
            }

            $unit->address = $incoming['address'];
            $unit->building_number = (int) $incoming['building_number'];
            $unit->section_number = (int) $incoming['section_number'];
            $unit->stage = (int) $incoming['stage'];
            $unit->status = $incoming['status'];
            $unit->is_visible = (bool) ((int) $incoming['is_visible']);
            $unit->price_cents = $incoming['price'] !== '' ? (int) round(((float) $incoming['price']) * 100) : null;
            $unit->price_public = (bool) ((int) $incoming['price_public']);
            $unit->rooms = (int) $incoming['rooms'];
            $unit->net_area = (float) $incoming['net_area'];
            $unit->terrace_area = $incoming['terrace_area'] !== '' ? (float) $incoming['terrace_area'] : null;
            $unit->balcony_area = $incoming['balcony_area'] !== '' ? (float) $incoming['balcony_area'] : null;
            $unit->storage_area = $incoming['storage_area'] !== '' ? (float) $incoming['storage_area'] : null;
            $unit->private_yard_area = $incoming['private_yard_area'] !== '' ? (float) $incoming['private_yard_area'] : null;
            $unit->parking_spaces = (int) $incoming['parking_spaces'];
            $unit->completion_key = $incoming['completion_key'];
            $unit->updated_by = $adminUserId;
            $unit->lock_version = $unit->lock_version + 1;
            $unit->save();

            $updated++;
        }

        return $updated;
    }
}
