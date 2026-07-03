<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('how_to_start', function (Blueprint $table) {
            if (! Schema::hasColumn('how_to_start', 'launcher_text_de')) {
                $table->string('launcher_text_de', 255)->nullable()->after('launcher_text_en');
            }
            if (! Schema::hasColumn('how_to_start', 'launcher_text_es')) {
                $table->string('launcher_text_es', 255)->nullable()->after('launcher_text_de');
            }
            if (! Schema::hasColumn('how_to_start', 'launcher_description_de')) {
                $table->text('launcher_description_de')->nullable()->after('launcher_description_en');
            }
            if (! Schema::hasColumn('how_to_start', 'launcher_description_es')) {
                $table->text('launcher_description_es')->nullable()->after('launcher_description_de');
            }
        });
    }

    public function down(): void
    {
        Schema::table('how_to_start', function (Blueprint $table) {
            $table->dropColumn(['launcher_text_de', 'launcher_text_es', 'launcher_description_de', 'launcher_description_es']);
        });
    }
};
