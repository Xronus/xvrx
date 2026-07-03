<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feature;
use App\Models\LanguageSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminFeatureController extends Controller
{
    public function index()
    {
        $features = Feature::orderBy('sort')->orderBy('id')->get();
        $enabledLangs = LanguageSetting::getActiveCodes();

        return view('admin.features.index', compact('features', 'enabledLangs'));
    }

    public function create()
    {
        $enabledLangs = LanguageSetting::getActiveCodes();

        return view('admin.features.create', compact('enabledLangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title_ru' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'title_de' => 'nullable|string|max:255',
            'title_es' => 'nullable|string|max:255',
            'title_fr' => 'nullable|string|max:255',
            'description_ru' => 'required|string',
            'description_en' => 'nullable|string',
            'description_de' => 'nullable|string',
            'description_es' => 'nullable|string',
            'description_fr' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,jpg,png,gif,webp|max:5120',
            'status' => 'nullable|boolean',
            'sort' => 'nullable|integer',
        ]);

        $imagePath = $this->uploadImage($request->file('image'));

        Feature::create([
            'title_ru' => $request->title_ru,
            'title_en' => $request->title_en,
            'title_de' => $request->title_de,
            'title_es' => $request->title_es,
            'title_fr' => $request->title_fr,
            'description_ru' => $request->description_ru,
            'description_en' => $request->description_en,
            'description_de' => $request->description_de,
            'description_es' => $request->description_es,
            'description_fr' => $request->description_fr,
            'image' => $imagePath,
            'status' => $request->has('status') ? true : false,
            'sort' => $request->sort ?? 0,
        ]);

        return redirect()->route('admin.features.index')->with('success', __('main.feature_added'));
    }

    public function edit(Feature $feature)
    {
        $enabledLangs = LanguageSetting::getActiveCodes();

        return view('admin.features.edit', compact('feature', 'enabledLangs'));
    }

    public function update(Request $request, Feature $feature)
    {
        $request->validate([
            'title_ru' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'title_de' => 'nullable|string|max:255',
            'title_es' => 'nullable|string|max:255',
            'title_fr' => 'nullable|string|max:255',
            'description_ru' => 'required|string',
            'description_en' => 'nullable|string',
            'description_de' => 'nullable|string',
            'description_es' => 'nullable|string',
            'description_fr' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:5120',
            'status' => 'nullable|boolean',
            'sort' => 'nullable|integer',
        ]);

        $data = [
            'title_ru' => $request->title_ru,
            'title_en' => $request->title_en,
            'title_de' => $request->title_de,
            'title_es' => $request->title_es,
            'title_fr' => $request->title_fr,
            'description_ru' => $request->description_ru,
            'description_en' => $request->description_en,
            'description_de' => $request->description_de,
            'description_es' => $request->description_es,
            'description_fr' => $request->description_fr,
            'status' => $request->has('status') ? true : false,
            'sort' => $request->sort ?? 0,
        ];

        if ($request->hasFile('image')) {
            $this->deleteOldImage($feature->getRawOriginal('image'));
            $data['image'] = $this->uploadImage($request->file('image'));
        }

        $feature->update($data);

        return redirect()->route('admin.features.index')->with('success', __('main.feature_updated'));
    }

    public function destroy(Feature $feature)
    {
        $this->deleteOldImage($feature->getRawOriginal('image'));
        $feature->delete();

        return redirect()->route('admin.features.index')->with('success', __('main.feature_deleted'));
    }

    private function uploadImage($file): string
    {
        $imageDir = config('xvrx.images.features', 'powerpuffsite/images/features');
        $dir = public_path($imageDir);
        if (! is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $filename = Str::random(20).'.'.$file->getClientOriginalExtension();
        $file->move($dir, $filename);

        return $imageDir.'/'.$filename;
    }

    private function deleteOldImage(?string $path): void
    {
        if (empty($path)) {
            return;
        }

        $fullPath = public_path($path);
        if (file_exists($fullPath) && str_contains($path, config('xvrx.images.features', 'powerpuffsite/images/features'))) {
            unlink($fullPath);
        }
    }
}
