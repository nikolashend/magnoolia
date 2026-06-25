<?php

namespace App\Filament\Pages;

use App\Services\LanguageSettingsService;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class LanguageSettings extends Page
{
    protected static string|\BackedEnum|null $navigationIcon  = 'heroicon-o-globe-alt';
    protected static string|\UnitEnum|null   $navigationGroup = 'Site Settings';
    protected static ?string $navigationLabel = 'Languages';
    protected static ?int    $navigationSort  = 3;
    protected static ?string $title = 'Language Settings';

    public function getView(): string { return 'filament.pages.language-settings'; }

    /** Phase 33.3 — advanced section: full system admin (ADME) only. */
    public static function canAccess(): bool
    {
        return optional(auth()->user())->role === 'magnoolia_admin';
    }

    public bool $lang_et = true;
    public bool $lang_ru = true;
    public bool $lang_en = true;

    public function mount(): void
    {
        $langs = LanguageSettingsService::all()['languages'] ?? [];
        $this->lang_et = (bool) ($langs['et'] ?? true);
        $this->lang_ru = (bool) ($langs['ru'] ?? true);
        $this->lang_en = (bool) ($langs['en'] ?? true);
    }

    public function save(): void
    {
        LanguageSettingsService::save([
            'languages' => [
                'et' => true,              // ET is always active
                'ru' => $this->lang_ru,
                'en' => $this->lang_en,
            ],
        ]);

        Notification::make()->title('Language settings saved')->success()->send();
    }
}
