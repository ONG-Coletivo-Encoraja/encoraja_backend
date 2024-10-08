<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\RequestVolunteer;

class RequestVolunteerFactory extends Factory
{
    protected $model = RequestVolunteer::class;

    public function definition()
    {
        return [
            'availability' => $this->faker->sentence(3),
            'course_experience' => $this->faker->paragraph(),
            'status' => $this->faker->randomElement(['pending', 'accepted', 'rejected']),
            'how_know' => $this->faker->paragraph(),
            'expectations' => $this->faker->paragraph(),
        ];
    }
}