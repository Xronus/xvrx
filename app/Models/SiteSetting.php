<?php

namespace App\Models;

use App\Models\Concerns\HasLocalization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    use HasFactory, HasLocalization;

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
        'mail_password_reset_enabled',
        'mail_from_name',
        'mail_reset_subject',
        'mail_reset_body',
        'mail_password_reset_rate_limit',
        'terms_text',
        'policy_text',
    ];

    protected function casts(): array
    {
        return [
            'mail_password_reset_enabled' => 'boolean',
            'mail_password_reset_rate_limit' => 'integer',
        ];
    }

    public function localizedTitle(): ?string
    {
        return $this->localized('main__title');
    }

    public function localizedText(): ?string
    {
        return $this->localized('main__text');
    }

    public function localizedSiteTitle(): ?string
    {
        return $this->localized('title');
    }

    public function localizedSiteDescription(): ?string
    {
        return $this->localized('description');
    }
}
