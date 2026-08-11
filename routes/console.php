<?php

use App\Models\Feature;
use App\Models\HowToStart;
use App\Models\News;
use App\Models\Realm;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Clean expired password reset tokens every hour
Schedule::call(function () {
    DB::table('password_reset_tokens')
        ->where('created_at', '<', now()->subHour())
        ->delete();
})->hourly();

// Clean expired sessions every 30 minutes — prevents gc lottery on user requests
Schedule::command('session:gc')->everyThirtyMinutes();

// Cache warming every 5 minutes — keeps hot caches alive during idle periods
Schedule::call(function () {
    // Homepage caches
    Cache::remember('homepage_news', 600, fn() => News::orderBy('id', 'desc')->limit(8)->get());
    Cache::remember('homepage_realms', 600, fn() => Realm::orderBy('id')->get());
    Cache::remember('homepage_howtostart', 600, fn() => HowToStart::first());
    Cache::remember('homepage_features', 600, fn() => Feature::where('status', true)->orderBy('sort')->orderBy('id')->get());

    // Online count
    Cache::remember('homepage_online', 300, function () {
        try {
            return ['count' => DB::connection('trinity')->table('characters')->where('online', 1)->count(), 'error' => false];
        } catch (\Exception $e) {
            return ['count' => 0, 'error' => true];
        }
    });

    // Admin dashboard stats
    Cache::remember('admin_total_accounts', 300, fn() => DB::connection('game_auth')->table('account')->count());
    Cache::remember('admin_total_banned', 300, function () {
        return DB::connection('game_auth')->table('account_banned')
            ->where('active', 1)
            ->where('bandate', '<=', time())
            ->where(function ($q) {
                $q->where('unbandate', 0)->orWhere('unbandate', '>', time());
            })
            ->count();
    });
    Cache::remember('admin_online', 300, fn() => DB::connection('trinity')->table('characters')->where('online', 1)->count());
})->everyFiveMinutes();
