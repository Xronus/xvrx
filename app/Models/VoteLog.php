<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoteLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'vote_top_id',
        'ip_address',
        'rewarded_at',
    ];

    protected function casts(): array
    {
        return [
            'rewarded_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function voteTop()
    {
        return $this->belongsTo(VoteTop::class);
    }
}
