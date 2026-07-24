<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class AdminFeatureController extends Controller
{
    public function index()
    {
        $features = Feature::orderBy('sort')->orderBy('id')->get();

        return view('admin.features.index', compact('features'));
    }

    public function create()
    {
        return view('admin.features.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title_ru' => 'required|string|max:255',
            'description_ru' => 'required|string',
            'image' => 'required|image|mimes:jpeg,jpg,png,gif,webp|max:10240',
            'status' => 'nullable|boolean',
            'sort' => 'nullable|integer',
        ]);

        $imagePath = $this->uploadImage($request->file('image'));

        Feature::create([
            'title_ru' => $request->title_ru,
            'description_ru' => $request->description_ru,
            'image' => $imagePath,
            'status' => $request->boolean('status', true),
            'sort' => $request->sort ?? 0,
        ]);

        Cache::forget('homepage_features');

        return redirect()->route('admin.features.index')->with('success', __('main.feature_added'));
    }

    public function edit(Feature $feature)
    {
        return view('admin.features.edit', compact('feature'));
    }

    public function update(Request $request, Feature $feature)
    {
        $request->validate([
            'title_ru' => 'required|string|max:255',
            'description_ru' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:10240',
            'status' => 'nullable|boolean',
            'sort' => 'nullable|integer',
        ]);

        $data = [
            'title_ru' => $request->title_ru,
            'description_ru' => $request->description_ru,
            'status' => $request->boolean('status', true),
            'sort' => $request->sort ?? 0,
        ];

        if ($request->hasFile('image')) {
            $this->deleteOldImage($feature->getRawOriginal('image'));
            $data['image'] = $this->uploadImage($request->file('image'));
        }

        $feature->update($data);

        Cache::forget('homepage_features');

        return redirect()->route('admin.features.index')->with('success', __('main.feature_updated'));
    }

    public function destroy(Feature $feature)
    {
        $this->deleteOldImage($feature->getRawOriginal('image'));
        $feature->delete();

        Cache::forget('homepage_features');

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
