<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShopCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminShopCategoryController extends Controller
{
    public function index(): View
    {
        $categories = ShopCategory::orderBy('sort_order')->get();

        return view('admin.shop-categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('admin.shop-categories.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name_ru' => 'required|string|max:128',
            'name_en' => 'nullable|string|max:128',
            'name_de' => 'nullable|string|max:128',
            'name_es' => 'nullable|string|max:128',
            'name_fr' => 'nullable|string|max:128',
            'sort_order' => 'integer|min:0',
        ]);

        $validated['parent_id'] = 0;

        ShopCategory::create($validated);

        return redirect()->route('admin.shop-categories.index')->with('success', __('main.shop_category_added'));
    }

    public function edit(ShopCategory $shop_category): View
    {
        $category = $shop_category;

        return view('admin.shop-categories.edit', compact('category'));
    }

    public function update(Request $request, ShopCategory $shop_category): RedirectResponse
    {
        $category = $shop_category;

        $validated = $request->validate([
            'name_ru' => 'required|string|max:128',
            'name_en' => 'nullable|string|max:128',
            'name_de' => 'nullable|string|max:128',
            'name_es' => 'nullable|string|max:128',
            'name_fr' => 'nullable|string|max:128',
            'sort_order' => 'integer|min:0',
        ]);

        $category->update($validated);

        return redirect()->route('admin.shop-categories.index')->with('success', __('main.shop_category_updated'));
    }

    public function destroy(ShopCategory $shop_category): RedirectResponse
    {
        if ($shop_category->allItems()->exists()) {
            return back()->withErrors(['message' => __('main.shop_category_has_items')]);
        }

        $shop_category->delete();

        return redirect()->route('admin.shop-categories.index')->with('success', __('main.shop_category_deleted'));
    }
}
