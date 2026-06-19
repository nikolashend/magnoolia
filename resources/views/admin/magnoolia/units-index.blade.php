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

    @php
        $badge = [
            'available' => ['Vaba', '#1f7a44', '#e6f4ec'],
            'reserved'  => ['Broneeritud', '#9a6b1f', '#fbf1dd'],
            'sold'      => ['Müüdud', '#8a2b2b', '#f6e3e3'],
            'coming_soon' => ['Varjatud / Draft', '#555', '#ececec'],
        ];
    @endphp
    <div class="card">
        <div style="font-size:13px;color:#666;margin-bottom:8px;">
            {{ $units->total() }} kodu · quick-edit and bulk actions change the <strong>draft</strong> only — Publish to make it live.
        </div>

        {{-- Bulk action bar (no destructive delete). Checkboxes below are collected by JS. --}}
        <form id="bulkForm" method="POST" action="{{ route('admin.magnoolia.units.bulk') }}"
              style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;background:#faf8f4;border:1px solid #ece6db;border-radius:8px;padding:8px 12px;margin-bottom:10px;">
            @csrf
            <strong style="font-size:12px;"><span id="bulkCount">0</span> selected</strong>
            <select name="bulk_action" style="width:auto;font-size:13px;">
                <option value="">Bulk action…</option>
                <option value="status_available">Set status → Vaba</option>
                <option value="status_reserved">Set status → Broneeritud</option>
                <option value="status_sold">Set status → Müüdud</option>
                <option value="hide">Hide from public</option>
                <option value="show">Show on public</option>
                <option value="price_public">Public price → visible</option>
                <option value="price_hidden">Public price → hidden</option>
            </select>
            <button type="submit" style="font-size:13px;">Apply to selected</button>
            <span style="font-size:11px;color:#9a948a;">Draft only · audited · Publish to go live</span>
        </form>

        <table>
            <thead>
            <tr>
                <th style="width:28px;"><input type="checkbox" id="bulkAll" style="width:auto;"></th>
                <th>Address</th><th>Stage</th><th>Status (quick)</th><th>Rooms</th><th>Net m²</th><th>Yard m²</th><th>Price (internal)</th><th>Public price</th><th>Updated</th><th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($units as $unit)
                @php $b = $badge[$unit->status] ?? $badge['coming_soon']; @endphp
                <tr>
                    <td><input type="checkbox" class="bulk-cb" value="{{ $unit->unit_key }}" style="width:auto;"></td>
                    <td><strong>{{ $unit->address }}</strong></td>
                    <td>{{ $unit->stage === 1 ? 'I' : 'II' }}</td>
                    <td>
                        <span style="display:inline-block;padding:2px 8px;border-radius:100px;font-size:11px;font-weight:700;color:{{ $b[1] }};background:{{ $b[2] }};">{{ $b[0] }}</span>
                        <form method="POST" action="{{ route('admin.magnoolia.units.quick', ['unit' => $unit->unit_key]) }}" style="display:inline-flex;gap:4px;margin-left:6px;">
                            @csrf @method('PATCH')
                            <input type="hidden" name="field" value="status">
                            <select name="status" onchange="this.form.submit()" style="font-size:11px;padding:2px;">
                                @foreach(['available'=>'Vaba','reserved'=>'Broneeritud','sold'=>'Müüdud','coming_soon'=>'Peida'] as $sv=>$sl)
                                    <option value="{{ $sv }}" @selected($unit->status===$sv)>{{ $sl }}</option>
                                @endforeach
                            </select>
                        </form>
                    </td>
                    <td>{{ $unit->rooms }}</td>
                    <td>{{ $unit->net_area }}</td>
                    <td>{{ $unit->private_yard_area }}</td>
                    <td>{{ $unit->price_cents ? number_format($unit->price_cents/100,0,'.',' ').' €' : '—' }}</td>
                    <td>
                        <form method="POST" action="{{ route('admin.magnoolia.units.quick', ['unit' => $unit->unit_key]) }}" style="display:inline;">
                            @csrf @method('PATCH')
                            <input type="hidden" name="field" value="price_public">
                            <input type="hidden" name="price_public" value="{{ $unit->price_public ? '0' : '1' }}">
                            <button type="submit" style="font-size:11px;padding:2px 8px;border-radius:100px;border:1px solid #ccc;background:{{ $unit->price_public ? '#e6f4ec' : '#f3f3f3' }};cursor:pointer;">
                                {{ $unit->price_public ? 'Avalik ✓' : 'Peidetud' }}
                            </button>
                        </form>
                    </td>
                    <td style="font-size:11px;color:#888;">{{ optional($unit->updated_at)->format('Y-m-d H:i') }}</td>
                    <td><a href="{{ route('admin.magnoolia.units.edit', ['unit' => $unit->unit_key]) }}">Edit →</a></td>
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

    <script>
    (function () {
        var all = document.getElementById('bulkAll');
        var form = document.getElementById('bulkForm');
        var countEl = document.getElementById('bulkCount');
        function cbs() { return Array.prototype.slice.call(document.querySelectorAll('.bulk-cb')); }
        function refresh() { countEl.textContent = cbs().filter(function (c) { return c.checked; }).length; }
        if (all) all.addEventListener('change', function () { cbs().forEach(function (c) { c.checked = all.checked; }); refresh(); });
        document.addEventListener('change', function (e) { if (e.target.classList && e.target.classList.contains('bulk-cb')) refresh(); });
        if (form) form.addEventListener('submit', function (e) {
            var checked = cbs().filter(function (c) { return c.checked; });
            var action = form.querySelector('[name=bulk_action]').value;
            if (!checked.length) { e.preventDefault(); alert('Select at least one home first.'); return; }
            if (!action) { e.preventDefault(); alert('Choose a bulk action first.'); return; }
            if (!confirm('Apply "' + action + '" to ' + checked.length + ' home(s)? This changes the draft only (Publish to go live).')) { e.preventDefault(); return; }
            form.querySelectorAll('input[name="units[]"]').forEach(function (n) { n.remove(); });
            checked.forEach(function (c) {
                var i = document.createElement('input'); i.type = 'hidden'; i.name = 'units[]'; i.value = c.value; form.appendChild(i);
            });
        });
        refresh();
    })();
    </script>
@endsection
