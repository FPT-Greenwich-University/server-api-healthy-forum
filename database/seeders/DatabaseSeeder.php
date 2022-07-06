<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            CategorySeeder::class,
            TagSeeder::class,
            RolePermissionSeeder::class,
//            UserSeeder::class,
            AdminAccountSeeder::class,
            DoctorAccountSeeder::class,
//            PostSeeder::class,
        ]);
    }
}
