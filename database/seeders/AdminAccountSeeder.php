<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdminAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::create([
            'name' => 'phuoctn admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('ngocphuocha'),
            'email_verified_at' => now()
        ]);
        $admin->assignRole('customer', 'admin'); // assign role customer and admin
        $admin->givePermissionTo('view all posts', 'view a post', 'create user', 'detail user', 'edit user', 'delete user', 'ban user'); // give permissions
        $admin->profile()->create([
            'phone' => '0984641362',
            'age' => 22,
            'gender' => Profile::MALE_GENDER,
            'city' => 'Quang Nam',
            'district' => 'Hoi An',
            'ward' => 'Cam Chau',
            'street' => '213 Cua Dai',
        ]);
    }
}
