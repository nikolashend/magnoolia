@extends('admin.magnoolia._layout')

@section('title', $definition['label'])

@section('admin_content')
@php
    // Row order is taken from the order the fields arrive in, which is the order
    // the rows sit in the DOM — so dragging a row is the whole reordering story
    // and no separate "save order" step can get out of step with the values.
    $optionsFor = fn (array $spec) => \App\Models\MagnooliaList::options($spec['options'] ?? '');
@endphp

<div class="card" style="margin-bottom:14px;">
    <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:16px;flex-wrap:wrap;">
        <div>
            <h2 style="margin:0 0 6px;">{{ $definition['label'] }}</h2>
            <p style="margin:0;color:#6f6a61;font-size:13.5px;max-width:70ch;">{{ $definition['description'] ?? '' }}</p>
        </div>
        <a href="{{ route('admin.magnoolia.lists.index') }}" style="font-size:13px;">← Kõik nimekirjad</a>
    </div>
    <p style="margin:10px 0 0;color:#6f6a61;font-size:12.5px;">
        Eesti keel on kohustuslik. Vene ja inglise keele võib tühjaks jätta — siis näidatakse seal eestikeelset teksti.
        Muudatus jõuab avalikule lehele alles pärast <strong>Publish Website Changes</strong>.
    </p>
</div>

@if(session('status'))
    <div class="status status-ok" style="margin-bottom:12px;">{{ session('status') }}</div>
@endif

@if($items->isEmpty())
    <div class="card" style="margin-bottom:14px;">
        <strong>See nimekiri on tühi.</strong>
        <p style="margin:6px 0 0;color:#6f6a61;font-size:13.5px;">
            Leht näitab praegu oma seniseid ridu. Lisa esimene rida allpool või loe olemasolev sisu sisse
            käsuga <code>php artisan magnoolia:seed-lists</code>.
        </p>
    </div>
@endif

