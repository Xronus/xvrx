<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Character extends Model
{
    protected $connection = 'trinity'; // указываем подключение к Trinity DB

    protected $table = 'characters'; // имя таблицы в БД

    public $timestamps = false; // если в таблице нет полей created_at/updated_at
}
