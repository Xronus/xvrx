<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('social_link')) {
            return;
        }

        $now = now();
        $links = [
            ['name' => 'Telegram', 'link' => '#', 'class' => 'ri-telegram-fill', 'icon' => 'ri-telegram-fill'],
            ['name' => 'Discord', 'link' => '#', 'class' => 'ri-discord-fill', 'icon' => 'ri-discord-fill'],
            ['name' => 'VK', 'link' => '#', 'class' => 'ri-vk-fill', 'icon' => 'ri-vk-fill'],
        ];

        foreach ($links as $link) {
            DB::table('social_link')->updateOrInsert(
                ['name' => $link['name']],
                array_merge($link, [
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ])
            );
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('social_link')) {
            return;
        }

        DB::table('social_link')
            ->whereIn('name', ['Telegram', 'Discord', 'VK'])
            ->where('link', '#')
            ->delete();
    }
};
