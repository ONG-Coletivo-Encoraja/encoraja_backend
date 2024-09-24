<?php

namespace Database\Seeders;

use App\Models\Reviews;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Reviews::create([
            'rating' => 4,
            'observation' => 'Bom evento, mas poderia melhorar a organização.',
            'recommendation' => true,
            'feel_welcomed' => true,
            'user_id' => 2,
            'event_id' => 3,
        ]);

        Reviews::create([
            'rating' => 3,
            'observation' => 'Evento interessante, mas não atendeu minhas expectativas.',
            'recommendation' => false,
            'feel_welcomed' => false,
            'user_id' => 1,
            'event_id' => 5,
        ]);
    }
}
