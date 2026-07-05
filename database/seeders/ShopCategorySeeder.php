<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShopCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['id' => 1, 'parent_id' => 0, 'name_ru' => 'Маунты', 'name_en' => 'Mounts', 'name_de' => 'Reittiere', 'name_es' => 'Monturas', 'name_fr' => 'Montures', 'sort_order' => 1],
            ['id' => 2, 'parent_id' => 1, 'name_ru' => 'Летающие', 'name_en' => 'Flying', 'name_de' => 'Flug', 'name_es' => 'Voladores', 'name_fr' => 'Volants', 'sort_order' => 1],
            ['id' => 3, 'parent_id' => 1, 'name_ru' => 'Наземные', 'name_en' => 'Ground', 'name_de' => 'Boden', 'name_es' => 'Terrestres', 'name_fr' => 'Terrestres', 'sort_order' => 2],
            ['id' => 4, 'parent_id' => 1, 'name_ru' => 'Редкие', 'name_en' => 'Rare', 'name_de' => 'Seltene', 'name_es' => 'Raros', 'name_fr' => 'Rares', 'sort_order' => 3],
            ['id' => 5, 'parent_id' => 0, 'name_ru' => 'Экипировка', 'name_en' => 'Equipment', 'name_de' => 'Ausrüstung', 'name_es' => 'Equipamiento', 'name_fr' => 'Équipement', 'sort_order' => 2],
            ['id' => 6, 'parent_id' => 5, 'name_ru' => 'Оружие', 'name_en' => 'Weapons', 'name_de' => 'Waffen', 'name_es' => 'Armas', 'name_fr' => 'Armes', 'sort_order' => 1],
            ['id' => 7, 'parent_id' => 5, 'name_ru' => 'Броня', 'name_en' => 'Armor', 'name_de' => 'Rüstung', 'name_es' => 'Armadura', 'name_fr' => 'Armure', 'sort_order' => 2],
            ['id' => 8, 'parent_id' => 5, 'name_ru' => 'Аксессуары', 'name_en' => 'Accessories', 'name_de' => 'Accessoires', 'name_es' => 'Accesorios', 'name_fr' => 'Accessoires', 'sort_order' => 3],
            ['id' => 9, 'parent_id' => 0, 'name_ru' => 'Питомцы', 'name_en' => 'Pets', 'name_de' => 'Haustiere', 'name_es' => 'Mascotas', 'name_fr' => 'Familiers', 'sort_order' => 3],
            ['id' => 10, 'parent_id' => 0, 'name_ru' => 'Расходники', 'name_en' => 'Consumables', 'name_de' => 'Verbrauchsgüter', 'name_es' => 'Consumibles', 'name_fr' => 'Consommables', 'sort_order' => 4],
            ['id' => 11, 'parent_id' => 10, 'name_ru' => 'Зелья и эликсиры', 'name_en' => 'Potions & Elixirs', 'name_de' => 'Tränke', 'name_es' => 'Pociones', 'name_fr' => 'Potions', 'sort_order' => 1],
            ['id' => 12, 'parent_id' => 10, 'name_ru' => 'Еда и напитки', 'name_en' => 'Food & Drinks', 'name_de' => 'Essen', 'name_es' => 'Comida', 'name_fr' => 'Nourriture', 'sort_order' => 2],
            ['id' => 13, 'parent_id' => 0, 'name_ru' => 'Разное', 'name_en' => 'Miscellaneous', 'name_de' => 'Sonstiges', 'name_es' => 'Varios', 'name_fr' => 'Divers', 'sort_order' => 5],
            ['id' => 14, 'parent_id' => 13, 'name_ru' => 'Сумки', 'name_en' => 'Bags', 'name_de' => 'Taschen', 'name_es' => 'Bolsas', 'name_fr' => 'Sacs', 'sort_order' => 1],
            ['id' => 15, 'parent_id' => 13, 'name_ru' => 'Ключи и пропуски', 'name_en' => 'Keys & Passes', 'name_de' => 'Schlüssel', 'name_es' => 'Llaves', 'name_fr' => 'Clés', 'sort_order' => 2],
        ];

        foreach ($categories as $cat) {
            DB::table('shop_categories')->insert(array_merge($cat, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
