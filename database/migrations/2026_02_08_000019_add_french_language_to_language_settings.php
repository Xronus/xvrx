<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Check if French language already exists
        $exists = DB::table('language_settings')->where('code', 'fr')->exists();

        if (! $exists) {
            DB::table('language_settings')->insert([
                ['code' => 'fr', 'name' => 'French', 'native_name' => 'Français', 'is_active' => false, 'sort_order' => 5, 'created_at' => now(), 'updated_at' => now()],
            ]);
        }
    }

    public function down(): void
    {
        DB::table('language_settings')->where('code', 'fr')->delete();
    }
};
