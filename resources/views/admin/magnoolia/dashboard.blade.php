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
    <div class="card" style="margin-bottom:14px;display:flex;flex-wrap:wrap;gap:16px;align-items:center;justify-content:space-between;border-left:4px solid {{ !empty($active) ? '#1f7a44' : '#c89443' }};">
        <div>
            <div style="font-size:11px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#9a8b6f;">Live public source</div>
            <div style="font-size:18px;font-weight:700;color:#1d2430;">{{ $liveSource ?? '—' }}</div>
            <div style="font-size:12.5px;color:#5b5446;margin-top:2px;">
                19 canonical homes · {{ $stats['available'] }} Vaba · {{ $stats['reserved'] }} Broneeritud · {{ $stats['sold'] }} Müüdud
                @if(!empty($active)) · published {{ optional($active->published_at)->format('Y-m-d H:i') }} @endif
                @if(!empty($lastEditedUnit)) · last draft edit {{ optional($lastEditedUnit->updated_at)->format('Y-m-d H:i') }} @endif
            </div>
        </div>
        <div style="display:flex;gap:8px;flex-wrap:wrap;">
            <a href="{{ route('admin.magnoolia.units.index') }}"><button type="button">Manage homes</button></a>
            <a href="{{ route('admin.magnoolia.changes') }}"><button type="button">Review changes</button></a>
            <a href="{{ route('admin.magnoolia.publish.form') }}"><button type="button">Publish</button></a>
            <a href="{{ route('admin.magnoolia.leads.index') }}"><button type="button">Leads</button></a>
            <a href="{{ route('admin.magnoolia.help') }}"><button type="button">Help</button></a>
            <a href="{{ url('/') }}" target="_blank"><button type="button">View site ↗</button></a>
        </div>
    </div>
    <div class="card" style="margin-bottom:14px;background:#fbf8f3;border-left:4px solid #c89443;">
        <strong style="font-size:13px;">How publishing works:</strong>
        <span style="font-size:13px;color:#5b5446;">Edit (draft) → <a href="{{ route('admin.magnoolia.changes') }}">Review changes</a> → <a href="{{ route('admin.magnoolia.preview') }}">Preview</a> → <a href="{{ route('admin.magnoolia.validate') }}">Validate</a> → <a href="{{ route('admin.magnoolia.publish.form') }}">Publish</a>. Nothing is public until you publish; you can <a href="{{ route('admin.magnoolia.publications.index') }}">roll back</a> anytime. <a href="{{ route('admin.magnoolia.help') }}">Full help →</a></span>
    </div>
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

    <div class="card" style="margin-top:14px;">
        <h3 style="margin:0 0 10px;">Recent changes <a href="{{ route('admin.magnoolia.audit') }}" style="font-size:12px;font-weight:400;">(full audit log →)</a></h3>
        @forelse(($recentAudit ?? []) as $log)
            <div style="display:flex;gap:12px;align-items:baseline;padding:6px 0;border-bottom:1px solid #f0ede8;font-size:13px;">
                <span style="color:#999;min-width:120px;">{{ optional($log->created_at)->format('Y-m-d H:i') }}</span>
                <span style="font-weight:600;min-width:160px;">{{ $log->action }}</span>
                <span style="color:#666;flex:1;">{{ $log->entity_type }} {{ $log->entity_id }} @if($log->reason)— {{ \Illuminate\Support\Str::limit($log->reason, 60) }}@endif</span>
                <span style="color:#999;">{{ optional($log->admin)->email ?? 'system' }}</span>
            </div>
        @empty
            <div class="status status-ok">No changes logged yet</div>
        @endforelse
    </div>
@endsection
