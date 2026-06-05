@extends('admin.magnoolia._layout')

@section('title', 'Validate draft')

@section('admin_content')
    <div class="grid" style="grid-template-columns:1fr 1fr 1fr;">
        <div class="card"><h3>BLOCKER</h3>@forelse($validation['blockers'] as $item)<div class="status status-bad" style="display:block;margin-bottom:8px;">{{ $item }}</div>@empty<div class="status status-ok">No blockers</div>@endforelse</div>
        <div class="card"><h3>WARNING</h3>@forelse($validation['warnings'] as $item)<div class="status status-warn" style="display:block;margin-bottom:8px;">{{ $item }}</div>@empty<div class="status status-ok">No warnings</div>@endforelse</div>
        <div class="card"><h3>INFO</h3>@forelse($validation['info'] as $item)<div>{{ $item }}</div>@empty<div class="status status-ok">No info</div>@endforelse</div>
    </div>
@endsection
