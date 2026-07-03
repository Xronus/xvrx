<?php

namespace App\Models;

use App\Models\Concerns\HasLocalization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory, HasLocalization;

    protected $fillable = [
        'date',
        'text',
        'text_en',
        'text_de',
        'text_es',
        'text_fr',
        'content',
        'content_en',
        'content_de',
        'content_es',
        'content_fr',
        'images',
    ];

    public function getImagesAttribute($value)
    {
        if (empty($value)) {
            return $value;
        }

        return $value;
    }
}
