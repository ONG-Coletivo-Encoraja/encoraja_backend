<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'administrator',
            'volunteer',
            'beneficiary',
        ];

        $users = User::all();

        foreach ($users as $index => $user) {
            Permission::create([
                'type' => $permissions[$index],
                'user_id' => $user->id,
            ]);
        }
    }

}
