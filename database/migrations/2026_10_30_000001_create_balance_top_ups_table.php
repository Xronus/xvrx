<?php

use App\Models\BalanceTopUp;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('balance_top_ups', function (Blueprint $table) {
            $table->id();
            $table->uuid('public_id')->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('provider', 32)->default('yookassa');
            $table->string('provider_payment_id')->nullable()->unique();
            $table->uuid('idempotence_key')->unique();
            $table->unsignedBigInteger('amount_kopecks');
            $table->char('currency', 3)->default('RUB');
            $table->unsignedBigInteger('bonus_amount');
            $table->string('status', 32)->default(BalanceTopUp::STATUS_CREATING);
            $table->boolean('is_test')->nullable();
            $table->text('confirmation_url')->nullable();
            $table->string('failure_code', 64)->nullable();
            $table->json('provider_snapshot')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('credited_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('balance_top_ups');
    }
};
