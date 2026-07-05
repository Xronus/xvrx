<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shop_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('parent_id')->default(0);
            $table->string('name_ru')->default('');
            $table->string('name_en')->default('');
            $table->string('name_de')->default('');
            $table->string('name_es')->default('');
            $table->string('name_fr')->default('');
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index('parent_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shop_categories');
    }
};
