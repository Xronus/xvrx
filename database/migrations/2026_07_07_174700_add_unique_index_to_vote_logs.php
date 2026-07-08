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

        // Remove the old non-unique index first if it exists
        DB::statement('ALTER TABLE vote_logs DROP INDEX IF EXISTS vote_logs_user_id_vote_top_id_rewarded_at_index');

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

        DB::statement('ALTER TABLE vote_logs DROP INDEX IF EXISTS vote_logs_user_vote_date_unique');
        DB::statement('ALTER TABLE vote_logs DROP COLUMN IF EXISTS rewarded_date');
        DB::statement('ALTER TABLE vote_logs ADD INDEX vote_logs_user_id_vote_top_id_rewarded_at_index (user_id, vote_top_id, rewarded_at)');
    }
};
