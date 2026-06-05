<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MagnooliaPublication extends Model
{
    protected $fillable = [
        'version',
        'status',
        'publication_note',
        'draft_checksum',
        'public_payload_json',
        'private_snapshot_json',
        'published_by',
        'published_at',
        'rolled_back_from_id',
    ];

    protected $casts = [
        'version' => 'integer',
        'published_by' => 'integer',
        'rolled_back_from_id' => 'integer',
        'published_at' => 'datetime',
    ];

    public function getPublicPayloadAttribute(): array
    {
        return json_decode($this->public_payload_json ?: '{}', true) ?: [];
    }

    public function getPrivateSnapshotAttribute(): array
    {
        return json_decode($this->private_snapshot_json ?: '{}', true) ?: [];
    }
}
