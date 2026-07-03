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
    ];
}
