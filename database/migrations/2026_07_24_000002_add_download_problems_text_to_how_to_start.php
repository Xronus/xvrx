<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('how_to_start', function (Blueprint $table) {
            if (! Schema::hasColumn('how_to_start', 'download_problems_text')) {
                $table->text('download_problems_text')->nullable()->after('torrent_active');
            }
        });
    }

    public function down(): void
    {
        Schema::table('how_to_start', function (Blueprint $table) {
            if (Schema::hasColumn('how_to_start', 'download_problems_text')) {
                $table->dropColumn('download_problems_text');
            }
        });
    }
};
