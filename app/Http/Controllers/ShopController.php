<?php

namespace App\Http\Controllers;

use App\Models\ShopCategory;
use App\Models\ShopItem;
use App\Models\SiteSetting;
use App\Services\ShopService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ShopController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(): View
    {
        $user = auth()->user();
        $settings = SiteSetting::first();

        $categories = ShopCategory::topLevel()->get();

        // Load enabled items grouped by category
        $items = ShopItem::enabled()
            ->get()
            ->groupBy('subcategory_id');

        // Get characters for the purchase dropdown
        $characters = $this->getCharacters($user);

        return view('cabinet.shop', compact('user', 'settings', 'categories', 'items', 'characters'));
    }

    public function buy(Request $request, ShopService $shop): JsonResponse
    {
        $request->validate([
            'item_id' => 'required|integer|exists:shop_items,id',
            'character_name' => 'required|string|max:12',
        ]);

        $user = auth()->user();
        $item = ShopItem::findOrFail($request->item_id);

        $result = $shop->buy($user, $item, $request->character_name);

        return response()->json($result);
    }

    private function getCharacters($user): array
    {
        try {
            $accountId = DB::connection('game_auth')
                ->table('account')
                ->where('username', strtoupper($user->username))
                ->value('id');

            if (! $accountId) {
                return [];
            }

            return DB::connection('game_char')
                ->table('characters')
                ->where('account', $accountId)
                ->select('name', 'level')
                ->orderBy('level', 'desc')
                ->get()
                ->toArray();
        } catch (\Exception $e) {
            return [];
        }
    }
}
