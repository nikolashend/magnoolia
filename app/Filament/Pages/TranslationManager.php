<?php

namespace App\Filament\Pages;

use App\Models\TranslationSnapshot;
use App\Services\LangFileService;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

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

    /**
     * Indexed list of editable rows: [['key' => 'nav.home', 'value' => 'Avaleht'], ...].
     * Indexed (not keyed by the dotted lang key) so Livewire's wire:model paths
     * like "entries.0.value" resolve correctly — dotted keys (e.g.
     * "values.nav.home") would be misread by Livewire as a nested path.
     */
    public array $entries = [];

    /** Snapshot being previewed (null = live) */
    public ?int $previewSnapshotId = null;

    public function mount(): void
    {
        $this->loadTranslations();
    }

    public function updatedLocale(): void
    {
        $this->previewSnapshotId = null;
        $this->loadTranslations();
    }

    public function updatedSection(): void
    {
        $this->previewSnapshotId = null;
        $this->loadTranslations();
    }

    private function loadTranslations(?array $nested = null): void
    {
        $flat = $nested
            ? \Illuminate\Support\Arr::dot($nested)
            : LangFileService::loadFlat($this->locale);

        $prefix        = $this->section . '.';
        $this->entries = [];
        foreach ($flat as $key => $value) {
            if (str_starts_with($key, $prefix) && is_string($value)) {
                $this->entries[] = ['key' => $key, 'value' => $value];
            }
        }
    }

    public function save(): void
    {
        // 1. Snapshot the CURRENT file state before overwriting
        $current = LangFileService::loadNested($this->locale);
        TranslationSnapshot::create([
            'locale' => $this->locale,
            'file'   => 'magnoolia',
            'data'   => $current,
            'label'  => 'Before save — ' . now()->format('d.m.Y H:i:s') . ' [' . strtoupper($this->locale) . '/' . $this->section . ']',
        ]);
        TranslationSnapshot::prune($this->locale, 'magnoolia', 20);

        // 2. Merge edited values back
        $edited = [];
        foreach ($this->entries as $row) {
            if (isset($row['key']) && is_string($row['key'])) {
                $edited[$row['key']] = (string) ($row['value'] ?? '');
            }
        }
        $flat    = LangFileService::loadFlat($this->locale);
        $updated = array_merge($flat, $edited);
        $scalar  = array_filter($updated, fn($v) => is_string($v));

        if (LangFileService::saveFlat($scalar, $this->locale)) {
            $this->previewSnapshotId = null;
            Notification::make()->title('Saved — snapshot created')->success()->send();
        } else {
            Notification::make()->title('Save failed — check file permissions')->danger()->send();
        }
    }

    public function restoreSnapshot(int $id): void
    {
        $snapshot = TranslationSnapshot::findOrFail($id);

        // Snapshot the CURRENT state before restoring
        $current = LangFileService::loadNested($this->locale);
        TranslationSnapshot::create([
            'locale' => $this->locale,
            'file'   => 'magnoolia',
            'data'   => $current,
            'label'  => 'Before restore — ' . now()->format('d.m.Y H:i:s'),
        ]);
        TranslationSnapshot::prune($this->locale, 'magnoolia', 20);

        if (LangFileService::writeFile(LangFileService::path($this->locale), $snapshot->data)) {
            $this->previewSnapshotId = null;
            $this->loadTranslations();
            Notification::make()->title('Restored to: ' . $snapshot->label)->success()->send();
        } else {
            Notification::make()->title('Restore failed — check file permissions')->danger()->send();
        }
    }

    public function previewSnapshot(int $id): void
    {
        $snapshot = TranslationSnapshot::findOrFail($id);
        $this->previewSnapshotId = $id;
        $this->loadTranslations($snapshot->data);
    }

    public function cancelPreview(): void
    {
        $this->previewSnapshotId = null;
        $this->loadTranslations();
    }

    public function deleteSnapshot(int $id): void
    {
        TranslationSnapshot::findOrFail($id)->delete();
        if ($this->previewSnapshotId === $id) {
            $this->previewSnapshotId = null;
            $this->loadTranslations();
        }
        Notification::make()->title('Snapshot deleted')->success()->send();
    }

    public function getSnapshots(): \Illuminate\Support\Collection
    {
        return TranslationSnapshot::where('locale', $this->locale)
            ->where('file', 'magnoolia')
            ->orderByDesc('id')
            ->limit(20)
            ->get(['id', 'label', 'created_at']);
    }

    /** Friendly labels for the most common top-level lang sections. */
    public const SECTION_LABELS = [
        'nav' => 'Navigation menu',
        'hero' => 'Homepage — hero',
        'section' => 'Homepage — sections',
        'facts' => 'Homepage — facts bar',
        'benefits' => 'Homepage — benefits',
        'pricing' => 'Homes & prices',
        'page' => 'Pages (headings & leads)',
        'statuses' => 'Status labels',
        'stages' => 'Stage labels',
        'contact' => 'Contact',
        'forms' => 'Forms',
        'footer' => 'Footer',
        'faq' => 'FAQ',
        'floorplan' => 'Floor plans',
    ];

    public function getSections(): array
    {
        $flat = LangFileService::loadFlat($this->locale);
        $sections = [];
        foreach (array_keys($flat) as $key) {
            $parts = explode('.', $key);
            if (count($parts) > 1) {
                $top = $parts[0];
                $sections[$top] = self::SECTION_LABELS[$top] ?? ucfirst($top);
            }
        }
        asort($sections);
        return $sections;
    }

    public function getLocales(): array
    {
        return ['et' => 'ET — Estonian', 'ru' => 'RU — Russian', 'en' => 'EN — English'];
    }
}
