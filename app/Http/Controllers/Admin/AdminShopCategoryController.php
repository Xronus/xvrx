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
        $categories = ShopCategory::with('parent')->orderBy('sort_order')->get();

        return view('admin.shop-categories.index', compact('categories'));
    }

    public function create(): View
    {
        $parentCategories = ShopCategory::topLevel()->get();

        return view('admin.shop-categories.create', compact('parentCategories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name_ru' => 'required|string|max:128',
            'sort_order' => 'integer|min:0',
            'parent_id' => 'nullable|integer',
        ]);

        $validated['parent_id'] = (int) ($validated['parent_id'] ?? 0);
        if ($validated['parent_id'] !== 0 && ! ShopCategory::where('id', $validated['parent_id'])->exists()) {
            return back()->withErrors(['parent_id' => __('main.parent_invalid')])->withInput();
        }

        ShopCategory::create($validated);

        return redirect()->route('admin.shop-categories.index')->with('success', __('main.shop_category_added'));
    }

    public function edit(ShopCategory $shop_category): View
    {
        $category = $shop_category;
        $parentCategories = ShopCategory::topLevel()->where('id', '!=', $category->id)->get();

        return view('admin.shop-categories.edit', compact('category', 'parentCategories'));
    }

    public function update(Request $request, ShopCategory $shop_category): RedirectResponse
    {
        $category = $shop_category;

        $validated = $request->validate([
            'name_ru' => 'required|string|max:128',
            'sort_order' => 'integer|min:0',
            'parent_id' => 'nullable|integer',
        ]);

        $validated['parent_id'] = (int) ($validated['parent_id'] ?? 0);

        if ($validated['parent_id'] !== 0 && ! ShopCategory::where('id', $validated['parent_id'])->exists()) {
            return back()->withErrors(['parent_id' => __('main.parent_invalid')])->withInput();
        }

        // Prevent circular reference: category cannot be its own parent
        if ($validated['parent_id'] === $category->id) {
            $validated['parent_id'] = 0;
        }

        $category->update($validated);

        return redirect()->route('admin.shop-categories.index')->with('success', __('main.shop_category_updated'));
    }

    public function destroy(ShopCategory $shop_category): RedirectResponse
    {
        if ($shop_category->subcategories()->exists()) {
            return back()->withErrors(['message' => __('main.shop_category_has_subcategories')]);
        }

        if ($shop_category->allItems()->exists()) {
            return back()->withErrors(['message' => __('main.shop_category_has_items')]);
        }

        $shop_category->delete();

        return redirect()->route('admin.shop-categories.index')->with('success', __('main.shop_category_deleted'));
    }
}
