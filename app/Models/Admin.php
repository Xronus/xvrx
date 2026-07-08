<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;

    protected $table = 'admin'; // Явно указываем имя таблицы

    protected $fillable = [
        'username',
        'email',
        'comment',
    ];

    protected $guarded = ['is_admin', 'password'];

    protected $hidden = [
        'password',
        'is_admin',
    ];
}
