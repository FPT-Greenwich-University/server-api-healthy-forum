<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Tag::insert([
            [
                "name" => "Water",
                "description" => "The water",
            ],
            [
                "name" => "HealthyFood",
                "description" => "The healthy food"
            ],
            [
                "name" => "Relax",
                "description" => "Relax"
            ],
            [
                "name" => "Gym",
                "description" => "Go to the gym"
            ]
        ]);
    }
}
