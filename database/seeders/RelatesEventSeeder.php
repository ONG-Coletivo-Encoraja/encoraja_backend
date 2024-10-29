<?php

namespace Database\Seeders;

use App\Models\RelatesEvent;
use Illuminate\Database\Seeder;

class RelatesEventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        RelatesEvent::create([
            'user_id' => 2,
            'event_id' => 1,
        ]);

        RelatesEvent::create([
            'user_id' => 2,
            'event_id' => 2,
        ]);

        RelatesEvent::create([
            'user_id' => 3,
            'event_id' => 3,
        ]);

        RelatesEvent::create([
            'user_id' => 2,
            'event_id' => 5,
        ]);
    }
}
