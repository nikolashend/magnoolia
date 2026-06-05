<?php

namespace App\Services\Magnoolia;

use App\Models\MagnooliaUnit;
use Illuminate\Support\Arr;

class MagnooliaDraftService
{
    public function changedFieldsForUnit(MagnooliaUnit $unit, array $incoming): array
    {
        $changed = [];
        foreach ($incoming as $key => $value) {
            if ($unit->{$key} != $value) {
                $changed[$key] = [
                    'before' => $unit->{$key},
                    'after' => $value,
                ];
            }
        }

        return $changed;
    }

    public function applyUnitDraft(MagnooliaUnit $unit, array $incoming, int $updatedBy): MagnooliaUnit
    {
        $unit->fill(Arr::only($incoming, [
            'status',
            'is_visible',
            'price_cents',
            'price_public',
            'rooms',
            'net_area',
            'terrace_area',
            'balcony_area',
            'storage_area',
            'private_yard_area',
            'parking_spaces',
            'completion_key',
            'floorplan_floor_1',
            'floorplan_floor_2',
            'asendiplaan_key',
            'featured',
            'sort_order',
            'internal_note',
        ]));

        $unit->updated_by = $updatedBy;
        $unit->lock_version = $unit->lock_version + 1;
        $unit->save();

        return $unit->fresh();
    }
}
