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
        .topbar{background:#1d2430;color:#fff;border-radius:12px;padding:12px 18px;margin-bottom:14px;display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap}
        .topbar .brand{font-weight:800;letter-spacing:.04em}
        .topbar .brand span{color:#c89443}
        .navgroups{display:flex;flex-wrap:wrap;gap:18px;margin-bottom:16px}
        .navgroup{display:flex;flex-direction:column;gap:6px}
        .navgroup h4{margin:0;font-size:10.5px;letter-spacing:.12em;text-transform:uppercase;color:#9a8b6f}
        .navgroup .links{display:flex;flex-wrap:wrap;gap:6px}
        .nav{display:flex;flex-wrap:wrap;gap:8px;margin-bottom:14px}
        .nav a, .navgroup a{padding:7px 11px;border-radius:8px;text-decoration:none;background:#fff;border:1px solid #d7dce6;color:#1d2430;font-size:13px}
        .navgroup a.active{background:#1d2430;color:#fff;border-color:#1d2430}
        .navgroup a.pub{border-color:#c89443;color:#9a6b1f;font-weight:700}
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
    @php $rn = request()->route()?->getName(); @endphp
    <div class="topbar">
        <div class="brand">MAGN<span>OO</span>LIA · Control Center</div>
        <a href="{{ url('/') }}" target="_blank" style="color:#c89443;text-decoration:none;font-size:13px;">View public site ↗</a>
    </div>
    @php $isFullAdmin = optional(auth()->user())->role === 'magnoolia_admin'; @endphp
    <div class="navgroups">
        <div class="navgroup">
            <h4>Daily work</h4>
            <div class="links">
                <a href="{{ route('admin.magnoolia.dashboard') }}" class="{{ $rn==='admin.magnoolia.dashboard'?'active':'' }}">Dashboard</a>
                <a href="{{ route('admin.magnoolia.sitemap') }}" class="{{ $rn==='admin.magnoolia.sitemap'?'active':'' }}">Veebilehe kaart</a>
                <a href="{{ route('admin.magnoolia.units.index') }}" class="{{ str_contains((string)$rn,'units')?'active':'' }}">Homes &amp; Prices</a>
                <a href="{{ route('admin.magnoolia.leads.index') }}" class="{{ str_contains((string)$rn,'leads')?'active':'' }}">Leads</a>
                <a href="{{ route('admin.magnoolia.campaign') }}" class="{{ $rn==='admin.magnoolia.campaign'?'active':'' }}">Campaign</a>
            </div>
        </div>
        <div class="navgroup">
            <h4>Content</h4>
            <div class="links">
                <a href="{{ route('admin.magnoolia.content.index') }}" class="{{ str_contains((string)$rn,'content')?'active':'' }}">Page Texts</a>
                <a href="{{ route('admin.magnoolia.media.index') }}" class="{{ str_contains((string)$rn,'media')?'active':'' }}">Images &amp; Media</a>
            </div>
        </div>
        <div class="navgroup">
            <h4>Publishing</h4>
            <div class="links">
                <a href="{{ route('admin.magnoolia.changes') }}" class="{{ $rn==='admin.magnoolia.changes'?'active':'' }}">Changes</a>
                <a href="{{ route('admin.magnoolia.preview') }}" class="{{ $rn==='admin.magnoolia.preview'?'active':'' }}">Preview Draft</a>
                <a href="{{ route('admin.magnoolia.validate') }}" class="{{ $rn==='admin.magnoolia.validate'?'active':'' }}">Validate</a>
                <a href="{{ route('admin.magnoolia.publish.form') }}" class="pub {{ str_contains((string)$rn,'publish')?'active':'' }}">Publish Website Changes</a>
                <a href="{{ route('admin.magnoolia.publications.index') }}" class="{{ str_contains((string)$rn,'publications')?'active':'' }}">Publication History</a>
            </div>
        </div>
        <div class="navgroup">
            <h4>Help</h4>
            <div class="links">
                <a href="{{ route('admin.magnoolia.help') }}" class="{{ $rn==='admin.magnoolia.help'?'active':'' }}">Help</a>
                <a href="{{ route('admin.magnoolia.export.csv') }}">Export CSV</a>
            </div>
        </div>
        @if($isFullAdmin)
        <div class="navgroup" style="border-left:2px solid #e0d5c0;padding-left:12px;">
            <h4 style="color:#b08646;">Advanced — ADME only</h4>
            <div class="links">
                <a href="{{ route('admin.magnoolia.audit') }}" class="{{ $rn==='admin.magnoolia.audit'?'active':'' }}">Audit log</a>
                <a href="/admin/translation-manager">Translations</a>
                <a href="/admin/language-settings">Languages</a>
                <a href="/admin/nav-items">Navigation Menu</a>
            </div>
        </div>
        @endif
    </div>
    @if($isFullAdmin)
    <div style="margin:-6px 0 14px;font-size:11.5px;color:#9a8b6f;">
        ⚙ <strong>Advanced</strong> sections (Translations, Languages, Navigation, Audit) are usually managed by ADME — use only if you know exactly what you are changing. For normal website copy use <a href="{{ route('admin.magnoolia.content.index') }}" style="color:#9a6b1f;">Page Texts</a>.
    </div>
    @endif

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
