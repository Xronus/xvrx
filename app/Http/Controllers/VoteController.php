<?php

namespace App\Http\Controllers;

use App\Models\VoteTop;
use App\Models\VoteLog;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class VoteController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $voteTops = VoteTop::where('is_active', true)->orderBy('id')->get();

        $todayLogs = VoteLog::where('user_id', $user->id)
            ->whereDate('rewarded_at', today())
            ->pluck('vote_top_id')
            ->toArray();

        $settings = SiteSetting::first();

        return view('cabinet.votes', compact('user', 'voteTops', 'todayLogs', 'settings'));
    }

    public function claim(Request $request, VoteTop $voteTop)
    {
        $user = auth()->user();
        $ip = $request->ip();

        $alreadyClaimed = VoteLog::where('user_id', $user->id)
            ->where('vote_top_id', $voteTop->id)
            ->whereDate('rewarded_at', today())
            ->exists();

        if ($alreadyClaimed) {
            return back()->with('error', 'Вы уже получили награду за голосование на ' . $voteTop->name . ' сегодня');
        }

        if (empty($voteTop->api_key) || empty($voteTop->api_url)) {
            return back()->with('error', 'API для этого топа не настроен');
        }

        try {
            $response = Http::timeout(5)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])
                ->post($voteTop->api_url, [
                    'api_key' => $voteTop->api_key,
                    'ip_address' => $ip,
                ]);

            $result = $response->json();

            if (!isset($result['success']) || !$result['success']) {
                return back()->with('error', 'Ошибка проверки голосования: ' . ($result['error'] ?? 'Неизвестная ошибка'));
            }

            if (!$result['has_voted']) {
                return back()->with('error', 'Вы ещё не голосовали на ' . $voteTop->name . ' сегодня. Сначала проголосуйте!');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Не удалось связаться с сервером голосования. Попробуйте позже.');
        }

        VoteLog::create([
            'user_id' => $user->id,
            'vote_top_id' => $voteTop->id,
            'ip_address' => $ip,
            'rewarded_at' => now(),
        ]);

        $user->increment('bonuses', $voteTop->bonus_amount);

        return back()->with('success', 'Спасибо за голос! Вы получили ' . $voteTop->bonus_amount . ' бонусов.');
    }
}
