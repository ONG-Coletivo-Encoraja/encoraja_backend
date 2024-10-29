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
            'status' => 'accepted',
            'availability' => 'full-time',
            'course_experience' => 'Nenhuma',
            'how_know' => 'Amigos',
            'expectations' => 'Aprender e ajudar',
        ]);

        RequestVolunteer::create([
            'status' => 'accepted',
            'availability' => 'Todas as horas',
            'course_experience' => 'Costura e moda',
            'how_know' => 'Redes sociais',
            'expectations' => 'Aprender e ajudar com minhas incríveis habilidades de moda',
        ]);

        RequestVolunteer::create([
            'status' => 'pending',
            'availability' => 'Finais de semana',
            'course_experience' => 'Maquiagem',
            'how_know' => 'Redes sociais e amigos',
            'expectations' => 'Aprender e ajudar com minhas incríveis habilidades de maquiagem',
        ]);
    }
}
