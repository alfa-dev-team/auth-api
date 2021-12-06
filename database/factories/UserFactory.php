<?php

namespace Database\Factories;

use App\Models\User\User;
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
            'phone' => (string)$this->faker->numberBetween(10000000000, 99999999999),
            'first_name' => 'Andriy',
            'surname' => 'Petrov',
            'email_confirmed_at' => now(),
            'phone_confirmed_at' => now(),
            'password' => Hash::make('123456789Db;'),
//            'avatar' => 'user.jpg',
//            'two_factor_authentication_type' => 'email'
        ];
    }
}
