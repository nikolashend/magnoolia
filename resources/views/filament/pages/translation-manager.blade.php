<x-filament-panels::page>
    <div class="space-y-4">

        {{-- Guidance: when to use Page Texts vs Translations --}}
        <div style="border-left:4px solid #c89443;background:rgba(200,148,67,0.12);border-radius:8px;padding:12px 16px;font-size:13px;line-height:1.6;">
            <strong style="color:#c89443;">Tip:</strong>
            For the main website copy (headlines, leads, notices) use
            <a href="/admin/magnoolia/content" style="text-decoration:underline;font-weight:600;">Page Texts</a> —
            it is simpler and edits ET / RU / EN side by side, with draft &amp; publish.
            Use this Translations screen only for advanced labels and navigation (menu items, buttons, status labels).
        </div>

        {{-- Preview banner --}}
        @if($previewSnapshotId)
            <div class="flex items-center gap-3 rounded-lg bg-amber-50 border border-amber-300 dark:bg-amber-900/20 dark:border-amber-700 px-4 py-3 text-sm">
                <svg class="h-5 w-5 text-amber-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="font-medium text-amber-800 dark:text-amber-200">Previewing a snapshot — values below are from the snapshot, not the live file.</span>
                <button wire:click="cancelPreview" class="ml-auto text-xs font-semibold text-amber-700 dark:text-amber-300 underline">Cancel preview</button>
                <button wire:click="restoreSnapshot({{ $previewSnapshotId }})" class="text-xs font-semibold text-green-700 dark:text-green-300 underline">Restore this snapshot</button>
            </div>
        @endif

        {{-- Locale + Section selectors --}}
        <div class="flex flex-wrap gap-4 items-end">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Language</label>
                <select wire:model.live="locale" class="fi-select-input block w-48 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-3 py-2 text-sm">
                    @foreach($this->getLocales() as $code => $label)
                        <option value="{{ $code }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Section</label>
                <select wire:model.live="section" class="fi-select-input block w-56 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-3 py-2 text-sm">
                    @foreach($this->getSections() as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Translation table --}}
        <div class="fi-ta-content overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
            @if(count($entries) === 0)
                <p class="p-6 text-sm text-gray-500">No string translations found for section "{{ $section }}" in "{{ $locale }}".</p>
            @else
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-900">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-400 w-2/5">Key</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-400">Value</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @php $inputStyle = 'width:100%;border:1px solid #9aa0a6;border-radius:6px;background:#ffffff;color:#1d2430;padding:6px 9px;font-size:13px;'; @endphp
                        @foreach($entries as $i => $entry)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                <td class="px-4 py-2 font-mono text-xs text-gray-500 select-all">{{ $entry['key'] }}</td>
                                <td class="px-4 py-2">
                                    @if(mb_strlen($entry['value']) > 80)
                                        <textarea
                                            wire:model="entries.{{ $i }}.value"
                                            rows="2"
                                            style="{{ $inputStyle }}"
                                        >{{ $entry['value'] }}</textarea>
                                    @else
                                        <input
                                            type="text"
                                            wire:model="entries.{{ $i }}.value"
                                            value="{{ $entry['value'] }}"
                                            style="{{ $inputStyle }}"
                                        />
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        {{-- Save button --}}
        @if(!$previewSnapshotId)
        <div class="flex justify-end">
            <button
                wire:click="save"
                wire:loading.attr="disabled"
                class="fi-btn fi-btn-size-md fi-color-primary fi-btn-color-primary inline-flex items-center gap-1.5 rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 focus:outline-none"
            >
                <svg wire:loading wire:target="save" class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                Save translations
            </button>
        </div>
        @endif

        {{-- Snapshot history --}}
        @php $snapshots = $this->getSnapshots(); @endphp
        @if($snapshots->count() > 0)
        <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 overflow-hidden">
            <div class="px-4 py-3 bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Snapshot history ({{ $locale }}) — last {{ $snapshots->count() }} saves</h3>
                <p class="text-xs text-gray-400 mt-0.5">A snapshot is created automatically before every save. Previewing lets you inspect a snapshot without overwriting the live file.</p>
            </div>
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 dark:text-gray-400">#</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 dark:text-gray-400">Label</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 dark:text-gray-400">Created</th>
                        <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500 dark:text-gray-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach($snapshots as $snap)
                        <tr class="{{ $previewSnapshotId === $snap->id ? 'bg-amber-50 dark:bg-amber-900/20' : 'hover:bg-gray-50 dark:hover:bg-gray-700/30' }}">
                            <td class="px-4 py-2 text-xs text-gray-400">{{ $snap->id }}</td>
                            <td class="px-4 py-2 text-xs text-gray-700 dark:text-gray-300">{{ $snap->label }}</td>
                            <td class="px-4 py-2 text-xs text-gray-400">{{ $snap->created_at->format('d.m.Y H:i:s') }}</td>
                            <td class="px-4 py-2 text-right">
                                <div class="inline-flex gap-3">
                                    @if($previewSnapshotId === $snap->id)
                                        <span class="text-xs text-amber-600 font-semibold">Previewing</span>
                                    @else
                                        <button wire:click="previewSnapshot({{ $snap->id }})"
                                            class="text-xs text-blue-600 hover:text-blue-700 dark:text-blue-400 font-medium">Preview</button>
                                    @endif
                                    <button wire:click="restoreSnapshot({{ $snap->id }})"
                                        wire:confirm="Restore this snapshot? The current state will be snapshotted first so you can undo."
                                        class="text-xs text-green-600 hover:text-green-700 dark:text-green-400 font-medium">Restore</button>
                                    <button wire:click="deleteSnapshot({{ $snap->id }})"
                                        wire:confirm="Delete this snapshot permanently?"
                                        class="text-xs text-red-500 hover:text-red-600 dark:text-red-400 font-medium">Delete</button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

    </div>
</x-filament-panels::page>
