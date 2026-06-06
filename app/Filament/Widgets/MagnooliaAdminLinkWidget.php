<?php

namespace App\Filament\Widgets;

use App\Models\MagnooliaPublication;
use App\Models\MagnooliaUnit;
use Filament\Widgets\Widget;

class MagnooliaAdminLinkWidget extends Widget
{
    protected string $view = 'filament.widgets.magnoolia-admin-link';

    protected static ?int $sort = -10;

    protected int|string|array $columnSpan = 'full';

    public function getViewData(): array
    {
        $active = MagnooliaPublication::query()
            ->where('status', 'active')
            ->orderByDesc('version')
            ->first();

        $unitCount = MagnooliaUnit::query()->count();
        $available = MagnooliaUnit::query()->where('status', 'available')->count();
        $reserved  = MagnooliaUnit::query()->where('status', 'reserved')->count();
        $sold      = MagnooliaUnit::query()->where('status', 'sold')->count();

        return [
            'publicationVersion' => $active?->version,
            'publishedAt'        => $active?->published_at?->format('d.m.Y H:i'),
            'unitCount'          => $unitCount,
            'available'          => $available,
            'reserved'           => $reserved,
            'sold'               => $sold,
        ];
    }
}
