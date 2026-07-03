<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Заполняем языковые поля для рас из существующего поля name
        DB::table('races')->get()->each(function ($race) {
            DB::table('races')
                ->where('id', $race->id)
                ->update([
                    'name_en' => $race->name,
                    'name_de' => $race->name,
                    'name_es' => $race->name,
                    'name_fr' => $race->name,
                ]);
        });

        // Заполняем языковые поля для классов из существующего поля name
        DB::table('character_classes')->get()->each(function ($class) {
            DB::table('character_classes')
                ->where('id', $class->id)
                ->update([
                    'name_en' => $class->name,
                    'name_de' => $class->name,
                    'name_es' => $class->name,
                    'name_fr' => $class->name,
                ]);
        });
    }

    public function down(): void
    {
        // При откате миграции просто очищаем языковые поля
        DB::table('races')->update([
            'name_en' => null,
            'name_de' => null,
            'name_es' => null,
            'name_fr' => null,
        ]);

        DB::table('character_classes')->update([
            'name_en' => null,
            'name_de' => null,
            'name_es' => null,
            'name_fr' => null,
        ]);
    }
};
