<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('realms') && ! Schema::hasColumn('realms', 'full_name')) {
            Schema::table('realms', function (Blueprint $table) {
                $table->string('full_name', 255)->nullable()->after('version');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('realms') && Schema::hasColumn('realms', 'full_name')) {
            Schema::table('realms', function (Blueprint $table) {
                $table->dropColumn('full_name');
            });
        }
    }
};
