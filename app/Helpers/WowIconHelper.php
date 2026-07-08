<?php

/**
 * Wowhead CDN icon URL helpers.
 *
 * Used in Blade templates to generate icon URLs for race, faction, and class.
 * Registered via composer.json autoload.files.
 */

if (! function_exists('race_icon_url')) {
    /**
     * Get the wowhead CDN URL for a race icon.
     *
     * @param  int  $raceId   TrinityCore race ID (1-11)
     * @param  int  $gender   0 = Male, 1 = Female, 2 = None
     * @param  bool $large    Use 'large' (default) or 'medium' size
     * @return string
     */
    function race_icon_url(int $raceId, int $gender = 0, bool $large = true): string
    {
        $icons = config('wow_icons.races');
        $default = config('wow_icons.default_icon', 'inv_misc_questionmark');
        $size = $large ? 'large' : 'medium';
        $cdn = config('wow_icons.cdn_base', 'https://wow.zamimg.com/images/wow/icons');

        $key = $gender === 1 ? 'female' : 'male';
        $iconName = $icons[$raceId][$key] ?? $icons[$raceId]['male'] ?? $default;

        return "{$cdn}/{$size}/{$iconName}.jpg";
    }
}

if (! function_exists('class_icon_url')) {
    /**
     * Get the wowhead CDN URL for a class icon.
     *
     * @param  int  $classId  TrinityCore class ID (1-11)
     * @param  bool $large    Use 'large' (default false — medium is fine for inline)
     * @return string
     */
    function class_icon_url(int $classId, bool $large = false): string
    {
        $icons = config('wow_icons.classes');
        $default = config('wow_icons.default_icon', 'inv_misc_questionmark');
        $size = $large ? 'large' : 'medium';
        $cdn = config('wow_icons.cdn_base', 'https://wow.zamimg.com/images/wow/icons');

        $iconName = $icons[$classId] ?? $default;

        return "{$cdn}/{$size}/{$iconName}.jpg";
    }
}

if (! function_exists('faction_icon_url')) {
    /**
     * Get the wowhead CDN URL for a faction icon.
     *
     * @param  int  $factionId  0 = Alliance, 1 = Horde
     * @param  bool $large      Use 'large' (default false — medium is fine for inline)
     * @return string
     */
    function faction_icon_url(int $factionId, bool $large = false): string
    {
        $icons = config('wow_icons.factions');
        $default = config('wow_icons.default_icon', 'inv_misc_questionmark');
        $size = $large ? 'large' : 'medium';
        $cdn = config('wow_icons.cdn_base', 'https://wow.zamimg.com/images/wow/icons');

        $iconName = $icons[$factionId] ?? $default;

        return "{$cdn}/{$size}/{$iconName}.jpg";
    }
}
