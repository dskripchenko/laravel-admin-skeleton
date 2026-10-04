<?php

namespace Database\Factories;

use App\Enums\PostStatus;
use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    public function definition(): array
    {
        $title = rtrim(fake()->sentence(5), '.');
        $status = fake()->randomElement([PostStatus::Draft, PostStatus::Review, PostStatus::Published, PostStatus::Published, PostStatus::Published]);

        return [
            'title' => $title,
            'slug' => Str::slug($title).'-'.fake()->unique()->numberBetween(1, 99999),
            'excerpt' => fake()->paragraph(),
            'body' => '## '.fake()->sentence()."\n\n".implode("\n\n", fake()->paragraphs(4)),
            'status' => $status,
            'published_at' => $status === PostStatus::Published ? fake()->dateTimeBetween('-6 months') : null,
        ];
    }
}
