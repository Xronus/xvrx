<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopItemType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_ru',
    ];

    public function scopeOrdered(Builder $query): void
    {
        $query->orderBy('id');
    }
}
