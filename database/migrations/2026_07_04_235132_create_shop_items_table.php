<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shop_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subcategory_id');
            $table->unsignedInteger('item_entry');
            $table->unsignedInteger('price');
            $table->unsignedInteger('quantity')->default(1);
            $table->boolean('enabled')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->foreign('subcategory_id')
                ->references('id')
                ->on('shop_categories')
                ->cascadeOnDelete();
            $table->index('enabled');
            $table->index('item_entry');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shop_items');
    }
};
