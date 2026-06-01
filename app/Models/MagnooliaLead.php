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
        'message',
        'locale',
        'source_url',
        'referrer',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'ip_address',
        'user_agent',
        'mail_status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
