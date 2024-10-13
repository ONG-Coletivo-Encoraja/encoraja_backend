<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\Inscription;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class InscriptionFactory extends Factory
{
    protected $model = Inscription::class;

    public function definition(): array
    {
        return [
            'status' => $this->faker->randomElement(['approved', 'pending', 'rejected']),
            'user_id' => User::factory(),
            'event_id' => Event::factory(),
            'present' => $this->faker->boolean,
        ];
    }
}
