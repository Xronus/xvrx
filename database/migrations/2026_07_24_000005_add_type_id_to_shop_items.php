<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shop_items', function (Blueprint $table) {
            if (Schema::hasColumn('shop_items', 'type_id')) {
                return;
            }

            $table->foreignId('type_id')->nullable()->after('subcategory_id')
                ->constrained('shop_item_types')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('shop_items', function (Blueprint $table) {
            if (Schema::hasColumn('shop_items', 'type_id')) {
                $table->dropForeign(['type_id']);
                $table->dropColumn('type_id');
            }
        });
    }
};
