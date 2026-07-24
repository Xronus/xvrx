<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('realms', function (Blueprint $table) {
            if (! Schema::hasColumn('realms', 'link_url')) {
                $table->string('link_url', 500)->nullable()->after('honor');
            }
            if (! Schema::hasColumn('realms', 'link_text')) {
                $table->string('link_text', 100)->nullable()->after('link_url');
            }
        });
    }

    public function down(): void
    {
        Schema::table('realms', function (Blueprint $table) {
            if (Schema::hasColumn('realms', 'link_url')) {
                $table->dropColumn('link_url');
            }
            if (Schema::hasColumn('realms', 'link_text')) {
                $table->dropColumn('link_text');
            }
        });
    }
};
