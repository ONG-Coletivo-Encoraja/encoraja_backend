<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AddressFactory extends Factory
{
    protected $model = Address::class;

    public function definition()
    {
        return [
            'street' => $this->faker->streetName,
            'number' => $this->faker->numberBetween(1, 9999),
            'neighbourhood' => $this->faker->word,
            'city' => $this->faker->city,
            'zip_code' => $this->faker->postcode,
            'user_id' => User::factory(),
        ];
    }
}