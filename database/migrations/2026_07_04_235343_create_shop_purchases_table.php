<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shop_purchases', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('item_id');
            $table->string('character_name');
            $table->unsignedInteger('price');
            $table->unsignedInteger('item_entry');
            $table->unsignedInteger('quantity');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->index('user_id');
            $table->index('item_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shop_purchases');
    }
};
