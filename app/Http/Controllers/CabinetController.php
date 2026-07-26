<?php

namespace App\Http\Controllers;

use App\Services\CharacterService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CabinetController extends Controller
{
    public function __construct(
        private CharacterService $characterService,
    ) {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user();
        $settings = site_settings();

        return view('cabinet.index', compact('user', 'settings'));
    }

    public function characters()
    {
        $user = auth()->user();
        $settings = site_settings();

        return view('cabinet.characters', compact('user', 'settings'));
    }

    public function votes()
    {
        $user = auth()->user();
        $settings = site_settings();

        return view('cabinet.votes', compact('user', 'settings'));
    }

    public function apiCharacters(Request $request): JsonResponse
    {
        try {
            $user = auth()->user();
            $mode = $request->query('mode');
            $full = !in_array($mode, ['minimal'], true);
            $characters = $this->characterService->getCharacters($user, $full);

            return response()->json([
                'ok' => true,
                'count' => $characters->count(),
                'characters' => $characters->values(),
            ]);
        } catch (\Exception $e) {
            \Log::error('API characters error for user '.auth()->user()?->username.': '.get_class($e));

            return response()->json([
                'ok' => false,
                'message' => __('main.server_error'),
            ]);
        }
    }
}
