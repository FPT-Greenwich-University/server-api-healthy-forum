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
//        Tag::factory()->count(10)->create();
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
            ]
        ]);
    }
}
