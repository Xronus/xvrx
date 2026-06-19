<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CharacterClass extends Model
{
    use HasFactory;

    protected $table = 'character_classes';

    protected $fillable = [
        'class_id',
        'name',
        'name_en',
        'name_de',
        'name_es',
        'name_fr',
    ];

    protected function casts(): array
    {
        return [
            'class_id' => 'integer',
        ];
    }
}
