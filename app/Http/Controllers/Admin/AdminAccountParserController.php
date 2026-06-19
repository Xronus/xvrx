<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

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
            $accountsToImport = count($gameUsernames) - count($existingUsernames);
        } catch (\Exception $e) {
            Log::error('Account parser error: ' . $e->getMessage());
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
                    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        // Генерируем email на основе username
                        $email = strtolower($gameAccount->username) . '@' . $defaultEmailDomain;
                    }

                    // Проверяем уникальность email
                    $emailExists = User::where('email', $email)->exists();
                    if ($emailExists) {
                        // Если email уже существует, добавляем суффикс
                        $counter = 1;
                        $originalEmail = $email;
                        while (User::where('email', $email)->exists()) {
                            $email = str_replace('@', $counter . '@', $originalEmail);
                            $counter++;
                        }
                    }

                    // Получаем salt и verifier из игровой БД
                    $salt = $gameAccount->salt;
                    $verifier = $gameAccount->verifier;

                    // Если salt/verifier в бинарном формате, конвертируем
                    if (is_resource($salt)) {
                        $salt = stream_get_contents($salt);
                    }
                    if (is_resource($verifier)) {
                        $verifier = stream_get_contents($verifier);
                    }

                    // Если salt/verifier не пустые и не являются валидным base64, кодируем
                    if (!empty($salt)) {
                        $decoded = @base64_decode($salt, true);
                        if ($decoded === false || base64_encode($decoded) !== $salt) {
                            $salt = base64_encode($salt);
                        }
                    } else {
                        $salt = '';
                    }
                    
                    if (!empty($verifier)) {
                        $decoded = @base64_decode($verifier, true);
                        if ($decoded === false || base64_encode($decoded) !== $verifier) {
                            $verifier = base64_encode($verifier);
                        }
                    } else {
                        $verifier = '';
                    }

                    // Создаем пользователя
                    $columns = ['username', 'email', 'salt', 'verifier', 'password', 'bonuses', 'votes', 'is_admin', 'created_at', 'updated_at'];
                    $values = [
                        $gameAccount->username,
                        $email,
                        $salt ?? '',
                        $verifier ?? '',
                        Hash::make(uniqid('', true)), // Генерируем случайный пароль для Laravel
                        0, // bonuses
                        0, // votes
                        0, // is_admin
                        now(),
                        now(),
                    ];

                    if (Schema::hasColumn('users', 'name')) {
                        $columns[] = 'name';
                        $values[] = $gameAccount->username;
                    }

                    $placeholders = implode(',', array_fill(0, count($values), '?'));
                    $columnsList = implode(', ', $columns);
                    
                    DB::statement("INSERT INTO users ({$columnsList}) VALUES ({$placeholders})", $values);

                    $stats['created']++;
                } catch (\Exception $e) {
                    $stats['errors']++;
                    $stats['errors_list'][] = [
                        'username' => $gameAccount->username,
                        'error' => $e->getMessage()
                    ];
                    Log::error('Error importing account: ' . $gameAccount->username . ' - ' . $e->getMessage());
                }
            }

            return response()->json([
                'success' => true,
                'message' => __('main.accounts_imported_successfully'),
                'stats' => $stats
            ]);
        } catch (\Exception $e) {
            Log::error('Account parser error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => __('main.accounts_import_error') . ': ' . $e->getMessage(),
                'stats' => $stats
            ], 500);
        }
    }
}
