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
            'street' => 'Rua da Flores',
            'number' => '123',
            'neighbourhood' => 'Centro',
            'city' => 'São Paulo',
            'zip_code' => '01000-054',
            'user_id' => 1,
        ]);

        Address::create([
            'street' => 'Rua das curvas distantes',
            'number' => '13',
            'neighbourhood' => 'Centro',
            'city' => 'Americana',
            'zip_code' => '01000-765',
            'user_id' => 2,
        ]);

        Address::create([
            'street' => 'Rua Itabuada',
            'number' => '43',
            'neighbourhood' => 'Centro',
            'city' => 'Curitiba',
            'zip_code' => '01000-999',
            'user_id' => 3,
        ]);

        Address::create([
            'street' => 'Rua da sereira',
            'number' => '423',
            'neighbourhood' => 'Centro',
            'city' => 'Curitiba',
            'zip_code' => '01000-030',
            'user_id' => 4,
        ]);

        Address::create([
            'street' => 'Rua terra rocha',
            'number' => '44',
            'neighbourhood' => 'Centro',
            'city' => 'Campo largo',
            'zip_code' => '01000-000',
            'user_id' => 5,
        ]);

        Address::create([
            'street' => 'Rua jose krenchiclova',
            'number' => '43',
            'neighbourhood' => 'Tatuquara',
            'city' => 'Curitiba',
            'zip_code' => '01000-000',
            'user_id' => 6,
        ]);

        Address::create([
            'street' => 'Rua paula gomes',
            'number' => '271',
            'neighbourhood' => 'Centro',
            'city' => 'Curitiba',
            'zip_code' => '01000-100',
            'user_id' => 7,
        ]);
    }
}
