<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShopCategory;
use App\Models\ShopItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminShopController extends Controller
{
    public function index(Request $request): View
    {
        $query = ShopItem::with('category')->orderBy('id', 'desc');

        if ($request->has('search') && $request->search) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('item_entry', 'like', '%'.$s.'%')
                    ->orWhere('name_ru', 'like', '%'.$s.'%');
            });
        }

        if ($request->has('category') && $request->category) {
            $query->where('subcategory_id', $request->category);
        }

        $items = $query->paginate(20)->withQueryString();
        $categories = ShopCategory::orderBy('sort_order')->get();

        return view('admin.shop.index', compact('items', 'categories'));
    }

    public function create(): View
    {
        $categories = ShopCategory::orderBy('sort_order')->get();

        return view('admin.shop.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'subcategory_id' => 'required|exists:shop_categories,id',
            'item_entry' => 'required|integer|min:1',
            'name_ru' => 'required|string|max:255',
            'icon_name' => 'required|string|max:255',
            'price' => 'required|integer|min:1',
            'quantity' => 'integer|min:1',
            'enabled' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        ShopItem::create($validated);

        return redirect()->route('admin.shop.index')->with('success', __('main.shop_item_added'));
    }

    public function edit(ShopItem $shop): View
    {
        $item = $shop;
        $categories = ShopCategory::orderBy('sort_order')->get();

        return view('admin.shop.edit', compact('item', 'categories'));
    }

    public function update(Request $request, ShopItem $shop): RedirectResponse
    {
        $item = $shop;

        $validated = $request->validate([
            'subcategory_id' => 'required|exists:shop_categories,id',
            'item_entry' => 'required|integer|min:1',
            'name_ru' => 'required|string|max:255',
            'icon_name' => 'required|string|max:255',
            'price' => 'required|integer|min:1',
            'quantity' => 'integer|min:1',
            'enabled' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        $item->update($validated);

        return redirect()->route('admin.shop.index')->with('success', __('main.shop_item_updated'));
    }

    public function destroy(ShopItem $shop): RedirectResponse
    {
        $shop->delete();

        return redirect()->route('admin.shop.index')->with('success', __('main.shop_item_deleted'));
    }
}
