<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AdminAccountParserController extends Controller
{
    public function index()
    {
        $totalGameAccounts = 0;
        $totalWebsiteAccounts = 0;
        $accountsToImport = 0;

        try {
            $totalGameAccounts = DB::connection('game_auth')->table('account')->count();
            $totalWebsiteAccounts = User::count();

            // Подсчитываем аккаунты, которых нет на сайте
            $gameAccounts = DB::connection('game_auth')
                ->table('account')
                ->select('username')
                ->get();

            $gameUsernames = $gameAccounts->pluck('username')->toArray();
            $existingUsernames = User::whereIn('username', $gameUsernames)->pluck('username')->toArray();
            $accountsToImport = max(0, count($gameUsernames) - count($existingUsernames));
        } catch (\Exception $e) {
            Log::error('Account parser error: '.$e->getMessage());
        }

        return view('admin.account-parser.index', compact('totalGameAccounts', 'totalWebsiteAccounts', 'accountsToImport'));
    }

    public function parse(Request $request)
    {
        $request->validate([
            'batch_size' => 'nullable|integer|min:1|max:1000',
            'default_email_domain' => 'nullable|string|max:255',
        ]);

        $batchSize = $request->input('batch_size', 100);
        $defaultEmailDomain = $request->input('default_email_domain', 'example.com');

        $stats = [
            'processed' => 0,
            'created' => 0,
            'skipped' => 0,
            'errors' => 0,
            'errors_list' => [],
        ];

        try {
            // Получаем аккаунты из игровой БД
            $gameAccounts = DB::connection('game_auth')
                ->table('account')
                ->select('id', 'username', 'salt', 'verifier', 'email')
                ->limit($batchSize)
                ->get();

            foreach ($gameAccounts as $gameAccount) {
                $stats['processed']++;

                try {
                    // Проверяем, существует ли уже пользователь с таким username
                    $existingUser = User::where('username', $gameAccount->username)->first();

                    if ($existingUser) {
                        $stats['skipped']++;

                        continue;
                    }

                    // Проверяем, есть ли email в игровой БД
                    $email = $gameAccount->email;
                    if (empty($email) || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        // Генерируем email на основе username
                        $email = strtolower($gameAccount->username).'@'.$defaultEmailDomain;
                    }

                    // Fix email dedup: modify local part only, never the domain
                    $emailExists = User::where('email', $email)->exists();
                    if ($emailExists) {
                        $counter = 1;
                        $originalEmail = $email;
                        $parts = explode('@', $originalEmail, 2);
                        $local = $parts[0];
                        $domain = $parts[1] ?? $defaultEmailDomain;
                        while (User::where('email', $email)->exists()) {
                            $email = $local . '+' . $counter . '@' . $domain;
                            $counter++;
                        }
                    }

                    // Normalize salt and verifier from game DB for Eloquent storage
                    $salt = $gameAccount->salt;
                    $verifier = $gameAccount->verifier;

                    if (is_resource($salt)) {
                        $salt = stream_get_contents($salt);
                    }
                    if (is_resource($verifier)) {
                        $verifier = stream_get_contents($verifier);
                    }

                    // Ensure base64-encoded storage
                    $salt = is_string($salt) && $salt !== ''
                        ? (base64_decode($salt, true) !== false ? $salt : base64_encode($salt))
                        : '';
                    $verifier = is_string($verifier) && $verifier !== ''
                        ? (base64_decode($verifier, true) !== false ? $verifier : base64_encode($verifier))
                        : '';

                    // Use Eloquent with forceFill for guarded fields (is_admin, bonuses, votes, etc.)
                    $user = new User();
                    $user->forceFill([
                        'username' => $gameAccount->username,
                        'email' => $email,
                        'salt' => $salt,
                        'verifier' => $verifier,
                        'password' => Hash::make(uniqid('', true)),
                        'bonuses' => 0,
                        'votes' => 0,
                        'is_admin' => 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ])->save();

                    $stats['created']++;
                } catch (\Exception $e) {
                    $stats['errors']++;
                    $stats['errors_list'][] = [
                        'username' => $gameAccount->username,
                        'error' => $e->getMessage(),
                    ];
                    Log::error('Error importing account: '.$gameAccount->username.' - '.$e->getMessage());
                }
            }

            return response()->json([
                'success' => true,
                'message' => __('main.accounts_imported_successfully'),
                'stats' => $stats,
            ]);
        } catch (\Exception $e) {
            Log::error('Account parser error: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => __('main.accounts_import_error').': '.$e->getMessage(),
                'stats' => $stats,
            ], 500);
        }
    }
}
