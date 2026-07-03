<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('language_settings', function (Blueprint $table) {
            $table->id();
            $table->string('code', 5)->unique();
            $table->string('name', 50);
            $table->string('native_name', 50);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        DB::table('language_settings')->insert([
            ['code' => 'ru', 'name' => 'Russian', 'native_name' => 'Русский', 'is_active' => true, 'sort_order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'en', 'name' => 'English', 'native_name' => 'English', 'is_active' => true, 'sort_order' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'de', 'name' => 'German', 'native_name' => 'Deutsch', 'is_active' => false, 'sort_order' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'es', 'name' => 'Spanish', 'native_name' => 'Español', 'is_active' => false, 'sort_order' => 4, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('language_settings');
    }
};
