<?php

namespace App\Services;

class LanguageSettingsService
{
    private static string $path = '';

    private static function filePath(): string
    {
        if (!self::$path) {
            self::$path = storage_path('app/magnoolia_settings.json');
        }
        return self::$path;
    }

    public static function all(): array
    {
        $file = self::filePath();
        if (!file_exists($file)) {
            return self::defaults();
        }
        $data = json_decode(file_get_contents($file), true);
        return is_array($data) ? array_merge(self::defaults(), $data) : self::defaults();
    }

    public static function defaults(): array
    {
        return [
            'languages' => ['et' => true, 'ru' => true, 'en' => true],
        ];
    }

    public static function activeLocales(): array
    {
        $langs = self::all()['languages'] ?? ['et' => true];
        return array_keys(array_filter($langs));
    }

    public static function isActive(string $locale): bool
    {
        return (bool) (self::all()['languages'][$locale] ?? false);
    }

    public static function save(array $data): bool
    {
        $current = self::all();
        $merged  = array_merge($current, $data);
        // ET is always active
        $merged['languages']['et'] = true;
        return file_put_contents(self::filePath(), json_encode($merged, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX) !== false;
    }
}
