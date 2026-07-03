<?php

declare(strict_types=1);

namespace App\Models\Concerns;

trait HasLocalization
{
    /**
     * Get localized value for a field.
     *
     * Supports two naming conventions:
     *   1. Bare fallback:  field='title' → looks for 'title_en', falls back to 'title'
     *   2. Suffixed fallback: field='title' → looks for 'title_en', falls back to 'title_ru'
     *      (used when the fallback-locale value is also stored with a locale suffix)
     *
     * Usage:
     *   $model->localized('title')         → returns 'title_en' or falls back to 'title' / 'title_ru'
     *   $model->localized('launcher_text') → returns 'launcher_text_en' or falls back to 'launcher_text_ru'
     */
    public function localized(string $field, string $fallbackLocale = 'ru'): ?string
    {
        $locale = app()->getLocale();

        if ($locale === $fallbackLocale) {
            // Try bare field first (e.g. 'title'), then suffixed (e.g. 'title_ru')
            return $this->{$field} ?? $this->{$field.'_'.$fallbackLocale} ?? null;
        }

        $localizedValue = $this->{$field.'_'.$locale};

        if (! empty($localizedValue)) {
            return $localizedValue;
        }

        // Fallback: try bare field, then suffixed
        return $this->{$field} ?? $this->{$field.'_'.$fallbackLocale} ?? null;
    }
}
