{{-- Draft↔live diff renderer. Expects $diff (from MagnooliaDiffService). --}}
@if($diff['first_publish'] ?? false)
    <div class="status status-warn" style="display:block;">
        No active publication yet — the first Publish will push all {{ \App\Models\MagnooliaUnit::count() }} homes live.
    </div>
@elseif(!($diff['has_changes'] ?? false))
    <div class="status status-ok" style="display:block;">No unpublished changes — the draft matches the live site (v{{ $diff['active_version'] ?? '—' }}).</div>
@else
    @if(!empty($diff['units']))
        <h4 style="margin:10px 0 6px;">Homes ({{ count($diff['units']) }})</h4>
        @foreach($diff['units'] as $u)
            <div style="border:1px solid #edf0f4;border-radius:8px;padding:8px 12px;margin-bottom:8px;">
                <strong>{{ $u['address'] }}</strong> @if($u['is_new'])<span class="status status-warn">new</span>@endif
                <table style="margin-top:4px;"><tbody>
                    @foreach($u['rows'] as $r)
                        <tr><td style="width:160px;color:#888;border:0;padding:3px 4px;">{{ $r['label'] }}</td>
                            <td style="border:0;padding:3px 4px;color:#b71c1c;">{{ $r['from'] }}</td>
                            <td style="border:0;padding:3px 4px;color:#1f7a44;">→ {{ $r['to'] }}</td></tr>
                    @endforeach
                </tbody></table>
            </div>
        @endforeach
    @endif

    @if(!empty($diff['content']))
        <h4 style="margin:14px 0 6px;">Page texts ({{ count($diff['content']) }})</h4>
        @foreach($diff['content'] as $c)
            <div style="border:1px solid #edf0f4;border-radius:8px;padding:8px 12px;margin-bottom:8px;">
                <strong>{{ $c['label'] }}</strong> @if($c['is_new'])<span class="status status-warn">new</span>@endif
                <table style="margin-top:4px;"><tbody>
                    @foreach($c['rows'] as $r)
                        <tr><td style="width:80px;color:#888;border:0;padding:3px 4px;">{{ $r['label'] }}</td>
                            <td style="border:0;padding:3px 4px;color:#b71c1c;">{{ $r['from'] }}</td>
                            <td style="border:0;padding:3px 4px;color:#1f7a44;">→ {{ $r['to'] }}</td></tr>
                    @endforeach
                </tbody></table>
            </div>
        @endforeach
    @endif

    @if(!empty($diff['settings']))
        <h4 style="margin:14px 0 6px;">Campaign / settings ({{ count($diff['settings']) }})</h4>
        <table><tbody>
            @foreach($diff['settings'] as $r)
                <tr><td style="width:200px;color:#888;">{{ $r['label'] }}</td>
                    <td style="color:#b71c1c;">{{ $r['from'] }}</td>
                    <td style="color:#1f7a44;">→ {{ $r['to'] }}</td></tr>
            @endforeach
        </tbody></table>
    @endif
@endif
