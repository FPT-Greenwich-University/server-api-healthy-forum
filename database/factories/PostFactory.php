<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => $this->faker->name,
            'description' => $this->faker->text,
            'user_id' => 22,
            'is_published' => 1,
            'body' => $this->faker->realText,
            'category_id' => rand(5, 10),
            'published_at' => now(),
        ];
    }
}
