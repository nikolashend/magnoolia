@extends('admin.magnoolia._layout')

@section('title', 'Audit log')

@section('admin_content')
    <div class="card">
        <table>
            <thead><tr><th>ID</th><th>Action</th><th>Entity</th><th>Entity ID</th><th>Reason</th><th>Admin</th><th>IP</th><th>Created</th></tr></thead>
            <tbody>
            @foreach($logs as $log)
                <tr>
                    <td>{{ $log->id }}</td>
                    <td>{{ $log->action }}</td>
                    <td>{{ $log->entity_type }}</td>
                    <td>{{ $log->entity_id }}</td>
                    <td>{{ $log->reason }}</td>
                    <td>{{ $log->admin?->name ?? 'Admin #'.$log->admin_user_id }}</td>
                    <td>{{ $log->ip_address }}</td>
                    <td>{{ $log->created_at }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div style="margin-top:12px;">{{ $logs->links() }}</div>
    </div>
@endsection
