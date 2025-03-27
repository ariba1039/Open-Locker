<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Locker>
 */
class LockerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->randomLetter().' '.$this->faker->randomNumber(3),
            'modbus_address' => $this->faker->numberBetween(1, 10),
            'coil_register' => $this->faker->numberBetween(1, 100),
            'status_register' => $this->faker->numberBetween(1, 1000),
        ];
    }
}
