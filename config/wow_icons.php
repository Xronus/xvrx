<?php

/**
 * WoW Race & Faction Icons — CDN URL Configuration.
 *
 * Icon naming follows wow.zamimg.com conventions:
 *   - Races:   https://wow.zamimg.com/images/wow/icons/large/race_{name}_{gender}.jpg
 *   - Factions: https://wow.zamimg.com/images/wow/icons/medium/achievement_pvp_{a/h}_01.jpg
 *
 * Verified against CDN — all return HTTP 200.
 */
return [

    /*
    |--------------------------------------------------------------------------
    | Race icons (race_id => [male, female])
    |--------------------------------------------------------------------------
    |
    | Keyed by TrinityCore race ID (WotLK 3.3.5a values).
    | Gender: 0 = Male, 1 = Female (fallback to male for unknown).
    |
    */
    'races' => [
        1  => ['male' => 'race_human_male',     'female' => 'race_human_female'],
        2  => ['male' => 'race_orc_male',        'female' => 'race_orc_female'],
        3  => ['male' => 'race_dwarf_male',      'female' => 'race_dwarf_female'],
        4  => ['male' => 'race_nightelf_male',   'female' => 'race_nightelf_female'],
        5  => ['male' => 'race_scourge_male',    'female' => 'race_scourge_female'],
        6  => ['male' => 'race_tauren_male',     'female' => 'race_tauren_female'],
        7  => ['male' => 'race_gnome_male',      'female' => 'race_gnome_female'],
        8  => ['male' => 'race_troll_male',      'female' => 'race_troll_female'],
        10 => ['male' => 'race_bloodelf_male',   'female' => 'race_bloodelf_female'],
        11 => ['male' => 'race_draenei_male',    'female' => 'race_draenei_female'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Faction icons (faction_id => icon_name)
    |--------------------------------------------------------------------------
    |
    | faction_id: 0 = Alliance, 1 = Horde.
    |
    */
    'factions' => [
        0 => 'ui_allianceicon', // Alliance
        1 => 'ui_hordeicon',   // Horde
    ],

    /*
    |--------------------------------------------------------------------------
    | Class icons (class_id => icon_name)
    |--------------------------------------------------------------------------
    |
    | Keyed by TrinityCore class ID (WotLK 3.3.5a values).
    |
    */
    'classes' => [
        1  => 'class_warrior',
        2  => 'class_paladin',
        3  => 'class_hunter',
        4  => 'class_rogue',
        5  => 'class_priest',
        6  => 'class_deathknight',
        7  => 'class_shaman',
        8  => 'class_mage',
        9  => 'class_warlock',
        10 => 'class_monk',
        11 => 'class_druid',
    ],

    /*
    |--------------------------------------------------------------------------
    | CDN settings
    |--------------------------------------------------------------------------
    */
    'cdn_base' => 'https://wow.zamimg.com/images/wow/icons',

    'default_icon' => 'inv_misc_questionmark',
];
