@extends('admin.magnoolia._layout')

@section('title', 'Nimekirjad')

@section('admin_content')
<div class="card" style="margin-bottom:14px;">
    <h2 style="margin:0 0 6px;">Nimekirjad lehel</h2>
    <p style="margin:0;color:#6f6a61;font-size:13.5px;">
        Korduvad plokid: avalehe kaardid, välisruumi elemendid, varustuse read, KKK, galerii ja
        arendaja projektid. Igas nimekirjas saab rida lisada, kustutada, järjekorda lohistada ja
        kolmes keeles täita. Muudatus jõuab avalikule lehele alles pärast
        <strong>Publish Website Changes</strong>.
    </p>
</div>

@if(session('status'))
    <div class="status status-ok" style="margin-bottom:12px;">{{ session('status') }}</div>
@endif

@if(count($unseeded) === count(collect($grouped)->flatten(1)))
    <div class="card" style="margin-bottom:14px;border-left:3px solid #c89443;">
        <strong>Nimekirjad on veel tühjad.</strong>
        <p style="margin:6px 0 0;color:#6f6a61;font-size:13.5px;">
            Lehel olev sisu tuleb korra sisse lugeda — käsk <code>php artisan magnoolia:seed-lists</code>.
            Kuni seda pole tehtud, näitab leht senist sisu ja siin ei ole midagi muuta.
        </p>
    </div>
@endif

@foreach($grouped as $page => $lists)
    <div class="card" style="margin-bottom:14px;">
        <h3 style="margin:0 0 12px;border-bottom:1px solid #edf0f4;padding-bottom:8px;">
            {{ $pages[$page] ?? ucfirst($page) }}
        </h3>

        @foreach($lists as $key => $list)
            <div style="display:flex;gap:16px;align-items:flex-start;padding:11px 0;border-bottom:1px solid #f2f4f7;">
                <div style="flex:1;min-width:0;">
                    <a href="{{ route('admin.magnoolia.lists.edit', ['listKey' => $key]) }}"
                       style="font-size:14px;font-weight:600;">{{ $list['label'] }}</a>
                    <div style="font-size:12.5px;color:#6f6a61;margin-top:2px;">{{ $list['description'] ?? '' }}</div>
                    <div style="font-size:11px;color:#9a948a;margin-top:3px;">{{ $list['type_label'] }} · {{ $key }}</div>
                </div>
                <div style="flex-shrink:0;text-align:right;min-width:130px;">
                    @if($list['count'] > 0)
                        <span style="font-size:13px;color:#1d2430;">{{ $list['count'] }} rida</span>
                    @else
                        <span style="font-size:12.5px;color:#9a948a;">Kasutab lehe enda sisu</span>
                    @endif
                    <div style="margin-top:5px;">
                        <a class="btn" style="font-size:12.5px;"
                           href="{{ route('admin.magnoolia.lists.edit', ['listKey' => $key]) }}">Muuda</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endforeach
@endsection
