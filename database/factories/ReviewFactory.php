<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'text' => fake()->paragraph(5),
            'stars' => fake()->randomNumber(1,true),
            'title' => fake()->sentence(5),
            'created_at' => fake()->dateTimeBetween('-5 years','now'),
        ];
    }
}
