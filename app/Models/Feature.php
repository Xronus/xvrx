<?php

namespace App\Models;

use App\Models\Concerns\HasLocalization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    use HasFactory, HasLocalization;

    protected $fillable = [
        'title_ru',
        'description_ru',
        'image',
        'status',
        'sort',
    ];

    protected function casts(): array
    {
        return [
            'status' => 'boolean',
            'sort' => 'integer',
        ];
    }

    public function localized($field = 'title')
    {
        $locale = app()->getLocale();
        $fieldName = $field.'_'.$locale;

        if (isset($this->$fieldName) && ! empty($this->$fieldName)) {
            return $this->$fieldName;
        }

        $fallbackField = $field.'_ru';

        return $this->$fallbackField ?? '';
    }

    public function getImageAttribute($value)
    {
        if ($value && strpos($value, 'powerpuffsite') === false) {
            return 'powerpuffsite/images/features/'.$value;
        }

        return $value;
    }
}
