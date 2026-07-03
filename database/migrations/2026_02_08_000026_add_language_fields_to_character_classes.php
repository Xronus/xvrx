<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('character_classes', function (Blueprint $table) {
            if (! Schema::hasColumn('character_classes', 'name_en')) {
                $table->string('name_en', 255)->nullable()->after('name');
            }
            if (! Schema::hasColumn('character_classes', 'name_de')) {
                $table->string('name_de', 255)->nullable()->after('name_en');
            }
            if (! Schema::hasColumn('character_classes', 'name_es')) {
                $table->string('name_es', 255)->nullable()->after('name_de');
            }
            if (! Schema::hasColumn('character_classes', 'name_fr')) {
                $table->string('name_fr', 255)->nullable()->after('name_es');
            }
        });
    }

    public function down(): void
    {
        Schema::table('character_classes', function (Blueprint $table) {
            $table->dropColumn(['name_en', 'name_de', 'name_es', 'name_fr']);
        });
    }
};
