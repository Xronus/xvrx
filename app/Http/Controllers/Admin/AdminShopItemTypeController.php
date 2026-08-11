<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShopItemType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

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
            'name_ru' => $this->nameRule(),
        ]);

        ShopItemType::create($request->only(['name_ru']));

        Cache::forget('shop_items');

        return redirect()->route('admin.shop-item-types.index')->with('success', __('main.type_added'));
    }

    public function edit(ShopItemType $shop_item_type)
    {
        return view('admin.shop-item-types.edit', compact('shop_item_type'));
    }

    public function update(Request $request, ShopItemType $shop_item_type)
    {
        $request->validate([
            'name_ru' => $this->nameRule(),
        ]);

        $shop_item_type->update($request->only(['name_ru']));

        Cache::forget('shop_items');

        return redirect()->route('admin.shop-item-types.index')->with('success', __('main.type_updated'));
    }

    public function destroy(ShopItemType $shop_item_type)
    {
        $shop_item_type->delete();

        Cache::forget('shop_items');

        return redirect()->route('admin.shop-item-types.index')->with('success', __('main.type_deleted'));
    }

    private function nameRule(): array
    {
        return [
            'required',
            'string',
            'max:128',
            function (string $attribute, mixed $value, \Closure $fail) {
                $allowed = ['item', 'items', 'предмет', 'предметы', 'money', 'gold', 'деньги', 'золото'];

                if (! in_array(strtolower(trim((string) $value)), $allowed, true)) {
                    $fail(__('validation.in', ['attribute' => $attribute]));
                }
            },
        ];
    }
}
