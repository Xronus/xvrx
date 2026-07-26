<?php

namespace App\Http\Controllers;

use App\Models\ShopCategory;
use App\Models\ShopItem;
use App\Services\ShopService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
        $settings = site_settings();

        $categories = ShopCategory::topLevel()
            ->with('subcategories')
            ->get();

        // Load enabled items grouped by subcategory.
        $items = ShopItem::enabled()
            ->with('category')
            ->get()
            ->groupBy('subcategory_id');

        return view('cabinet.shop', compact('user', 'settings', 'categories', 'items'));
    }

    public function buy(Request $request, ShopService $shop): JsonResponse
    {
        $request->validate([
            'item_id' => 'required|integer|exists:shop_items,id',
            'character_name' => 'required|string|max:12',
        ]);

        $user = auth()->user();
        $item = ShopItem::with('type')->findOrFail($request->item_id);

        $result = $shop->buy($user, $item, $request->character_name);

        return response()->json($result);
    }
}