<form method="POST" action="{{ route('admin.magnoolia.lists.update', ['listKey' => $list->list_key]) }}" id="mg-list-form">
    @csrf @method('PUT')

    <div id="mg-list-rows">
    @foreach($items as $index => $item)
        <div class="card mg-row" draggable="false" style="margin-bottom:10px;padding:14px;">
            <input type="hidden" name="items[{{ $index }}][id]" value="{{ $item->id }}">

            <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px;">
                <span class="mg-drag" title="Lohista järjekorra muutmiseks"
                      style="cursor:grab;font-size:16px;color:#9a948a;user-select:none;">⣿</span>
                <strong style="font-size:13px;">
                    {{ config('magnoolia_list_types')[$list->type]['item_label'] ?? 'Rida' }}
                    <span class="mg-row-no" style="color:#9a948a;font-weight:400;">#{{ $index + 1 }}</span>
                </strong>

                <label style="margin-left:auto;font-size:12.5px;color:#6f6a61;display:flex;align-items:center;gap:5px;">
                    <input type="checkbox" name="items[{{ $index }}][is_active]" value="1" @checked($item->is_active)>
                    Näita lehel
                </label>

                <button type="submit" formmethod="POST" formnovalidate
                        form="mg-del-{{ $item->id }}"
                        style="background:none;border:1px solid #e5c2c2;color:#a33;border-radius:6px;font-size:12px;padding:3px 9px;cursor:pointer;">
                    Kustuta
                </button>
            </div>

            <div style="display:flex;gap:16px;flex-wrap:wrap;">
                @foreach($fields as $field => $spec)
                    @if(($spec['kind'] ?? 'text') === 'image')
                        <div style="flex:0 0 190px;">
                            <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;">{{ $spec['label'] }}</label>
                            @if($item->mediaItem?->thumb_url)
                                <img src="{{ $item->mediaItem->thumb_url }}" alt="" loading="lazy"
                                     style="width:180px;height:112px;object-fit:cover;border-radius:7px;border:1px solid #e7e9ee;margin-bottom:5px;">
                            @else
                                <div style="width:180px;height:112px;border-radius:7px;border:1px dashed #d8dbe2;background:#f8f9fb;
                                            display:flex;align-items:center;justify-content:center;color:#9a948a;font-size:11.5px;margin-bottom:5px;">
                                    Pilt puudub
                                </div>
                            @endif
                            <select name="items[{{ $index }}][media_item_id]" style="width:180px;font-size:12px;padding:4px;">
                                <option value="">— Pilti pole —</option>
                                @foreach($library as $media)
                                    <option value="{{ $media->id }}" @selected($item->media_item_id === $media->id)>
                                        [{{ $media->category }}] {{ \Illuminate\Support\Str::limit($media->title, 40) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    @elseif($spec['t'] ?? false)
                        <div style="flex:1 1 300px;min-width:240px;">
                            <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;">
                                {{ $spec['label'] }}
                                @if($spec['required'] ?? false)<span style="color:#a33;">*</span>@endif
                            </label>
                            @if($spec['help'] ?? false)
                                <div style="font-size:11px;color:#9a948a;margin-bottom:4px;">{{ $spec['help'] }}</div>
                            @endif
                            @foreach($locales as $locale => $localeLabel)
                                @php $value = ($item->{'payload_' . $locale} ?? [])[$field] ?? ($spec['kind'] === 'lines' ? [] : ''); @endphp
                                <div style="display:flex;gap:6px;align-items:flex-start;margin-bottom:4px;">
                                    <span style="flex:0 0 26px;font-size:10.5px;color:#9a948a;padding-top:6px;text-transform:uppercase;">{{ $locale }}</span>
                                    @if($spec['kind'] === 'textarea')
                                        <textarea name="items[{{ $index }}][{{ $locale }}][{{ $field }}]" rows="3"
                                                  style="flex:1;font-size:12.5px;padding:5px;">{{ $value }}</textarea>
                                    @elseif($spec['kind'] === 'lines')
                                        <textarea name="items[{{ $index }}][{{ $locale }}][{{ $field }}]" rows="4"
                                                  placeholder="Üks punkt rea kohta"
                                                  style="flex:1;font-size:12.5px;padding:5px;">{{ is_array($value) ? implode("\n", $value) : $value }}</textarea>
                                    @else
                                        <input type="text" name="items[{{ $index }}][{{ $locale }}][{{ $field }}]"
                                               value="{{ $value }}" style="flex:1;font-size:12.5px;padding:5px;">
                                    @endif
                                </div>
                            @endforeach
                        </div>

                    @else
                        @php $metaValue = ($item->meta ?? [])[$field] ?? ($spec['default'] ?? ''); @endphp
                        <div style="flex:0 1 230px;min-width:170px;">
                            <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;">{{ $spec['label'] }}</label>
                            @if($spec['help'] ?? false)
                                <div style="font-size:11px;color:#9a948a;margin-bottom:4px;">{{ $spec['help'] }}</div>
                            @endif

                            @if($spec['kind'] === 'select')
                                <select name="items[{{ $index }}][meta][{{ $field }}]" style="width:100%;font-size:12.5px;padding:5px;">
                                    <option value="">—</option>
                                    @foreach($optionsFor($spec) as $optionValue => $optionLabel)
                                        <option value="{{ $optionValue }}" @selected((string) $metaValue === (string) $optionValue)>{{ $optionLabel }}</option>
                                    @endforeach
                                </select>
                            @elseif($spec['kind'] === 'bool')
                                <label style="font-size:12.5px;display:flex;align-items:center;gap:6px;">
                                    <input type="hidden" name="items[{{ $index }}][meta][{{ $field }}]" value="0">
                                    <input type="checkbox" name="items[{{ $index }}][meta][{{ $field }}]" value="1" @checked((bool) $metaValue)>
                                    jah
                                </label>
                            @elseif($spec['kind'] === 'url')
                                <input type="url" name="items[{{ $index }}][meta][{{ $field }}]" value="{{ $metaValue }}"
                                       placeholder="https://" style="width:100%;font-size:12.5px;padding:5px;">
                            @else
                                <input type="text" name="items[{{ $index }}][meta][{{ $field }}]" value="{{ $metaValue }}"
                                       style="width:100%;font-size:12.5px;padding:5px;">
                            @endif
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    @endforeach
    </div>

    <div style="display:flex;gap:10px;align-items:center;margin:16px 0 30px;">
        <button type="submit" class="btn pub">Salvesta nimekiri</button>
        <span style="font-size:12.5px;color:#6f6a61;">Salvestamine ei muuda avalikku lehte — selleks on „Publish Website Changes“.</span>
    </div>
</form>

{{-- Delete forms live outside the editing form: a nested <form> is invalid HTML
     and the browser would drop it, leaving a button that silently does nothing. --}}
@foreach($items as $item)
    <form id="mg-del-{{ $item->id }}" method="POST"
          action="{{ route('admin.magnoolia.lists.items.destroy', ['listKey' => $list->list_key, 'item' => $item->id]) }}">
        @csrf @method('DELETE')
    </form>
@endforeach

<form method="POST" action="{{ route('admin.magnoolia.lists.items.add', ['listKey' => $list->list_key]) }}">
    @csrf
    <button type="submit" class="btn">+ Lisa rida</button>
</form>

<script>
// Drag to reorder. The row's position in the DOM is its position in the submitted
// data, so moving the node is all that has to happen — there is no order value to
// keep in step and nothing to save separately.
(function () {
    var container = document.getElementById('mg-list-rows');
    if (!container) return;
    var dragged = null;

    container.querySelectorAll('.mg-row').forEach(function (row) {
        var handle = row.querySelector('.mg-drag');
        if (!handle) return;
        handle.addEventListener('mousedown', function () { row.draggable = true; });
        row.addEventListener('dragstart', function (e) {
            dragged = row;
            row.style.opacity = '.45';
            e.dataTransfer.effectAllowed = 'move';
        });
        row.addEventListener('dragend', function () {
            row.style.opacity = '';
            row.draggable = false;
            dragged = null;
            renumber();
        });
        row.addEventListener('dragover', function (e) {
            if (!dragged || dragged === row) return;
            e.preventDefault();
            var box = row.getBoundingClientRect();
            var after = (e.clientY - box.top) > box.height / 2;
            container.insertBefore(dragged, after ? row.nextSibling : row);
        });
    });

    function renumber() {
        container.querySelectorAll('.mg-row .mg-row-no').forEach(function (label, i) {
            label.textContent = '#' + (i + 1);
        });
    }
})();
</script>
@endsection
