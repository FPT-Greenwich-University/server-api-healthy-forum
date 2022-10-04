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
        $doctor = User::create([
            'name' => 'doctor',
            'email' => 'doctor@gmail.com',
            'password' => bcrypt('ngocphuocha'),
            'email_verified_at' => now()
        ]);
        $doctor->assignRole('customer', 'doctor'); // assign role customer and doctor
        $doctor->givePermissionTo('view all posts', 'view a post', 'create user', 'create a post', 'update a post', 'delete a post'); // give permissions
        $doctor->profile()->create([
            'phone' => '0915085410',
            'age' => 30,
            'gender' => Profile::MALE_GENDER,
            'city' => 'Quang Nam',
            'district' => 'Hoi An',
            'ward' => 'Cam Chau',
            'street' => '213 Cua Dai',
        ]);

        // Set default avatar
        $doctor->image()->create(['path' => "default/avatar/user-avatar.png"]);
    }
}
