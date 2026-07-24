<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('vote_logs')) {
            return;
        }

        $driver = DB::connection()->getDriverName();

        // Remove the old non-unique index first if it exists
        try {
            if ($driver === 'sqlite') {
                DB::statement('DROP INDEX IF EXISTS vote_logs_user_id_vote_top_id_rewarded_at_index');
            } else {
                DB::statement('ALTER TABLE vote_logs DROP INDEX vote_logs_user_id_vote_top_id_rewarded_at_index');
            }
        } catch (\Exception) {
            // Index didn't exist — that's fine
        }

        if ($driver === 'sqlite') {
            DB::statement(
                'CREATE UNIQUE INDEX IF NOT EXISTS vote_logs_user_vote_date_unique ON vote_logs (user_id, vote_top_id, DATE(rewarded_at))'
            );

            return;
        }

        // Add generated column for the date portion of rewarded_at
        DB::statement(
            'ALTER TABLE vote_logs ADD COLUMN rewarded_date DATE GENERATED ALWAYS AS (DATE(rewarded_at)) STORED'
        );

        // Add unique constraint: one reward per user per top per day
        DB::statement(
            'ALTER TABLE vote_logs ADD UNIQUE INDEX vote_logs_user_vote_date_unique (user_id, vote_top_id, rewarded_date)'
        );
    }

    public function down(): void
    {
        if (! Schema::hasTable('vote_logs')) {
            return;
        }

        $driver = DB::connection()->getDriverName();

        try {
            if ($driver === 'sqlite') {
                DB::statement('DROP INDEX IF EXISTS vote_logs_user_vote_date_unique');
            } else {
                DB::statement('ALTER TABLE vote_logs DROP INDEX vote_logs_user_vote_date_unique');
            }
        } catch (\Exception) {
            // Index didn't exist — that's fine
        }

        if ($driver === 'sqlite') {
            return;
        }

        try {
            DB::statement('ALTER TABLE vote_logs DROP COLUMN rewarded_date');
        } catch (\Exception) {
            // Column didn't exist — that's fine
        }
        DB::statement('ALTER TABLE vote_logs ADD INDEX vote_logs_user_id_vote_top_id_rewarded_at_index (user_id, vote_top_id, rewarded_at)');
    }
};
