@extends('admin.magnoolia._layout')

@section('title', 'Magnoolia Units Draft')

@section('admin_content')
    <div class="card" style="margin-bottom:14px;">
        <form method="GET" style="display:grid;grid-template-columns:2fr 1fr 1fr 1fr 1fr auto;gap:8px;align-items:end;">
            <div><label>Search</label><input type="text" name="q" value="{{ request('q') }}" placeholder="Address"></div>
            <div><label>Stage</label><select name="stage"><option value="">All</option><option value="1" @selected(request('stage')==='1')>I</option><option value="2" @selected(request('stage')==='2')>II</option></select></div>
            <div><label>Status</label><select name="status"><option value="">All</option><option>available</option><option>reserved</option><option>sold</option><option>coming_soon</option></select></div>
            <div><label>Price public</label><select name="price_public"><option value="">All</option><option value="1">Yes</option><option value="0">No</option></select></div>
            <div><label>Building</label><select name="building"><option value="">All</option>@foreach([1,3,5,7,9,11] as $b)<option value="{{ $b }}">{{ $b }}</option>@endforeach</select></div>
            <div><button type="submit">Filter</button></div>
        </form>
    </div>

    <div class="card">
        <table>
            <thead>
            <tr>
                <th>Address</th><th>Stage</th><th>Status</th><th>Rooms</th><th>Net area</th><th>Private yard</th><th>Price</th><th>Price public</th><th>Last updated</th><th>Updated by</th><th>Validation</th><th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($units as $unit)
                <tr>
                    <td>{{ $unit->address }}</td>
                    <td>{{ $unit->stage }}</td>
                    <td>{{ $unit->status }}</td>
                    <td>{{ $unit->rooms }}</td>
                    <td>{{ $unit->net_area }}</td>
                    <td>{{ $unit->private_yard_area }}</td>
                    <td>{{ $unit->price_cents ? number_format($unit->price_cents/100,2,'.',' ') : '—' }}</td>
                    <td>{{ $unit->price_public ? 'Yes' : 'No' }}</td>
                    <td>{{ $unit->updated_at }}</td>
                    <td>{{ $unit->updated_by }}</td>
                    <td><span class="status status-ok">OK</span></td>
                    <td><a href="{{ route('admin.magnoolia.units.edit', ['unit' => $unit->unit_key]) }}">Edit</a></td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div style="margin-top:12px;">{{ $units->links() }}</div>
    </div>

    <div class="card" style="margin-top:14px;">
        <h3>CSV import preview</h3>
        <form method="POST" action="{{ route('admin.magnoolia.import.csv.preview') }}" enctype="multipart/form-data" style="display:flex;gap:8px;align-items:end;">
            @csrf
            <div style="flex:1;"><input type="file" name="csv_file" required></div>
            <button type="submit">Upload CSV</button>
        </form>
    </div>
@endsection
