<?php

declare(strict_types=1);

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Cache;

if (! function_exists('site_settings')) {
    /**
     * Get cached site settings.
     *
     * Returns the cached SiteSetting model or fetches (and caches) it from DB.
     * The cache is invalidated when settings are updated in the admin panel.
     */
    function site_settings(): ?SiteSetting
    {
        return Cache::rememberForever('site_settings', function () {
            return SiteSetting::first();
        });
    }
}

if (! function_exists('site_settings_forget')) {
    /**
     * Invalidate the cached site settings.
     *
     * Call this after updating site settings in the admin panel.
     */
    function site_settings_forget(): void
    {
        Cache::forget('site_settings');
    }
}
