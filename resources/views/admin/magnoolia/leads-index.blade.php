@extends('admin.magnoolia._layout')

@section('title', 'Leads / Inquiries')

@section('admin_content')
    <div class="card" style="margin-bottom:14px;">
        <h2 style="margin:0 0 6px;">Leads / Inquiries</h2>
        <p style="margin:0;color:#6f6a61;font-size:13px;">
            Inquiries submitted through the public contact form and inquiry drawer. Update the handling status as you follow up.
            <strong>{{ $counts['new'] ?? 0 }}</strong> new ·
            <strong>{{ $counts['contacted'] ?? 0 }}</strong> contacted ·
            <strong>{{ $counts['archived'] ?? 0 }}</strong> archived.
        </p>
    </div>

    <div class="card" style="margin-bottom:14px;">
        <form method="GET" style="display:flex;gap:10px;align-items:end;flex-wrap:wrap;">
            <div style="min-width:220px;"><label>Search</label><input type="text" name="q" value="{{ request('q') }}" placeholder="Name, email, home"></div>
            <div><label>Status</label><select name="lead_status"><option value="">All</option>@foreach(['new'=>'New','contacted'=>'Contacted','archived'=>'Archived'] as $k=>$v)<option value="{{ $k }}" @selected(request('lead_status')===$k)>{{ $v }}</option>@endforeach</select></div>
            <div><button type="submit">Filter</button></div>
            <a href="{{ route('admin.magnoolia.leads.export') }}" style="margin-left:auto;"><button type="button" class="btn-muted">Export CSV</button></a>
        </form>
    </div>

    <div class="card">
        @if($leads->total() === 0)
            <div class="status status-ok">No inquiries yet. New leads from the public forms will appear here.</div>
        @else
        <table>
            <thead><tr><th>When</th><th>Name</th><th>Contact</th><th>Home</th><th>Locale</th><th>Source</th><th>E-mail</th><th>Status</th></tr></thead>
            <tbody>
            @foreach($leads as $lead)
                @php $ls = $lead->lead_status ?? 'new'; @endphp
                <tr>
                    <td style="font-size:12px;color:#888;white-space:nowrap;">{{ optional($lead->created_at)->format('Y-m-d H:i') }}</td>
                    <td><strong>{{ $lead->name }}</strong>@if($lead->message)<div style="font-size:11px;color:#9a948a;max-width:260px;">{{ \Illuminate\Support\Str::limit($lead->message, 90) }}</div>@endif</td>
                    <td style="font-size:12px;"><a href="mailto:{{ $lead->email }}">{{ $lead->email }}</a>@if($lead->phone)<br><a href="tel:{{ $lead->phone }}">{{ $lead->phone }}</a>@endif</td>
                    <td style="font-size:12px;">{{ $lead->unit_address ?? '—' }}</td>
                    <td>{{ strtoupper($lead->locale) }}</td>
                    <td style="font-size:11px;color:#888;">{{ $lead->source_component ?? $lead->source_page }}</td>
                    {{-- Phase 36 — delivery status. The lead is always stored, but the
                         mail can fail silently (wrong SMTP, SPF, mailtrap left in .env).
                         Without this column there is no way to tell "nobody enquired"
                         from "the e-mail never reached the sales contact". --}}
                    <td style="font-size:11px;white-space:nowrap;">
                        @php $ms = $lead->mail_status ?? 'sent'; @endphp
                        @if($ms === 'sent')
                            <span title="E-mail sent to the sales contact" style="color:#2e7d32;">&#10003; sent</span>
                        @elseif($ms === 'failed')
                            <span title="Sending failed — check the mail settings and storage/logs/laravel.log" style="color:#c0392b;font-weight:700;">&#9888; failed</span>
                        @else
                            <span title="Sending was skipped" style="color:#9a948a;">skipped</span>
                        @endif
                    </td>
                    <td>
                        <form method="POST" action="{{ route('admin.magnoolia.leads.status', $lead) }}" style="display:inline;">
                            @csrf @method('PATCH')
                            <select name="lead_status" onchange="this.form.submit()" style="font-size:12px;padding:3px;">
                                @foreach(['new'=>'New','contacted'=>'Contacted','archived'=>'Archived'] as $k=>$v)
                                    <option value="{{ $k }}" @selected($ls===$k)>{{ $v }}</option>
                                @endforeach
                            </select>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div style="margin-top:12px;">{{ $leads->links() }}</div>
        @endif
    </div>
@endsection
