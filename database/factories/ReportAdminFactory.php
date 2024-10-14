<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\ReportAdmin;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReportAdminFactory extends Factory
{
    protected $model = ReportAdmin::class;

    public function definition()
    {
        return [
            'qtt_person' => $this->faker->numberBetween(1, 100),
            'description' => $this->faker->paragraph(),
            'results' => $this->faker->paragraph(),
            'observation' => $this->faker->optional()->sentence(),
            'relates_event_id' => Event::factory(),
        ];
    }
}
