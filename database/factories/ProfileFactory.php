<?php

namespace Database\Factories;

use App\Models\Profile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Profile>
 */
class ProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'phone' => $this->faker->phoneNumber(),
            'age' => rand(20, 60),
            'gender'=> rand(Profile::MALE_GENDER, Profile::FEMALE_GENDER),
            'description' => $this->faker->realText(100),
            'city' => $this->faker->city,
            'district' =>  $this->faker->state,
            'ward' => $this->faker->streetName,
            'street' => $this->faker->streetAddress
        ];
    }
}
