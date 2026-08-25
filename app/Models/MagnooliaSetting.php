<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MagnooliaSetting extends Model
{
    protected $fillable = [
        'campaign_active',
        'campaign_discount_cents',
        'campaign_discount_type',
        'campaign_deadline',
        'campaign_note_et',
        'campaign_note_ru',
        'campaign_note_en',
        // The site shows a long campaign sentence AND a one-line version; they are
        // different texts and the ribbon is sized for the short one.
        'campaign_note_short_et',
        'campaign_note_short_ru',
        'campaign_note_short_en',
        'campaign_legal_note',
        'campaign_legal_note_ru',
        'campaign_legal_note_en',
        'campaign_cta_label',
        'campaign_cta_target',
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
