@extends('admin.magnoolia._layout')

@section('title', 'Veebilehe kaart')

@section('admin_content')
    <div class="card" style="margin-bottom:14px;">
        <h2 style="margin:0 0 4px;">Veebilehe kaart — mida soovid muuta?</h2>
        <p style="margin:0;color:#6f6a61;font-size:13px;">
            Vali leht, mida soovid muuta. Iga kaart viib otse õige redaktori juurde — tekstid lähevad <strong>Veebilehe tekstidesse</strong>,
            pildid <strong>Piltide ja galerii</strong> alla. Kõik muudatused on mustand, kuni vajutad <em>Avalda</em>.
        </p>
    </div>

    <div class="grid" style="grid-template-columns:repeat(auto-fill,minmax(320px,1fr));">
        @foreach($pages as $pg)
            <div class="card" style="display:flex;flex-direction:column;gap:8px;">
                <div style="display:flex;align-items:baseline;justify-content:space-between;gap:8px;">
                    <strong style="font-size:15.5px;color:#1d2430;">{{ $pg['label'] }}</strong>
                    @if($pg['url'])<a href="{{ $pg['url'] }}" target="_blank" rel="noopener" style="font-size:12px;color:#9a6b1f;text-decoration:none;white-space:nowrap;">Ava avalik leht ↗</a>@endif
                </div>
                @if($pg['url'])<div style="font-size:11.5px;color:#9a948a;word-break:break-all;">{{ $pg['url'] }}</div>@endif

                <div style="display:flex;gap:14px;font-size:12.5px;color:#5b5446;">
                    <span>📝 {{ $pg['text_count'] }} tekstiplokki</span>
                    @if(!is_null($pg['img_count']))<span>🖼 {{ $pg['img_count'] }} galerii pilti</span>@endif
                </div>

                <div style="display:flex;gap:6px;flex-wrap:wrap;">
                    @foreach($pg['statuses'] as $st)
                        <span class="status status-{{ $st['kind'] }}">{{ $st['label'] }}</span>
                    @endforeach
                </div>

                <div style="display:flex;gap:7px;flex-wrap:wrap;margin-top:4px;">
                    @if($pg['text_count'] > 0)
                        <a href="{{ $pg['content_anchor'] }}"><button type="button">Muuda tekste</button></a>
                    @endif
                    <a href="{{ $pg['media_url'] }}"><button type="button" class="btn-muted">Halda pilte</button></a>
                    <a href="{{ route('admin.magnoolia.preview') }}"><button type="button" class="btn-muted">Eelvaade</button></a>
                </div>
            </div>
        @endforeach
    </div>

    <div class="card" style="margin-top:14px;background:#fbf8f3;border-left:4px solid #c89443;">
        <span style="font-size:13px;color:#5b5446;">
            Pärast muudatuste tegemist mine <a href="{{ route('admin.magnoolia.changes') }}">Muudatused</a> →
            <a href="{{ route('admin.magnoolia.preview') }}">Eelvaade</a> →
            <a href="{{ route('admin.magnoolia.publish.form') }}">Avalda</a>. Vajadusel saad
            <a href="{{ route('admin.magnoolia.publications.index') }}">tagasi pöörduda</a>.
        </span>
    </div>
@endsection
