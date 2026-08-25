@extends('admin.magnoolia._layout')

@section('title', 'Piltide asukohad')

@section('admin_content')
<div class="card" style="margin-bottom:14px;">
    <h2 style="margin:0 0 6px;">Piltide asukohad lehel</h2>
    <p style="margin:0;color:#6f6a61;font-size:13.5px;">
        Määra, milline pilt meediakogust ilmub lehel millisesse kohta. Määramata koht näitab
        seniseid pilte — leht ei jää kunagi tühjaks. Muudatus jõuab avalikule lehele alles
        pärast <strong>Publish Website Changes</strong>.
    </p>
</div>

@if(session('status'))
    <div class="status status-ok" style="margin-bottom:12px;">{{ session('status') }}</div>
@endif

@foreach($grouped as $page => $groups)
    <div class="card" id="page-{{ $page }}" style="margin-bottom:14px;scroll-margin-top:16px;">
        <h3 style="margin:0 0 12px;border-bottom:1px solid #edf0f4;padding-bottom:8px;">
            {{ $pages[$page] ?? ucfirst($page) }}
        </h3>

        @foreach($groups as $groupName => $slots)
            @if(count($groups) > 1)
                <div style="margin:18px 0 10px;font-size:12px;font-weight:700;letter-spacing:.04em;text-transform:uppercase;color:#c89443;">{{ $groupName }}</div>
            @endif

            @foreach($slots as $slotKey => $definition)
                @php
                    $binding = $bindings[$slotKey] ?? null;
                    $bound   = $binding?->mediaItem;
                @endphp
                <div style="display:flex;gap:16px;align-items:flex-start;padding:12px 0;border-bottom:1px solid #f2f4f7;">
                    {{-- Current picture: the bound media item, or the file the page ships with --}}
                    <div style="flex-shrink:0;width:132px;">
                        <img src="{{ asset($bound?->thumb_path ?: ($bound?->public_path ?: $definition['default'])) }}"
                             alt="" loading="lazy"
                             style="width:132px;height:88px;object-fit:cover;border-radius:8px;border:1px solid #e7e9ee;background:#f6f7f9;">
                        <div style="font-size:10.5px;color:#9a948a;margin-top:4px;text-align:center;">
                            {{ $bound ? 'Määratud' : 'Vaikimisi pilt' }}
                        </div>
                    </div>

                    <div style="flex:1;min-width:0;">
                        <strong style="font-size:13.5px;">{{ $definition['label'] }}</strong>
                        <div style="font-size:11px;color:#9a948a;margin-bottom:8px;">{{ $slotKey }}</div>

                        <form method="POST" action="{{ route('admin.magnoolia.media.slots.assign', ['slotKey' => $slotKey]) }}"
                              style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
                            @csrf @method('PATCH')
                            <select name="media_item_id" style="min-width:280px;font-size:13px;padding:5px;">
                                <option value="">— Kasuta vaikimisi pilti —</option>
                                @foreach($library as $item)
                                    <option value="{{ $item->id }}" @selected($bound?->id === $item->id)>
                                        [{{ $item->category }}] {{ $item->title }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn" style="font-size:13px;">Salvesta</button>
                        </form>
                    </div>
                </div>
            @endforeach
        @endforeach
    </div>
@endforeach
@endsection
