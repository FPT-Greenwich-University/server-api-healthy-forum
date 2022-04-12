<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Image;
use App\Models\Post;
use App\Models\PostLike;
use App\Models\PostRating;
use App\Models\Profile;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $models = collect(['\App\Models\User', '\App\Models\Post']);
        User::factory()
            ->has(Post::factory(1)
                ->has(Image::factory()->count(1)
                    ->state(new Sequence(fn($sequence) => ['imageable_type' => $models->random()])
                    ))
                ->has(Comment::factory()->count(5))
                ->has(Tag::factory()->count(1))
                ->has(PostLike::factory()->count(1))
                ->has(PostRating::factory()->count(1))
                ->count(1))
            ->has(Profile::factory()->count(1))
            ->has(Image::factory()->count(1))
            ->count(20)->create();
    }
}
