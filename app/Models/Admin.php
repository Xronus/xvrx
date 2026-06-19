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
        'password',
		'is_admin', // обязательно добавьте это поле
        'email',      // Обязательно добавьте email
        'comment',
    ];

    protected $hidden = [
        'password',
    ];
}