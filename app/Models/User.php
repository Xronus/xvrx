<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'email',
        'password',
        'salt',
        'verifier',
        'banned_at',
        'ban_reason',
        'votes',
    ];

    protected $guarded = ['is_admin', 'bonuses'];

    protected $hidden = [
        'password',
        'salt',
        'verifier',
        'remember_token',
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
        $this->update([
            'banned_at' => now(),
            'ban_reason' => $reason,
        ]);
    }

    /**
     * Unban the user.
     */
    public function unban(): void
    {
        $this->update([
            'banned_at' => null,
            'ban_reason' => null,
        ]);
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
