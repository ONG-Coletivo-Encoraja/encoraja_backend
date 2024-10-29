<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RequestVolunteerSeeder::class,
            UserSeeder::class,
            AddressSeeder::class,
            PermissionSeeder::class,
            EventSeeder::class,
            RelatesEventSeeder::class,
            InscriptionSeeder::class,
            ReviewSeeder::class,
            ReportAdminSeeder::class,
            CompliancesSeeder::class
        ]);
    }
}
