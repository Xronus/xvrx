<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // Поиск по username или email
        if ($request->has('search') && ! empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('username', 'like', '%'.$searchTerm.'%')
                    ->orWhere('email', 'like', '%'.$searchTerm.'%');
            });
        }

        $users = $query->orderBy('id', 'desc')->paginate(20)->withQueryString();
        $searchTerm = $request->input('search', '');

        // Fetch game account mapping (username uppercase → account id) and ban statuses
        $gameAccounts = $this->getGameAccounts();
        $gameBanned = $this->getGameBannedAccounts();

        return view('admin.users.index', compact('users', 'searchTerm', 'gameAccounts', 'gameBanned'));
    }

    private function getGameAccounts(): array
    {
        try {
            return DB::connection('game_auth')
                ->table('account')
                ->pluck('id', 'username')
                ->toArray();
        } catch (\Exception $e) {
            return [];
        }
    }

    private function getGameBannedAccounts(): array
    {
        try {
            return DB::connection('game_auth')
                ->table('account_banned')
                ->where('active', 1)
                ->where(function ($q) {
                    $q->where('unbandate', 0)
                        ->orWhere('unbandate', '>', time());
                })
                ->pluck('banreason', 'id')
                ->toArray();
        } catch (\Exception $e) {
            return [];
        }
    }

    public function ban(Request $request, User $user)
    {
        $reason = $request->input('ban_reason', '');
        $user->ban($reason ?: null);

        return redirect()->route('admin.users.index')->with('success', __('main.user_banned'));
    }

    public function unban(User $user)
    {
        $user->unban();

        return redirect()->route('admin.users.index')->with('success', __('main.user_unbanned'));
    }

    public function edit(User $user)
    {
        // Game ban info
        $gameBanned = false;
        $gameBanReason = null;
        $gameBanDate = null;

        try {
            $gameAccId = DB::connection('game_auth')
                ->table('account')
                ->where('username', strtoupper($user->username))
                ->value('id');

            if ($gameAccId) {
                $gameBan = DB::connection('game_auth')
                    ->table('account_banned')
                    ->where('account', $gameAccId)
                    ->where('active', 1)
                    ->where(function ($q) {
                        $q->where('unbandate', 0)
                            ->orWhere('unbandate', '>', time());
                    })
                    ->first();

                if ($gameBan) {
                    $gameBanned = true;
                    $gameBanReason = $gameBan->banreason;
                    $gameBanDate = $gameBan->bandate;
                }
            }
        } catch (\Exception $e) {
        }

        return view('admin.users.edit', compact('user', 'gameBanned', 'gameBanReason', 'gameBanDate'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'is_admin' => 'boolean',
            'bonuses' => 'integer|min:0',
            'banned' => 'boolean',
            'ban_reason' => 'nullable|string|max:255',
            'game_banned' => 'boolean',
            'game_ban_reason' => 'nullable|string|max:255',
        ]);

        $user->forceFill([
            'is_admin' => $request->boolean('is_admin'),
            'bonuses' => (int) $validated['bonuses'],
        ])->save();

        // Website ban/unban via toggle
        $adminUser = auth()->user()->username;
        if ($request->boolean('banned') && !$user->isBanned()) {
            $user->ban($validated['ban_reason'] ?: null);
            Log::info('User banned', ['admin' => $adminUser, 'user' => $user->username, 'reason' => $validated['ban_reason'] ?? '']);
        } elseif (!$request->boolean('banned') && $user->isBanned()) {
            $user->unban();
            Log::info('User unbanned', ['admin' => $adminUser, 'user' => $user->username]);
        } elseif ($user->isBanned() && $request->boolean('banned')) {
            $user->update(['ban_reason' => $validated['ban_reason'] ?: null]);
        }

        // Game ban/unban via toggle
        $this->syncGameBan($user, $request->boolean('game_banned'), $validated['game_ban_reason'] ?: '');

        return redirect()->route('admin.users.index')->with('success', __('main.user_updated'));
    }

    private function syncGameBan(User $user, bool $shouldBan, string $reason): void
    {
        try {
            $gameAccId = DB::connection('game_auth')
                ->table('account')
                ->where('username', strtoupper($user->username))
                ->value('id');

            if (!$gameAccId) {
                return;
            }

            $existingBan = DB::connection('game_auth')
                ->table('account_banned')
                ->where('account', $gameAccId)
                ->where('active', 1)
                ->where(function ($q) {
                    $q->where('unbandate', 0)
                        ->orWhere('unbandate', '>', time());
                })
                ->first();

            $currentlyBanned = (bool) $existingBan;

            if ($shouldBan && !$currentlyBanned) {
                DB::connection('game_auth')->table('account_banned')->insert([
                    'account' => $gameAccId,
                    'bandate' => time(),
                    'unbandate' => 0,
                    'bannedby' => auth()->user()->username,
                    'banreason' => $reason,
                    'active' => 1,
                ]);
            } elseif (!$shouldBan && $currentlyBanned) {
                DB::connection('game_auth')->table('account_banned')
                    ->where('id', $existingBan->id)
                    ->update(['active' => 0, 'unbandate' => time()]);
            } elseif ($shouldBan && $currentlyBanned && $reason) {
                DB::connection('game_auth')->table('account_banned')
                    ->where('id', $existingBan->id)
                    ->update(['banreason' => $reason]);
            }
        } catch (\Exception $e) {
            Log::error('Game ban sync error: '.$e->getMessage(), [
                'username' => $user->username,
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }
}
