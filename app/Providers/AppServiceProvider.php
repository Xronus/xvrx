<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\LanguageSetting;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        View::composer('admin.*', function ($view) {
            try {
                $languages = LanguageSetting::orderBy('sort_order')->get();
                $enabledLangs = $languages->where('is_active', true)->pluck('code')->toArray();
            } catch (\Exception $e) {
                $languages = collect();
                $enabledLangs = ['ru', 'en'];
            }

            $view->with('languages', $languages);
            $view->with('enabledLangs', $enabledLangs);
        });

        View::composer(['home', 'news.*', 'cabinet.*', 'auth.*'], function ($view) {
            try {
                $activeLangs = LanguageSetting::where('is_active', true)->orderBy('sort_order')->get();
            } catch (\Exception $e) {
                $activeLangs = collect();
            }

            $view->with('activeLangs', $activeLangs);
        });
    }
}
