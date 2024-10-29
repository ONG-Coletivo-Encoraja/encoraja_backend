<?php

namespace Database\Seeders;

use App\Models\ReportAdmin;
use Illuminate\Database\Seeder;

class ReportAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        ReportAdmin::create([
            'qtt_person' => 5,
            'description' => 'Reunião de planejamento',
            'results' => 'Planejamento concluído',
            'observation' => 'Todos os participantes estavam presentes',
            'relates_event_id' => 3,
        ]);

        ReportAdmin::create([
            'qtt_person' => 10,
            'description' => 'Treinamento de equipe',
            'results' => 'Treinamento realizado com sucesso',
            'observation' => 'Feedback positivo da equipe',
            'relates_event_id' => 4,
        ]);
    }
}
