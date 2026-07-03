<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'images',
        'title',
        'description',
        'link',
    ];

    public function getImagesAttribute($value)
    {
        if (empty($value)) {
            return $value;
        }

        return $value;
    }
}
