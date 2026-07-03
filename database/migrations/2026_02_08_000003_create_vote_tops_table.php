<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('vote_tops')) {
            Schema::create('vote_tops', function (Blueprint $table) {
                $table->id();
                $table->string('name', 255);
                $table->string('url', 255);
                $table->string('api_key', 255)->nullable();
                $table->string('api_url', 255)->nullable();
                $table->integer('bonus_amount')->default(1);
                $table->string('image', 255)->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('vote_tops');
    }
};
