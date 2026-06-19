<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'text',
        'text_en',
        'text_de',
        'text_es',
        'text_fr',
        'content',
        'content_en',
        'content_de',
        'content_es',
        'content_fr',
        'images',
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

    public function getImagesAttribute($value)
    {
        if (empty($value)) {
            return $value;
        }

        $value = str_replace('../template/nighthold/', 'powerpuffsite/', $value);
        $value = str_replace('template/nighthold/', 'powerpuffsite/', $value);

        return $value;
    }
}
