<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ResetUserTwoFactor extends Command
{
    protected $signature = '2fa:reset {email : Email of the user to reset 2FA for}';
    protected $description = 'Emergency reset of 2FA for a user (when phone and recovery codes are lost)';

    public function handle(): int
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();

        if (! $user) {
            $this->error("User with email '{$email}' not found.");

            return self::FAILURE;
        }

        if (! $user->hasTwoFactorEnabled()) {
            $this->info("User '{$user->username}' does not have 2FA enabled.");

            return self::SUCCESS;
        }

        $user->disableTwoFactor();
        $this->info("2FA has been reset for user '{$user->username}' (email: {$user->email}).");
        $this->warn('The user must set up 2FA again before accessing the admin panel.');

        return self::SUCCESS;
    }
}
