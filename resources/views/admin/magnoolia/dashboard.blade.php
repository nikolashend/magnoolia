@extends('admin.magnoolia._layout')

@section('title', 'Magnoolia Admin Dashboard')

@section('admin_content')
    @if(!empty($usingCanonicalFallback))
    <div class="card" style="margin-bottom:14px;border-left:4px solid #c89443;background:#fbf6ec;">
        <strong style="color:#9a6b1f;">⚠ Admin editing is not yet client-ready (Phase 33).</strong>
        <p style="margin:6px 0 0;font-size:13px;color:#5b5446;">
            The public website currently serves its <strong>{{ $canonicalConfigCount }} canonical homes</strong>
            from the fallback config (<code>config/magnoolia_units.php</code>), with the approved
            reserved/sold statuses and prices withheld until confirmed.
            The counters below reflect <strong>DB-managed units</strong> only —
            <strong>0 DB units / no active publication</strong> is expected at this stage and does
            <em>not</em> mean the public site is empty. DB-backed editing &amp; publishing arrive in Phase 33.
        </p>
    </div>
    @endif
    <div class="card" style="margin-bottom:14px;">
        <h2 style="margin:0 0 10px;">Operational truth</h2>
        <div class="grid grid-4">
            <div class="card"><strong>Current published version</strong><div>{{ $stats['published_version'] ?? '—' }}</div></div>
            <div class="card"><strong>Last published date</strong><div>{{ $stats['published_at'] ?? '—' }}</div></div>
            <div class="card"><strong>Unpublished changes</strong><div>{{ $stats['unpublished_changes'] }}</div></div>
            <div class="card"><strong>Validation blockers</strong><div>{{ $stats['blockers'] }}</div></div>
            <div class="card"><strong>Validation warnings</strong><div>{{ $stats['warnings'] }}</div></div>
            <div class="card"><strong>Available units</strong><div>{{ $stats['available'] }}</div></div>
            <div class="card"><strong>Reserved units</strong><div>{{ $stats['reserved'] }}</div></div>
            <div class="card"><strong>Sold units</strong><div>{{ $stats['sold'] }}</div></div>
            <div class="card"><strong>Stage I count</strong><div>{{ $stats['stage_1'] }}</div></div>
            <div class="card"><strong>Stage II count</strong><div>{{ $stats['stage_2'] }}</div></div>
            <div class="card"><strong>Units with public price</strong><div>{{ $stats['public_prices'] }}</div></div>
            <div class="card"><strong>Units with hidden price</strong><div>{{ $stats['hidden_prices'] }}</div></div>
        </div>
    </div>

    <div class="grid" style="grid-template-columns:1fr 1fr;">
        <div class="card">
            <h3>BLOCKER</h3>
            @forelse($validation['blockers'] as $item)
                <div class="status status-bad" style="display:block;margin-bottom:8px;">{{ $item }}</div>
            @empty
                <div class="status status-ok">No blockers</div>
            @endforelse
        </div>
        <div class="card">
            <h3>WARNING</h3>
            @forelse($validation['warnings'] as $item)
                <div class="status status-warn" style="display:block;margin-bottom:8px;">{{ $item }}</div>
            @empty
                <div class="status status-ok">No warnings</div>
            @endforelse
        </div>
    </div>
@endsection
