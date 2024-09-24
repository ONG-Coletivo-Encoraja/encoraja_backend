<?php

namespace Database\Seeders;

use App\Models\Address;
use Illuminate\Database\Seeder;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Address::create([
            'street' => 'Rua Exemplo',
            'number' => '123',
            'neighbourhood' => 'Centro',
            'city' => 'São Paulo',
            'zip_code' => '01000-000',
            'user_id' => 1,
        ]);

        Address::create([
            'street' => 'Rua Exemplo',
            'number' => '13',
            'neighbourhood' => 'Centro',
            'city' => 'Americana',
            'zip_code' => '01000-000',
            'user_id' => 2,
        ]);

        Address::create([
            'street' => 'Rua Exemplo',
            'number' => '43',
            'neighbourhood' => 'Centro',
            'city' => 'Curitiba',
            'zip_code' => '01000-000',
            'user_id' => 3,
        ]);
    }
}
