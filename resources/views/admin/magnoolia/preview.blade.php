@extends('admin.magnoolia._layout')

@section('title', 'Draft preview')

@section('admin_content')
    <div class="card" style="background:#fff3cd;border-color:#f0d98a;margin-bottom:12px;">
        <strong>{{ $banner }}</strong>
    </div>
    <div class="card" style="margin-bottom:12px;">
        <div><strong>Units:</strong> {{ $units->count() }}</div>
        <div><strong>Campaign active:</strong> {{ $settings?->campaign_active ? 'Yes' : 'No' }}</div>
    </div>
    <div class="card">
        <table>
            <thead><tr><th>Address</th><th>Status</th><th>Stage</th><th>Price public</th><th>Price</th></tr></thead>
            <tbody>
            @foreach($units as $unit)
                <tr>
                    <td>{{ $unit->address }}</td>
                    <td>{{ $unit->status }}</td>
                    <td>{{ $unit->stage }}</td>
                    <td>{{ $unit->price_public ? 'Yes' : 'No' }}</td>
                    <td>{{ $unit->price_public ? number_format(($unit->price_cents ?? 0)/100, 2, '.', ' ') : 'Hidden' }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
