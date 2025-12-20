<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'phone' => $this->faker->unique()->phoneNumber(),
            'password' => bcrypt('password123'),
            'date_of_birth' => $this->faker->date(),
            'profile_image' => 'profiles/default.jpg',
            'id_image' => 'ids/default.jpg',
            'user_type' => 'tenant', // سيتم تغييره في الـ seeder
            'status' => 'approved',
            'remember_token' => Str::random(10),
        ];
    }
}
