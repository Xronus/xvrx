<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('social_link', function (Blueprint $table) {
            if (! Schema::hasColumn('social_link', 'name')) {
                $table->string('name', 100)->nullable()->after('id');
            }
            if (! Schema::hasColumn('social_link', 'icon')) {
                $table->string('icon', 100)->nullable()->after('class');
            }
            if (! Schema::hasColumn('social_link', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('icon');
            }
        });
    }

    public function down(): void
    {
        Schema::table('social_link', function (Blueprint $table) {
            $table->dropColumn(['name', 'icon', 'is_active']);
        });
    }
};
