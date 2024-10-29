<?php

namespace Database\Seeders;

use App\Models\Complaince;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class CompliancesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Complaince::create([
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'phone_number' => '1234567890',
            'description' => 'Me senti acoada por um dos voluntários',
            'relation' => 'Beneficiária',
            'motivation' => 'Tratamento inadequado',
            'ip_address' => '192.168.1.1',
            'browser' => 'Chrome',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Complaince::create([
            'name' => 'Jane Doe',
            'email' => 'jane.doe@example.com',
            'phone_number' => '0987654321',
            'description' => 'Fui julgada por uma pessoa presente.',
            'relation' => 'Beneficiária',
            'motivation' => 'Julgamento',
            'ip_address' => '192.168.1.2',
            'browser' => 'Firefox',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Complaince::create([
            'name' => 'Alice Johnson',
            'email' => 'alice.johnson@example.com',
            'phone_number' => '1112233445',
            'description' => 'Fui até a ong onde não recebi o apoio que disseram que poderiam me ofertar.',
            'relation' => 'Beneficiário',
            'motivation' => 'Enganação',
            'ip_address' => '192.168.1.3',
            'browser' => 'Safari',
            'created_at' => Carbon::create(2024, 5, 1, 12, 30, 45),
            'updated_at' => now(),
        ]);
    }
}