<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('character_classes')) {
            Schema::create('character_classes', function (Blueprint $table) {
                $table->id();
                $table->integer('class_id')->unique();
                $table->string('name', 100);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('character_classes');
    }
};
