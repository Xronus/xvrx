<?php

namespace App\Http\Controllers;

use App\Models\Feature;
use App\Models\HowToStart;
use App\Models\News;
use App\Models\Realm;
use App\Models\SiteSetting;
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
        $news = Cache::remember('homepage_news', 600, fn() => News::orderBy('id', 'desc')->limit(8)->get());
        $realms = Cache::remember('homepage_realms', 600, fn() => Realm::orderBy('id')->get());
        $hts = Cache::remember('homepage_howtostart', 600, fn() => HowToStart::first());
        $features = Cache::remember('homepage_features', 600, fn() => Feature::where('status', true)->orderBy('sort')->orderBy('id')->get());

        // Online count — cached for 5 minutes
        $online = Cache::remember('homepage_online', 300, function () {
            $onlineError = false;
            try {
                $connection = DB::connection('trinity');

                $onlineCount = $connection
                    ->table('characters')
                    ->where('online', 1)
                    ->count();
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
            'hts',
            'features',
        ))->with([
            'onlineCount' => $online['count'],
            'onlineError' => $online['error'],
        ]);
    }
}
