<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'night_hold_tag')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('night_hold_tag');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('users') && ! Schema::hasColumn('users', 'night_hold_tag')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('night_hold_tag')->after('email');
            });
        }
    }
};
