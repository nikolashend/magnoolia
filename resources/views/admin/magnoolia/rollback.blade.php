@extends('admin.magnoolia._layout')

@section('title', 'Rollback publication')

@section('admin_content')
    <div class="card">
        <h2>Rollback source version #{{ $publication->version }}</h2>
        <p>This will create a new publication version and switch public snapshot atomically.</p>
        <form method="POST" action="{{ route('admin.magnoolia.publications.rollback', ['id' => $publication->id]) }}">
            @csrf
            <div style="margin-bottom:8px;"><label>Reason (required)</label><input type="text" name="reason" required maxlength="500"></div>
            <button type="submit">Create rollback publication</button>
        </form>
    </div>
@endsection
