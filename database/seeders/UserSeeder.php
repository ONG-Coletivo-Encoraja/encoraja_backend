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
        //administrator
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

        //volunteer
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
            'name' => 'Carina Silva',
            'email' => 'carina@example.com',
            'password' => Hash::make('aqswdefr'),
            'cpf' => '123.654.321-00',
            'date_birthday' => '1992-02-02',
            'ethnicity' => 'white',
            'gender' => 'female',
            'image_term' => true,
            'data_term' => true,
            'phone' => '11912345678',
            'request_volunteer_id' => 2,
            'status' => 'active',
        ]);

        //beneficary
        User::create([
            'name' => 'Ana Oliveira',
            'email' => 'ana@example.com',
            'password' => Hash::make('aqswdefr'),
            'cpf' => '987.654.321-67',
            'date_birthday' => '1992-02-02',
            'ethnicity' => 'black',
            'gender' => 'female',
            'image_term' => true,
            'data_term' => true,
            'phone' => '11912345678',
            'request_volunteer_id' => 3,
            'status' => 'active',
        ]);

        User::create([
            'name' => 'Marcia Abrel',
            'email' => 'marcia@example.com',
            'password' => Hash::make('aqswdefr'),
            'cpf' => '987.654.321-68',
            'date_birthday' => '1992-02-02',
            'ethnicity' => 'black',
            'gender' => 'female',
            'image_term' => true,
            'data_term' => true,
            'phone' => '11912345678',
            'request_volunteer_id' => 1,
            'status' => 'active',
        ]);
        User::create([
            'name' => 'Carla Machado',
            'email' => 'carla@example.com',
            'password' => Hash::make('aqswdefr'),
            'cpf' => '987.654.321-69',
            'date_birthday' => '1992-02-02',
            'ethnicity' => 'black',
            'gender' => 'female',
            'image_term' => true,
            'data_term' => true,
            'phone' => '11912345678',
            'request_volunteer_id' => null,
            'status' => 'active',
        ]);
        User::create([
            'name' => 'Jessica Ferreira',
            'email' => 'jessica@example.com',
            'password' => Hash::make('aqswdefr'),
            'cpf' => '987.654.321-71',
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
