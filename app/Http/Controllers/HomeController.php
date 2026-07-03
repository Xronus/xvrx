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
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        // Исходные данные
        $settings = SiteSetting::first();
        $news = News::orderBy('id', 'desc')->limit(8)->get();
        $realms = Realm::where('version', 'lich')->orderBy('id')->get();
        $stocks = Stock::orderBy('id')->get();
        $votes = Vote::orderBy('id')->get();
        $socialLinks = SocialLink::where('is_active', true)->orderBy('id')->get();
        $hts = HowToStart::first();
        $features = Feature::where('status', true)->orderBy('sort')->orderBy('id')->get();
        $activeLangs = LanguageSetting::where('is_active', true)->orderBy('sort_order')->get();

        // Получение данных онлайн-игроков БЕЗ кэширования
        $onlineError = false;
        try {
            // Проверяем, существует ли соединение в конфигурации
            $connection = DB::connection('trinity');

            // Выполняем запрос к таблице characters
            $onlineCount = $connection
                ->table('characters')
                ->where('online', 1)
                ->count();

            \Log::info('Успешно получен онлайн-счётчик: '.$onlineCount);
        } catch (QueryException $e) {
            \Log::error('SQL-ошибка при получении онлайн-игроков: '.$e->getMessage());
            \Log::error('Код ошибки: '.$e->getCode());
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
            'onlineCount',
            'onlineError'
        ));
    }
}
