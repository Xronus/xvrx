<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('main', function (Blueprint $table) {
            if (! Schema::hasColumn('main', 'mail_password_reset_rate_limit')) {
                $table->unsignedTinyInteger('mail_password_reset_rate_limit')->default(3)->after('mail_reset_body');
            }
        });

        DB::table('main')->updateOrInsert(
            ['id' => 1],
            [
                'mail_password_reset_rate_limit' => 3,
                'updated_at' => now(),
            ]
        );
    }

    public function down(): void
    {
        Schema::table('main', function (Blueprint $table) {
            if (Schema::hasColumn('main', 'mail_password_reset_rate_limit')) {
                $table->dropColumn('mail_password_reset_rate_limit');
            }
        });
    }
};
