<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('vote')) {
            Schema::create('vote', function (Blueprint $table) {
                $table->id();
                $table->string('images_company', 255)->nullable();
                $table->string('name', 255)->nullable();
                $table->string('link', 255)->nullable();
                $table->string('bonus', 255)->nullable();
                $table->string('imgcp', 255)->nullable();
                $table->string('file_stat', 255)->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('vote');
    }
};
