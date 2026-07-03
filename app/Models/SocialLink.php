<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialLink extends Model
{
    use HasFactory;

    protected $table = 'social_link';

    protected $fillable = [
        'name',
        'link',
        'class',
        'icon',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function remixIconClass(): string
    {
        $source = trim((string) ($this->icon ?: $this->class));

        if (preg_match('/\bri-[a-z0-9-]+(?:-fill|-line)?\b/i', $source, $matches)) {
            return $matches[0];
        }

        $needle = strtolower($source.' '.$this->name);

        return match (true) {
            str_contains($needle, 'telegram') => 'ri-telegram-fill',
            str_contains($needle, 'discord') => 'ri-discord-fill',
            str_contains($needle, 'vk'), str_contains($needle, 'vkontakte') => 'ri-vk-fill',
            str_contains($needle, 'youtube') => 'ri-youtube-fill',
            str_contains($needle, 'facebook') => 'ri-facebook-fill',
            str_contains($needle, 'twitter'), str_contains($needle, 'x.com') => 'ri-twitter-x-fill',
            default => 'ri-links-fill',
        };
    }
}
