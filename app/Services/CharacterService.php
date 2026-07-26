<?php

namespace App\Services;

use App\Models\CharacterClass;
use App\Models\Race;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CharacterService
{
    /**
     * Fetch game characters for the given user.
     *
     * @param  User  $user
     * @param  bool  $full  When true, enrich with race/class/faction names and icon URLs.
     *                       When false, return only name and level (for shop dropdowns).
     * @return Collection
     */
    public function getCharacters(User $user, bool $full = true): Collection
    {
        $accountId = DB::connection('game_auth')
            ->table('account')
            ->where('username', strtoupper($user->username))
            ->value('id');

        if (! $accountId) {
            return collect();
        }

        $query = DB::connection('game_char')
            ->table('characters')
            ->where('account', $accountId)
            ->orderBy('level', 'desc');

        if (! $full) {
            return $query->select('name', 'level')->get();
        }

        $races = Race::pluck('name', 'race_id')->toArray();
        $factions = Race::pluck('faction', 'race_id')->toArray();
        $classes = CharacterClass::pluck('name', 'class_id')->toArray();

        return $query
            ->select('name', 'level', 'class', 'race', 'gender', 'online', 'logout_time')
            ->get()
            ->map(function ($char) use ($races, $factions, $classes) {
                $char->class_name = $classes[$char->class] ?? __('main.unknown');
                $char->race_name = $races[$char->race] ?? __('main.unknown');
                $char->faction_id = $factions[$char->race] ?? 0;
                $char->faction = $char->faction_id === 0 ? __('main.alliance') : __('main.horde');
                $char->last_login = $char->logout_time > 0
                    ? date('d.m.Y H:i', $char->logout_time)
                    : __('main.no_data');

                // Icon URLs for the frontend
                $char->race_icon = race_icon_url($char->race, $char->gender);
                $char->race_icon_small = race_icon_url($char->race, $char->gender, false);
                $char->class_icon = class_icon_url($char->class);
                $char->faction_icon = faction_icon_url($char->faction_id);

                return $char;
            });
    }
}
