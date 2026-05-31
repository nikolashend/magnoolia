<x-filament-panels::page>
    <div class="fi-section rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 p-6 shadow-sm max-w-lg space-y-6">
        <p class="text-sm text-gray-600 dark:text-gray-400">
            Enable or disable languages on the public website. Estonian (ET) is always active and cannot be disabled.
        </p>

        <div class="space-y-4">
            {{-- ET --}}
            <div class="flex items-center justify-between rounded-lg border border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 px-4 py-3">
                <div>
                    <p class="font-semibold text-gray-900 dark:text-white">🇪🇪 Estonian (ET)</p>
                    <p class="text-xs text-gray-500">Primary language — always active</p>
                </div>
                <span class="inline-flex items-center rounded-full bg-success-100 px-2.5 py-0.5 text-xs font-medium text-success-800">Active</span>
            </div>

            {{-- RU --}}
            <div class="flex items-center justify-between rounded-lg border border-gray-100 dark:border-gray-700 px-4 py-3">
                <div>
                    <p class="font-semibold text-gray-900 dark:text-white">🇷🇺 Russian (RU)</p>
                    <p class="text-xs text-gray-500">Shown as /ru/... prefix</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" wire:model="lang_ru" class="sr-only peer" @if($lang_ru) checked @endif>
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-gray-600 peer-checked:bg-primary-600 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-full"></div>
                </label>
            </div>

            {{-- EN --}}
            <div class="flex items-center justify-between rounded-lg border border-gray-100 dark:border-gray-700 px-4 py-3">
                <div>
                    <p class="font-semibold text-gray-900 dark:text-white">🇬🇧 English (EN)</p>
                    <p class="text-xs text-gray-500">Shown as /en/... prefix</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" wire:model="lang_en" class="sr-only peer" @if($lang_en) checked @endif>
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-gray-600 peer-checked:bg-primary-600 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-full"></div>
                </label>
            </div>
        </div>

        <div class="flex justify-end pt-2">
            <button
                wire:click="save"
                class="fi-btn fi-btn-size-md inline-flex items-center gap-1.5 rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500"
            >
                Save settings
            </button>
        </div>
    </div>
</x-filament-panels::page>
