<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class AdminLogoController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::first();
        $currentLogo = $settings->logo_path ?? null;
        
        // Получаем список существующих логотипов
        $logoPath = public_path('powerpuffsite/images/logo');
        $logos = [];
        
        if (File::exists($logoPath)) {
            $files = File::files($logoPath);
            foreach ($files as $file) {
                $fileName = $file->getFilename();
                if (strpos($fileName, 'powerpuff-site-') === 0) {
                    $logos[] = [
                        'name' => $fileName,
                        'path' => 'powerpuffsite/images/logo/' . $fileName,
                        'url' => asset('powerpuffsite/images/logo/' . $fileName),
                        'is_current' => $currentLogo === 'powerpuffsite/images/logo/' . $fileName
                    ];
                }
            }
        }

        return view('admin.logo.index', compact('currentLogo', 'logos'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'logo' => 'required|image|mimes:png,jpg,jpeg,gif,webp|max:2048',
        ]);

        try {
            $logoPath = public_path('powerpuffsite/images/logo');
            
            // Создаем директорию, если её нет
            if (!File::exists($logoPath)) {
                File::makeDirectory($logoPath, 0755, true);
            }

            // Генерируем уникальное имя файла
            $extension = $request->file('logo')->getClientOriginalExtension();
            $counter = 1;
            do {
                $fileName = 'powerpuff-site-logo' . $counter . '.' . $extension;
                $filePath = $logoPath . '/' . $fileName;
                $counter++;
            } while (File::exists($filePath) && $counter < 1000);

            // Сохраняем файл
            $request->file('logo')->move($logoPath, $fileName);

            // Сохраняем путь в настройках
            $settings = SiteSetting::first();
            if (!$settings) {
                $settings = new SiteSetting();
            }
            
            $logoRelativePath = 'powerpuffsite/images/logo/' . $fileName;
            $settings->logo_path = $logoRelativePath;
            $settings->save();

            return redirect()->route('admin.logo.index')->with('success', __('main.logo_uploaded_successfully'));
        } catch (\Exception $e) {
            return redirect()->route('admin.logo.index')->with('error', __('main.logo_upload_error') . ': ' . $e->getMessage());
        }
    }

    public function setCurrent(Request $request)
    {
        $request->validate([
            'logo_path' => 'required|string',
        ]);

        try {
            $settings = SiteSetting::first();
            if (!$settings) {
                $settings = new SiteSetting();
            }
            
            $settings->logo_path = $request->logo_path;
            $settings->save();

            return redirect()->route('admin.logo.index')->with('success', __('main.logo_set_successfully'));
        } catch (\Exception $e) {
            return redirect()->route('admin.logo.index')->with('error', __('main.logo_set_error') . ': ' . $e->getMessage());
        }
    }

    public function delete(Request $request)
    {
        $request->validate([
            'logo_path' => 'required|string',
        ]);

        try {
            $filePath = public_path($request->logo_path);
            
            if (File::exists($filePath)) {
                File::delete($filePath);
            }

            // Если удаляемый логотип был текущим, сбрасываем настройку
            $settings = SiteSetting::first();
            if ($settings && $settings->logo_path === $request->logo_path) {
                $settings->logo_path = null;
                $settings->save();
            }

            return redirect()->route('admin.logo.index')->with('success', __('main.logo_deleted_successfully'));
        } catch (\Exception $e) {
            return redirect()->route('admin.logo.index')->with('error', __('main.logo_delete_error') . ': ' . $e->getMessage());
        }
    }
}
