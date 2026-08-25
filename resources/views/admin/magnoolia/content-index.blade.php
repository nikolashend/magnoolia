@extends('admin.magnoolia._layout')

@section('title', 'Magnoolia — Page Texts')

@section('admin_content')
    <div class="card" style="margin-bottom:14px;">
        <h2 style="margin:0 0 6px;">Page Texts</h2>
        <p style="margin:0;color:#6f6a61;font-size:13px;">
            Edit the headlines and notices that appear on the public website, in <strong>ET / RU / EN</strong>.
            Edits are <strong>draft only</strong> — they go live after you
            <a href="{{ route('admin.magnoolia.publish.form') }}">Publish</a>. Empty fields fall back to the built-in text (nothing is ever blanked).
        </p>
    </div>

    @if($blocks->isEmpty())
        <div class="card"><div class="status status-warn">Content blocks are not initialized yet. Please contact your administrator to finish setup.</div></div>
    @endif

    @foreach($blocks as $page => $pageBlocks)
        <div class="card" id="page-{{ $page }}" style="margin-bottom:14px;scroll-margin-top:16px;">
            <h3 style="margin:0 0 12px;border-bottom:1px solid #edf0f4;padding-bottom:8px;">{{ $pages[$page] ?? ucfirst($page) }}</h3>
            {{-- Phase 36 Module A: a page can now hold ~40 texts instead of three, so
                 they are grouped by the block they belong to on the site. Without this
                 the client scrolls a flat list looking for "the area column heading". --}}
            @foreach($pageBlocks->groupBy(fn ($b) => $b->group ?: '—') as $groupName => $groupBlocks)
            @if($pageBlocks->pluck('group')->filter()->unique()->count() > 1)
                <div style="margin:18px 0 10px;font-size:12px;font-weight:700;letter-spacing:.04em;text-transform:uppercase;color:#c89443;">{{ $groupName }}</div>
            @endif
            @foreach($groupBlocks as $block)
                <form method="POST" action="{{ route('admin.magnoolia.content.update', $block) }}" style="margin-bottom:18px;">
                    @csrf @method('PATCH')
                    <div style="display:flex;align-items:center;justify-content:space-between;gap:10px;margin-bottom:6px;">
                        <strong style="font-size:13.5px;">{{ $block->label }}</strong>
                        <span style="font-size:11px;color:#9a948a;">key: {{ $block->key }}
                            @if($block->ru_missing)<span class="status status-warn">RU missing</span>@endif
                            @if($block->en_missing)<span class="status status-warn">EN missing</span>@endif
                        </span>
                    </div>
                    <div class="grid" style="grid-template-columns:repeat(3,1fr);">
                        <div><label>Estonian (ET) <span style="color:#b71c1c;">*</span></label><textarea name="et" rows="2">{{ $block->et }}</textarea></div>
                        <div><label>Russian (RU)</label><textarea name="ru" rows="2">{{ $block->ru }}</textarea></div>
                        <div><label>English (EN)</label><textarea name="en" rows="2">{{ $block->en }}</textarea></div>
                    </div>
                    <div style="display:flex;align-items:center;gap:14px;margin-top:8px;">
                        <label style="display:flex;align-items:center;gap:6px;font-size:12.5px;width:auto;">
                            <input type="checkbox" name="is_active" value="1" @checked($block->is_active) style="width:auto;"> Active (shown on site)
                        </label>
                        <button type="submit">Save draft</button>
                    </div>
                </form>
            @endforeach
            @endforeach
        </div>
    @endforeach
@endsection
