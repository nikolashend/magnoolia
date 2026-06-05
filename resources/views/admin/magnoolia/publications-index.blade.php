@extends('admin.magnoolia._layout')

@section('title', 'Publication history')

@section('admin_content')
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
