<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        if (! Schema::hasTable('shop_purchases') || ! Schema::hasTable('vote_logs')) {
            return;
        }

        Schema::table('shop_purchases', function (Blueprint $table) {
            if (! $this->hasForeign('shop_purchases', 'shop_purchases_item_id_foreign')) {
                $table->foreign('item_id')->references('id')->on('shop_items')->cascadeOnDelete();
            }
        });

        Schema::table('vote_logs', function (Blueprint $table) {
            if (! $this->hasForeign('vote_logs', 'vote_logs_user_id_foreign')) {
                $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            }
            if (! $this->hasForeign('vote_logs', 'vote_logs_vote_top_id_foreign')) {
                $table->foreign('vote_top_id')->references('id')->on('vote_tops')->cascadeOnDelete();
            }
        });
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        Schema::table('shop_purchases', function (Blueprint $table) {
            if ($this->hasForeign('shop_purchases', 'shop_purchases_item_id_foreign')) {
                $table->dropForeign(['item_id']);
            }
        });
        Schema::table('vote_logs', function (Blueprint $table) {
            if ($this->hasForeign('vote_logs', 'vote_logs_user_id_foreign')) {
                $table->dropForeign(['user_id']);
            }
            if ($this->hasForeign('vote_logs', 'vote_logs_vote_top_id_foreign')) {
                $table->dropForeign(['vote_top_id']);
            }
        });
    }

    private function hasForeign(string $table, string $key): bool
    {
        $foreigns = DB::select("SELECT CONSTRAINT_NAME FROM information_schema.TABLE_CONSTRAINTS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND CONSTRAINT_TYPE = 'FOREIGN KEY'", [
            DB::connection()->getDatabaseName(),
            $table,
        ]);

        foreach ($foreigns as $fk) {
            if ($fk->CONSTRAINT_NAME === $key) {
                return true;
            }
        }

        return false;
    }
};
