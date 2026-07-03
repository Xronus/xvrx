<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('realms')) {
            Schema::create('realms', function (Blueprint $table) {
                $table->id();
                $table->string('name', 255)->nullable();
                $table->string('rate', 255)->nullable();
                $table->string('version', 255)->nullable();
                $table->string('icon', 255)->nullable();
                $table->string('description', 255)->nullable();
                $table->string('proffesion', 255)->nullable();
                $table->string('gold', 255)->nullable();
                $table->string('rep', 255)->nullable();
                $table->string('loot', 255)->nullable();
                $table->string('honor', 255)->nullable();
                $table->string('class', 255)->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('realms');
    }
};
