<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    public function definition(): array
    {
        $publishAt = rand(0, 1) === 0
            ? null
            : now()->addDays(rand(-30, 30));

        return [
            'title' => $this->faker->words(4, true),
            'headline' => $this->faker->sentence(8, true),
            'content' => $this->faker->text(),
            'author_id' => User::factory(),
            'publish_at' => $publishAt,
            'is_published' => !!($publishAt?->isPast()),
        ];
    }

    public function unpublished(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'is_published' => false,
                'publish_at' => null,
            ];
        });
    }

    public function published(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'is_published' => true,
                'publish_at' => now()->addDays(rand(-30, 30)),
            ];
        });
    }
}
