<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('main', function (Blueprint $table) {
            if (! Schema::hasColumn('main', 'terms_text')) {
                $table->text('terms_text')->nullable()->after('mail_reset_body');
            }
            if (! Schema::hasColumn('main', 'policy_text')) {
                $table->text('policy_text')->nullable()->after('terms_text');
            }
        });
    }

    public function down(): void
    {
        Schema::table('main', function (Blueprint $table) {
            if (Schema::hasColumn('main', 'terms_text')) {
                $table->dropColumn('terms_text');
            }
            if (Schema::hasColumn('main', 'policy_text')) {
                $table->dropColumn('policy_text');
            }
        });
    }
};
