<?php

namespace App\Http\Controllers;

use App\Models\LanguageSetting;
use App\Models\News;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $query = News::query();

        // Поиск по тексту новости
        if ($request->has('search') && ! empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('text', 'like', '%'.$searchTerm.'%')
                    ->orWhere('text_en', 'like', '%'.$searchTerm.'%')
                    ->orWhere('text_de', 'like', '%'.$searchTerm.'%')
                    ->orWhere('text_es', 'like', '%'.$searchTerm.'%')
                    ->orWhere('text_fr', 'like', '%'.$searchTerm.'%')
                    ->orWhere('content', 'like', '%'.$searchTerm.'%')
                    ->orWhere('content_en', 'like', '%'.$searchTerm.'%')
                    ->orWhere('content_de', 'like', '%'.$searchTerm.'%')
                    ->orWhere('content_es', 'like', '%'.$searchTerm.'%')
                    ->orWhere('content_fr', 'like', '%'.$searchTerm.'%');
            });
        }

        $news = $query->orderBy('id', 'desc')->paginate(12)->withQueryString();
        $searchTerm = $request->input('search', '');

        // Получаем настройки и активные языки для хедера
        $settings = site_settings();
        $activeLangs = LanguageSetting::where('is_active', true)->orderBy('sort_order')->get();

        return view('news.index', compact('news', 'searchTerm', 'settings', 'activeLangs'));
    }

    public function show(News $news)
    {
        $settings = site_settings();
        $activeLangs = LanguageSetting::where('is_active', true)->orderBy('sort_order')->get();

        return view('news.show', compact('news', 'settings', 'activeLangs'));
    }
}
