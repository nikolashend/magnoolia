<?php

namespace App\Services\Magnoolia;

use App\Models\MagnooliaUnit;

class MagnooliaExportService
{
    public function exportUnitsCsv(): string
    {
        $headers = [
            'unit_key',
            'address',
            'building_number',
            'section_number',
            'stage',
            'status',
            'is_visible',
            'price',
            'price_public',
            'rooms',
            'net_area',
            'terrace_area',
            'balcony_area',
            'storage_area',
            'private_yard_area',
            'parking_spaces',
            'completion_key',
        ];

        $rows = [implode(',', $headers)];

        $units = MagnooliaUnit::query()->orderBy('sort_order')->get();
        foreach ($units as $unit) {
            $price = $unit->price_cents !== null ? number_format($unit->price_cents / 100, 2, '.', '') : '';
            $rows[] = implode(',', [
                $unit->unit_key,
                '"' . str_replace('"', '""', $unit->address) . '"',
                $unit->building_number,
                $unit->section_number,
                $unit->stage,
                $unit->status,
                $unit->is_visible ? '1' : '0',
                $price,
                $unit->price_public ? '1' : '0',
                $unit->rooms,
                $unit->net_area,
                $unit->terrace_area,
                $unit->balcony_area,
                $unit->storage_area,
                $unit->private_yard_area,
                $unit->parking_spaces,
                $unit->completion_key,
            ]);
        }

        return implode("\n", $rows) . "\n";
    }
}
