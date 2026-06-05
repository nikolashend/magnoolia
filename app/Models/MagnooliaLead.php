<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MagnooliaLead extends Model
{
    protected $table = 'magnoolia_leads';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'selected_unit',
        'unit_key',
        'unit_address',
        'published_version',
        'status_at_submission',
        'price_public_at_submission',
        'source_page',
        'source_component',
        'message',
        'locale',
        'source_url',
        'referrer',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'utm_content',
        'ip_address',
        'user_agent',
        'mail_status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
