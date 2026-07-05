<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShopItem extends Model
{
    protected $fillable = [
        'subcategory_id', 'item_entry', 'name_ru', 'icon_name', 'price', 'quantity', 'enabled', 'sort_order',
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'price' => 'integer',
        'quantity' => 'integer',
        'item_entry' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ShopCategory::class, 'subcategory_id');
    }

    public function scopeEnabled(Builder $query): Builder
    {
        return $query->where('enabled', true)->orderBy('sort_order');
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order');
    }
}
