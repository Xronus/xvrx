<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopItemType extends Model
{
    use HasFactory;

    public const ITEMS = 'items';

    public const MONEY = 'money';

    protected $fillable = [
        'name_ru',
    ];

    public function scopeOrdered(Builder $query): void
    {
        $query->orderBy('id');
    }

    public static function allowedValues(): array
    {
        return [self::ITEMS, self::MONEY];
    }

    public static function normalize(?string $value): ?string
    {
        return match (strtolower(trim((string) $value))) {
            '', 'item', 'items', 'предмет', 'предметы' => self::ITEMS,
            'money', 'gold', 'деньги', 'золото' => self::MONEY,
            default => null,
        };
    }

    public function normalizedName(): ?string
    {
        return self::normalize($this->name_ru);
    }

    public function displayName(): string
    {
        return match ($this->normalizedName()) {
            self::ITEMS => __('main.shop_item_type_items'),
            self::MONEY => __('main.shop_item_type_money'),
            default => $this->name_ru,
        };
    }
}
