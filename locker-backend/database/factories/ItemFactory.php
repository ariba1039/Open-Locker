<?php

namespace Database\Factories;

use App\Models\Locker;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => ucfirst($this->faker->unique()->word()),
            'description' => fake()->text(),
            'image_path' => fake()->imageUrl(),
            'locker_id' => Locker::factory()->create()->id,
        ];
    }
}
