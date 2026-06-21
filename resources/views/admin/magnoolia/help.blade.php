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

    <div class="card" style="margin-bottom:14px;border-left:4px solid #c89443;">
        <h3 style="margin:0 0 4px;">First 5 tasks — a 15-minute walkthrough</h3>
        <p style="margin:0 0 12px;color:#6f6a61;font-size:13px;">The five things you will do most often. Each shows where to click, what happens straight away, when it becomes public, and how to undo it.</p>
        @php $first5 = [
            [
                'n' => '1', 'title' => 'Change a home’s status',
                'click' => 'Homes &amp; Prices → find the home → choose Vaba / Broneeritud / Müüdud in the status dropdown (or tick several rows and use the bulk bar). Enter a short reason and Save.',
                'now' => 'The home is updated in your <strong>draft</strong>. The public website does not change yet.',
                'public' => 'After you go to <strong>Publish Website Changes</strong> and confirm.',
                'undo' => 'Change the status back and Publish again, or use <strong>Publication History → Rollback</strong> to restore the previous version.',
            ],
            [
                'n' => '2', 'title' => 'Hide or show a price',
                'click' => 'Homes &amp; Prices → toggle <strong>Public price</strong> on the row (or bulk). The internal price is always kept private; the toggle only controls whether it is shown publicly.',
                'now' => 'Saved to the <strong>draft</strong>. Public pages are unchanged for now.',
                'public' => 'After <strong>Publish</strong>.',
                'undo' => 'Toggle it back and Publish, or roll back to an earlier publication.',
            ],
            [
                'n' => '3', 'title' => 'Edit a page headline or notice',
                'click' => 'Page Texts → pick the page → edit the Estonian (ET) field; Russian/English are optional. Click <strong>Save draft</strong>.',
                'now' => 'Saved as a <strong>draft</strong> text. Empty fields automatically fall back to the built-in wording — nothing is ever left blank.',
                'public' => 'After <strong>Publish</strong>.',
                'undo' => 'Clear the field (it falls back to the original) or type the previous text, then Publish.',
            ],
            [
                'n' => '4', 'title' => 'Upload or replace a gallery image',
                'click' => 'Images &amp; Media → upload a file (it is auto-optimized to WebP + thumbnail), set the category to <strong>Gallery</strong>, and add alt text (ET/RU/EN).',
                'now' => 'The image is stored and visible in the Media Library immediately; the public gallery is unchanged until you publish.',
                'public' => 'After <strong>Publish</strong>, gallery-category images appear on the public <a href="/galerii" target="_blank" rel="noopener">/galerii</a> page.',
                'undo' => 'Delete the image (you will be warned if it is live) or roll back the publication.',
            ],
            [
                'n' => '5', 'title' => 'Preview and publish',
                'click' => 'Preview Draft → check how it looks → Validate (fix any blockers) → <strong>Publish Website Changes</strong> and confirm.',
                'now' => 'A new published version is created and the <strong>public site updates immediately</strong>.',
                'public' => 'Right away, the moment you confirm Publish.',
                'undo' => '<strong>Publication History → Rollback</strong> restores any earlier version with one click.',
            ],
        ]; @endphp
        <div style="display:flex;flex-direction:column;gap:10px;">
            @foreach($first5 as $t)
                <div style="border:1px solid #edf0f4;border-radius:8px;padding:12px 14px;">
                    <div style="font-weight:700;color:#1d2430;margin-bottom:6px;">{{ $t['n'] }}. {{ $t['title'] }}</div>
                    <div style="font-size:13px;color:#5b5446;line-height:1.6;">
                        <div><span style="color:#9a6b1f;font-weight:600;">Where:</span> {!! $t['click'] !!}</div>
                        <div style="margin-top:3px;"><span style="color:#9a6b1f;font-weight:600;">Happens now:</span> {!! $t['now'] !!}</div>
                        <div style="margin-top:3px;"><span style="color:#9a6b1f;font-weight:600;">Goes public:</span> {!! $t['public'] !!}</div>
                        <div style="margin-top:3px;"><span style="color:#9a6b1f;font-weight:600;">How to undo:</span> {!! $t['undo'] !!}</div>
                    </div>
                </div>
            @endforeach
        </div>
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
