<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class AdminNewsController extends Controller
{
    public function index()
    {
        $news = News::orderBy('id', 'desc')->paginate(15);

        return view('admin.news.index', compact('news'));
    }

    public function create()
    {
        return view('admin.news.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'text' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'required|image|mimes:jpeg,jpg,png,gif,webp|max:10240',
        ], [
            'image.required' => 'Выберите изображение.',
            'image.image' => 'Файл должен быть изображением.',
            'image.mimes' => 'Допустимые форматы: jpeg, png, gif, webp.',
            'image.max' => 'Размер изображения не должен превышать 10MB.',
            'image.uploaded' => 'Не удалось загрузить изображение. Возможно, файл слишком большой.',
        ]);

        $imagePath = $this->uploadImage($request->file('image'));

        News::create([
            'date' => now()->format('d.m.Y'),
            'text' => $request->text,
            'content' => $request->content,
            'images' => $imagePath,
        ]);

        Cache::forget('homepage_news');

        return redirect()->route('admin.news.index')->with('success', __('main.news_added'));
    }

    public function edit(News $news)
    {
        return view('admin.news.edit', compact('news'));
    }

    public function update(Request $request, News $news)
    {
        $request->validate([
            'text' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:10240',
        ], [
            'image.image' => 'Файл должен быть изображением.',
            'image.mimes' => 'Допустимые форматы: jpeg, png, gif, webp.',
            'image.max' => 'Размер изображения не должен превышать 10MB.',
            'image.uploaded' => 'Не удалось загрузить изображение. Возможно, файл слишком большой.',
        ]);

        $data = [
            'text' => $request->text,
            'content' => $request->content,
        ];

        if ($request->hasFile('image')) {
            $this->deleteOldImage($news->getRawOriginal('images'));
            $data['images'] = $this->uploadImage($request->file('image'));
        }

        $news->update($data);

        Cache::forget('homepage_news');

        return redirect()->route('admin.news.index')->with('success', __('main.news_updated'));
    }

    public function destroy(News $news)
    {
        $this->deleteOldImage($news->getRawOriginal('images'));
        $news->delete();

        Cache::forget('homepage_news');

        return redirect()->route('admin.news.index')->with('success', __('main.news_deleted'));
    }

    private function uploadImage($file): string
    {
        $imageDir = config('xvrx.images.news', 'powerpuffsite/images/news');
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
        if (file_exists($fullPath) && str_contains($path, config('xvrx.images.news', 'powerpuffsite/images/news'))) {
            unlink($fullPath);
        }
    }
}
