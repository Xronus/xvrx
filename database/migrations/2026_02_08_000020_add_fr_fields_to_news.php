<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('news', function (Blueprint $table) {
            if (! Schema::hasColumn('news', 'text_fr')) {
                $table->string('text_fr', 255)->nullable()->after('text_es');
            }
            if (! Schema::hasColumn('news', 'content_fr')) {
                $table->text('content_fr')->nullable()->after('content_es');
            }
        });
    }

    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->dropColumn(['text_fr', 'content_fr']);
        });
    }
};
