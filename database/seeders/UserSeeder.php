<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'João da Silva',
            'email' => 'joao@example.com',
            'password' => Hash::make('aqswdefr'),
            'cpf' => '123.456.789-00',
            'date_birthday' => '1990-01-01',
            'ethnicity' => 'other',
            'gender' => 'male',
            'image_term' => true,
            'data_term' => true,
            'phone' => '11987654321',
            'request_volunteer_id' => null,
            'status' => 'active',
        ]);

        User::create([
            'name' => 'Maria Oliveira',
            'email' => 'maria@example.com',
            'password' => Hash::make('aqswdefr'),
            'cpf' => '987.654.321-00',
            'date_birthday' => '1992-02-02',
            'ethnicity' => 'white',
            'gender' => 'female',
            'image_term' => true,
            'data_term' => true,
            'phone' => '11912345678',
            'request_volunteer_id' => 1,
            'status' => 'active',
        ]);

        User::create([
            'name' => 'ana Oliveira',
            'email' => 'ana@example.com',
            'password' => Hash::make('aqswdefr'),
            'cpf' => '987.654.321-01',
            'date_birthday' => '1992-02-02',
            'ethnicity' => 'black',
            'gender' => 'female',
            'image_term' => true,
            'data_term' => true,
            'phone' => '11912345678',
            'request_volunteer_id' => null,
            'status' => 'active',
        ]);
    }
}
