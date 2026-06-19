@extends('admin.magnoolia._layout')

@section('title', 'Publish website changes')

@section('admin_content')
    <div class="card" style="margin-bottom:12px;">
        <h2 style="margin:0 0 6px;">Publish website changes</h2>
        <p style="margin:0;color:#6f6a61;font-size:13px;">
            Review what will change, then confirm. Publishing creates a new versioned snapshot and updates the public website immediately.
            Current live version: <strong>v{{ $active?->version ?? '—' }}</strong>.
        </p>
    </div>

    {{-- Step 1 — validation --}}
    <div class="grid" style="grid-template-columns:1fr 1fr;margin-bottom:12px;">
        <div class="card"><h3>1 · Blockers</h3>@forelse($validation['blockers'] as $item)<div class="status status-bad" style="display:block;margin-bottom:8px;">{{ $item }}</div>@empty<div class="status status-ok">No blockers — safe to publish</div>@endforelse</div>
        <div class="card"><h3>Warnings</h3>@forelse($validation['warnings'] as $item)<div class="status status-warn" style="display:block;margin-bottom:8px;">{{ $item }}</div>@empty<div class="status status-ok">No warnings</div>@endforelse</div>
    </div>

    {{-- Step 2 — changes summary --}}
    <div class="card" style="margin-bottom:12px;">
        <h3 style="margin:0 0 8px;">2 · What will change</h3>
        @include('admin.magnoolia._diff')
    </div>

    {{-- Step 3 — preview links --}}
    <div class="card" style="margin-bottom:12px;">
        <h3 style="margin:0 0 8px;">3 · Preview the draft</h3>
        <a href="{{ route('admin.magnoolia.preview') }}"><button type="button">Open draft preview</button></a>
        <a href="{{ url('/kodud-ja-hinnad') }}" target="_blank"><button type="button" class="btn-muted">Public homes ↗</button></a>
        <a href="{{ url('/asendiplaan') }}" target="_blank"><button type="button" class="btn-muted">Public asendiplaan ↗</button></a>
    </div>

    {{-- Step 4 — confirm + publish --}}
    <div class="card">
        <h3 style="margin:0 0 8px;">4 · Confirm &amp; publish</h3>
        <form method="POST" action="{{ route('admin.magnoolia.publish') }}">
            @csrf
            <div style="margin-bottom:10px;"><label>Publication note (required)</label><input type="text" name="publication_note" required maxlength="500" placeholder="e.g. Updated availability + spring campaign"></div>
            @if(!empty($validation['warnings']))
                <div style="margin-bottom:10px;"><label style="display:flex;gap:8px;align-items:center;width:auto;"><input type="checkbox" name="confirm_warnings" value="1" style="width:auto;"> I have reviewed the warnings above.</label></div>
            @endif
            <div style="margin-bottom:12px;"><label style="display:flex;gap:8px;align-items:center;width:auto;font-weight:600;"><input type="checkbox" name="confirm_publish" value="1" style="width:auto;"> I understand these changes will update the <strong>public website</strong>.</label></div>
            <button type="submit" class="{{ !empty($validation['blockers']) ? '' : '' }}" @if(!empty($validation['blockers'])) disabled style="opacity:.5;cursor:not-allowed;" @endif
                style="background:#c89443;@if(!empty($validation['blockers']))opacity:.5;cursor:not-allowed;@endif">Publish to public website</button>
            @if(!empty($validation['blockers']))<div style="margin-top:8px;color:#b71c1c;font-size:13px;">Fix the blockers above before publishing.</div>@endif
        </form>
    </div>
@endsection
