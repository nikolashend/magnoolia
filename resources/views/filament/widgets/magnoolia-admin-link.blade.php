<x-filament-widgets::widget>
    <x-filament::section>
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;">
            <div>
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:6px;">
                    <div style="width:36px;height:36px;background:#c89443;border-radius:8px;display:flex;align-items:center;justify-content:center;">
                        <x-heroicon-o-building-office style="width:20px;height:20px;color:white;" />
                    </div>
                    <div>
                        <div style="font-size:17px;font-weight:700;color:#1d2430;">Magnoolia ridaelamukodud</div>
                        <div style="font-size:12px;color:#6b7280;">Manage units, prices, availability and publications</div>
                    </div>
                </div>
                <div style="display:flex;gap:20px;margin-top:10px;flex-wrap:wrap;">
                    <div style="font-size:13px;color:#374151;">
                        <span style="font-weight:600;color:#1d2430;">{{ $unitCount }}</span> total units
                    </div>
                    @if($available)
                    <div style="font-size:13px;color:#16a34a;font-weight:600;">
                        {{ $available }} available
                    </div>
                    @endif
                    @if($reserved)
                    <div style="font-size:13px;color:#d97706;font-weight:600;">
                        {{ $reserved }} reserved
                    </div>
                    @endif
                    @if($sold)
                    <div style="font-size:13px;color:#6b7280;font-weight:600;">
                        {{ $sold }} sold
                    </div>
                    @endif
                    <div style="font-size:13px;color:#6b7280;">
                        @if($publicationVersion)
                            Publication v{{ $publicationVersion }} &middot; {{ $publishedAt }}
                        @else
                            <span style="color:#dc2626;font-weight:600;">No active publication</span>
                        @endif
                    </div>
                </div>
            </div>
            <div style="display:flex;gap:10px;flex-wrap:wrap;">
                <a href="/admin/magnoolia/units"
                   style="background:#c89443;color:#fff;padding:8px 18px;border-radius:8px;font-size:14px;font-weight:600;text-decoration:none;white-space:nowrap;">
                    Open Magnoolia Admin
                </a>
                <a href="/admin/magnoolia/publish"
                   style="border:1px solid #c89443;color:#c89443;padding:8px 18px;border-radius:8px;font-size:14px;font-weight:600;text-decoration:none;white-space:nowrap;">
                    Publish
                </a>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
