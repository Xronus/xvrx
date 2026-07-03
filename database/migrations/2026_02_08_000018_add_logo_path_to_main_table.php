<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('main', function (Blueprint $table) {
            if (! Schema::hasColumn('main', 'logo_path')) {
                $table->string('logo_path', 255)->nullable()->after('description_es');
            }
        });
    }

    public function down(): void
    {
        Schema::table('main', function (Blueprint $table) {
            if (Schema::hasColumn('main', 'logo_path')) {
                $table->dropColumn('logo_path');
            }
        });
    }
};
