<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HowToStart;
use App\Models\LanguageSetting;
use Illuminate\Http\Request;

class AdminHowToStartController extends Controller
{
    public function index()
    {
        $hts = HowToStart::first();
        $enabledLangs = LanguageSetting::getActiveCodes();

        return view('admin.howtostart.index', compact('hts', 'enabledLangs'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'client_size' => 'nullable|string|max:100',
            'google_drive_url' => 'nullable|string|max:500',
            'yandex_disk_url' => 'nullable|string|max:500',
            'filemail_url' => 'nullable|string|max:500',
            'mega_url' => 'nullable|string|max:500',
            'torrent_url' => 'nullable|string|max:500',
            'launcher_text_ru' => 'nullable|string|max:255',
            'launcher_text_en' => 'nullable|string|max:255',
            'launcher_text_de' => 'nullable|string|max:255',
            'launcher_text_es' => 'nullable|string|max:255',
            'launcher_text_fr' => 'nullable|string|max:255',
            'launcher_url' => 'nullable|string|max:500',
            'launcher_description_ru' => 'nullable|string',
            'launcher_description_en' => 'nullable|string',
            'launcher_description_de' => 'nullable|string',
            'launcher_description_es' => 'nullable|string',
            'launcher_description_fr' => 'nullable|string',
            'req_storage_min' => 'nullable|string|max:50',
            'req_storage_rec' => 'nullable|string|max:50',
            'req_windows_min' => 'nullable|string|max:50',
            'req_windows_rec' => 'nullable|string|max:50',
            'req_ram_min' => 'nullable|string|max:50',
            'req_ram_rec' => 'nullable|string|max:50',
            'req_gpu_min' => 'nullable|string|max:50',
            'req_gpu_rec' => 'nullable|string|max:50',
            'req_internet_min' => 'nullable|string|max:50',
            'req_internet_rec' => 'nullable|string|max:50',
        ]);

        $hts = HowToStart::first();

        $data = $request->only([
            'client_size',
            'google_drive_url', 'yandex_disk_url', 'filemail_url', 'mega_url', 'torrent_url',
            'launcher_text_ru', 'launcher_text_en', 'launcher_text_de', 'launcher_text_es', 'launcher_text_fr',
            'launcher_url',
            'launcher_description_ru', 'launcher_description_en', 'launcher_description_de', 'launcher_description_es', 'launcher_description_fr',
            'req_storage_min', 'req_storage_rec',
            'req_windows_min', 'req_windows_rec',
            'req_ram_min', 'req_ram_rec',
            'req_gpu_min', 'req_gpu_rec',
            'req_internet_min', 'req_internet_rec',
        ]);

        $data['google_drive_active'] = $request->boolean('google_drive_active');
        $data['yandex_disk_active'] = $request->boolean('yandex_disk_active');
        $data['filemail_active'] = $request->boolean('filemail_active');
        $data['mega_active'] = $request->boolean('mega_active');
        $data['torrent_active'] = $request->boolean('torrent_active');

        if ($hts) {
            $hts->update($data);
        } else {
            HowToStart::create($data);
        }

        return redirect()->route('admin.howtostart.index')->with('success', 'Настройки сохранены');
    }
}
