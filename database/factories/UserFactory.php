<?php

namespace Database\Factories;

use App\Models\RequestVolunteer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => Hash::make('password'),
            'cpf' => $this->faker->unique()->numerify('###.###.###-##'),
            'date_birthday' => $this->faker->date(),
            'ethnicity' => $this->faker->randomElement(['white', 'black', 'mixed', 'asian', 'other']),
            'gender' => $this->faker->randomElement(['female', 'male', 'prefer not say']),
            'phone' => $this->faker->phoneNumber,
            'status' => $this->faker->randomElement(['active', 'inactive']),
            'image_term' => $this->faker->boolean,
            'data_term' => $this->faker->boolean,
            'request_volunteer_id' => null,
        ];
    }
}
