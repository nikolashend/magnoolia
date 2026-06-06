<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MagnooliaUnit extends Model
{
    public const STATUS_AVAILABLE = 'available';
    public const STATUS_RESERVED = 'reserved';
    public const STATUS_SOLD = 'sold';
    public const STATUS_COMING_SOON = 'coming_soon';

    public const ALLOWED_STATUSES = [
        self::STATUS_AVAILABLE,
        self::STATUS_RESERVED,
        self::STATUS_SOLD,
        self::STATUS_COMING_SOON,
    ];

    protected $fillable = [
        'unit_key',
        'slug',
        'address',
        'building_number',
        'section_number',
        'stage',
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
        'plan_type',
        'public_page_visible',
        'featured',
        'sort_order',
        'internal_note',
        'lock_version',
        'updated_by',
    ];

    protected $casts = [
        'building_number' => 'integer',
        'section_number' => 'integer',
        'stage' => 'integer',
        'is_visible' => 'boolean',
        'price_cents' => 'integer',
        'price_public' => 'boolean',
        'rooms' => 'integer',
        'net_area' => 'decimal:1',
        'terrace_area' => 'decimal:1',
        'balcony_area' => 'decimal:1',
        'storage_area' => 'decimal:1',
        'private_yard_area' => 'decimal:1',
        'parking_spaces' => 'integer',
        'public_page_visible' => 'boolean',
        'featured' => 'boolean',
        'sort_order' => 'integer',
        'lock_version' => 'integer',
        'updated_by' => 'integer',
    ];
}
