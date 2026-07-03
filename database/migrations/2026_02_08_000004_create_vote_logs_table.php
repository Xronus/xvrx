<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('vote_logs')) {
            Schema::create('vote_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('vote_top_id');
                $table->string('ip_address', 45);
                $table->timestamp('rewarded_at');
                $table->timestamps();

                $table->index(['user_id', 'vote_top_id', 'rewarded_at']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('vote_logs');
    }
};
