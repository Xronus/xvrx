<?php

namespace App\Models;

use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmailContract
{
    use HasFactory, Notifiable, MustVerifyEmail;

    protected $fillable = [
        'username',
        'email',
        'salt',
        'verifier',
        'password',
        'votes',
    ];

    protected $hidden = [
        'password',
        'salt',
        'verifier',
        'remember_token',
        'is_admin',
        'bonuses',
        'banned_at',
        'ban_reason',
        'votes',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'banned_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getIsAdminAttribute($value)
    {
        if ($value === null) {
            return false;
        }
        if (is_bool($value)) {
            return $value;
        }
        if (is_string($value)) {
            return $value === '1' || $value === 'true';
        }
        if (is_int($value)) {
            return $value === 1;
        }

        return (bool) $value;
    }

    /**
     * Check if the user is banned on the website.
     */
    public function isBanned(): bool
    {
        return $this->banned_at !== null;
    }

    /**
     * Ban the user with an optional reason.
     */
    public function ban(?string $reason = null): void
    {
        $this->forceFill([
            'banned_at' => now(),
            'ban_reason' => $reason,
        ])->save();
    }

    /**
     * Unban the user.
     */
    public function unban(): void
    {
        $this->forceFill([
            'banned_at' => null,
            'ban_reason' => null,
        ])->save();
    }

    /**
     * Override to send a custom verification email.
     */
    public function sendEmailVerificationNotification(): void
    {
        $url = \Illuminate\Support\Facades\URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $this->getKey(), 'hash' => sha1($this->getEmailForVerification())]
        );

        $this->notify(new \App\Notifications\VerifyEmailNotification($url));
    }

    protected function getSaltAttribute($value)
    {
        if ($value === null) {
            return null;
        }
        if (is_string($value)) {
            $decoded = base64_decode($value, true);
            if ($decoded !== false) {
                return $decoded;
            }
        }
        if (is_resource($value)) {
            return stream_get_contents($value);
        }

        return $value;
    }

    protected function getVerifierAttribute($value)
    {
        if ($value === null) {
            return null;
        }
        if (is_string($value)) {
            $decoded = base64_decode($value, true);
            if ($decoded !== false) {
                return $decoded;
            }
        }
        if (is_resource($value)) {
            return stream_get_contents($value);
        }

        return $value;
    }
}
