<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VoteTop;
use Illuminate\Http\Request;

class AdminVoteController extends Controller
{
    public function index()
    {
        $voteTop = VoteTop::where('name', 'mmorating.top')->first();

        return view('admin.votes.index', compact('voteTop'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'url' => 'required|url|max:255',
            'api_key' => 'nullable|string|max:255',
            'bonus_amount' => 'required|integer|min:1',
        ]);

        $voteTop = VoteTop::where('name', 'mmorating.top')->first();

        $data = [
            'name' => 'mmorating.top',
            'url' => $request->url,
            'api_key' => $request->api_key,
            'api_url' => 'https://mmorating.top/api/v1/vote/check',
            'bonus_amount' => $request->bonus_amount,
            'is_active' => $request->boolean('is_active', true),
        ];

        if ($voteTop) {
            $voteTop->update($data);
        } else {
            VoteTop::create($data);
        }

        return redirect()->route('admin.votes.index')->with('success', 'Настройки mmorating.top сохранены');
    }
}
