<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShopItemSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['subcategory_id' => 2, 'item_entry' => 46109, 'price' => 1000, 'quantity' => 1, 'sort_order' => 1], // Al'ar
            ['subcategory_id' => 4, 'item_entry' => 44168, 'price' => 500, 'quantity' => 1, 'sort_order' => 1],  // Reins of the Raven Lord
            ['subcategory_id' => 3, 'item_entry' => 13335, 'price' => 200, 'quantity' => 1, 'sort_order' => 1],  // Deathcharger
            ['subcategory_id' => 6, 'item_entry' => 46017, 'price' => 350, 'quantity' => 1, 'sort_order' => 1],  // Val'anyr
            ['subcategory_id' => 6, 'item_entry' => 49623, 'price' => 350, 'quantity' => 1, 'sort_order' => 2],  // Shadowmourne
            ['subcategory_id' => 11, 'item_entry' => 33447, 'price' => 25, 'quantity' => 5, 'sort_order' => 1],  // Runic Healing Potion
            ['subcategory_id' => 11, 'item_entry' => 33448, 'price' => 25, 'quantity' => 5, 'sort_order' => 2],  // Runic Mana Potion
            ['subcategory_id' => 14, 'item_entry' => 43345, 'price' => 100, 'quantity' => 1, 'sort_order' => 1],  // Dragon Hide Bag
        ];

        foreach ($items as $item) {
            DB::table('shop_items')->insert(array_merge($item, [
                'enabled' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
