<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoteTop extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'url',
        'api_key',
        'api_url',
        'bonus_amount',
        'image',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'bonus_amount' => 'integer',
        ];
    }

    public function voteLogs()
    {
        return $this->hasMany(VoteLog::class);
    }
}
