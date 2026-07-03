<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('features', function (Blueprint $table) {
            if (! Schema::hasColumn('features', 'title_fr')) {
                $table->string('title_fr')->nullable()->after('title_es');
            }
            if (! Schema::hasColumn('features', 'description_fr')) {
                $table->text('description_fr')->nullable()->after('description_es');
            }
        });
    }

    public function down(): void
    {
        Schema::table('features', function (Blueprint $table) {
            $table->dropColumn(['title_fr', 'description_fr']);
        });
    }
};
