<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('main', function (Blueprint $table) {
            if (! Schema::hasColumn('main', 'title_fr')) {
                $table->string('title_fr', 255)->nullable()->after('title_es');
            }
            if (! Schema::hasColumn('main', 'description_fr')) {
                $table->string('description_fr', 255)->nullable()->after('description_es');
            }
            if (! Schema::hasColumn('main', 'main__title_fr')) {
                $table->string('main__title_fr', 255)->nullable()->after('main__title_es');
            }
            if (! Schema::hasColumn('main', 'main__text_fr')) {
                $table->string('main__text_fr', 255)->nullable()->after('main__text_es');
            }
        });
    }

    public function down(): void
    {
        Schema::table('main', function (Blueprint $table) {
            $table->dropColumn(['title_fr', 'description_fr', 'main__title_fr', 'main__text_fr']);
        });
    }
};
