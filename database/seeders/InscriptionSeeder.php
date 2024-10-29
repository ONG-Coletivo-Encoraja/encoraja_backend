<?php

namespace Database\Seeders;

use App\Models\Inscription;
use Illuminate\Database\Seeder;

class InscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Inscription::create([
            'user_id' => 5,
            'event_id' => 1,
            'status' => 'approved',
            'present' => false,
        ]);

        Inscription::create([
            'user_id' => 6,
            'event_id' => 1,
            'status' => 'pending',
            'present' => false,
        ]);

        Inscription::create([
            'user_id' => 1,
            'event_id' => 5,
            'status' => 'approved',
            'present' => false,
        ]);

        Inscription::create([
            'user_id' => 1,
            'event_id' => 5,
            'status' => 'approved',
            'present' => true,
        ]);

        Inscription::create([
            'user_id' => 4,
            'event_id' => 3,
            'status' => 'approved',
            'present' => true,
        ]);   
        
        Inscription::create([
            'user_id' => 5,
            'event_id' => 3,
            'status' => 'approved',
            'present' => true,
        ]); 

        Inscription::create([
            'user_id' => 6,
            'event_id' => 3,
            'status' => 'approved',
            'present' => true,
        ]); 
    }
}
