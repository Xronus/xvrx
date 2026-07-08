<?php

namespace App\Providers;

use App\Http\Middleware\CheckBanned;
use App\Models\LanguageSetting;
use App\Models\SocialLink;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind('check.banned', CheckBanned::class);

        // Load helpers (also in composer.json autoload — belt and suspenders)
        require_once app_path('Helpers/WowIconHelper.php');
        require_once app_path('Helpers/SiteSettingsHelper.php');
    }

    public function boot(): void
    {
        // Share site name from DB across all views (cached)
        View::composer('*', function ($view) {
            try {
                $settings = site_settings();
                $view->with('siteName', $settings && $settings->title ? $settings->title : config('app.name'));
            } catch (\Exception $e) {
                $view->with('siteName', config('app.name'));
            }
        });

        // Warn if captcha is disabled on production
        if (app()->environment('production') && config('captcha.method') === 'false') {
            Log::warning('CAPTCHA_METHOD is disabled on production environment. Enable google or cloudflare captcha for security.');
        }

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

        View::composer('partials.xvrx-social', function ($view) {
            try {
                $socialLinks = SocialLink::where('is_active', true)->orderBy('id')->get();
            } catch (\Exception $e) {
                $socialLinks = collect();
            }

            $view->with('socialLinks', $socialLinks);
        });
    }
}
