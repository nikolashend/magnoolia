<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MagnooliaSetting extends Model
{
    protected $fillable = [
        'campaign_active',
        'campaign_discount_cents',
        'campaign_deadline',
        'campaign_note_et',
        'campaign_note_ru',
        'campaign_note_en',
        'campaign_legal_note',
        'stage_1_completion',
        'stage_2_completion',
        'default_stage_1_price_public',
        'default_stage_2_price_public',
        'sales_contact_name',
        'sales_contact_phone',
        'sales_contact_email',
        'updated_by',
    ];

    protected $casts = [
        'campaign_active' => 'boolean',
        'campaign_discount_cents' => 'integer',
        'campaign_deadline' => 'date',
        'default_stage_1_price_public' => 'boolean',
        'default_stage_2_price_public' => 'boolean',
        'updated_by' => 'integer',
    ];
}
