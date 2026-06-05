<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MagnooliaAuditLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'admin_user_id',
        'action',
        'entity_type',
        'entity_id',
        'before_json',
        'after_json',
        'reason',
        'ip_address',
        'user_agent',
        'created_at',
    ];

    protected $casts = [
        'admin_user_id' => 'integer',
        'created_at' => 'datetime',
    ];

    public function admin(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'admin_user_id');
    }
}
