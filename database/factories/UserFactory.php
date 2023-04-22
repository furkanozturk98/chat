<?php

namespace Database\Factories;

use Hash;
use Illuminate\Database\Eloquent\Factories\Factory;
use Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name'      => $this->faker->name,
            'email'     => $this->faker->email,
            'password'  => Hash::make($this->faker->password),
            'api_token' => Str::random(60),
            'image'     => \Str::random(10) . 'jpg',
        ];
    }
}
