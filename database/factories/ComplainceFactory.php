<?php

namespace Database\Factories;

use App\Models\Complaince;
use Illuminate\Database\Eloquent\Factories\Factory;

class ComplainceFactory extends Factory
{
    protected $model = Complaince::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'phone_number' => $this->faker->phoneNumber,
            'description' => $this->faker->text(200),
            'relation' => $this->faker->word,
            'motivation' => $this->faker->sentence,
            'browser' => 'Chrome',
            'ip_address' => $this->faker->ipv4,
        ];
    }
}