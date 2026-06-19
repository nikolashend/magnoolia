@extends('admin.magnoolia._layout')

@section('title', 'Changes since last publish')

@section('admin_content')
    <div class="card" style="margin-bottom:14px;">
        <h2 style="margin:0 0 6px;">Changes since last publish</h2>
        <p style="margin:0;color:#6f6a61;font-size:13px;">
            Everything edited in the draft that is <strong>not yet live</strong>. Review here, then
            <a href="{{ route('admin.magnoolia.publish.form') }}">Publish</a> to push it to the public website.
        </p>
    </div>
    <div class="card">
        @include('admin.magnoolia._diff')
    </div>
@endsection
