<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Magnoolia Admin')</title>
    <style>
        body{font-family:Inter,Arial,sans-serif;background:#f5f6f8;color:#1d2430;margin:0}
        .wrap{max-width:1320px;margin:0 auto;padding:20px}
        .card{background:#fff;border:1px solid #e7e9ee;border-radius:12px;padding:16px}
        .grid{display:grid;gap:14px}
        .grid-4{grid-template-columns:repeat(4,minmax(0,1fr))}
        .nav{display:flex;flex-wrap:wrap;gap:8px;margin-bottom:14px}
        .nav a{padding:8px 12px;border-radius:8px;text-decoration:none;background:#fff;border:1px solid #d7dce6;color:#1d2430}
        table{width:100%;border-collapse:collapse}
        th,td{padding:10px;border-bottom:1px solid #edf0f4;text-align:left;font-size:13px}
        th{font-size:12px;text-transform:uppercase;letter-spacing:.05em;color:#6f6a61}
        input,select,textarea{width:100%;padding:9px;border:1px solid #d6dce5;border-radius:8px}
        button{padding:10px 14px;border:0;border-radius:8px;background:#1d2430;color:#fff;cursor:pointer}
        .btn-muted{background:#6f6a61}
        .status{padding:2px 8px;border-radius:999px;font-size:11px;font-weight:600}
        .status-ok{background:#e8f5e9;color:#2e7d32}
        .status-warn{background:#fff8e1;color:#8d6e00}
        .status-bad{background:#ffebee;color:#b71c1c}
        .flash{padding:10px;border-radius:8px;margin-bottom:10px}
        .flash-ok{background:#e8f5e9;color:#2e7d32}
        .flash-err{background:#ffebee;color:#b71c1c}
    </style>
</head>
<body>
<div class="wrap">
    <div class="nav">
        <a href="{{ route('admin.magnoolia.dashboard') }}">Dashboard</a>
        <a href="{{ route('admin.magnoolia.units.index') }}">Manage units</a>
        <a href="{{ route('admin.magnoolia.preview') }}">Preview draft</a>
        <a href="{{ route('admin.magnoolia.validate') }}">Validate</a>
        <a href="{{ route('admin.magnoolia.publish.form') }}">Publish changes</a>
        <a href="{{ route('admin.magnoolia.publications.index') }}">Publication history</a>
        <a href="{{ route('admin.magnoolia.campaign') }}">Campaign</a>
        <a href="{{ route('admin.magnoolia.audit') }}">Audit log</a>
        <a href="{{ route('admin.magnoolia.export.csv') }}">Export CSV</a>
    </div>

    @if(session('status'))
        <div class="flash flash-ok">{{ session('status') }}</div>
    @endif
    @if($errors->any())
        <div class="flash flash-err">
            @foreach($errors->all() as $err)<div>{{ $err }}</div>@endforeach
        </div>
    @endif

    @yield('admin_content')
</div>
</body>
</html>
