<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('races')) {
            Schema::create('races', function (Blueprint $table) {
                $table->id();
                $table->integer('race_id')->unique();
                $table->string('name', 100);
                $table->tinyInteger('faction')->default(0);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('races');
    }
};
