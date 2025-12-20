<?php

namespace Database\Factories;

use App\Models\Apartment;
use App\Models\User;
use App\Models\City;
use Illuminate\Database\Eloquent\Factories\Factory;

class ApartmentFactory extends Factory
{
    protected $model = Apartment::class;

    public function definition(): array
    {
        return [
            'user_id' => User::where('user_type', 'owner')->inRandomOrder()->first()->id,
            'city_id' => City::inRandomOrder()->first()->id,
            'description' => $this->faker->sentence(10),
            'address' => $this->faker->address(),
            'size' => $this->faker->numberBetween(50, 200) . ' m²',
            'num_of_rooms' => $this->faker->numberBetween(1, 6),
            'price' => $this->faker->numberBetween(100000, 800000),
            'apartment_images' => 'apartment/default.jpg',
            'apartment_status' => 'available',
        ];
    }
}
