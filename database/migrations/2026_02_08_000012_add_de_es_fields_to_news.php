<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('news', function (Blueprint $table) {
            if (! Schema::hasColumn('news', 'text_de')) {
                $table->string('text_de', 255)->nullable()->after('text_en');
            }
            if (! Schema::hasColumn('news', 'text_es')) {
                $table->string('text_es', 255)->nullable()->after('text_de');
            }
            if (! Schema::hasColumn('news', 'content_de')) {
                $table->text('content_de')->nullable()->after('content_en');
            }
            if (! Schema::hasColumn('news', 'content_es')) {
                $table->text('content_es')->nullable()->after('content_de');
            }
        });
    }

    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->dropColumn(['text_de', 'text_es', 'content_de', 'content_es']);
        });
    }
};
