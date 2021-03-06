<?php

namespace AlfaDevTeam\AuthApi\Database\Factories;

use AlfaDevTeam\AuthApi\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => 'CS' . $this->faker->numberBetween(100000, 999999),
            'email' => $this->faker->email(),
            'email_confirmed_at' => now(),
            'password' => Hash::make('123456789Db;'),
        ];
    }
}
