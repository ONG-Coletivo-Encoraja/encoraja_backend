<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Event;

class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition()
    {
        return [
            'name' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'date' => $this->faker->date(),
            'time' => $this->faker->time(),
            'modality' => $this->faker->randomElement(['presential', 'hybrid', 'remote']),
            'status' => $this->faker->randomElement(['active', 'pending']),
            'type' => $this->faker->randomElement(['course', 'workshop', 'lecture']),
            'target_audience' => $this->faker->word(),
            'vacancies' => $this->faker->numberBetween(1, 100),
            'social_vacancies' => $this->faker->numberBetween(0, 50),
            'regular_vacancies' => $this->faker->numberBetween(0, 50),
            'material' => $this->faker->text(100),
            'interest_area' => $this->faker->word(),
            'price' => $this->faker->randomFloat(2, 0, 1000),
            'workload' => $this->faker->numberBetween(1, 40),
        ];
    }
}
