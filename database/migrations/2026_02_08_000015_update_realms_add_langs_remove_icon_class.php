<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('realms', function (Blueprint $table) {
            if (! Schema::hasColumn('realms', 'name_en')) {
                $table->string('name_en', 255)->nullable()->after('name');
            }
            if (! Schema::hasColumn('realms', 'name_de')) {
                $table->string('name_de', 255)->nullable()->after('name_en');
            }
            if (! Schema::hasColumn('realms', 'name_es')) {
                $table->string('name_es', 255)->nullable()->after('name_de');
            }
            if (! Schema::hasColumn('realms', 'description_en')) {
                $table->string('description_en', 255)->nullable()->after('description');
            }
            if (! Schema::hasColumn('realms', 'description_de')) {
                $table->string('description_de', 255)->nullable()->after('description_en');
            }
            if (! Schema::hasColumn('realms', 'description_es')) {
                $table->string('description_es', 255)->nullable()->after('description_de');
            }
            if (Schema::hasColumn('realms', 'icon')) {
                $table->dropColumn('icon');
            }
            if (Schema::hasColumn('realms', 'class')) {
                $table->dropColumn('class');
            }
        });
    }

    public function down(): void
    {
        Schema::table('realms', function (Blueprint $table) {
            $table->dropColumn(['name_en', 'name_de', 'name_es', 'description_en', 'description_de', 'description_es']);
            if (! Schema::hasColumn('realms', 'icon')) {
                $table->string('icon', 255)->nullable();
            }
            if (! Schema::hasColumn('realms', 'class')) {
                $table->string('class', 255)->nullable();
            }
        });
    }
};
