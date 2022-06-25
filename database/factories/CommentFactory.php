<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory
 */
class CommentFactory extends Factory
{
    public array $listUserID = [];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $listUserID = User::all()->pluck('id')->toArray();

        return [
            'content' => $this->faker->colorName,
            'user_id' => 1
        ];
    }
}
