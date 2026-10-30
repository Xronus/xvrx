<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BalanceTopUp extends Model
{
    use HasFactory;

    public const STATUS_CREATING = 'creating';

    public const STATUS_PENDING = 'pending';

    public const STATUS_SUCCEEDED = 'succeeded';

    public const STATUS_CANCELED = 'canceled';

    public const STATUS_FAILED = 'failed';

    protected $fillable = [
        'public_id',
        'user_id',
        'provider',
        'provider_payment_id',
        'idempotence_key',
        'amount_kopecks',
        'currency',
        'bonus_amount',
        'status',
        'is_test',
        'confirmation_url',
        'failure_code',
        'provider_snapshot',
        'paid_at',
        'credited_at',
    ];

    protected function casts(): array
    {
        return [
            'amount_kopecks' => 'integer',
            'bonus_amount' => 'integer',
            'is_test' => 'boolean',
            'provider_snapshot' => 'array',
            'paid_at' => 'datetime',
            'credited_at' => 'datetime',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'public_id';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
