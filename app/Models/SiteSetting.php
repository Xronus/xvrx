<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    use HasFactory;

    protected $table = 'main';

    protected $fillable = [
        'title',
        'title_en',
        'title_de',
        'title_es',
        'title_fr',
        'description',
        'description_en',
        'description_de',
        'description_es',
        'description_fr',
        'main__title',
        'main__title_en',
        'main__title_de',
        'main__title_es',
        'main__title_fr',
        'main__text',
        'main__text_en',
        'main__text_de',
        'main__text_es',
        'main__text_fr',
        'logo_path',
    ];

    public function localizedTitle(): ?string
    {
        $locale = app()->getLocale();

        if ($locale === 'ru') {
            return $this->main__title;
        }

        $value = $this->{'main__title_' . $locale};

        return $value ?: $this->main__title;
    }

    public function localizedText(): ?string
    {
        $locale = app()->getLocale();

        if ($locale === 'ru') {
            return $this->main__text;
        }

        $value = $this->{'main__text_' . $locale};

        return $value ?: $this->main__text;
    }

    public function localizedSiteTitle(): ?string
    {
        $locale = app()->getLocale();

        if ($locale === 'ru') {
            return $this->title;
        }

        $value = $this->{'title_' . $locale};

        return $value ?: $this->title;
    }

    public function localizedSiteDescription(): ?string
    {
        $locale = app()->getLocale();

        if ($locale === 'ru') {
            return $this->description;
        }

        $value = $this->{'description_' . $locale};

        return $value ?: $this->description;
    }
}
