<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ItemLoan>
 */
class ItemLoanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'item_id' => Item::factory(),
            'user_id' => User::factory(),
            'borrowed_at' => now(),
            'returned_at' => null,
            'notes' => fake()->optional()->sentence(),
        ];
    }

    /**
     * Indicate that the loan has been returned.
     */
    public function returned(): static
    {
        return $this->state(fn (array $attributes) => [
            'returned_at' => now(),
        ]);
    }
}
