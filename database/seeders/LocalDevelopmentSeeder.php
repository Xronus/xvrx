<?php

namespace Database\Seeders;

use App\Models\User;
use App\Services\SRP6Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LocalDevelopmentSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedUser('admin', 'admin@example.test', 'Admin123!', true, 2);
        $this->seedUser('test', 'test@example.test', 'User123!', false, 1);
    }

    private function seedUser(string $username, string $email, string $password, bool $isAdmin, int $gameAccountId): void
    {
        [$salt, $verifier] = app(SRP6Service::class)->getRegistrationData(
            strtoupper($username),
            $password
        );

        User::query()->updateOrCreate(
            ['username' => $username],
            [
                'email' => $email,
                'salt' => base64_encode($salt),
                'verifier' => base64_encode($verifier),
                'password' => Hash::make($password),
                'is_admin' => $isAdmin,
                'bonuses' => 5000,
                'votes' => 0,
                'email_verified_at' => now(),
                'banned_at' => null,
                'ban_reason' => null,
            ]
        );

        DB::connection('game_auth')->table('account')->updateOrInsert(
            ['id' => $gameAccountId],
            [
                'username' => strtoupper($username),
                'email' => $email,
                'reg_mail' => $email,
                'salt' => $salt,
                'verifier' => $verifier,
                'expansion' => 2,
                'totaltime' => 0,
            ]
        );
    }
}
