<?php

namespace App\Http\Controllers;

use App\Models\Feature;
use App\Models\HowToStart;
use App\Models\LanguageSetting;
use App\Models\News;
use App\Models\Realm;
use App\Models\SiteSetting;
use App\Models\SocialLink;
use App\Models\Stock;
use App\Models\Vote;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        // Cached site settings
        $settings = site_settings();

        // Cached homepage data (5 minutes)
        $news = Cache::remember('homepage_news', 300, fn() => News::orderBy('id', 'desc')->limit(8)->get());
        $realms = Cache::remember('homepage_realms', 300, fn() => Realm::orderBy('id')->get());
        $stocks = Cache::remember('homepage_stocks', 300, fn() => Stock::orderBy('id')->get());
        $votes = Cache::remember('homepage_votes', 300, fn() => Vote::orderBy('id')->get());
        $socialLinks = Cache::remember('homepage_socials', 300, fn() => SocialLink::where('is_active', true)->orderBy('id')->get());
        $hts = Cache::remember('homepage_howtostart', 300, fn() => HowToStart::first());
        $features = Cache::remember('homepage_features', 300, fn() => Feature::where('status', true)->orderBy('sort')->orderBy('id')->get());
        $activeLangs = Cache::remember('homepage_langs', 300, fn() => LanguageSetting::where('is_active', true)->orderBy('sort_order')->get());

        // Online count — cached for 60 seconds
        $online = Cache::remember('homepage_online', 60, function () {
            $onlineError = false;
            try {
                $connection = DB::connection('trinity');

                $onlineCount = $connection
                    ->table('characters')
                    ->where('online', 1)
                    ->count();

                \Log::info('Успешно получен онлайн-счётчик: '.$onlineCount);
            } catch (QueryException $e) {
                \Log::error('SQL-ошибка при получении онлайн-игроков: '.$e->getMessage());
                $onlineCount = 0;
                $onlineError = true;
            } catch (\PDOException $e) {
                \Log::error('Ошибка PDO подключения к БД TrinityCore: '.$e->getMessage());
                $onlineCount = 0;
                $onlineError = true;
            } catch (\Exception $e) {
                \Log::error('Общая ошибка получения онлайн-игроков: '.$e->getMessage());
                $onlineCount = 0;
                $onlineError = true;
            }

            return ['count' => $onlineCount, 'error' => $onlineError];
        });

        return view('home', compact(
            'settings',
            'news',
            'realms',
            'stocks',
            'votes',
            'socialLinks',
            'hts',
            'features',
            'activeLangs',
        ))->with([
            'onlineCount' => $online['count'],
            'onlineError' => $online['error'],
        ]);
    }
}
