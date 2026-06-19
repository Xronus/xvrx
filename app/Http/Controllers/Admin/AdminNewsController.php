<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
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
            'text_en' => 'nullable|string|max:255',
            'text_de' => 'nullable|string|max:255',
            'text_es' => 'nullable|string|max:255',
            'text_fr' => 'nullable|string|max:255',
            'content' => 'required|string',
            'content_en' => 'nullable|string',
            'content_de' => 'nullable|string',
            'content_es' => 'nullable|string',
            'content_fr' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,jpg,png,gif,webp|max:5120',
        ]);

        $imagePath = $this->uploadImage($request->file('image'));

        News::create([
            'date' => now()->format('d.m.Y'),
            'text' => $request->text,
            'text_en' => $request->text_en,
            'text_de' => $request->text_de,
            'text_es' => $request->text_es,
            'text_fr' => $request->text_fr,
            'content' => $request->content,
            'content_en' => $request->content_en,
            'content_de' => $request->content_de,
            'content_es' => $request->content_es,
            'content_fr' => $request->content_fr,
            'images' => $imagePath,
        ]);

        return redirect()->route('admin.news.index')->with('success', 'Новость успешно добавлена');
    }

    public function edit(News $news)
    {
        return view('admin.news.edit', compact('news'));
    }

    public function update(Request $request, News $news)
    {
        $request->validate([
            'text' => 'required|string|max:255',
            'text_en' => 'nullable|string|max:255',
            'text_de' => 'nullable|string|max:255',
            'text_es' => 'nullable|string|max:255',
            'text_fr' => 'nullable|string|max:255',
            'content' => 'required|string',
            'content_en' => 'nullable|string',
            'content_de' => 'nullable|string',
            'content_es' => 'nullable|string',
            'content_fr' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:5120',
        ]);

        $data = [
            'text' => $request->text,
            'text_en' => $request->text_en,
            'text_de' => $request->text_de,
            'text_es' => $request->text_es,
            'text_fr' => $request->text_fr,
            'content' => $request->content,
            'content_en' => $request->content_en,
            'content_de' => $request->content_de,
            'content_es' => $request->content_es,
            'content_fr' => $request->content_fr,
        ];

        if ($request->hasFile('image')) {
            $this->deleteOldImage($news->getRawOriginal('images'));
            $data['images'] = $this->uploadImage($request->file('image'));
        }

        $news->update($data);

        return redirect()->route('admin.news.index')->with('success', 'Новость успешно обновлена');
    }

    public function destroy(News $news)
    {
        $this->deleteOldImage($news->getRawOriginal('images'));
        $news->delete();

        return redirect()->route('admin.news.index')->with('success', 'Новость успешно удалена');
    }

    private function uploadImage($file): string
    {
        $dir = public_path('powerpuffsite/images/news');
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $filename = Str::random(20) . '.' . $file->getClientOriginalExtension();
        $file->move($dir, $filename);

        return 'powerpuffsite/images/news/' . $filename;
    }

    private function deleteOldImage(?string $path): void
    {
        if (empty($path)) {
            return;
        }

        $fullPath = public_path($path);
        if (file_exists($fullPath) && str_contains($path, 'powerpuffsite/images/news/')) {
            unlink($fullPath);
        }
    }
}
