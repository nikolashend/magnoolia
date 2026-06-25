@extends('admin.magnoolia._layout')

@section('title', 'Magnoolia — Images & Media')

@section('admin_content')
    <div class="card" style="margin-bottom:14px;">
        <h2 style="margin:0 0 6px;">Images &amp; Media</h2>
        <p style="margin:0 0 10px;color:#6f6a61;font-size:13px;">
            Upload and manage images for the website. Add <strong>alt text (ET/RU/EN)</strong> for accessibility/SEO and set a category.
            Changes are <strong>draft-only</strong> — they go live after you <a href="{{ route('admin.magnoolia.publish.form') }}">Publish</a>.
        </p>
        <ul style="margin:0 0 14px;padding-left:18px;color:#6f6a61;font-size:12.5px;line-height:1.7;">
            <li><strong>Gallery images</strong> (category “Gallery”) appear on the public <a href="/galerii" target="_blank" rel="noopener">/galerii</a> page after Publish. ✓ live-wired</li>
            <li><strong>Home floor plans</strong> assigned below appear on that home’s detail after Publish. ✓ live-wired</li>
            <li><strong>Page hero images</strong> are <em>recorded for reference only</em> — replacing a page hero is currently handled by the developer. (Selecting one here does not change the live hero yet.)</li>
        </ul>
        <form method="POST" action="{{ route('admin.magnoolia.media.store') }}" enctype="multipart/form-data"
              style="display:grid;grid-template-columns:2fr 2fr 1.5fr auto;gap:10px;align-items:end;">
            @csrf
            <div><label>Image / file</label><input type="file" name="file" required accept=".jpg,.jpeg,.png,.webp,.pdf"></div>
            <div><label>Title</label><input type="text" name="title" placeholder="e.g. Hero — front view"></div>
            <div><label>Category</label>
                <select name="category">@foreach($categories as $k=>$v)<option value="{{ $k }}">{{ $v }}</option>@endforeach</select>
            </div>
            <div><button type="submit">Upload</button></div>
        </form>
        <p style="margin:8px 0 0;color:#9a948a;font-size:11.5px;">Allowed: JPG, PNG, WebP, PDF · max 12&nbsp;MB · raster images are auto-converted to optimized WebP + thumbnail.</p>
    </div>

    <div class="card" style="margin-bottom:14px;">
        <form method="GET" style="display:flex;gap:10px;align-items:end;flex-wrap:wrap;">
            <div style="min-width:220px;"><label>Search</label><input type="text" name="q" value="{{ request('q') }}" placeholder="Title"></div>
            <div><label>Category</label><select name="category"><option value="">All</option>@foreach($categories as $k=>$v)<option value="{{ $k }}" @selected(request('category')===$k)>{{ $v }}</option>@endforeach</select></div>
            <div><label>Alt text</label><select name="missing_alt"><option value="">All</option><option value="1" @selected(request('missing_alt')==='1')>Missing alt only</option></select></div>
            <div><button type="submit">Filter</button></div>
            <div style="margin-left:auto;color:#6f6a61;font-size:13px;align-self:center;">{{ $items->total() }} items</div>
        </form>
    </div>

    @if($items->count() === 0)
        <div class="card"><div class="status status-ok">No media yet — upload your first image above.</div></div>
    @endif

    <div class="grid" style="grid-template-columns:repeat(auto-fill,minmax(300px,1fr));">
        @foreach($items as $item)
            <div class="card">
                <div style="position:relative;aspect-ratio:4/3;background:#f0ede8;border-radius:8px;overflow:hidden;display:flex;align-items:center;justify-content:center;margin-bottom:10px;">
                    @if($item->thumb_url)
                        <img src="{{ $item->thumb_url }}" alt="" style="width:100%;height:100%;object-fit:cover;">
                    @else
                        <span style="color:#9a948a;font-size:12px;">{{ strtoupper(pathinfo($item->public_path ?? $item->original_name, PATHINFO_EXTENSION)) }} file</span>
                    @endif
                    @if($item->alt_missing)
                        <span class="status status-warn" style="position:absolute;top:8px;left:8px;">No alt text</span>
                    @endif
                </div>
                <form method="POST" action="{{ route('admin.magnoolia.media.update', $item) }}" style="display:flex;flex-direction:column;gap:7px;">
                    @csrf @method('PATCH')
                    <input type="text" name="title" value="{{ $item->title }}" required>
                    <div style="display:flex;gap:7px;">
                        <select name="category" style="flex:1;">@foreach($categories as $k=>$v)<option value="{{ $k }}" @selected($item->category===$k)>{{ $v }}</option>@endforeach</select>
                    </div>
                    <input type="text" name="alt_et" value="{{ $item->alt_et }}" placeholder="Alt (ET)">
                    <input type="text" name="alt_ru" value="{{ $item->alt_ru }}" placeholder="Alt (RU)">
                    <input type="text" name="alt_en" value="{{ $item->alt_en }}" placeholder="Alt (EN)">
                    <label style="font-size:11px;color:#6f6a61;">Use this image for…</label>
                    <select name="assignment_target">
                        <option value="">— not assigned —</option>
                        <optgroup label="Home floor plans (draft → publish)">
                            @foreach($units as $u)
                                <option value="unit:{{ $u->unit_key }}:floor1" @selected($item->assignment_target==='unit:'.$u->unit_key.':floor1')>{{ $u->address }} · 1st floor</option>
                                <option value="unit:{{ $u->unit_key }}:floor2" @selected($item->assignment_target==='unit:'.$u->unit_key.':floor2')>{{ $u->address }} · 2nd floor</option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Page hero (recorded for reference only — not yet live)">
                            @foreach(['page:home:hero'=>'Homepage hero','page:asendiplaan:hero'=>'Asendiplaan hero','page:asukoht:hero'=>'Location hero'] as $tk=>$tl)
                                <option value="{{ $tk }}" @selected($item->assignment_target===$tk)>{{ $tl }}</option>
                            @endforeach
                        </optgroup>
                    </select>
                    <div style="display:flex;gap:7px;align-items:center;justify-content:space-between;font-size:11px;color:#9a948a;">
                        <span>{{ $item->width ? $item->width.'×'.$item->height : '' }} · {{ $item->size_bytes ? round($item->size_bytes/1024).' KB' : '' }} · {{ $categories[$item->category] ?? $item->category }}</span>
                    </div>
                    <button type="submit">Save</button>
                </form>
                <form method="POST" action="{{ route('admin.magnoolia.media.destroy', $item) }}" style="margin-top:7px;"
                      onsubmit="return confirm('Delete this media item? If it is used on the live site, you will be asked to re-confirm.');">
                    @csrf @method('DELETE')
                    <input type="hidden" name="confirm_used" value="0">
                    <button type="submit" class="btn-muted" style="background:#b71c1c;width:100%;">Delete</button>
                </form>
            </div>
        @endforeach
    </div>

    <div style="margin-top:14px;">{{ $items->links() }}</div>
@endsection
