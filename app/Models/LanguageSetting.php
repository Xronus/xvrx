<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LanguageSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'native_name',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public static function getActiveCodes(): array
    {
        return self::where('is_active', true)->orderBy('sort_order')->pluck('code')->toArray();
    }

    public static function getAllOrdered()
    {
        return self::orderBy('sort_order')->get();
    }
}
