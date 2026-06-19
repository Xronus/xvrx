<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LanguageSetting;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::first();
        $enabledLangs = LanguageSetting::getActiveCodes();

        $totalAccounts = 0;
        $totalBanned = 0;
        $totalPremium = 0;
        $totalRealms = 0;

        try {
            $totalAccounts = DB::connection('game_auth')->table('account')->count();
            $totalBanned = DB::connection('game_auth')->table('account_banned')->count();
            $totalPremium = DB::connection('game_auth')->table('account_premium')->where('active', 1)->count();
            $totalRealms = DB::connection('game_auth')->table('realmlist')->count();
        } catch (\Exception $e) {
        }

        return view('admin.index', compact('settings', 'totalAccounts', 'totalBanned', 'totalPremium', 'totalRealms', 'enabledLangs'));
    }

    public function languages()
    {
        $languages = LanguageSetting::getAllOrdered();

        return view('admin.languages', compact('languages'));
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

        $settings = SiteSetting::first();

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
