<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LanguageSetting;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdminController extends Controller
{
    public function index()
    {
        $settings = site_settings();
        $enabledLangs = LanguageSetting::getActiveCodes();

        $totalAccounts = 0;
        $totalBanned = 0;
        $onlineCount = 0;
        $gameStatsError = false;

        try {
            $totalAccounts = DB::connection('game_auth')->table('account')->count();
            $totalBanned = DB::connection('game_auth')->table('account_banned')->count();
            $onlineCount = DB::connection('trinity')->table('characters')->where('online', 1)->count();
        } catch (\Exception $e) {
            \Log::error('Admin dashboard: failed to fetch game stats: '.$e->getMessage());
            $gameStatsError = true;
        }

        return view('admin.index', compact('settings', 'totalAccounts', 'totalBanned', 'onlineCount', 'gameStatsError', 'enabledLangs'));
    }

    public function languages()
    {
        $languages = LanguageSetting::getAllOrdered();

        return view('admin.languages', compact('languages'));
    }

    public function mail()
    {
        $settings = site_settings();
        // Only expose non-sensitive SMTP info: configured status and from address
        $smtp = [
            'from_address' => env('MAIL_FROM_ADDRESS'),
            'password_configured' => filled(env('MAIL_PASSWORD')),
            'configured' => filled(env('MAIL_HOST')) && filled(env('MAIL_USERNAME')) && filled(env('MAIL_PASSWORD')),
            'reachable' => $this->checkSmtpReachable(),
        ];

        return view('admin.mail.index', compact('settings', 'smtp'));
    }

    private function checkSmtpReachable(): bool
    {
        $host = env('MAIL_HOST');
        $port = (int) env('MAIL_PORT', 587);

        if (empty($host)) {
            return false;
        }

        try {
            $socket = @fsockopen($host, $port, $errno, $errstr, 5);
            if ($socket) {
                fclose($socket);
                return true;
            }
        } catch (\Exception $e) {
        }

        return false;
    }

    public function updateMail(Request $request)
    {
        $request->validate([
            'mail_password_reset_enabled' => 'nullable|boolean',
            'mail_from_name' => 'nullable|string|max:100',
            'mail_reset_subject' => 'nullable|string|max:180',
            'mail_reset_body' => 'nullable|string|max:2000',
            'mail_password_reset_rate_limit' => 'required|integer|min:1|max:60',
        ]);

        $settings = site_settings() ?: new SiteSetting();

        $settings->fill([
            'mail_password_reset_enabled' => $request->boolean('mail_password_reset_enabled'),
            'mail_from_name' => $request->mail_from_name,
            'mail_reset_subject' => $request->mail_reset_subject,
            'mail_reset_body' => $request->mail_reset_body,
            'mail_password_reset_rate_limit' => $request->integer('mail_password_reset_rate_limit'),
        ]);
        $settings->save();

        site_settings_forget();

        return redirect()->route('admin.mail.index')->with('success', __('main.mail_settings_saved'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'title_de' => 'nullable|string|max:255',
            'title_es' => 'nullable|string|max:255',
            'title_fr' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:255',
            'description_en' => 'nullable|string|max:255',
            'description_de' => 'nullable|string|max:255',
            'description_es' => 'nullable|string|max:255',
            'description_fr' => 'nullable|string|max:255',
            'main__title' => 'nullable|string|max:255',
            'main__title_en' => 'nullable|string|max:255',
            'main__title_de' => 'nullable|string|max:255',
            'main__title_es' => 'nullable|string|max:255',
            'main__title_fr' => 'nullable|string|max:255',
            'main__text' => 'nullable|string|max:255',
            'main__text_en' => 'nullable|string|max:255',
            'main__text_de' => 'nullable|string|max:255',
            'main__text_es' => 'nullable|string|max:255',
            'main__text_fr' => 'nullable|string|max:255',
        ]);

        $settings = site_settings();

        $data = $request->only([
            'title', 'title_en', 'title_de', 'title_es', 'title_fr',
            'description', 'description_en', 'description_de', 'description_es', 'description_fr',
            'main__title', 'main__title_en', 'main__title_de', 'main__title_es', 'main__title_fr',
            'main__text', 'main__text_en', 'main__text_de', 'main__text_es', 'main__text_fr',
        ]);

        if ($settings) {
            $settings->update($data);
        } else {
            SiteSetting::create($data);
        }

        site_settings_forget();

        return redirect()->route('admin.index')->with('success', __('main.save_settings'));
    }

    public function toggleLanguage(Request $request)
    {
        $request->validate([
            'code' => 'required|string|in:ru,en,de,es,fr',
            'is_active' => 'required|boolean',
        ]);

        LanguageSetting::where('code', $request->code)->update([
            'is_active' => $request->is_active,
        ]);

        return response()->json(['success' => true]);
    }
}
