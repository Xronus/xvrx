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
        'is_admin',
        'bonuses',
        'votes',
    ];

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
