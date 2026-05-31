<x-filament-panels::page>
    <div class="space-y-4">
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
            @if(count($values) === 0)
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
                        @foreach($values as $key => $value)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                <td class="px-4 py-2 font-mono text-xs text-gray-500 select-all">{{ $key }}</td>
                                <td class="px-4 py-2">
                                    @if(mb_strlen($value) > 80)
                                        <textarea
                                            wire:model="values.{{ $key }}"
                                            rows="2"
                                            class="w-full rounded border border-gray-200 dark:border-gray-600 bg-transparent px-2 py-1 text-sm focus:ring-1 focus:ring-primary-500 focus:border-primary-500"
                                        >{{ $value }}</textarea>
                                    @else
                                        <input
                                            type="text"
                                            wire:model="values.{{ $key }}"
                                            value="{{ $value }}"
                                            class="w-full rounded border border-gray-200 dark:border-gray-600 bg-transparent px-2 py-1 text-sm focus:ring-1 focus:ring-primary-500 focus:border-primary-500"
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
    </div>
</x-filament-panels::page>
