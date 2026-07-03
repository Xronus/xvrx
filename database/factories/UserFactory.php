<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'username' => fake()->unique()->userName(),
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'salt' => base64_encode(random_bytes(32)),
            'verifier' => base64_encode(random_bytes(32)),
            'bonuses' => 0,
            'votes' => 0,
            'is_admin' => 0,
        ];
    }
}
