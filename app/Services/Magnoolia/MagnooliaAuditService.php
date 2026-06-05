<?php

namespace App\Services\Magnoolia;

use App\Models\MagnooliaAuditLog;

class MagnooliaAuditService
{
    public function log(
        string $action,
        ?int $adminUserId = null,
        ?string $entityType = null,
        ?string $entityId = null,
        ?array $before = null,
        ?array $after = null,
        ?string $reason = null,
        ?string $ip = null,
        ?string $userAgent = null,
    ): void {
        MagnooliaAuditLog::create([
            'admin_user_id' => $adminUserId,
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'before_json' => $before ? json_encode($before, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : null,
            'after_json' => $after ? json_encode($after, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : null,
            'reason' => $reason,
            'ip_address' => $ip,
            'user_agent' => $userAgent ? mb_substr($userAgent, 0, 500) : null,
            'created_at' => now(),
        ]);
    }
}
