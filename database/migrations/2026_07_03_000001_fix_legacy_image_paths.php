<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Replace legacy nighthold paths in news images
        DB::table('news')->update([
            'images' => DB::raw("REPLACE(images, '../template/nighthold/', 'powerpuffsite/')"),
        ]);
        DB::table('news')->update([
            'images' => DB::raw("REPLACE(images, 'template/nighthold/', 'powerpuffsite/')"),
        ]);

        // Replace legacy paths in stocks
        DB::table('stocks')->update([
            'images' => DB::raw("REPLACE(images, '../template/nighthold/', 'powerpuffsite/')"),
        ]);
        DB::table('stocks')->update([
            'images' => DB::raw("REPLACE(images, 'template/nighthold/', 'powerpuffsite/')"),
        ]);
    }

    public function down(): void
    {
        // Revert is not needed — legacy paths stay in DB
    }
};
