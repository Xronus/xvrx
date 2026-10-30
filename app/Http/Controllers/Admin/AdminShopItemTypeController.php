<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShopItemType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;

class AdminShopItemTypeController extends Controller
{
    public function index()
    {
        $types = ShopItemType::ordered()->get();

        return view('admin.shop-item-types.index', compact('types'));
    }

    public function create()
    {
        return view('admin.shop-item-types.create', [
            'typeOptions' => $this->typeOptions(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateType($request);

        ShopItemType::create($validated);

        Cache::forget('shop_items');

        return redirect()->route('admin.shop-item-types.index')->with('success', __('main.type_added'));
    }

    public function edit(ShopItemType $shop_item_type)
    {
        return view('admin.shop-item-types.edit', [
            'shop_item_type' => $shop_item_type,
            'typeOptions' => $this->typeOptions(),
        ]);
    }

    public function update(Request $request, ShopItemType $shop_item_type)
    {
        $validated = $this->validateType($request);

        $shop_item_type->update($validated);

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
            Rule::in(ShopItemType::allowedValues()),
        ];
    }

    private function validateType(Request $request): array
    {
        return $request->validate(
            ['name_ru' => $this->nameRule()],
            ['name_ru.in' => __('main.shop_item_type_invalid')],
            ['name_ru' => __('main.type')],
        );
    }

    private function typeOptions(): array
    {
        return [
            ShopItemType::ITEMS => __('main.shop_item_type_items'),
            ShopItemType::MONEY => __('main.shop_item_type_money'),
        ];
    }
}
