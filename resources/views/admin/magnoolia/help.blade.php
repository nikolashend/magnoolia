@extends('admin.magnoolia._layout')

@section('title', 'Help')

@section('admin_content')
    <div class="card" style="margin-bottom:14px;">
        <h2 style="margin:0 0 6px;">How the Magnoolia Control Center works</h2>
        <p style="margin:0;color:#6f6a61;font-size:13px;">Everything you change is a <strong>draft</strong>. Nothing reaches the public website until you <strong>Publish</strong>. You can always roll back.</p>
    </div>

    <div class="card" style="margin-bottom:14px;">
        <h3 style="margin:0 0 8px;">The publishing flow</h3>
        <ol style="margin:0;padding-left:18px;line-height:1.9;font-size:14px;">
            <li><strong>Edit</strong> homes, prices, page texts, images or the campaign (draft).</li>
            <li><strong>Changes</strong> — review exactly what differs from the live site.</li>
            <li><strong>Preview Draft</strong> — see how it will look.</li>
            <li><strong>Validate</strong> — the system checks for problems (blockers must be fixed).</li>
            <li><strong>Publish Website Changes</strong> — confirm; the public site updates immediately and a new version is saved.</li>
            <li><strong>Publication History → Rollback</strong> — restore an earlier version if something is wrong.</li>
        </ol>
    </div>

    <div class="grid" style="grid-template-columns:1fr 1fr;">
        @php $how = [
            'Change a home’s status' => 'Homes &amp; Prices → use the status dropdown (Vaba / Broneeritud / Müüdud), or select several and use the bulk bar. Then Publish.',
            'Show or hide a price' => 'Homes &amp; Prices → click the “Public price” toggle on a row (or bulk). Internal prices stay private. Then Publish.',
            'Update the campaign' => 'Campaign → set discount type + amount in €, ET/RU/EN text, deadline and CTA. The live preview shows how it looks. Then Publish.',
            'Edit a page headline / notice' => 'Page Texts → edit ET (RU/EN optional). Empty fields fall back to the built-in text. Then Publish.',
            'Replace or add an image' => 'Images &amp; Media → upload (auto-optimized), add alt text (ET/RU/EN), and assign a floor plan to a home. Then Publish.',
            'See who changed what' => 'Change History — every edit, publish and rollback is logged with the user and time.',
        ]; @endphp
        @foreach($how as $title => $body)
            <div class="card"><strong>{{ $title }}</strong><p style="margin:6px 0 0;font-size:13px;color:#5b5446;">{!! $body !!}</p></div>
        @endforeach
    </div>
@endsection
