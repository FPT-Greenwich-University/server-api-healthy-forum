<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Seeder;

class DoctorAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::create([
            'name' => 'doctor demo',
            'email' => 'doctor@gmail.com',
            'password' => bcrypt('ngocphuocha'),
            'email_verified_at' => now()
        ]);
        $admin->assignRole('customer', 'doctor'); // assign role customer and admin
        $admin->givePermissionTo('view all posts', 'view a post', 'create user', 'create a post', 'update a post', 'delete a post'); // give permissions
        $admin->profile()->create([
            'phone' => '0915085410',
            'age' => 30,
            'gender' => Profile::MALE_GENDER,
            'city' => 'Quang Nam',
            'district' => 'Hoi An',
            'ward' => 'Cam Chau',
            'street' => '213 Cua Dai',
        ]);
    }
}
