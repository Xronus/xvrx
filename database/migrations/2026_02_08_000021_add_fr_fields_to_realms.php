<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('realms', function (Blueprint $table) {
            if (! Schema::hasColumn('realms', 'name_fr')) {
                $table->string('name_fr', 255)->nullable()->after('name_es');
            }
            if (! Schema::hasColumn('realms', 'description_fr')) {
                $table->string('description_fr', 255)->nullable()->after('description_es');
            }
        });
    }

    public function down(): void
    {
        Schema::table('realms', function (Blueprint $table) {
            $table->dropColumn(['name_fr', 'description_fr']);
        });
    }
};
