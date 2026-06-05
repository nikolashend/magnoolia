@extends('admin.magnoolia._layout')

@section('title', 'Publish draft')

@section('admin_content')
    <div class="card" style="margin-bottom:12px;">
        <div><strong>Current active version:</strong> {{ $active?->version ?? '—' }}</div>
        <div><strong>Changed units (draft count):</strong> {{ $units->count() }}</div>
    </div>

    <div class="grid" style="grid-template-columns:1fr 1fr;margin-bottom:12px;">
        <div class="card"><h3>BLOCKER</h3>@forelse($validation['blockers'] as $item)<div class="status status-bad" style="display:block;margin-bottom:8px;">{{ $item }}</div>@empty<div class="status status-ok">No blockers</div>@endforelse</div>
        <div class="card"><h3>WARNING</h3>@forelse($validation['warnings'] as $item)<div class="status status-warn" style="display:block;margin-bottom:8px;">{{ $item }}</div>@empty<div class="status status-ok">No warnings</div>@endforelse</div>
    </div>

    <div class="card">
        <form method="POST" action="{{ route('admin.magnoolia.publish') }}">
            @csrf
            <div style="margin-bottom:8px;"><label>Publication note (required)</label><input type="text" name="publication_note" required maxlength="500"></div>
            @if(!empty($validation['warnings']))
                <div style="margin-bottom:8px;"><label><input type="checkbox" name="confirm_warnings" value="1"> Confirm warnings</label></div>
            @endif
            <button type="submit" @if(!empty($validation['blockers'])) disabled style="opacity:.5;cursor:not-allowed;" @endif>Publish</button>
        </form>
    </div>
@endsection
