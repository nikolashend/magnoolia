@extends('admin.magnoolia._layout')

@section('title', 'Edit unit draft')

@section('admin_content')
    <div class="card">
        <h2>{{ $unit->address }}</h2>
        <form method="POST" action="{{ route('admin.magnoolia.units.update', ['unit' => $unit->unit_key]) }}" style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
            @csrf
            @method('PUT')

            @php $statusLabels = ['available' => 'Vaba (available)', 'reserved' => 'Broneeritud (reserved)', 'sold' => 'Müüdud (sold)', 'coming_soon' => 'Tulekul (coming soon)']; @endphp
            <div><label>Status</label><select name="status" required>@foreach($statusLabels as $s => $sLabel)<option value="{{ $s }}" @selected($unit->status===$s)>{{ $sLabel }}</option>@endforeach</select></div>
            <div><label>Visible publicly</label><select name="is_visible"><option value="1" @selected($unit->is_visible)>Yes</option><option value="0" @selected(!$unit->is_visible)>No</option></select></div>
            <div><label>Internal price (in cents — never shown publicly)</label><input type="number" name="price_cents" value="{{ $unit->price_cents }}"><small style="color:#9a948a;">e.g. 31900000 = 319 000 €. Use “Show price publicly” to control visibility.</small></div>
            <div><label>Show price publicly</label><select name="price_public"><option value="1" @selected($unit->price_public)>Yes</option><option value="0" @selected(!$unit->price_public)>No</option></select></div>

            <div><label>Rooms</label><input type="number" name="rooms" value="{{ $unit->rooms }}" required></div>
            <div><label>Net area</label><input type="number" step="0.1" name="net_area" value="{{ $unit->net_area }}" required></div>
            <div><label>Terrace</label><input type="number" step="0.1" name="terrace_area" value="{{ $unit->terrace_area }}"></div>
            <div><label>Balcony</label><input type="number" step="0.1" name="balcony_area" value="{{ $unit->balcony_area }}"></div>
            <div><label>Storage</label><input type="number" step="0.1" name="storage_area" value="{{ $unit->storage_area }}"></div>
            <div><label>Private yard</label><input type="number" step="0.1" name="private_yard_area" value="{{ $unit->private_yard_area }}"></div>
            <div><label>Parking spaces</label><input type="number" name="parking_spaces" value="{{ $unit->parking_spaces }}" required></div>
            <div><label>Completion</label><input type="text" name="completion_key" value="{{ $unit->completion_key }}" required></div>
            <div><label>Floor 1 PDF</label><input type="text" name="floorplan_floor_1" value="{{ $unit->floorplan_floor_1 }}" required></div>
            <div><label>Floor 2 PDF</label><input type="text" name="floorplan_floor_2" value="{{ $unit->floorplan_floor_2 }}" required></div>
            <div><label>Asendiplaan key</label><input type="text" name="asendiplaan_key" value="{{ $unit->asendiplaan_key }}"></div>
            <div><label>Featured</label><select name="featured"><option value="1" @selected($unit->featured)>Yes</option><option value="0" @selected(!$unit->featured)>No</option></select></div>
            <div><label>Sort order</label><input type="number" name="sort_order" value="{{ $unit->sort_order }}" required></div>
            <div style="grid-column:1/-1;"><label>Internal note</label><textarea name="internal_note" rows="3">{{ $unit->internal_note }}</textarea></div>
            <div style="grid-column:1/-1;"><label>Change reason (required for status/price/price_public/is_visible)</label><input type="text" name="change_reason" value="{{ old('change_reason') }}"></div>

            <div style="grid-column:1/-1;display:flex;gap:8px;align-items:center;">
                <button type="submit">Save draft</button>
                <a class="btn-muted" style="padding:10px 14px;border-radius:8px;color:#fff;text-decoration:none;" href="{{ route('admin.magnoolia.preview') }}">Preview unit on public site</a>
            </div>
        </form>
    </div>
@endsection
