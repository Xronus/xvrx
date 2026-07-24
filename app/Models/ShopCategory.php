<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShopCategory extends Model
{
    protected $fillable = [
        'parent_id', 'name_ru', 'sort_order',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(ShopCategory::class, 'parent_id');
    }

    public function subcategories(): HasMany
    {
        return $this->hasMany(ShopCategory::class, 'parent_id')->orderBy('sort_order');
    }

    public function items(): HasMany
    {
        return $this->hasMany(ShopItem::class, 'subcategory_id')->where('enabled', true)->orderBy('sort_order');
    }

    public function allItems(): HasMany
    {
        return $this->hasMany(ShopItem::class, 'subcategory_id');
    }

    public function localizedName(): string
    {
        return $this->name_ru ?: '';
    }

    public function scopeTopLevel(Builder $query): Builder
    {
        return $query->where('parent_id', 0)->orderBy('sort_order');
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order');
    }
}
