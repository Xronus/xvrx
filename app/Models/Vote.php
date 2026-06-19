<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    use HasFactory;

    protected $table = 'vote';

    protected $fillable = [
        'images_company',
        'name',
        'link',
        'bonus',
        'imgcp',
        'file_stat',
    ];

    public function getImagesCompanyAttribute($value)
    {
        if (empty($value)) {
            return $value;
        }

        $value = str_replace('../template/nighthold/', 'powerpuffsite/', $value);
        $value = str_replace('template/nighthold/', 'powerpuffsite/', $value);
        
        return $value;
    }
}
