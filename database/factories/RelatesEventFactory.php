<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\RelatesEvent;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RelatesEvent>
 */
class RelatesEventFactory extends Factory
{
    protected $model = RelatesEvent::class;

    public function definition(): array
    {
        return [
            'event_id' => Event::factory(),
            'user_id' => User::factory(),
        ];
    }
}
