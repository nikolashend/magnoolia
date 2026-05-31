<?php

namespace App\Filament\Pages;

use App\Services\LangFileService;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Arr;

class TranslationManager extends Page
{
    protected static string|\BackedEnum|null $navigationIcon  = 'heroicon-o-language';
    protected static string|\UnitEnum|null   $navigationGroup = 'Site Settings';
    protected static ?string $navigationLabel = 'Translations';
    protected static ?int    $navigationSort  = 2;
    protected static ?string $title = 'Edit Translations';

    public function getView(): string { return 'filament.pages.translation-manager'; }

    public string $locale  = 'et';
    public string $section = 'nav';

    /** Flat key => value pairs currently displayed */
    public array $values = [];

    public function mount(): void
    {
        $this->loadTranslations();
    }

    public function updatedLocale(): void
    {
        $this->loadTranslations();
    }

    public function updatedSection(): void
    {
        $this->loadTranslations();
    }

    private function loadTranslations(): void
    {
        $flat = LangFileService::loadFlat($this->locale);

        // Keep only keys starting with selected section, scalar values only
        $prefix  = $this->section . '.';
        $this->values = [];
        foreach ($flat as $key => $value) {
            if (str_starts_with($key, $prefix) && is_string($value)) {
                $this->values[$key] = $value;
            }
        }
    }

    public function save(): void
    {
        // Merge changes back to the full nested file
        $flat    = LangFileService::loadFlat($this->locale);
        $updated = array_merge($flat, $this->values);

        // Filter to scalar only (don't let arrays sneak in from the form)
        $scalar = array_filter($updated, fn($v) => is_string($v));

        if (LangFileService::saveFlat($scalar, $this->locale)) {
            // Clear Laravel translation cache
            app('translator')->getLoader()->load($this->locale, 'magnoolia', '*');
            Notification::make()->title('Saved')->success()->send();
        } else {
            Notification::make()->title('Save failed — check file permissions')->danger()->send();
        }
    }

    /** Available sections (first-level keys that contain sub-strings) */
    public function getSections(): array
    {
        $flat = LangFileService::loadFlat($this->locale);
        $sections = [];
        foreach (array_keys($flat) as $key) {
            $parts = explode('.', $key);
            if (count($parts) > 1) {
                $sections[$parts[0]] = $parts[0];
            }
        }
        ksort($sections);
        return $sections;
    }

    public function getLocales(): array
    {
        return ['et' => 'ET — Estonian', 'ru' => 'RU — Russian', 'en' => 'EN — English'];
    }
}
