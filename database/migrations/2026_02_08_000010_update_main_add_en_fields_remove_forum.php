<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('main', function (Blueprint $table) {
            if (! Schema::hasColumn('main', 'title_en')) {
                $table->string('title_en', 255)->nullable()->after('title');
            }
            if (! Schema::hasColumn('main', 'description_en')) {
                $table->string('description_en', 255)->nullable()->after('description');
            }
            if (! Schema::hasColumn('main', 'main__title_en')) {
                $table->string('main__title_en', 255)->nullable()->after('main__title');
            }
            if (! Schema::hasColumn('main', 'main__text_en')) {
                $table->string('main__text_en', 255)->nullable()->after('main__text');
            }
            if (Schema::hasColumn('main', 'forum')) {
                $table->dropColumn('forum');
            }
        });
    }

    public function down(): void
    {
        Schema::table('main', function (Blueprint $table) {
            $table->dropColumn(['title_en', 'description_en', 'main__title_en', 'main__text_en']);
            if (! Schema::hasColumn('main', 'forum')) {
                $table->string('forum', 255)->nullable();
            }
        });
    }
};
