<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    public function definition(): array
    {
        $title = $this->faker->sentence(6);

        return [
            'user_id'      => User::factory(),
            'title'        => $title,
            'slug'         => Str::slug($title),
            'content'      => $this->faker->paragraphs(3, true),
            'published'    => false,
            'published_at' => null,
        ];
    }

    // État : article publié
    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'published'    => true,
            'published_at' => now(),
        ]);
    }
}
