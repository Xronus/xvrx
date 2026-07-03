<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('main', function (Blueprint $table) {
            if (! Schema::hasColumn('main', 'main__title_de')) {
                $table->string('main__title_de', 255)->nullable()->after('main__title_en');
            }
            if (! Schema::hasColumn('main', 'main__title_es')) {
                $table->string('main__title_es', 255)->nullable()->after('main__title_de');
            }
            if (! Schema::hasColumn('main', 'main__text_de')) {
                $table->string('main__text_de', 255)->nullable()->after('main__text_en');
            }
            if (! Schema::hasColumn('main', 'main__text_es')) {
                $table->string('main__text_es', 255)->nullable()->after('main__text_de');
            }
        });
    }

    public function down(): void
    {
        Schema::table('main', function (Blueprint $table) {
            $table->dropColumn(['main__title_de', 'main__title_es', 'main__text_de', 'main__text_es']);
        });
    }
};
