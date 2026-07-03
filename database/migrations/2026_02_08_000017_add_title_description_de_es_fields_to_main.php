<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('main', function (Blueprint $table) {
            if (! Schema::hasColumn('main', 'title_de')) {
                $table->string('title_de', 255)->nullable()->after('title_en');
            }
            if (! Schema::hasColumn('main', 'title_es')) {
                $table->string('title_es', 255)->nullable()->after('title_de');
            }
            if (! Schema::hasColumn('main', 'description_de')) {
                $table->string('description_de', 255)->nullable()->after('description_en');
            }
            if (! Schema::hasColumn('main', 'description_es')) {
                $table->string('description_es', 255)->nullable()->after('description_de');
            }
        });
    }

    public function down(): void
    {
        Schema::table('main', function (Blueprint $table) {
            $table->dropColumn(['title_de', 'title_es', 'description_de', 'description_es']);
        });
    }
};
