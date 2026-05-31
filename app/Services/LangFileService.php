<?php

namespace App\Services;

use Illuminate\Support\Arr;

class LangFileService
{
    /** Return all supported locales */
    public static function locales(): array
    {
        return ['et', 'ru', 'en'];
    }

    /** Path to a lang file */
    public static function path(string $locale, string $file = 'magnoolia'): string
    {
        return lang_path("{$locale}/{$file}.php");
    }

    /** Load a lang file and return flat dot-notation array */
    public static function loadFlat(string $locale, string $file = 'magnoolia'): array
    {
        $path = self::path($locale, $file);
        if (!file_exists($path)) {
            return [];
        }
        $data = include $path;
        return is_array($data) ? Arr::dot($data) : [];
    }

    /** Load nested array */
    public static function loadNested(string $locale, string $file = 'magnoolia'): array
    {
        $path = self::path($locale, $file);
        if (!file_exists($path)) {
            return [];
        }
        $data = include $path;
        return is_array($data) ? $data : [];
    }

    /**
     * Save flat dot-notation key=>value array back to a lang PHP file.
     * Converts back to nested structure.
     */
    public static function saveFlat(array $flat, string $locale, string $file = 'magnoolia'): bool
    {
        // We merge onto existing nested to preserve non-scalar structures (arrays)
        $existing = self::loadNested($locale, $file);
        $nested   = self::undot($flat, $existing);
        return self::writeFile(self::path($locale, $file), $nested);
    }

    /** Write nested array to a PHP lang file without BOM */
    public static function writeFile(string $path, array $data): bool
    {
        $export  = self::exportArray($data, 1);
        $content = "<?php\n\nreturn [\n{$export}];\n";
        return file_put_contents($path, $content, LOCK_EX) !== false;
    }

    // ────────────────────────────────────────────────────────────────────────

    /**
     * Undot: merge flat dot-keys back into a nested array,
     * preserving existing nested array structures that are not scalar.
     */
    private static function undot(array $flat, array $existing = []): array
    {
        $result = $existing;
        foreach ($flat as $key => $value) {
            Arr::set($result, $key, $value);
        }
        return $result;
    }

    /** Pretty-print a PHP array for a lang file */
    private static function exportArray(array $data, int $depth): string
    {
        $indent  = str_repeat('    ', $depth);
        $output  = '';
        foreach ($data as $key => $value) {
            $keyStr = is_int($key) ? $key : "'" . addcslashes((string) $key, "'\\") . "'";
            if (is_array($value)) {
                $inner   = self::exportArray($value, $depth + 1);
                $output .= "{$indent}{$keyStr} => [\n{$inner}{$indent}],\n";
            } else {
                $escaped = addcslashes((string) $value, "'\\");
                $output .= "{$indent}{$keyStr} => '{$escaped}',\n";
            }
        }
        return $output;
    }
}
