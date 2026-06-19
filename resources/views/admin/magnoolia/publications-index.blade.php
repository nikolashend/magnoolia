@extends('admin.magnoolia._layout')

@section('title', 'Publication history')

@section('admin_content')
    @if(session('published_version'))
        <div class="card" style="margin-bottom:14px;border-left:4px solid #1f7a44;background:#f1f8f3;">
            <h3 style="margin:0 0 6px;color:#1f7a44;">✓ Published — version {{ session('published_version') }} is now live</h3>
            <p style="margin:0 0 10px;font-size:13px;color:#5b5446;">The public website now reflects this version.</p>
            <div style="display:flex;gap:8px;flex-wrap:wrap;">
                <a href="{{ url('/') }}" target="_blank"><button type="button">View homepage ↗</button></a>
                <a href="{{ url('/kodud-ja-hinnad') }}" target="_blank"><button type="button">Homes &amp; prices ↗</button></a>
                <a href="{{ url('/asendiplaan') }}" target="_blank"><button type="button">Asendiplaan ↗</button></a>
                <a href="{{ route('admin.magnoolia.audit') }}"><button type="button" class="btn-muted">Change history</button></a>
            </div>
        </div>
    @endif
    <div class="card">
        <table>
            <thead><tr><th>Version</th><th>Status</th><th>Published at</th><th>Published by</th><th>Publication note</th><th>Checksum</th><th>Action</th></tr></thead>
            <tbody>
            @foreach($publications as $pub)
                <tr>
                    <td>{{ $pub->version }}</td>
                    <td>{{ $pub->status }}</td>
                    <td>{{ $pub->published_at }}</td>
                    <td>{{ $pub->published_by }}</td>
                    <td>{{ $pub->publication_note }}</td>
                    <td style="max-width:220px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $pub->draft_checksum }}</td>
                    <td>
                        @if(auth()->user()?->isMagnooliaAdmin())
                            <a href="{{ route('admin.magnoolia.publications.rollback.form', ['id' => $pub->id]) }}">Rollback</a>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div style="margin-top:12px;">{{ $publications->links() }}</div>
    </div>
@endsection
