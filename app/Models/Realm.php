<?php

namespace App\Models;

use App\Models\Concerns\HasLocalization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Realm extends Model
{
    use HasFactory, HasLocalization;

    protected $fillable = [
        'name',
        'name_en',
        'name_de',
        'name_es',
        'name_fr',
        'rate',
        'version',
        'full_name',
        'description',
        'description_en',
        'description_de',
        'description_es',
        'description_fr',
        'proffesion',
        'gold',
        'rep',
        'loot',
        'honor',
        'link_url',
        'link_text',
    ];

    public function getPatchAttribute(): string
    {
        return match ($this->version) {
            'lich' => '3.3.5a',
            'legion' => '7.3.5',
            'bfa' => '8.3',
            'sl' => '9.2',
            default => (string) $this->version,
        };
    }
}
