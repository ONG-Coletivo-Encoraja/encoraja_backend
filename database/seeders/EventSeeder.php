<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Event::create([
            'name' => 'Workshop de Desenvolvimento Pessoal',
            'description' => 'Um workshop focado em habilidades de desenvolvimento pessoal.',
            'date' => '2024-10-01',
            'time' => '10:00:00',
            'modality' => 'presential',
            'status' => 'active',
            'type' => 'workshop',
            'target_audience' => 'Jovens adultos',
            'vacancies' => 30,
            'social_vacancies' => 10,
            'regular_vacancies' => 20,
            'material' => 'Materiais do workshop serão fornecidos.',
            'interest_area' => 'Desenvolvimento Pessoal',
            'price' => 50.00,
            'workload' => 4,
        ]);

        Event::create([
            'name' => 'Curso de Programação em PHP',
            'description' => 'Curso completo sobre programação em PHP.',
            'date' => '2024-11-05',
            'time' => '14:00:00',
            'modality' => 'hybrid',
            'status' => 'pending',
            'type' => 'course',
            'target_audience' => 'Iniciantes em programação',
            'vacancies' => 25,
            'social_vacancies' => 5,
            'regular_vacancies' => 20,
            'material' => 'Acesso ao material online.',
            'interest_area' => 'Tecnologia',
            'price' => 200.00,
            'workload' => 40,
        ]);

        Event::create([
            'name' => 'Palestra sobre Sustentabilidade',
            'description' => 'Uma palestra sobre práticas sustentáveis no cotidiano.',
            'date' => '2024-10-15',
            'time' => '19:00:00',
            'modality' => 'remote',
            'status' => 'finished',
            'type' => 'lecture',
            'target_audience' => 'Público geral',
            'vacancies' => 100,
            'social_vacancies' => null,
            'regular_vacancies' => null,
            'material' => null,
            'interest_area' => 'Meio Ambiente',
            'price' => 0.00,
            'workload' => 2,
        ]);

        Event::create([
            'name' => 'Oficina de Criatividade',
            'description' => 'Oficina prática para desenvolver a criatividade.',
            'date' => '2024-12-01',
            'time' => '09:00:00',
            'modality' => 'presential',
            'status' => 'inactive',
            'type' => 'workshop',
            'target_audience' => 'Profissionais de diversas áreas',
            'vacancies' => 15,
            'social_vacancies' => 3,
            'regular_vacancies' => 12,
            'material' => 'Materiais serão fornecidos.',
            'interest_area' => 'Artes',
            'price' => 75.00,
            'workload' => 3,
        ]);

        Event::create([
            'name' => 'Curso Avançado de Marketing Digital',
            'description' => 'Curso avançado para profissionais de marketing.',
            'date' => '2024-11-20',
            'time' => '13:00:00',
            'modality' => 'hybrid',
            'status' => 'finished',
            'type' => 'course',
            'target_audience' => 'Profissionais de marketing',
            'vacancies' => 20,
            'social_vacancies' => 5,
            'regular_vacancies' => 15,
            'material' => 'Acesso a conteúdo online e presencial.',
            'interest_area' => 'Marketing',
            'price' => 300.00,
            'workload' => 50,
        ]);
    }
}
