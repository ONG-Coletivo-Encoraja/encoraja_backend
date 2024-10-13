<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\Reviews;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewsFactory extends Factory
{
    protected $model = Reviews::class;

    public function definition()
    {
        return [
            'rating' => $this->faker->numberBetween(1, 5),
            'observation' => $this->faker->text(255),
            'recommendation' => $this->faker->boolean(), 
            'feel_welcomed' => $this->faker->boolean(), 
            'user_id' => User::factory(),
            'event_id' => Event::factory(), 
        ];
    }
}
