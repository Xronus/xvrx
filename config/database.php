<?php

use Illuminate\Support\Str;

return [
    'default' => env('DB_CONNECTION', 'mysql'),
    'connections' => [
        'mysql' => [
            'driver' => 'mysql',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],

        'game_auth' => [
            'driver' => 'mysql',
            'host' => env('GAME_AUTH_DB_HOST', '127.0.0.1'),
            'port' => env('GAME_AUTH_DB_PORT', '3306'),
            'database' => env('GAME_AUTH_DB_DATABASE', 'auth'),
            'username' => env('GAME_AUTH_DB_USERNAME', 'root'),
            'password' => env('GAME_AUTH_DB_PASSWORD', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],

        'game_char' => [
            'driver' => 'mysql',
            'host' => env('GAME_CHAR_DB_HOST', '127.0.0.1'),
            'port' => env('GAME_CHAR_DB_PORT', '3306'),
            'database' => env('GAME_CHAR_DB_DATABASE', 'characters'),
            'username' => env('GAME_CHAR_DB_USERNAME', 'root'),
            'password' => env('GAME_CHAR_DB_PASSWORD', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],

        // НОВОЕ СОЕДИНЕНИЕ ДЛЯ TRINITYCORE CHARACTERS
        'trinity_characters' => [
            'driver' => 'mysql',
            'host' => env('TRINITY_DB_HOST', '127.0.0.1'),
            'port' => env('TRINITY_DB_PORT', '3306'),
            'database' => env('TRINITY_DB_DATABASE', 'characters'),
            'username' => env('TRINITY_DB_USERNAME'),
            'password' => env('TRINITY_DB_PASSWORD'),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => false,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],

        'trinity' => [
            'driver' => 'mysql',
            'host' => env('TRINITY_DB_HOST', '127.0.0.1'),
            'port' => env('TRINITY_DB_PORT', '3306'),
            'database' => env('TRINITY_DB_DATABASE', 'characters'),
            'username' => env('TRINITY_DB_USERNAME', 'root'),
            'password' => env('TRINITY_DB_PASSWORD', 'ascent'),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => false,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ], // <-- ВАЖНО: здесь добавлена закрывающая скобка для массива 'trinity'
    ],
    'migrations' => 'migrations',
    'redis' => [
        'client' => env('REDIS_CLIENT', 'phpredis'),
        'options' => [
            'cluster' => env('REDIS_CLUSTER', 'redis'),
            'prefix' => env('REDIS_PREFIX', Str::slug(env('APP_NAME', 'laravel'), '_').'_database_'),
        ],
        'default' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'username' => env('REDIS_USERNAME'),
            'password' => env('REDIS_PASSWORD'),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_DB', '0'),
        ],
        'cache' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'username' => env('REDIS_USERNAME'),
            'password' => env('REDIS_PASSWORD'),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_CACHE_DB', '1'),
        ],
    ],
];
