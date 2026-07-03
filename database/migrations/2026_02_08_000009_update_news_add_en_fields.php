<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('news', function (Blueprint $table) {
            if (! Schema::hasColumn('news', 'text_en')) {
                $table->string('text_en', 255)->nullable()->after('text');
            }
            if (! Schema::hasColumn('news', 'content_en')) {
                $table->text('content_en')->nullable()->after('content');
            }
        });
    }

    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->dropColumn(['text_en', 'content_en']);
        });
    }
};
