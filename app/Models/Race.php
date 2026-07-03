<?php

namespace App\Models;

use App\Models\Concerns\HasLocalization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Race extends Model
{
    use HasFactory, HasLocalization;

    protected $fillable = [
        'race_id',
        'name',
        'name_en',
        'name_de',
        'name_es',
        'name_fr',
        'faction',
    ];

    protected function casts(): array
    {
        return [
            'race_id' => 'integer',
            'faction' => 'integer',
        ];
    }

    public function getFactionNameAttribute(): string
    {
        return $this->faction === 0 ? 'Альянс' : 'Орда';
    }
}
