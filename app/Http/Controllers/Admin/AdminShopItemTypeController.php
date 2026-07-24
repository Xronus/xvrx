<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShopItemType;
use Illuminate\Http\Request;

class AdminShopItemTypeController extends Controller
{
    public function index()
    {
        $types = ShopItemType::ordered()->get();

        return view('admin.shop-item-types.index', compact('types'));
    }

    public function create()
    {
        return view('admin.shop-item-types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name_ru' => 'required|string|max:128',
        ]);

        ShopItemType::create($request->only(['name_ru']));

        return redirect()->route('admin.shop-item-types.index')->with('success', __('main.type_added'));
    }

    public function edit(ShopItemType $shop_item_type)
    {
        return view('admin.shop-item-types.edit', compact('shop_item_type'));
    }

    public function update(Request $request, ShopItemType $shop_item_type)
    {
        $request->validate([
            'name_ru' => 'required|string|max:128',
        ]);

        $shop_item_type->update($request->only(['name_ru']));

        return redirect()->route('admin.shop-item-types.index')->with('success', __('main.type_updated'));
    }

    public function destroy(ShopItemType $shop_item_type)
    {
        $shop_item_type->delete();

        return redirect()->route('admin.shop-item-types.index')->with('success', __('main.type_deleted'));
    }
}
