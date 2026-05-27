<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Support\ImageGenerator;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::factory()->count(4)->create();
        $tags = Tag::factory()->count(6)->create();

        Post::factory()
            ->count(8)
            ->published()
            ->recycle($categories)
            ->create()
            ->each(function (Post $post) use ($tags) {
                $post->update(['cover_image' => ImageGenerator::cover($post->title)]);
                $post->tags()->attach($tags->random(rand(1, 3)));
            });

        Post::factory()
            ->count(2)
            ->recycle($categories)
            ->create();
    }
}
