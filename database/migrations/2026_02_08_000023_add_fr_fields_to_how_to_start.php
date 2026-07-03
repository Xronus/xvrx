<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('how_to_start', function (Blueprint $table) {
            if (! Schema::hasColumn('how_to_start', 'launcher_text_fr')) {
                $table->string('launcher_text_fr', 255)->nullable()->after('launcher_text_es');
            }
            if (! Schema::hasColumn('how_to_start', 'launcher_description_fr')) {
                $table->text('launcher_description_fr')->nullable()->after('launcher_description_es');
            }
        });
    }

    public function down(): void
    {
        Schema::table('how_to_start', function (Blueprint $table) {
            $table->dropColumn(['launcher_text_fr', 'launcher_description_fr']);
        });
    }
};
