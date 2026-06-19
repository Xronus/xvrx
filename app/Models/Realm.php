<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Realm extends Model
{
    use HasFactory;

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

    public function localized(string $field): ?string
    {
        $locale = app()->getLocale();

        if ($locale === 'ru') {
            return $this->{$field};
        }

        $localizedValue = $this->{$field . '_' . $locale};

        return $localizedValue ?: $this->{$field};
    }
}
