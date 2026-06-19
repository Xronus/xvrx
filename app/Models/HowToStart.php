<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HowToStart extends Model
{
    use HasFactory;

    protected $table = 'how_to_start';

    protected $fillable = [
        'client_size',
        'google_drive_url',
        'google_drive_active',
        'yandex_disk_url',
        'yandex_disk_active',
        'filemail_url',
        'filemail_active',
        'mega_url',
        'mega_active',
        'torrent_url',
        'torrent_active',
        'launcher_text_ru',
        'launcher_text_en',
        'launcher_text_de',
        'launcher_text_es',
        'launcher_text_fr',
        'launcher_url',
        'launcher_description_ru',
        'launcher_description_en',
        'launcher_description_de',
        'launcher_description_es',
        'launcher_description_fr',
        'req_storage_min',
        'req_storage_rec',
        'req_windows_min',
        'req_windows_rec',
        'req_ram_min',
        'req_ram_rec',
        'req_gpu_min',
        'req_gpu_rec',
        'req_internet_min',
        'req_internet_rec',
    ];

    protected function casts(): array
    {
        return [
            'google_drive_active' => 'boolean',
            'yandex_disk_active' => 'boolean',
            'filemail_active' => 'boolean',
            'mega_active' => 'boolean',
            'torrent_active' => 'boolean',
        ];
    }

    public function localizedLauncherText(): ?string
    {
        $locale = app()->getLocale();
        $value = $this->{'launcher_text_' . $locale};

        return $value ?: $this->launcher_text_ru;
    }

    public function localizedLauncherDescription(): ?string
    {
        $locale = app()->getLocale();
        $value = $this->{'launcher_description_' . $locale};

        return $value ?: $this->launcher_description_ru;
    }
}
