<?php

namespace Database\Factories;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PermissionFactory extends Factory
{
    protected $model = Permission::class;

    public function definition()
    {
        return [
            'type' => $this->faker->randomElement(['administrator', 'volunteer', 'beneficiary']),
            'user_id' => User::factory(),
        ];
    }
}