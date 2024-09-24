<?php

namespace Database\Seeders;

use App\Models\RequestVolunteer;
use Illuminate\Database\Seeder;

class RequestVolunteerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        RequestVolunteer::create([
            'status' => 'pending',
            'availability' => 'full-time',
            'course_experience' => 'Nenhuma',
            'how_know' => 'Amigos',
            'expectations' => 'Aprender e ajudar',
        ]);
    }
}
