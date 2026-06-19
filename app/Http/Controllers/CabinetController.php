<?php

namespace App\Http\Controllers;

use App\Models\Race;
use App\Models\CharacterClass;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CabinetController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user();
        $characters = $this->getCharacters($user);
        $settings = SiteSetting::first();

        return view('cabinet.index', compact('user', 'characters', 'settings'));
    }

    public function characters()
    {
        $user = auth()->user();
        $characters = $this->getCharacters($user);
        $settings = SiteSetting::first();

        return view('cabinet.characters', compact('user', 'characters', 'settings'));
    }

    public function votes()
    {
        $user = auth()->user();
        $settings = SiteSetting::first();

        return view('cabinet.votes', compact('user', 'settings'));
    }

    private function getCharacters($user): \Illuminate\Support\Collection
    {
        $characters = collect();

        try {
            $accountId = DB::connection('game_auth')
                ->table('account')
                ->where('username', strtoupper($user->username))
                ->value('id');

            if (!$accountId) {
                return $characters;
            }

            $races = Race::pluck('name', 'race_id')->toArray();
            $factions = Race::pluck('faction', 'race_id')->toArray();
            $classes = CharacterClass::pluck('name', 'class_id')->toArray();

            $characters = DB::connection('game_char')
                ->table('characters')
                ->where('account', $accountId)
                ->select('name', 'level', 'class', 'race', 'online', 'logout_time')
                ->orderBy('level', 'desc')
                ->get()
                ->map(function ($char) use ($races, $factions, $classes) {
                    $char->class_name = $classes[$char->class] ?? 'Неизвестно';
                    $char->race_name = $races[$char->race] ?? 'Неизвестно';
                    $faction = $factions[$char->race] ?? 0;
                    $char->faction = $faction === 0 ? 'Альянс' : 'Орда';
                    $char->last_login = $char->logout_time > 0 ? date('d.m.Y H:i', $char->logout_time) : 'Нет данных';
                    return $char;
                });
        } catch (\Exception $e) {
        }

        return $characters;
    }
}
