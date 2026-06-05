@extends('admin.magnoolia._layout')

@section('title', 'CSV import preview')

@section('admin_content')
    <div class="card" style="margin-bottom:12px;">
        <h3>Import preview token</h3>
        <div>{{ $token }}</div>
    </div>

    <div class="grid" style="grid-template-columns:1fr 2fr;">
        <div class="card">
            <h3>Errors</h3>
            @forelse(($preview['errors'] ?? []) as $error)
                <div class="status status-bad" style="display:block;margin-bottom:8px;">{{ $error }}</div>
            @empty
                <div class="status status-ok">No errors</div>
            @endforelse
        </div>

        <div class="card">
            <h3>Row-by-row diff</h3>
            <table>
                <thead><tr><th>Line</th><th>Unit</th><th>Incoming status</th><th>Incoming price</th></tr></thead>
                <tbody>
                @foreach(($preview['rows'] ?? []) as $row)
                    <tr>
                        <td>{{ $row['line'] }}</td>
                        <td>{{ $row['unit_key'] }}</td>
                        <td>{{ $row['incoming']['status'] ?? '' }}</td>
                        <td>{{ $row['incoming']['price'] ?? '' }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @if(empty($preview['errors']))
        <div class="card" style="margin-top:12px;">
            <form method="POST" action="{{ route('admin.magnoolia.import.csv.apply') }}" style="display:flex;gap:10px;align-items:center;">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <label><input type="checkbox" name="confirm_apply" value="1" required> Confirm import to draft</label>
                <button type="submit">Apply import to draft</button>
            </form>
        </div>
    @endif
@endsection
