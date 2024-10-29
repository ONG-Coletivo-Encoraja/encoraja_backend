<?php

namespace Database\Seeders;

use App\Models\Permission;
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

        Permission::create([
            'type' => $permissions[0],
            'user_id' => 1,
        ]);

        Permission::create([
            'type' => $permissions[1],
            'user_id' => 2,
        ]);

        Permission::create([
            'type' => $permissions[1],
            'user_id' => 3,
        ]);

        Permission::create([
            'type' => $permissions[2],
            'user_id' => 4,
        ]);

        Permission::create([
            'type' => $permissions[2],
            'user_id' => 5,
        ]);

        Permission::create([
            'type' => $permissions[2],
            'user_id' => 6,
        ]);

        Permission::create([
            'type' => $permissions[2],
            'user_id' => 7,
        ]);
        
    }

}
